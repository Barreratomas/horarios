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
use App\Services\horarios\HorarioService;

class DisponibilidadService implements DisponibilidadRepository
{
    protected $disponibilidadMapper;
    protected $horarioService;


    public function __construct(DisponibilidadMapper $disponibilidadMapper,  HorarioService $horarioService)
    {
        $this->disponibilidadMapper = $disponibilidadMapper;
        $this->horarioService = $horarioService;
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


    public function guardarUnaDisponibilidad($id_uc, $id_docente, $id_aula, $id_carrera_grado, $dia, $modulo, $modalidad)
    {
        try {
            Log::info("arrancando disponibilidad");

            // Obtener la disponibilidad horaria del docente
            $disponibilidadTotal = DB::table('docente')
                ->where('id_docente', $id_docente)
                ->value('DispHoraria');

            // Contar la cantidad de módulos ya asignados en disponibilidad
            $modulosAsignados = DB::table('disponibilidad')
                ->where('id_docente', $id_docente)
                ->sum(DB::raw('modulo_fin - modulo_inicio + 1')); // Contar los módulos ocupados



            // Verificar si el total excede la disponibilidad horaria
            if (($modulosAsignados + 1) > $disponibilidadTotal) {
                Log::warning("El docente {$id_docente} no tiene suficiente disponibilidad horaria. Asignados: {$modulosAsignados}, Nuevos: 1, Límite: {$disponibilidadTotal}");
                return response()->json(['error' => 'Se alcanzó la capacidad maxima del docente'], 400);
            } else {
                Log::info("Paso la validacion de modulos totales");
            }

            $verificacionRequest = $this->verificarModulosActualizacion(
                $dia,
                $modulo,
                $id_docente,
                $id_carrera_grado,
                $id_uc,
                $id_aula,
            );

            if (!$verificacionRequest) {
                Log::info("Conflicto detectado durante la verificación de disponibilidades");
                return response()->json(['error' => 'No hay mas disponibilidad'], 400);
            }
            $id_disp = DB::table('disponibilidad')->insertGetId([
                'id_docente' => $id_docente,
                'id_carrera_grado' => $id_carrera_grado,
                'id_uc' => $id_uc,
                'id_aula' => $id_aula,
                'dia' => $dia,
                'modulo_inicio' => $modulo,
                'modulo_fin' => $modulo
            ]);
            $this->horarioService->guardarHorarios($dia, $modulo, $modulo, $id_disp, $modalidad);

            return  $this->horarioService->obtenerTodosHorarios();
        } catch (\Throwable $th) {
            return response()->json(['error' => "Error al asignar el nuevo horario: {$th->getMessage()}"], 500);
        }
    }

    public function modulosRepartidos($modulos_semanales, $id_docente, $id_carrera_grado, $id_uc,  $diasPresenciales, $id_h_p_d = null, $moduloPrevio = null, $diaInstituto = null)
    {
        Log::info('Iniciando la asignación de módulos', [
            'modulos_semanales' => $modulos_semanales,
            'id_docente' => $id_docente,
            'id_carrera_grado' => $id_carrera_grado,
            'moduloPrevio' => $moduloPrevio,
            'diaInstituto' => $diaInstituto,
        ]);
        $modulos_semanales_o = $modulos_semanales;

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
            Log::info("Procesando día {$dia}");

            // Verificar si ya hay módulos asignados para el id_carrera_grado en este día
            $moduloExistente  = DB::table('disponibilidad')
                ->where('id_carrera_grado', $id_carrera_grado)
                ->where('dia', $dia) // Solo el día actual en la iteración
                ->select('id_aula')
                ->first();


            // Determinar la modalidad si ya hay un módulo asignado en este día
            $modoDelDia = null;
            if ($moduloExistente) {
                if ($moduloExistente->id_aula === 'V') {
                    $modoDelDia = 'V'; // Si encontramos un virtual, todo el día debe ser virtual
                } elseif (is_numeric($moduloExistente->id_aula)) {
                    $modoDelDia = 'P'; // Si es un número, es presencial
                }
            }


            $virtual = !in_array($dia, $diasPresenciales);

            // Verificar si hay inconsistencia entre la modalidad del día y la modalidad esperada
            if (!is_null($modoDelDia) && ($modoDelDia === 'V') !== $virtual) {
                Log::warning("Día {$dia} no cumple con las condiciones, pasando al siguiente día.");

                // Avanzar al siguiente día manteniendo la lógica de ciclos semanales
                $contadorDia++;

                if ($contadorDia >= count($diasSemana)) {
                    $contadorDia = 0;
                    $intentos++;
                }

                if ($intentos >= 2) {
                    Log::warning("No se pudieron asignar más módulos después de 2 intentos.", [
                        'modulos_restantes' => $modulos_semanales,
                        'intentos' => $intentos,
                    ]);
                    break;
                }

                continue;
            }





            $asignado = false; // Variable para saber si se asignaron módulos este día

            // Calcular la cantidad de módulos a asignar según el número restante
            $modulosHoy = min($modulos_semanales, $maxModulosPorDia); // Asignar hasta 3 módulos o el resto

            // Intentar asignar módulos para el día actual
            for ($inicio = 1; $inicio <= 6; $inicio++) {
                $fin = min($inicio + $modulosHoy - 1, 6); // Asignar hasta $modulosHoy módulos por día
                Log::info("Procesando dia {$inicio}");

                // Si es el día del instituto, respetar moduloPrevio (si está definido)
                if ($diaInstituto && $dia === $diaInstituto && $moduloPrevio !== null && $inicio <= $moduloPrevio - 1) {

                    continue;
                }


                // Verificar disponibilidad en la base de datos

                $disponible = $this->verificarModulosDia($dia, $inicio, $fin, $id_docente, $id_carrera_grado, $id_uc, $modulos_semanales, $modulos_semanales_o, $virtual);
                Log::info("Procesando dis");

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
                        'id_carrera_grado' => $id_carrera_grado,
                        'dia' => $dia,
                        'modulo_inicio' => $inicio,
                        'modulo_fin' => $fin,
                    ];
                    $response = $this->guardarDisponibilidad($params);
                    if ($response->getStatusCode() == 201) {
                        $id_disp = $response->getData()->id;
                        if ($disponible == "V") {
                            $this->horarioService->guardarHorarios($dia, $inicio, $fin,  $id_disp, $disponible);
                        } else {
                            $this->horarioService->guardarHorarios($dia, $inicio, $fin,  $id_disp);
                        }
                    }
                    $responses[] = $response;

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
                        'id_carrera_grado' => $id_carrera_grado,
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




    public function verificarModulosDia($dia, $modulo_inicio, $modulo_fin, $id_docente, $id_carrera_grado, $id_uc, $modulos_semanales, $modulos_semanales_o, $virtual)
    {
        // Calcular el rango de módulos que se intentan asignar

        $modulosPorAsignar = range($modulo_inicio, $modulo_fin);
        // Consultar la base de datos para obtener todos los módulos ya asignados de lunes a viernes
        Log::info("arranco dispo");


        // Obtener la disponibilidad horaria del docente
        $disponibilidadTotal = DB::table('docente')
            ->where('id_docente', $id_docente)
            ->value('DispHoraria');

        // Contar la cantidad de módulos ya asignados en disponibilidad
        $modulosAsignados = DB::table('disponibilidad')
            ->where('id_docente', $id_docente)
            ->sum(DB::raw('modulo_fin - modulo_inicio + 1')); // Contar los módulos ocupados


        // Calcular los nuevos módulos a asignar
        $nuevosModulos = count($modulosPorAsignar);

        // Verificar si el total excede la disponibilidad horaria
        if (($modulosAsignados + $nuevosModulos) > $disponibilidadTotal) {

            Log::warning("El docente {$id_docente} no tiene suficiente disponibilidad horaria. Asignados: {$modulosAsignados}, Nuevos: {$nuevosModulos}, Límite: {$disponibilidadTotal}");
            return false; // No permitir la asignación
        } else {
            Log::info("docente {$id_docente}", [
                'modulo_a_asignar' => $modulosPorAsignar,
                'modulos_ya_asignados' => $modulosAsignados,
                'disponibilidad_docente' => $disponibilidadTotal
            ]);
        }



        // 
        $modulosAsignados = DB::table('disponibilidad')
            ->where('id_uc', $id_uc)
            ->where('id_carrera_grado', $id_carrera_grado)
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

        // Verificar si el total excede los módulos semanales permitidos de la uc
        if ($totalModulosAsignados + $modulosNuevos > $modulos_semanales_o) {
            Log::info("modulo inico {$modulo_inicio} y modulo fin {$modulo_fin} total:{$modulosNuevos} sin espcio en modulos semanales");
            return false; // No es posible asignar más módulos
        } else {
            Log::info("modulo inico {$modulo_inicio} y modulo fin {$modulo_fin} total:{$modulosNuevos} tiene modulos disponibles semanales.");
        }


        Log::info("arranco dispo docente");

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
            ->where('id_carrera_grado', $id_carrera_grado)    // Filtrar por el grado
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
            Log::info("grado {$id_carrera_grado} ya está asignada en este rango de tiempo.");
            return false;
        } else {
            Log::info("grado {$id_docente} está disponible para este rango de tiempo.");
        }

        if ($virtual) {
            return "V";
        } else {


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
                    'id' => $disponibilidad->id_disp,

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



    public function verificarModulosActualizacion(
        $dia,
        $modulo,
        $id_docente,
        $id_carrera_grado,
        $id_uc,
        $id_aula,

    ) {
        Log::info("Parámetros recibidos:", [
            'dia' => $dia,
            'modulo' => $modulo,
            'id_docente' => $id_docente,
            'id_carrera_grado' => $id_carrera_grado,
            'id_uc' => $id_uc,
            'id_aula' => $id_aula,
        ]);





        // Verificar si el docente está asignado en el rango de módulos
        $disponibilidadDocente = DB::table('disponibilidad')
            ->where('id_docente', $id_docente) // Filtrar por el docente
            ->where('dia', $dia)               // Filtrar por el día
            ->where(function ($query) use ($modulo) {
                // Verificar si la asignación cubre todo el rango
                $query->where('modulo_inicio', '<=', $modulo)
                    ->where('modulo_fin', '>=',  $modulo);
            })
            ->exists();


        // Log::info("Consulta SQL Docente: " . $disponibilidadDocente->toSql(), ['bindings' => $disponibilidadDocente->getBindings()]);

        // retornar si existe una disponibilidad (es decir, el docente no está  disponible)
        if ($disponibilidadDocente) {
            Log::info("docente {$id_docente} ya está asignada en este rango de tiempo.");
            return false;
        } else {
            Log::info("docente {$id_docente} está disponible para este rango de tiempo.");
        }




        // Verificar las asignaciones para el grado en el mismo día y en el rango de módulos
        $disponibilidadGrado = DB::table('disponibilidad')
            ->where('id_carrera_grado', $id_carrera_grado)    // Filtrar por el grado
            ->where('dia', $dia)               // Filtrar por el día
            ->where(function ($query) use ($modulo) {
                // Verificar si la asignación cubre todo el rango
                $query->where('modulo_inicio', '<=', $modulo)
                    ->where('modulo_fin', '>=',  $modulo);
            })
            ->exists();

        // retornar si existe una disponibilidad (es decir, el grado no está  disponible)
        if ($disponibilidadGrado) {
            Log::info("grado {$id_carrera_grado} ya está asignada en este rango de tiempo.");
            return false;
        } else {
            Log::info("grado {$id_docente} está disponible para este rango de tiempo.");
        }




        // Verificar si el aula tiene asignaciones en el mismo día con los módulos solicitados
        $disponibilidadAula = DB::table('disponibilidad')
            ->where('id_aula', $id_aula)
            ->where('dia', $dia)
            ->where(function ($query) use ($modulo) {
                // Verificar si la asignación cubre todo el rango
                $query->where('modulo_inicio', '<=', $modulo)
                    ->where('modulo_fin', '>=',  $modulo);
            })
            ->exists();
        // retornar si no existe una disponibilidad (es decir, el aula está disponible)
        if ($disponibilidadAula) {
            Log::info("Aula {$id_aula} ya está asignada en este rango de tiempo.");
        } else {
            Log::info("Aula {$id_aula} está disponible para este rango de tiempo.");
            return $id_aula;
        }

        return false;

        // return 1;

    }
    public function actualizarDisponibilidad($disponibilidades)

    {
        DB::beginTransaction();
        try {
            if (count($disponibilidades) !== 2) {
                DB::rollBack();
                return response()->json(['error' => 'Se requieren exactamente dos disponibilidades para el intercambio'], 400);
            }
            $disponibilidad1 = $disponibilidades[0];
            $disponibilidad2 = $disponibilidades[1];

            $id_disp_1 = $disponibilidad1['id_disp'];
            $dia_1 = $disponibilidad1['dia'];
            $modulo_1 = $disponibilidad1['modulo'];

            $id_disp_2 = $disponibilidad2['id_disp'];
            $dia_2 = $disponibilidad2['dia'];
            $modulo_2 = $disponibilidad2['modulo'];

            $disponibilidadActual1 = DB::table('disponibilidad')->where('id_disp', $id_disp_1)->first();
            $disponibilidadActual2 = DB::table('disponibilidad')->where('id_disp', $id_disp_2)->first();

            $id_docente_1 = $disponibilidadActual1->id_docente;
            $id_docente_2 = $disponibilidadActual2->id_docente;

            $id_carrera_grado_1 = $disponibilidadActual1->id_carrera_grado;
            $id_carrera_grado_2 = $disponibilidadActual2->id_carrera_grado;

            $id_uc_1 = $disponibilidadActual1->id_uc;
            $id_uc_2 = $disponibilidadActual2->id_uc;


            $id_aula_1 = $disponibilidadActual1->id_aula;
            $id_aula_2 = $disponibilidadActual2->id_aula;

            $DeleteRequest = $this->eliminarDisponibilidad([$disponibilidad1, $disponibilidad2]);


            if ($DeleteRequest->getStatusCode() != 200) {
                Log::info("entra 200");
                db::rollBack();

                return $DeleteRequest;
            }


            $resultado1 = $this->verificarModulosActualizacion(
                $dia_2,
                $modulo_2,
                $id_docente_1,
                $id_carrera_grado_2,
                $id_uc_1,
                $id_aula_2,

            );


            $resultado2 = $this->verificarModulosActualizacion(
                $dia_1,
                $modulo_1,
                $id_docente_2,
                $id_carrera_grado_1,
                $id_uc_2,
                $id_aula_1,


            );
            Log::info("Disponibilidades insertadas: res1=$resultado1, res2=$resultado2");


            if (!$resultado1 || !$resultado2) {
                Log::info("Conflicto detectado durante la verificación de disponibilidades");
                return response()->json(['error' => 'Conflicto detectado, no se puede realizar el intercambio'], 400);
            }


            $id_disp_1 = DB::table('disponibilidad')->insertGetId([
                'id_docente' => $id_docente_1,
                'id_carrera_grado' => $id_carrera_grado_2,
                'id_uc' => $id_uc_1,
                'id_aula' => $id_aula_2,
                'dia' => $dia_2,
                'modulo_inicio' => $modulo_2,
                'modulo_fin' => $modulo_2 // Ajusta según la duración del módulo
            ]);

            $id_disp_2 = DB::table('disponibilidad')->insertGetId([
                'id_docente' => $id_docente_2,
                'id_carrera_grado' => $id_carrera_grado_1,
                'id_uc' => $id_uc_2,
                'id_aula' => $id_aula_1,
                'dia' => $dia_1,
                'modulo_inicio' => $modulo_1,
                'modulo_fin' => $modulo_1 // Ajusta según la duración del módulo
            ]);

            Log::info("Disponibilidades insertadas: ID1=$id_disp_1, ID2=$id_disp_2");

            $this->horarioService->guardarHorarios($dia_1, $modulo_1, $modulo_1, $id_disp_1);
            $this->horarioService->guardarHorarios($dia_2, $modulo_2, $modulo_2, $id_disp_2);

            DB::commit();
            return  $this->horarioService->obtenerTodosHorarios();
        } catch (Exception $e) {
            Log::error('Error al actualizar la disponibilidad: ' . $e->getMessage());
            db::rollBack();
            return response()->json(['error' => 'Hubo un error al actualizar la disponibilidad'], 500);
        }
    }

    public function eliminarDisponibilidad($disponibilidades)
    {
        try {
            // Recorrer el array de disponibilidades
            usort($disponibilidades, function ($a, $b) {
                return $a['id_disp'] <=> $b['id_disp']; // Ordena en orden ascendente
            });
            foreach ($disponibilidades as $disponibilidad) {
                // Verificar si la disponibilidad existe antes de intentar eliminarla
                $disponibilidadBD = Disponibilidad::find($disponibilidad['id_disp']);

                // Log::info($disponibilidad);
                // Log::info($disponibilidadBD);


                // Si el modulo_inicio es igual a modulo_fin, se elimina el registro
                if ($disponibilidadBD->modulo_inicio == $disponibilidadBD->modulo_fin) {
                    $disponibilidadBD->delete();
                    Log::info('Registro eliminado: ' . $disponibilidadBD->id_disp);
                }
                // Si el módulo es igual a modulo_inicio, incrementamos modulo_inicio
                elseif ($disponibilidad['modulo'] == $disponibilidadBD->modulo_inicio) {
                    $disponibilidadBD->modulo_inicio += 1;
                    $disponibilidadBD->save();
                    Log::info('Modulo_inicio actualizado: ' . $disponibilidadBD->modulo_inicio);
                }
                // Si el módulo es igual a modulo_fin, decrementamos modulo_fin
                elseif ($disponibilidad['modulo'] == $disponibilidadBD->modulo_fin) {
                    $disponibilidadBD->modulo_fin -= 1;
                    $disponibilidadBD->save();
                    Log::info('Modulo_fin actualizado: ' . $disponibilidadBD->modulo_fin);
                }
                // Verificar si el módulo que se quiere eliminar está dentro del rango
                elseif ($disponibilidad['modulo'] > $disponibilidadBD->modulo_inicio && $disponibilidad['modulo'] < $disponibilidadBD->modulo_fin) {
                    $test = [];
                    foreach ($disponibilidades as &$mod) {
                        if ($mod['modulo'] > $disponibilidad['modulo'] && $mod['id_disp'] == $disponibilidad['id_disp']) {
                            $test[] = &$mod; // Agregar todas las coincidencias al array
                        }
                    }



                    // Crear un nuevo registro con el módulo posterior
                    $nuevoRegistro = new Disponibilidad();
                    $nuevoRegistro->id_uc = $disponibilidadBD->id_uc;
                    $nuevoRegistro->id_docente = $disponibilidadBD->id_docente;
                    $nuevoRegistro->id_h_p_d = $disponibilidadBD->id_h_p_d;
                    $nuevoRegistro->id_aula = $disponibilidadBD->id_aula;
                    $nuevoRegistro->dia = $disponibilidadBD->dia;
                    $nuevoRegistro->modulo_inicio = $disponibilidad['modulo'] + 1; // El módulo posterior
                    $nuevoRegistro->modulo_fin = $disponibilidadBD->modulo_fin;
                    $nuevoRegistro->id_carrera_grado = $disponibilidadBD->id_carrera_grado;

                    // Actualizar el registro actual
                    $disponibilidadBD->modulo_fin = $disponibilidad['modulo'] - 1; // Ajustamos el modulo_fin
                    $disponibilidadBD->save();

                    DB::table('horario')
                        ->where('id_disp', $disponibilidadBD->id_disp)
                        ->update(['modulo_fin' => $disponibilidadBD->modulo_fin]);

                    $nuevoRegistro->save();
                    $this->horarioService->guardarHorarios($nuevoRegistro->dia, $nuevoRegistro->modulo_inicio, $nuevoRegistro->modulo_fin,  $nuevoRegistro->id_disp);


                    Log::info('Registro actualizado y nuevo registro creado.');
                    if ($test) {
                        Log::info($test);
                        foreach ($test as &$t) {
                            $t['id_disp'] = $nuevoRegistro->id_disp;
                        }
                        Log::info($test);
                    }
                }
            }


            // Si todo ha ido bien, retornamos una respuesta exitosa
            return  $this->horarioService->obtenerTodosHorarios();
        } catch (\Exception $e) {
            // Si ocurre algún error, capturamos la excepción y devolvemos un mensaje de error
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Ocurrió un error al intentar eliminar las disponibilidades.',
            ], 500);
        }
    }

    public function getDisponibles($id_carrera_grado, $modulo, $dia)
    {
        Log::info("carrera_g:{$id_carrera_grado}, modulo:{$modulo}, dia:{$dia}");

        try {
            //code...

            // Subconsulta para encontrar todas las aulas ocupadas
            $aulasOcupadas = DB::table('disponibilidad')

                ->where('dia', $dia) // Filtra por el día
                ->where(function ($query) use ($modulo) {
                    $query->where(function ($q) use ($modulo) {
                        $q->where('modulo_inicio', '<=', $modulo)
                            ->where('modulo_fin', '>=', $modulo);
                    })
                        ->orWhere(function ($q) use ($modulo) {
                            $q->where('modulo_inicio', '<=', $modulo)
                                ->where('modulo_fin', '>=', $modulo);
                        });
                })
                ->pluck('id_aula'); // Obtiene las aulas ocupadas

            // Consulta  para encontrar las aulas disponibles
            $aulasDisponibles = DB::table('aula')
                ->whereNotIn('id_aula', $aulasOcupadas) // Excluye las aulas ocupadas
                ->get();



            // filtra las uc que son para el grado
            $ucDisponibles = DB::table('grado_uc')
                ->join('unidad_curricular', 'grado_uc.id_uc', '=', 'unidad_curricular.id_uc')
                ->where('grado_uc.id_carrera_grado', $id_carrera_grado)
                ->select('unidad_curricular.id_uc', 'unidad_curricular.unidad_curricular') // Seleccionar solo nombre y ID
                ->get();






            return response()->json([
                'success' => true,
                'ucDisponibles' => $ucDisponibles,
                'aulasDisponibles' => $aulasDisponibles,
            ], 200);
        } catch (\Exception $e) {

            Log::error("Error en getDisponibles: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al obtener las disponibilidades.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getDocentesDisponibles($id_uc, $modulo, $dia)
    {
        Log::info("iduc:{$id_uc}, modulo:{$modulo}, dia:{$dia}");

        try {

            // Consulta para encontrar los docentes que las uc obtenidas anteriormente
            $docentes = DB::table('docente_uc')
                ->where('id_uc', $id_uc) // Filtra por las UC disponibles
                ->pluck('id_docente'); // Extrae solo los IDs

            // obtiene los docente ocupados
            $docentesOcupados = DB::table("disponibilidad")
                ->where('dia', $dia) // Filtra por el día
                ->where(function ($query) use ($modulo) {
                    $query->where(function ($q) use ($modulo) {
                        $q->where('modulo_inicio', '<=', $modulo)
                            ->where('modulo_fin', '>=', $modulo);
                    })
                        ->orWhere(function ($q) use ($modulo) {
                            $q->where('modulo_inicio', '<=', $modulo)
                                ->where('modulo_fin', '>=', $modulo);
                        });
                })
                ->pluck('id_docente');

            // obtiende los deocentes disponibles
            $docentesDisponibles = DB::table('docente')
                ->whereIn('id_docente', $docentes) // Filtra los docentes asociados a las UC disponibles
                ->whereNotIn('id_docente', $docentesOcupados) // Excluye los docentes ocupados
                ->get();



            return response()->json([
                'success' => true,
                'docentesDisponibles' => $docentesDisponibles,

            ], 200);
        } catch (\Exception $e) {

            Log::error("Error en getDisponibles: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al obtener las disponibilidades.',
                'error' => $e->getMessage(),
            ], 500);
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
