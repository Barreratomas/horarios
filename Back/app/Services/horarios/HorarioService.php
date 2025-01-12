<?php


namespace App\Services\horarios;

use App\Mappers\horarios\HorarioMapper;
use App\Repositories\horarios\HorarioRepository;
use App\Models\horarios\Horario;
use App\Models\horarios\Disponibilidad;
use App\Models\horarios\Grado;
use App\Models\horarios\Aula;
use App\Models\horarios\UnidadCurricular;
use Exception;
use Illuminate\Support\Facades\Log;

class HorarioService implements HorarioRepository
{

    protected $horarioMapper;

    public function __construct(HorarioMapper $horarioMapper)
    {
        $this->horarioMapper = $horarioMapper;
    }

    public function obtenerTodosHorarios()
    {
        try {
            $horarios = Horario::all();
            return response()->json($horarios, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los horarios: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los horarios'], 500);
        }
    }

    public function obtenerHorarioPorId($id)
    {
        try {
            $horario = Horario::find($id);
            if (!$horario) {
                return response()->json(['error' => 'Horario no encontrado'], 404);
            }
            return response()->json($horario, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el horario: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el horario'], 500);
        }
    }

    public function guardarHorarios($dia, $modulo_inicio, $modulo_fin, $id_disp)
    {
        try {
            // los datos para el mapper
            $horarioData = [
                'dia' => $dia,
                'modulo_inicio' => $modulo_inicio,
                'modulo_fin' => $modulo_fin,
                'modalidad' => "p", // hay que hacer la modalidad dinamica
                'id_disp' => $id_disp,
            ];

            $horario = HorarioMapper::toHorario($horarioData);

            if ($horario->save()) {
                return response()->json([
                    'status' => 'success',
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Error al guardar el horario'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function actualizarHorarios($request, $id)
    {
        Log::info("Iniciando la actualización del horario con ID: $id");

        // Buscar el horario existente
        $horario = Horario::find($id);
        if (!$horario) {
            Log::warning("Horario con ID $id no encontrado");
            return response()->json(['error' => 'Horario no encontrado'], 404);
        }

        Log::info("Horario encontrado. Validando relaciones...");

        // Obtener relaciones de las tablas relacionadas
        $grado = Grado::find($request->id_grado ?? $horario->id_grado);
        $aula = Aula::find($request->id_aula ?? $horario->id_aula);
        $uc = UnidadCurricular::find($request->id_uc ?? $horario->id_uc);
        $disponibilidad = Disponibilidad::find($request->id_disp ?? $horario->id_disp);

        // Verificar si las relaciones existen
        if (!$grado || !$aula || !$uc || !$disponibilidad) {
            Log::warning("Una o más relaciones no existen. Grado: $grado, Aula: $aula, UC: $uc, Disponibilidad: $disponibilidad");
            return response()->json(['error' => 'Una o más relaciones no existen'], 400);
        }

        try {
            // Validar conflictos de horarios en el aula
            $moduloInicio = $request->modulo_inicio ?? $horario->modulo_inicio;
            $moduloFin = $request->modulo_fin ?? $horario->modulo_fin;
            $dia = $request->dia ?? $horario->dia;

            Log::info("Validando conflictos de horarios en el aula: {$aula->id_aula}, día: $dia");
            $aulaConflictos = Horario::where('id_aula', $aula->id_aula)
                ->where('dia', $dia)
                ->where('id_horario', '!=', $horario->id_horario) // Excluir el horario actual
                ->where(function ($query) use ($moduloInicio, $moduloFin) {
                    $query->whereBetween('modulo_inicio', [$moduloInicio, $moduloFin])
                        ->orWhereBetween('modulo_fin', [$moduloInicio, $moduloFin])
                        ->orWhere(function ($query) use ($moduloInicio, $moduloFin) {
                            $query->where('modulo_inicio', '<=', $moduloInicio)
                                ->where('modulo_fin', '>=', $moduloFin);
                        });
                })
                ->exists();

            if ($aulaConflictos) {
                Log::warning("Conflicto detectado en el aula: {$aula->id_aula}, día: $dia");
                return response()->json(['error' => 'El aula ya tiene un horario en conflicto'], 400);
            }

            // Validar disponibilidad del docente
            Log::info("Validando disponibilidad del docente con ID de disponibilidad: {$disponibilidad->id_disp}");
            if (
                $disponibilidad->dia !== $dia ||
                $disponibilidad->modulo_inicio > $moduloInicio ||
                $disponibilidad->modulo_fin < $moduloFin
            ) {
                Log::warning("Disponibilidad del docente no coincide. Día: $dia, Modulo inicio: $moduloInicio, Modulo fin: $moduloFin");
                return response()->json(['error' => 'El docente no está disponible en el horario indicado'], 400);
            }

            // Actualizar solo los atributos enviados en la solicitud
            $actualizaciones = array_filter($request->only([
                'dia',
                'modulo_inicio',
                'modulo_fin',
                'modalidad',
                'id_disp',
                'id_uc',
                'id_aula',
                'id_grado'
            ]), fn($value) => !is_null($value)); // Filtrar valores nulos

            Log::info("Actualizando horario con los datos: ", $actualizaciones);
            $horario->update($actualizaciones);

            Log::info("Horario actualizado correctamente con ID: {$horario->id_horario}");
            return response()->json($horario, 200);
        } catch (Exception $e) {
            Log::error("Error al actualizar el horario: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar el horario'], 500);
        }
    }

    public function eliminarHorarios($id)
    {
        Log::info("Iniciando la eliminación del horario con ID: $id");

        try {
            // Buscar el horario
            $horario = Horario::find($id);

            if ($horario) {
                Log::info("Horario encontrado. Procediendo a eliminar. ID: {$horario->id_horario}, Día: {$horario->dia}, Módulo inicio: {$horario->modulo_inicio}, Módulo fin: {$horario->modulo_fin}");

                // Eliminar el horario
                $horario->delete();

                Log::info("Horario eliminado correctamente. ID: $id");
                return response()->json(['message' => 'Horario eliminado correctamente'], 200);
            } else {
                Log::warning("Horario no encontrado para ID: $id");
                return response()->json(['error' => 'Horario no encontrado'], 404);
            }
        } catch (Exception $e) {
            Log::error("Error al eliminar el horario con ID $id: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el horario'], 500);
        }
    }

    //------------------------------------------------------------------------------------------------------------------
    // Swagger

    public function obtenerTodosHorariosSwagger()
    {
        try {
            $horarios = Horario::all();
            return response()->json($horarios, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los horarios: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los horarios'], 500);
        }
    }

    public function obtenerHorarioPorIdSwagger($id)
    {
        try {
            $horario = Horario::find($id);
            if ($horario) {
                return response()->json($horario, 200);
            }
            return response()->json(['error' => 'No existe el horario'], 404);
        } catch (Exception $e) {
            Log::error('Error al obtener el horario: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el horario'], 500);
        }
    }
    public function guardarHorariosSwagger($request)
    {
        try {
            $horarioData = $request->all();
            $horario = new Horario($horarioData);
            $horarioModel = $this->horarioMapper->toHorario($horario);
            $horarioModel->save();
            return response()->json($horarioModel, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar el horario: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el horario'], 500);
        }
    }
    public function actualizarHorariosSwagger($request, $id)
    {
        $horario = Horario::find($id);
        if (!$horario) {
            return response()->json(['error' => 'No existe el horario'], 404);
        }
        try {
            $horario->update($request->all());
            return response()->json($horario, 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar el horario: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar el horario'], 500);
        }
    }
    public function eliminarHorariosSwagger($id)
    {
        try {
            $horario = Horario::find($id);
            if ($horario) {
                $horario->delete();
                return response()->json(['success' => 'Se eliminó el horario'], 200);
            } else {
                return response()->json(['error' => 'No existe el horario'], 404);
            }
        } catch (Exception $e) {
            Log::error('Error al eliminar el horario: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el horario'], 500);
        }
    }
}
