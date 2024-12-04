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


    public function obtenerTodasDisponibilidades()
    {

        $disponibilidades = Disponibilidad::all();
        return $disponibilidades;
    }

    public function obtenerDisponibilidadPorId($id)
    {

        $disponibilidad = Disponibilidad::find($id);
        if (is_null($disponibilidad)) {
            return [];
        }
        return $disponibilidad;
    }


    public function horaPrevia($horaPrevia)
    {

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
        // Asegurando que la hora previa esté en el formato correcto (H:i)
        if (strpos($horaPrevia, ':') !== false) {
            $horaPrevia = substr($horaPrevia, 0, 5); // Recortamos los segundos si los hay
        }


        // Verificando si la hora previa es mayor que la hora límite
        if ($horaPrevia > $horaLimite) {

            $horarioSiguiente = false;

            // Intentando crear un objeto DateTime con el formato esperado
            $hora_datetime = DateTime::createFromFormat('H:i', $horaPrevia);

            if ($hora_datetime === false) {
                Log::error("Formato de hora inválido: {$horaPrevia}");
                return null;  // Retornar null si el formato es inválido
            }

            // Sumar 30 minutos
            $hora_datetime->modify('+30 minutes');
            $horaPrevia = $hora_datetime->format('H:i');


            // Recorriendo las horas permitidas para buscar el módulo
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
        } else {
            return null;
        }
    }


    public function modulosRepartidos($modulos_semanales, $id_docente, $id_grado, $id_uc, $id_h_p_d = null, $moduloPrevio = null, $diaInstituto = null)
    {
        Log::info('Iniciando la asignación de módulos', [
            'modulos_semanales' => $modulos_semanales,
            'id_docente' => $id_docente,
            'id_grado' => $id_grado,
            'moduloPrevio' => $moduloPrevio,
            'diaInstituto' => $diaInstituto,
        ]);
        $modulos_semanales_o=$modulos_semanales;

        $diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];

        $responses = []; // Inicializar arreglo para almacenar respuestas
        $seAsignoModulo = false; // Bandera para verificar si hubo algún módulo asignado
    

        // Determinar el índice de inicio según diaInstituto
        $indiceDiaInstituto = $diaInstituto ? array_search($diaInstituto, $diasSemana) : 0;

        // Validar si diaInstituto es inválido
        if ($diaInstituto && $indiceDiaInstituto === false) {
            Log::error("Error: El día del instituto no es válido.", [
                'diaInstituto' => $diaInstituto,
                'dias_validos' => $diasSemana,
            ]);
            return null;
        }

        $contadorDia = $indiceDiaInstituto; // Iniciar desde el díaInstituto o lunes
        $intentos = 0; // Contador para limitar el número de iteraciones

        $modulosAsignados = 0;
        $maxModulosPorDia = 3;

        while ($modulos_semanales > 0) {
            $dia = $diasSemana[$contadorDia];
            $asignado = false; // Variable para saber si se asignaron módulos este día

            // Calcular la cantidad de módulos a asignar según el número restante
            $modulosHoy = min($modulos_semanales, $maxModulosPorDia); // Asignar hasta 3 módulos o el resto

            // Intentar asignar módulos para el día actual
            for ($inicio = 1; $inicio <= 6; $inicio++) {
                $fin = min($inicio + $modulosHoy - 1, 6); // Asignar hasta $modulosHoy módulos por día

                // Si es el día del instituto, respetar moduloPrevio (si está definido)
                if ($diaInstituto && $dia === $diaInstituto && $moduloPrevio !== null && $inicio <= $moduloPrevio - 1) {

                    continue;
                }

                // Verificar disponibilidad en la base de datos
                $disponible = $this->verificarModulosDia($dia, $inicio, $fin, $id_docente, $id_grado, $id_uc, $modulos_semanales,$modulos_semanales_o);

                // Si están disponibles, asignar los módulos y descontar los módulos semanales
                if ($disponible) {
                    $modulosAsignados = range($inicio, $fin);
                    $distribucion[$dia]['modulo_inicio'] = $inicio;
                    $distribucion[$dia]['modulo_fin'] = $fin;
                    $distribucion[$dia]['aula'] = $disponible;

                    Log::info("Aula asignada para el día $dia", [
                        'aula' => $disponible,
                        'modulo_inicio' => $inicio,
                        'modulo_fin' => $fin,
                    ]);
                    $params = [
                        'id_uc' => $id_uc,
                        'id_docente' => $id_docente,
                        'id_h_p_d' => $id_h_p_d,
                        'id_aula' => $disponible,
                        'id_grado' => $id_grado,
                        'dia' => $dia,
                        'modulo_inicio' => $inicio,
                        'modulo_fin' => $fin,
                    ];
                    $response = $this->guardarDisponibilidad($params);
                    $responses[] = $response; // Agregar respuesta al arreglo

                    // Descontar los módulos asignados
                    $modulos_semanales -= count($modulosAsignados);
                    Log::info("modulo semanales  $modulos_semanales");
                    // Verificar que no haya módulos negativos
                    if ($modulos_semanales < 0) {
                        Log::warning("El número de módulos semanales se volvió negativo. Corrigiendo el valor.", [
                            'modulos_semanales' => $modulos_semanales,
                        ]);
                        $modulos_semanales = 0; // Asegurarse de que no sea negativo
                    }

                    $asignado = true; // Se logró asignar módulo
                    $seAsignoModulo = true; 

                    break; // Salir del bucle si se asignó correctamente
                } else {
                    Log::warning("No se pudo asignar módulos para el día $dia", [
                        'inicio' => $inicio,
                        'fin' => $fin,
                        'id_docente' => $id_docente,
                        'id_grado' => $id_grado,
                    ]);
                }
            }

            // Cambiar al siguiente día si no quedan módulos disponibles para el actual
            $contadorDia++;
            if ($contadorDia >= count($diasSemana)) {
                $contadorDia = 0; // Reiniciar la semana si se acaban los días
                $intentos++; // Incrementar el contador de intentos por semana
            }

            // Detener si todos los módulos semanales se han asignado
            if (!$asignado && $intentos >= 2) { // Número máximo de intentos
                Log::warning("No se pudieron asignar más módulos después de 2 intentos.", [
                    'modulos_restantes' => $modulos_semanales,
                    'intentos' => $intentos,
                ]);
                break;
            }
        }


        if (!$seAsignoModulo) {
            $responses[] = response()->json([
                'status' => 'error',
            ], 500);
        }

        return $responses;
    }




    public function verificarModulosDia($dia, $modulo_inicio, $modulo_fin, $id_docente, $id_grado, $id_uc, $modulos_semanales,$modulos_semanales_o)
    {
        // Calcular el rango de módulos que se intentan asignar
        $modulosPorAsignar = range($modulo_inicio, $modulo_fin);
        // Consultar la base de datos para obtener todos los módulos ya asignados de lunes a viernes
        $modulosAsignados = DB::table('disponibilidad')
            ->where('id_uc', $id_uc)
            ->where('id_grado', $id_grado)
            ->whereIn('dia', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'])
            ->get(['modulo_inicio', 'modulo_fin']);

        $totalModulosAsignados = 0;

        // Calcular el número total de módulos asignados en la semana
        foreach ($modulosAsignados as $modulo) {
            $inicio = (int)$modulo->modulo_inicio;
            $fin = (int)$modulo->modulo_fin;
            $totalModulosAsignados += $fin - $inicio + 1;
        }

        // Calcular los nuevos módulos que se sumarían
        $modulosNuevos = count($modulosPorAsignar);

        // Verificar si el total excede los módulos semanales permitidos
        if ($totalModulosAsignados + $modulosNuevos > $modulos_semanales_o) {
            Log::info("modulo inico {$modulo_inicio} y modulo fin {$modulo_fin} total:{$modulosNuevos} sin espcio en modulos semanales");
            return false; // No es posible asignar más módulos
        } else {
            Log::info("modulo inico {$modulo_inicio} y modulo fin {$modulo_fin} total:{$modulosNuevos} tiene modulos disponibles semanales.");
        }



        // Verificar si el docente está asignado en el rango de módulos
        $disponibilidadDocente = DB::table('disponibilidad')
            ->where('id_docente', $id_docente) // Filtrar por el docente
            ->where('dia', $dia)               // Filtrar por el día
            ->where(function ($query) use ($modulo_inicio, $modulo_fin) {
                // Verificar si hay superposición de módulos
                $query->whereBetween('modulo_inicio', [$modulo_inicio, $modulo_fin])
                    ->orWhereBetween('modulo_fin', [$modulo_inicio, $modulo_fin])
                    ->orWhere(function ($query) use ($modulo_inicio, $modulo_fin) {
                        $query->where('modulo_inicio', '<=', $modulo_inicio)
                            ->where('modulo_fin', '>=', $modulo_fin);
                    });
            })
            ->exists();

        // retornar si existe una disponibilidad (es decir, el docente no está  disponible)
        if ($disponibilidadDocente) {
            Log::info("docente {$id_docente} ya está asignada en este rango de tiempo.");
            return false;
        } else {
            Log::info("docente {$id_docente} está disponible para este rango de tiempo.");
        }


        // Verificar las asignaciones para el grado en el mismo día y en el rango de módulos
        $disponibilidadGrado = DB::table('disponibilidad')
            ->where('id_grado', $id_grado)    // Filtrar por el grado
            ->where('dia', $dia)               // Filtrar por el día
            ->where(function ($query) use ($modulo_inicio, $modulo_fin) {
                // Verificar si hay superposición de módulos para el grado
                $query->whereBetween('modulo_inicio', [$modulo_inicio, $modulo_fin])  // Verificar si el inicio se solapa
                    ->orWhereBetween('modulo_fin', [$modulo_inicio, $modulo_fin])  // Verificar si el fin se solapa
                    ->orWhere(function ($query) use ($modulo_inicio, $modulo_fin) {
                        // Verificar si la asignación cubre todo el rango
                        $query->where('modulo_inicio', '<=', $modulo_inicio)
                            ->where('modulo_fin', '>=', $modulo_fin);
                    });
            })
            ->exists();

        // retornar si existe una disponibilidad (es decir, el grado no está  disponible)
        if ($disponibilidadGrado) {
            Log::info("grado {$id_grado} ya está asignada en este rango de tiempo.");
            return false;
        } else {
            Log::info("grado {$id_docente} está disponible para este rango de tiempo.");
        }


        $aulas = Aula::all();

        foreach ($aulas as $aula) {
            // Verificar si el aula tiene asignaciones en el mismo día con los módulos solicitados
            $disponibilidadAula = DB::table('disponibilidad')
                ->where('id_aula', $aula->id_aula)
                ->where('dia', $dia)
                ->where(function ($query) use ($modulo_inicio, $modulo_fin) {
                    // Verificar si hay coincidencia de los módulos
                    $query->whereBetween('modulo_inicio', [$modulo_inicio, $modulo_fin])
                        ->orWhereBetween('modulo_fin', [$modulo_inicio, $modulo_fin])
                        ->orWhere(function ($query) use ($modulo_inicio, $modulo_fin) {
                            $query->where('modulo_inicio', '<=', $modulo_inicio)
                                ->where('modulo_fin', '>=', $modulo_fin);
                        });
                })
                ->exists();  // Verifica si existe alguna asignación con los parámetros dados

            // retornar si no existe una disponibilidad (es decir, el aula está disponible)
            if ($disponibilidadAula) {
                Log::info("Aula {$aula->id_aula} ya está asignada en este rango de tiempo.");
            } else {
                Log::info("Aula {$aula->id_aula} está disponible para este rango de tiempo.");
                return $aula->id_aula;
            }
        }
        return false;

        // return 1;

    }


    public function guardarDisponibilidad($params)
    {
        try {
            $disponibilidad = new Disponibilidad();
            foreach ($params as $key => $value) {
                $disponibilidad->{$key} = $value;
            }

            if ($disponibilidad->save()) {
                // Mail::to($disponibilidad->docenteUC->docente->email)->send(new AssignedToSchedule($disponibilidad->docenteUC->docente->nombre));

                return response()->json([
                    'status' => 'success',
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                ], 500); 
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error'   
            ], 500);
        }
    }



    public function verificarModulosDiaIndividual($dia, $modulo_inicio, $modulo_fin, $id_docente, $id_grado, $id_uc, $modulos_semanales,$modulos_semanales_o,$id_aula)
    {
                // Calcular el rango de módulos que se intentan asignar
                $modulosPorAsignar = range($modulo_inicio, $modulo_fin);
                // Consultar la base de datos para obtener todos los módulos ya asignados de lunes a viernes
                $modulosAsignados = DB::table('disponibilidad')
                    ->where('id_uc', $id_uc)
                    ->where('id_grado', $id_grado)
                    ->whereIn('dia', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'])
                    ->get(['modulo_inicio', 'modulo_fin']);
        
                $totalModulosAsignados = 0;
        
                // Calcular el número total de módulos asignados en la semana
                foreach ($modulosAsignados as $modulo) {
                    $inicio = (int)$modulo->modulo_inicio;
                    $fin = (int)$modulo->modulo_fin;
                    $totalModulosAsignados += $fin - $inicio + 1;
                }
        
                // Calcular los nuevos módulos que se sumarían
                $modulosNuevos = count($modulosPorAsignar);
        
                // Verificar si el total excede los módulos semanales permitidos
                if ($totalModulosAsignados + $modulosNuevos > $modulos_semanales_o) {
                    Log::info("modulo inico {$modulo_inicio} y modulo fin {$modulo_fin} total:{$modulosNuevos} sin espcio en modulos semanales");
                    return false; // No es posible asignar más módulos
                } else {
                    Log::info("modulo inico {$modulo_inicio} y modulo fin {$modulo_fin} total:{$modulosNuevos} tiene modulos disponibles semanales.");
                }

        // Verificar si el docente está asignado en el rango de módulos
        $disponibilidadDocente = DB::table('disponibilidad')
            ->where('id_docente', $id_docente) // Filtrar por el docente
            ->where('dia', $dia)               // Filtrar por el día
            ->where(function ($query) use ($modulo_inicio, $modulo_fin) {
                // Verificar si hay superposición de módulos
                $query->whereBetween('modulo_inicio', [$modulo_inicio, $modulo_fin])
                    ->orWhereBetween('modulo_fin', [$modulo_inicio, $modulo_fin])
                    ->orWhere(function ($query) use ($modulo_inicio, $modulo_fin) {
                        $query->where('modulo_inicio', '<=', $modulo_inicio)
                            ->where('modulo_fin', '>=', $modulo_fin);
                    });
            })
            ->exists();

        // retornar si existe una disponibilidad (es decir, el docente no está  disponible)
        if ($disponibilidadDocente) {
            Log::info("docente {$id_docente} ya está asignada en este rango de tiempo.");
            return false;
        } else {
            Log::info("docente {$id_docente} está disponible para este rango de tiempo.");
        }


        // Verificar las asignaciones para el grado en el mismo día y en el rango de módulos
        $disponibilidadGrado = DB::table('disponibilidad')
            ->where('id_grado', $id_grado)    // Filtrar por el grado
            ->where('dia', $dia)               // Filtrar por el día
            ->where(function ($query) use ($modulo_inicio, $modulo_fin) {
                // Verificar si hay superposición de módulos para el grado
                $query->whereBetween('modulo_inicio', [$modulo_inicio, $modulo_fin])  // Verificar si el inicio se solapa
                    ->orWhereBetween('modulo_fin', [$modulo_inicio, $modulo_fin])  // Verificar si el fin se solapa
                    ->orWhere(function ($query) use ($modulo_inicio, $modulo_fin) {
                        // Verificar si la asignación cubre todo el rango
                        $query->where('modulo_inicio', '<=', $modulo_inicio)
                            ->where('modulo_fin', '>=', $modulo_fin);
                    });
            })
            ->exists();

        // retornar si existe una disponibilidad (es decir, el grado no está  disponible)
        if ($disponibilidadGrado) {
            Log::info("grado {$id_grado} ya está asignada en este rango de tiempo.");
            return false;
        } else {
            Log::info("grado {$id_docente} está disponible para este rango de tiempo.");
        }



        // Verificar si el aula tiene asignaciones en el mismo día con los módulos solicitados
        $disponibilidadAula = DB::table('disponibilidad')
            ->where('id_aula', $id_aula)
            ->where('dia', $dia)
            ->where(function ($query) use ($modulo_inicio, $modulo_fin) {
                // Verificar si hay coincidencia de los módulos
                $query->whereBetween('modulo_inicio', [$modulo_inicio, $modulo_fin])
                    ->orWhereBetween('modulo_fin', [$modulo_inicio, $modulo_fin])
                    ->orWhere(function ($query) use ($modulo_inicio, $modulo_fin) {
                        $query->where('modulo_inicio', '<=', $modulo_inicio)
                            ->where('modulo_fin', '>=', $modulo_fin);
                    });
            })
            ->exists();  // Verifica si existe alguna asignación con los parámetros dados

        // retornar si no existe una disponibilidad (es decir, el aula está disponible)
        if ($disponibilidadAula) {
            Log::info("Aula {$id_aula} ya está asignada en este rango de tiempo.");
        } else {
            Log::info("Aula {$id_aula} está disponible para este rango de tiempo.");
            return $id_aula;
        }


        // return 1;

    }
    public function actualizarDisponibilidad($request, $id)

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
    /*
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
        */
}
