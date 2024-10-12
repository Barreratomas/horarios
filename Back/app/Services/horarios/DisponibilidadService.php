<?php

namespace App\Services\horarios;

use App\Mail\AssignedToSchedule;
use App\Repositories\horarios\DisponibilidadRepository;
use App\Mappers\horarios\DisponibilidadMapper;
use App\Models\horarios\Aula;
use App\Models\horarios\Disponibilidad;
use App\Models\horarios\Horario;
use App\Models\horarios\HorarioPrevioDocente;
use App\Models\horarios\DocenteUC;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DisponibilidadService implements DisponibilidadRepository
{
    protected $disponibilidadMapper;

    public function __construct(DisponibilidadMapper $disponibilidadMapper)
    {
        $this->disponibilidadMapper = $disponibilidadMapper;
    }

    /*
    public function obtenerTodasDisponibilidades()
    {
        
        $disponibilidades = Disponibilidad::all();
        return $disponibilidades;
        
    }

    public function obtenerDisponibilidadPorId($id)
    {
        
        $disponibilidad=Disponibilidad::find($id);
        if (is_null($disponibilidad)) {
            return [];
        }
        return $disponibilidad;
        
    }
    */

    public function horaPrevia($id_h_p_d)
    {

        $modelo = HorarioPrevioDocente::find($id_h_p_d);
        $horaPrevia = $modelo->hora;

        $horaLimite = new DateTime('18:50');
        $horaLimite = $horaLimite->format('H:i');

        $horasPermitidas = [
            '19:20' => 1,
            '20:00' => 2,
            '20:40' => 3,
            '21:20' => 4,
            '21:30' => 5,
            '22:10' => 6,
            '22:50' => 7,
        ];

        if ($horaPrevia > $horaLimite) {
            $horarioSiguiente = false;
            // se suman 30 min (el tiempo que tiene el docente despues de salir de otro instituto)
            $hora_datetime = DateTime::createFromFormat('H:i', $horaPrevia);

            // Sumar 30 minutos
            $hora_datetime->modify('+30 minutes');

            $horaPrevia = $hora_datetime->format('H:i');
            foreach ($horasPermitidas as $horaPermitida => $modulo) {

                if ($horarioSiguiente) {
                    $modulo--;

                    return $modulo;
                }

                if ($horaPrevia == $horaPermitida) {


                    return $modulo;
                } elseif ($horaPrevia < $horaPermitida) {
                    $horarioSiguiente = true;
                }

            }
            dd($horaPrevia);
        } else {
            return null;
        }



    }

    public function modulosRepartidos($modulos_semanales, $moduloPrevio, $id_uc, $id_grado, $id_aula, $diaInstituto)
    {

        $modulosPermitidos = range(1, 7);
        $distribucion = [];
        $diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];
        $siguienteDia = false;


        foreach ($diasSemana as $dia) {

            // SI EL DIA DE LA SEMANA NO ES IGUAL A EL DIA O DIAS QUE EL DOCENTE TRABAJA EN  OTRA EN OTRA INSTITUCION
            if ($dia !== $diaInstituto) {

                // RECORRE LOS MODULOS 
                foreach ($modulosPermitidos as $modulo) {

                    $modulo_inicio = $modulo;
                    if ($modulo_inicio >= 7) {
                        continue; // Saltar este módulo y pasar al siguiente dia
                    }

                    // cantidad de modulos semandales que tiene el docente
                    switch ($modulos_semanales) {
                        case 1:
                        case 2:
                        case 3:

                            $modulo_fin = min($modulo_inicio + $modulos_semanales, 7);

                            $disponible = $this->verificarModulosDia($dia, $modulo_inicio, $modulo_fin, $id_uc, $id_grado, $id_aula);
                            // si no hay superposicion de horarios se almacena el horario para el docente
                            if ($disponible) {
                                $distribucion[] = [
                                    'dia' => $dia,
                                    'modulo_inicio' => $modulo_inicio,
                                    'modulo_fin' => $modulo_fin
                                ];
                                return $distribucion;

                            }
                            break;
                        case 4:
                        case 5:
                        case 6:
                            // si ya se asignaron horarios para el docente y se avanzo al otro dia aplica la condicion
                            if ($siguienteDia && $modulos_semanales == 5) {

                                $modulos_semanales = 4;
                            }

                            $mitadModulos = ($modulos_semanales % 2 == 0) ? $modulos_semanales / 2 : intval(ceil($modulos_semanales / 2));

                            $modulo_fin = min($modulo_inicio + $mitadModulos, 7);
                            $disponible = $this->verificarModulosDia($dia, $modulo_inicio, $modulo_fin, $id_uc, $id_grado, $id_aula);

                            if ($disponible) {
                                if ($siguienteDia) {
                                    $distribucion[] = [
                                        'dia' => $dia,
                                        'modulo_inicio' => $modulo_inicio,
                                        'modulo_fin' => $modulo_fin
                                    ];
                                    return $distribucion;

                                } else {
                                    $distribucion[] = [
                                        'dia' => $dia,
                                        'modulo_inicio' => $modulo_inicio,
                                        'modulo_fin' => $modulo_fin
                                    ];
                                    $siguienteDia = true;
                                    break 2;

                                }
                            }
                            break;
                    }
                }
                // si el dia de la semana es igual a el dia de la semana que el docente trabaja en otra institucion
            } else {

                //modulo inicio toma el valor de modulo previo, es decir, desde que modulo el docente puede empezar a dar clases. $moduloPrevio se obtiene en la funcion horaPrevia()
                $modulo_inicio = $moduloPrevio;

                switch ($modulos_semanales) {
                    case 1:
                    case 2:
                    case 3:
                        $modulo_fin = min($modulo_inicio + $modulos_semanales, 7);
                        $disponible = $this->verificarModulosDia($dia, $modulo_inicio, $modulo_fin, $id_uc, $id_grado, $id_aula);
                        if ($disponible) {
                            $distribucion[] = [
                                'dia' => $dia,
                                'modulo_inicio' => $modulo_inicio,
                                'modulo_fin' => $modulo_fin
                            ];
                            return $distribucion;

                        }
                        break;
                    case 4:
                    case 5:
                    case 6:
                        if ($siguienteDia && $modulos_semanales == 5) {
                            $modulos_semanales = 4;
                        }
                        $mitadModulos = ($modulos_semanales % 2 == 0) ? $modulos_semanales / 2 : intval(ceil($modulos_semanales / 2));

                        $modulo_fin = min($modulo_inicio + $mitadModulos, 7);

                        $disponible = $this->verificarModulosDia($dia, $modulo_inicio, $modulo_fin, $id_uc, $id_grado, $id_aula);
                        if ($disponible) {

                            if ($siguienteDia) {
                                $distribucion[] = [
                                    'dia' => $dia,
                                    'modulo_inicio' => $modulo_inicio,
                                    'modulo_fin' => $modulo_fin
                                ];
                                return $distribucion;

                            } else {
                                $distribucion[] = [
                                    'dia' => $dia,
                                    'modulo_inicio' => $modulo_inicio,
                                    'modulo_fin' => $modulo_fin
                                ];
                                $siguienteDia = true;
                                break;
                            }
                        }
                        break;
                }
            }

        }


        return $distribucion = null;
        ;
    }

    public function verificarModulosDia($dia, $modulo_inicio, $modulo_fin, $id_uc, $id_grado, $id_aula)
    {
        $dUC = DocenteUC::find($id_uc);

        // verificar si ya existe disponibilidad con el mismo dia, grado y en horarios superpuestos
        $existeSuperposicionGrado = Disponibilidad::where('dia', $dia)
            ->whereExists(function ($query) use ($id_grado, $modulo_inicio, $modulo_fin) {
                // verificar si ya existe id_uc y id_grado
                $query->selectRaw(1)
                    ->from('docentes_uc')
                    ->whereColumn('disponibilidades.id_uc', 'docentes_uc.id_uc')
                    ->where('docente_uc.id_grado', $id_grado)
                    ->where(function ($query) use ($modulo_inicio, $modulo_fin) {
                    $query->whereBetween('disponibilidades.modulo_inicio', [$modulo_inicio, $modulo_fin])
                        ->orWhereBetween(DB::raw('disponibilidades.modulo_fin -1'), [$modulo_inicio, $modulo_fin]);
                });
            })->exists();
        // verificar si se superponen los horarios

        // verificar si ya existe aula con horarios superpuestos el mismo dia
        $existeSuperposicionAula = Disponibilidad::where('dia', $dia)
            ->whereExists(function ($query) use ($id_aula, $modulo_inicio, $modulo_fin, $dia) {
                $query->selectRaw(1)
                    ->from('docentes_uc as dUC2')
                    ->join('disponibilidades as d2', 'dUC2.id_uc', '=', 'd2.id_uc')
                    ->where('dUC2.id_aula', $id_aula)
                    ->where('d2.dia', $dia) // Condición para verificar el mismo día
                    ->where(function ($query) use ($modulo_inicio, $modulo_fin) {
                        $query->whereBetween('d2.modulo_inicio', [$modulo_inicio, $modulo_fin])
                            ->orWhereBetween(DB::raw('d2.modulo_fin - 1'), [$modulo_inicio, $modulo_fin]);
                    });
            })->exists();


        // Verificar si el docente ya tiene disponibilidad en el mismo día y horarios superpuestos
        $existeSuperposicionDocente = Disponibilidad::where('dia', $dia)
            ->whereExists(function ($query) use ($dUC, $dia, $modulo_inicio, $modulo_fin) {
                $query->selectRaw(1)
                    ->from('docentes_uc as dUC2')
                    ->join('disponibilidades as d2', 'dUC2.id_uc', '=', 'd2.id_uc')
                    ->where('dUC2.DNI', $dUC->DNI) // Condición para verificar el mismo docente
                    ->where('d2.dia', $dia) // Condición para verificar el mismo día
                    ->where(function ($query) use ($modulo_inicio, $modulo_fin) {
                        $query->whereBetween('d2.modulo_inicio', [$modulo_inicio, $modulo_fin])
                            ->orWhereBetween(DB::raw('d2.modulo_fin - 1'), [$modulo_inicio, $modulo_fin]);
                    });
            })
            ->exists();

        if ($existeSuperposicionGrado) {
            session(['error' => 'La grado seleccionada ya no tiene horarios disponibles']);

            return false;


        } elseif ($existeSuperposicionAula) {
            session(['error' => 'El aula seleccionada ya no tiene horarios disponibles']);
            return false;

        } elseif ($existeSuperposicionDocente) {
            session(['error' => 'El docente seleccionado ya no tiene horarios disponibles']);
            return false;

        }
        session()->forget('error'); // Limpiar cualquier mensaje de error existente

        return true;
    }
    

    public function guardarDisponibilidad($params)
    {
        
        $disponibilidad = new Disponibilidad();
        foreach ($params as $key => $value) {
            $disponibilidad->{$key} = $value;
        }

        if ($disponibilidad->save()) 
        {
            
            Mail::to($disponibilidad->docenteUC->docente->email)->send(new AssignedToSchedule($disponibilidad->docenteUC->docente->nombre));

            return ['success' => 'Disponibilidad guardada correctamente'];
        } else {
            return ['error' => 'Hubo un error al guardar la disponibilidad'];
        }
    }

    
    public function actualizarDisponibilidad($params)
    {
        
        $disponibilidad = new Disponibilidad();
        foreach ($params as $key => $value) {
            $disponibilidad->{$key} = $value;
        }
        

        if ($disponibilidad->save()) 
        {
            return ['success' => 'Disponibilidad actualizada correctamente'];
        } else {
            return ['error' => 'Hubo un error al guardar la disponibilidad'];
        }
    }

    public function eliminarDisponibilidadPorId($id)
    {
        try {
            $disponibilidad = Disponibilidad::find($id);
            if (!$disponibilidad) {
                return ['error' => 'hubo un error al buscar disponibilidad'];
                
            }
            $disponibilidad->delete();
            return ['success' => 'Disponibilidad eliminada correctamente'];
        } catch (Exception $e) {
            return ['error' => 'Hubo un error al eliminar la disponibilidad'];
        }
    }

    
    //------------------------------------------------------------------------------------------------------------------
    // swagger

    public function obtenerTodasDisponibilidades()
    {
        try {
            $disponibilidades = Disponibilidad::all();
            return response()->json($disponibilidades, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener las disponibilidades: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener las disponibilidades'], 500);
        }
    }
    public function obtenerDisponibilidadPorId($id)
    {
        try {
            $disponibilidad = Disponibilidad::find($id);
            if ($disponibilidad) {
                return response()->json($disponibilidad, 200);
            }
            return response()->json(['error' => 'No existe la disponibilidad'], 404);
        } catch (Exception $e) {
            Log::error('Error al obtener la disponibilidad: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener la disponibilidad'], 500);
        }
    }
    public function guardarDisponibilidadSwagger($request)
    {
        try {
            $disponibilidadData = $request->all();
            $disponibilidad = new Disponibilidad($disponibilidadData);
            $disponibilidadModel = $this->disponibilidadMapper->toDisponibilidad($disponibilidad);
            $disponibilidadModel->save();
            return response()->json($disponibilidadModel, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar la disponibilidad: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar la disponibilidad'], 500);
        }
    }
    public function actualizarDisponibilidadSwagger($request, $id)
    {

        $disponibilidad = Disponibilidad::find($id);
        if (!$disponibilidad) {
            return response()->json(['error' => 'No existe la disponibilidad'], 404);
        }
        try {
            $disponibilidad->update($request->all());
            return response()->json($disponibilidad, 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar la disponibilidad: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar la disponibilidad'], 500);
        }
    }
    public function eliminarDisponibilidadPorIdSwagger($id)
    {
        try {
            $disponibilidad = Disponibilidad::find($id);
            if ($disponibilidad) {
                $disponibilidad->delete();
                return response()->json(['success' => 'Se eliminó la disponibilidad'], 200);
            }
            return response()->json(['error' => 'No existe la disponibilidad'], 404);
        } catch (Exception $e) {
            Log::error('Error al eliminar la disponibilidad: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar la disponibilidad'], 500);
        }
    }
}





