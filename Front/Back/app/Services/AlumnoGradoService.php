<?php

namespace App\Services;

use App\Http\Controllers\horarios\GradoController;
use App\Models\AlumnoCarrera;
use App\Models\horarios\UCPlan;
use App\Repositories\AlumnoGradoRepository;
use App\Mappers\AlumnoGradoMapper;
use App\Models\AlumnoGrado;
use App\Services\horarios\GradoService;
use App\Models\horarios\Grado;
use App\Models\Alumno;
use App\Models\AlumnoUC;
use App\Models\CarreraGrado;
use App\Models\CarreraPlan;
use App\Models\horarios\GradoUC;
use App\Models\horarios\PlanEstudio;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AlumnoGradoService implements AlumnoGradoRepository
{
    protected $alumnoGradoMapper;
    protected $gradoService;
    public function __construct(AlumnoGradoMapper $alumnoGradoMapper, GradoService $gradoService)
    {
        $this->alumnoGradoMapper = $alumnoGradoMapper;
        $this->gradoService = $gradoService;
    }

    public function obtenerTodosAlumnoGrado()
    {
        try {
            $alumnosGrado = AlumnoGrado::all();
            return response()->json($alumnosGrado, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los alumnosGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los alumnosGrado'], 500);
        }
    }
    public function obtenerTodosAlumnoGradoConRelaciones()
    {
        try {
            // Cargar los registros de AlumnoGrado con sus relaciones, incluida la carrera
            $alumnosGrado = AlumnoGrado::with(['alumno', 'grado', 'alumno.alumno_carrera'])->get();

            // Transformar los datos para estructurarlos correctamente
            $result = $alumnosGrado->map(function ($alumnoGrado) {
                return [
                    'id_alumno' => $alumnoGrado->id_alumno,
                    'id_grado' => $alumnoGrado->id_grado,
                    'alumno' => $alumnoGrado->alumno ? [
                        'id_alumno' => $alumnoGrado->alumno->id_alumno,
                        'DNI' => $alumnoGrado->alumno->DNI,
                        'nombre' => $alumnoGrado->alumno->nombre,
                        'apellido' => $alumnoGrado->alumno->apellido,
                        'carrera' => $alumnoGrado->alumno->alumno_carrera->first()->carrera->carrera ?? 'No asignada',
                    ] : null,
                    'grado' => $alumnoGrado->grado ? [
                        'id_grado' => $alumnoGrado->grado->id_grado,
                        'grado' => $alumnoGrado->grado->grado,
                        'division' => $alumnoGrado->grado->division,
                        'detalle' => $alumnoGrado->grado->detalle,
                        'capacidad' => $alumnoGrado->grado->capacidad,
                    ] : null,
                ];
            });

            return response()->json($result, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los alumnosGrado con relaciones: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los datos'], 500);
        }
    }



    public function obtenerAlumnoGradoPorIdAlumno($id_alumno)
    {
        $alumnoGrado = AlumnoGrado::where('id_alumno', $id_alumno)->get();
        if (!$alumnoGrado) {
            return response()->json(['error' => 'AlumnoGrado no encontrado'], 404);
        }
        try {
            return response()->json($alumnoGrado, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el alumnoGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el alumnoGrado'], 500);
        }
    }
    public function obtenerAlumnoGradoPorIdAlumnoConRelaciones($id_alumno)
    {
        $alumnoGrado = AlumnoGrado::where('id_alumno', $id_alumno)->get();
        if (!$alumnoGrado) {
            return response()->json(['error' => 'AlumnoGrado no encontrado'], 404);
        }
        try {
            return response()->json($alumnoGrado, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el alumnoGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el alumnoGrado'], 500);
        }
    }
    public function obtenerAlumnoGradoPorIdGrado($id_grado)
    {
        $alumnoGrado = AlumnoGrado::where('id_grado', $id_grado)->get();
        if (!$alumnoGrado) {
            return response()->json(['error' => 'AlumnoGrado no encontrado'], 404);
        }
        try {
            return response()->json($alumnoGrado, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el alumnoGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el alumnoGrado'], 500);
        }
    }


    public function guardarAlumnoGrado($id_alumno, $id_grado)
    {

        $gradoResponse = $this->gradoService->obtenerGradoPorId($id_grado);
        $grado = $gradoResponse->getData();
        $capacidad = $grado->capacidad;

        $alumnosGrados = $this->obtenerAlumnoGradoPorIdGrado($id_grado)->original;
        //Log::info('Alumnos en el grado: ' . $capacidad . ' ' . json_encode($alumnosGrados));
        //Log::info('Count:' . count($alumnosGrados->original));

        if (count($alumnosGrados) >= $capacidad) {
            return response()->json(['error' => 'El grado ya alcanzó su capacidad máxima'], 400);
        }
        try {
            $alumnoGradoModel = $this->alumnoGradoMapper->toAlumnoGrado($id_alumno, $id_grado);
            $alumnoGradoModel->save();
            return response()->json($alumnoGradoModel, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar el alumnoGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el alumnoGrado'], 500);
        }
    }

    public function actualizarAlumnoGrado($id_alumno, $id_grado_actual, $id_grado_nuevo)
    {
        try {
            // Verificar si el alumno está asignado al grado actual
            $alumnoGradoActual = AlumnoGrado::where('id_alumno', $id_alumno)
                ->where('id_grado', $id_grado_actual)
                ->first();

            if (!$alumnoGradoActual) {
                Log::warning("El alumno con ID $id_alumno no está asignado al grado con ID $id_grado_actual.");
                return response()->json(['error' => 'El alumno no está asignado a este grado'], 404);
            }
            // obtener dni
            $dniAlumno = $alumnoGradoActual->alumno->DNI;

            // obtener detalle grado actual
            $gradoActual = $alumnoGradoActual->grado;
            $detalleGradoActual = $gradoActual->detalle;

            // Verificar la capacidad del nuevo grado
            $gradoResponse = $this->gradoService->obtenerGradoPorId($id_grado_nuevo);
            $gradoNuevo = $gradoResponse->getData();
            $capacidad = $gradoNuevo->capacidad;

            // obtener detalle grado nuevo
            $detalleGradoNuevo = $gradoNuevo->detalle;

            // Verificar si el nuevo grado ya alcanzó su capacidad
            $alumnosEnNuevoGrado = AlumnoGrado::where('id_grado', $id_grado_nuevo)->count();

            if ($alumnosEnNuevoGrado >= $capacidad) {
                return response()->json(['error' => 'El nuevo grado ya alcanzó su capacidad máxima'], 400);
            }

            // Actualizar el grado del alumno usando update con where
            $updated = AlumnoGrado::where('id_alumno', $id_alumno)
                ->where('id_grado', $id_grado_actual)
                ->update(['id_grado' => $id_grado_nuevo]);

            if ($updated) {
                return response()->json([
                    'dni_alumno' => $dniAlumno,
                    'detalle_grado_actual' => $detalleGradoActual,
                    'detalle_grado_nuevo' => $detalleGradoNuevo,
                ], 200);
            } else {
                Log::error("No se pudo actualizar el grado del alumno con DNI $dniAlumno. Error en la actualización.");
                return response()->json(['error' => 'No se pudo actualizar el grado del alumno'], 500);
            }
        } catch (Exception $e) {
            Log::error('Error al actualizar el grado del alumno: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar el grado del alumno'], 500);
        }
    }



    public function eliminarAlumnoGrado($id_alumno, $id_grado)
    {
        try {
            $alumnoGrado = AlumnoGrado::with(['alumno', 'grado'])
                ->where('id_alumno', $id_alumno)
                ->where('id_grado', $id_grado)
                ->first();

            $dniAlumno = $alumnoGrado->alumno->DNI ?? 'N/A';
            $detalleGrado = $alumnoGrado->grado->detalle ?? 'N/A';

            // Intentar eliminar el registro con la clave compuesta
            $deletedCount = AlumnoGrado::where('id_alumno', $id_alumno)
                ->where('id_grado', $id_grado)
                ->delete();

            // Verificar si se eliminó algún registro
            if ($deletedCount === 0) {
                return response()->json(['message' => 'AlumnoGrado no encontrado'], 404);
            }

            return response()->json([
                'dni_alumno' => $dniAlumno,
                'detalle_grado' => $detalleGrado,
            ], 200);
        } catch (Exception $e) {
            // Registrar el error
            Log::error('Error al eliminar el AlumnoGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar la relación AlumnoGrado'], 500);
        }
    }


    public function eliminarAlumnoGradoPorIdGrado($id_grado)
    {
        $alumnoGrado = AlumnoGrado::where('id_grado', $id_grado)->first();
        if (!$alumnoGrado) {
            return response()->json(['error' => 'AlumnoGrado no encontrado'], 404);
        }
        try {
            $alumnoGrado->delete();
            return response()->json(['success' => 'AlumnoGrado eliminado correctamente'], 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar el alumnoGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el alumnoGrado'], 500);
        }
    }

    public function asignarAlumnosACarrerasIngresante()
    {
        // Iniciar la transacción
        DB::beginTransaction();

        try {
            $alumnosCarrera = AlumnoCarrera::all(); // Relación alumnos a carreras

            $existentesAlumnoGrado = collect();  // Colección para almacenar alumnos con grado asignado

            foreach ($alumnosCarrera as $alumnoCa) {
                $ucsPlan = UCPlan::all();               // Relación UCs con Planes
                $carrerasPlan = CarreraPlan::all();     // Relación Planes con Carreras
                $gradoUC = GradoUC::all();              // Relación entre grados y UCs
                $alumnosGrado = AlumnoGrado::all();     // Relación entre alumnos y grados

                $id_alumno = $alumnoCa->id_alumno;
                $id_carrera = $alumnoCa->id_carrera;

                Log::info("Procesando alumno $id_alumno para la carrera $id_carrera.");

                $carrera_plan = $carrerasPlan->firstWhere("id_carrera", $id_carrera);
                if ($carrera_plan) {
                    $id_plan = $carrera_plan->id_plan;

                    Log::info("Plan $id_plan encontrado para la carrera $id_carrera.");

                    $uc_plan = $ucsPlan->where("id_plan", $id_plan);
                    $gradosDelAlumno = $alumnosGrado->where('id_alumno', $id_alumno);
                    $esIngresante = $gradosDelAlumno->isEmpty();  // Si no tiene grados asignados, es un ingresante

                    if ($uc_plan && $esIngresante) {
                        Log::info("Alumno $id_alumno es ingresante y tiene UC plan asociadas.");

                        foreach ($uc_plan as $uc) {
                            $id_uc = $uc->id_uc;

                            $gradosPrimerAnio = $gradoUC->filter(function ($grado) use ($id_uc) {
                                return $grado->id_uc === $id_uc;
                            });

                            foreach ($gradosPrimerAnio as $grado_uc) {
                                $id_carrera_grado = $grado_uc->id_carrera_grado;
                                // Obtener los datos de carrera_grado usando id_carrera_grado
                                $carreraGrado = CarreraGrado::find($id_carrera_grado);

                                if (!$carreraGrado) {
                                    Log::info("No se encontró el grado con id_carrera_grado $id_carrera_grado.");
                                    continue; // Si no existe, saltar al siguiente
                                }

                                // Verificar la capacidad del grado
                                $alumnosEnGrado = AlumnoGrado::where('id_carrera_grado', $id_carrera_grado)->count();
                                $capacidadGrado = $carreraGrado->capacidad;

                                if ($alumnosEnGrado >= $capacidadGrado) {
                                    Log::info("El grado $id_carrera_grado ha alcanzado su capacidad máxima.");
                                    continue; // Saltar este grado
                                }

                                // Verificar si el alumno ya tiene asignado un grado del mismo año
                                $gradoDelMismoAnio = $gradosDelAlumno->first(function ($asignacion) use ($grado_uc) {
                                    return $asignacion->id_grado === $grado_uc->id_grado;
                                });

                                if (!$gradoDelMismoAnio) {
                                    Log::info("Alumno $id_alumno no tiene asignado un grado del mismo año.");

                                    // Verificar si la combinación de alumno y grado ya existe
                                    $existeAlumnoGrado = AlumnoGrado::where('id_alumno', $id_alumno)
                                        ->where('id_carrera_grado', $id_carrera_grado)
                                        ->exists();

                                    if (!$existeAlumnoGrado) {
                                        Log::info("Asignando alumno $id_alumno al grado $id_carrera_grado.");

                                        // Asignar el alumno al grado
                                        AlumnoGrado::create([
                                            'id_alumno' => $id_alumno,
                                            'id_carrera_grado' => $id_carrera_grado,
                                        ]);

                                        // Guardar las materias asociadas al grado en la tabla intermedia alumno_uc
                                        $uc_grado = $gradoUC->where('id_carrera_grado', $id_carrera_grado)->where('id_uc', $id_uc);
                                        foreach ($uc_grado as $grado) {
                                            // Verificar si ya existe el registro antes de insertar
                                            $existeAlumnoUC = AlumnoUC::where('id_alumno', $id_alumno)
                                                ->where('id_uc', $grado->id_uc)
                                                ->exists();
                                            if (!$existeAlumnoUC) {
                                                // Insertar el nuevo registro
                                                AlumnoUC::create([
                                                    'id_alumno' => $id_alumno,
                                                    'id_uc' => $grado->id_uc,
                                                ]);
                                                Log::info("Materia UC asignada al alumno $id_alumno: UC ID {$grado->id_uc}");
                                            }
                                        }

                                        // Salir del bucle para no asignar más grados del mismo año
                                        break;
                                    } else {
                                        Log::info("El alumno $id_alumno ya tiene asignado el grado $id_carrera_grado.");
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Si no hubo errores, hacer commit de la transacción
            DB::commit();
            Log::info("Transacción completada exitosamente.");

            // Respuesta exitosa
            return response()->json([
                'success' => 'Alumno asignado a carrera y grado correctamente',
                'data' => [
                    'alumnoGradoExistente' => $existentesAlumnoGrado->mapWithKeys(function ($item, $key) {
                        return [$key => $item];
                    }),
                ]
            ], 200);
        } catch (\Exception $e) {
            // En caso de error, hacer rollback de la transacción
            DB::rollBack();
            Log::error('Error al asignar alumnos a carreras y grados: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            // Retornar una respuesta con el error
            return response()->json([
                'error' => 'Hubo un problema al procesar la asignación',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function asignarAlumnosACarreras()
    {
        // Iniciar la transacción
        DB::beginTransaction();

        try {
            // Obtener todas las relaciones necesarias
            $alumnosUC = AlumnoUC::all(); // Relación alumnos con UCs
            // $alumnosUC = collect([
            //     (object) ['id_alumno' => 23, 'id_uc' => 3]
            // ]);
            Log::info('Iniciando asignación de alumnos a grados.');

            foreach ($alumnosUC as $alumnoUC) {
                $id_alumno = $alumnoUC->id_alumno;
                Log::info("Procesando alumno con ID: $id_alumno");

                // Obtener todas las materias (UCs) del alumno
                $materiasAlumno = AlumnoUC::where('id_alumno', $id_alumno)->pluck('id_uc');

                foreach ($materiasAlumno as $id_uc) {
                    Log::info("Procesando UC con ID: $id_uc para el alumno $id_alumno");

                    // Obtener la carrera del alumno
                    $id_carrera = AlumnoCarrera::where('id_alumno', $id_alumno)->value('id_carrera');
                    if (!$id_carrera) {
                        Log::warning("Alumno $id_alumno no tiene carrera asociada.");
                        continue; // Si no tiene carrera asociada, saltar al siguiente alumno
                    }
                    Log::info("Procesando id de carrera: $id_carrera para el alumno $id_alumno");

                    // Obtener el plan asociado a la carrera
                    $id_plan = CarreraPlan::where('id_carrera', $id_carrera)->value('id_plan');
                    if (!$id_plan) {
                        Log::warning("Alumno $id_alumno no tiene plan asociado.");
                        continue; // Si no hay plan asociado, pasar al siguiente UC
                    }
                    Log::info("Procesando id del plan: $id_plan para el alumno $id_alumno");

                    // Verificar si la UC pertenece al plan
                    $uc_pertenece = UcPlan::where('id_plan', $id_plan)->where('id_uc', $id_uc)->exists();
                    if (!$uc_pertenece) {
                        Log::warning("La UC $id_uc no pertenece al plan del alumno $id_alumno.");
                        continue; // Si la UC no pertenece al plan, pasar a la siguiente UC
                    }

                    // Obtener todos los grados asociados a la UC
                    $gradosAsociados = GradoUC::where('id_uc', $id_uc)
                        ->join('carrera_grado', 'grado_uc.id_carrera_grado', '=', 'carrera_grado.id_carrera_grado')
                        ->where('carrera_grado.id_carrera', $id_carrera) // Verificar que la carrera coincida
                        ->pluck('carrera_grado.id_carrera_grado');

                    Log::info("UC $id_uc tiene los siguientes grados asociados: " . implode(', ', $gradosAsociados->toArray()));

                    foreach ($gradosAsociados as $id_carrera_grado) {
                        // Obtener el id_grado desde carrera_grado
                        $id_grado = DB::table('carrera_grado')
                            ->where('id_carrera_grado', $id_carrera_grado)
                            ->value('id_grado');

                        if (!$id_grado) {
                            Log::warning("No se encontró el grado para el id_carrera_grado $id_carrera_grado.");
                            continue; // Si no se encuentra el grado, pasar al siguiente
                        }

                        // Verificar que el grado corresponde a la carrera del alumno
                        $carreraAsociada = DB::table('carrera_grado')
                            ->where('id_grado', $id_grado)
                            ->where('id_carrera', $id_carrera)
                            ->exists();
                        if (!$carreraAsociada) {
                            Log::warning("El grado $id_grado no pertenece a la carrera del alumno $id_alumno.");
                            continue; // Si el grado no pertenece a la carrera del alumno, saltar al siguiente
                        }

                        // Obtener el año del grado desde la tabla grado
                        $anio_grado = DB::table('grado')
                            ->where('id_grado', $id_grado)
                            ->value('grado');

                        if (!$anio_grado) {
                            Log::warning("No se pudo determinar el año para el grado $id_grado.");
                            continue; // Si no se puede determinar el año, pasar al siguiente
                        }

                        // Comprobar si el alumno ya está inscrito en un grado de ese mismo año
                        $yaInscritoEnAnio = DB::table('alumno_grado')
                            ->join('carrera_grado', 'alumno_grado.id_carrera_grado', '=', 'carrera_grado.id_carrera_grado')
                            ->join('grado', 'carrera_grado.id_grado', '=', 'grado.id_grado')
                            ->where('alumno_grado.id_alumno', $id_alumno)
                            ->where('grado.grado', $anio_grado)
                            ->exists();

                        if ($yaInscritoEnAnio) {
                            Log::info("El alumno $id_alumno ya está inscrito en un grado del año $anio_grado.");
                            continue; // Si ya está inscrito en un grado de este año, pasar al siguiente grado
                        }

                        $capacidad_grado = CarreraGrado::where('id_grado', $id_grado)
                            ->where('id_carrera', $id_carrera)
                            ->value('capacidad');
                        $alumnos_asignados = AlumnoGrado::where('id_carrera_grado', $id_carrera_grado)
                            ->count();
                        if ($alumnos_asignados >= $capacidad_grado) {
                            Log::info("No se pudo asignar al alumno $id_alumno al grado $id_grado por falta de capacidad.");
                            continue; // Si no hay capacidad, pasar al siguiente grado
                        }

                        // Inscribir al alumno en el grado
                        DB::table('alumno_grado')->insert([
                            'id_alumno' => $id_alumno,
                            'id_carrera_grado' => $id_carrera_grado,
                        ]);
                        Log::info("Alumno $id_alumno inscrito en el grado $id_grado.");
                    }
                }

                // Eliminar grados donde el alumno ya no tenga materias asignadas
                $gradosAlumno = AlumnoGrado::where('id_alumno', $id_alumno)->pluck('id_carrera_grado');
                foreach ($gradosAlumno as $id_carrera_grado) {
                    $materiasEnGrado = GradoUC::where('id_carrera_grado', $id_carrera_grado)->pluck('id_uc');
                    $materiasAlumno = AlumnoUC::where('id_alumno', $id_alumno)->pluck('id_uc');

                    // Verificar si el alumno tiene materias asociadas al grado actual
                    $tieneMaterias = $materiasEnGrado->intersect($materiasAlumno)->isNotEmpty();

                    if (!$tieneMaterias) {
                        // Eliminar la inscripción en el grado si no tiene materias asociadas
                        DB::table('alumno_grado')
                            ->where('id_alumno', $id_alumno)
                            ->where('id_carrera_grado', $id_carrera_grado) // Cambiado para usar id_carrera_grado
                            ->delete();
                        Log::info("Eliminada inscripción del alumno $id_alumno en el grado $id_carrera_grado.");
                    }
                }
            }

            // Confirmar la transacción
            DB::commit();
            Log::info('Asignación de grados completada con éxito.');
            return response()->json(['message' => 'Asignación de grados completada con éxito.'], 200);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            Log::error('Error al asignar grados: ' . $e->getMessage());
            return response()->json(['error' => 'Error al asignar grados: ' . $e->getMessage()], 500);
        }
    }






    //     public function asignarAlumnosACarrerass()
    //     {
    //         // Iniciar la transacción
    //         DB::beginTransaction();

    //             // Obtener las relaciones de alumnos, UCs, carreras, planes, grados, etc.
    //         $alumnosUC = AlumnoUC::all(); // Relación alumnos con UCs
    //         $ucsPlan = UCPlan::all(); // Relación UCs con Planes
    //         $gradoUC = GradoUC::all(); // Relación entre grados y UCs
    //         $alumnosGrado = AlumnoGrado::all(); // Relación entre alumnos y grados

    //         $existentesAlumnoGrado = collect();  // Son diccionarios con los alumnos que ya tienen asignado un grado y su id





    //         foreach ($alumnosUC as $alumnoUC) {
    //             $id_alumno = $alumnoUC->id_alumno;
    //             $id_uc = $alumnoUC->id_uc;

    //             // Obtener la carrera del alumno
    //             $id_carrera = AlumnoCarrera::where('id_alumno', $id_alumno)->value('id_carrera');
    //             if (!$id_carrera) {
    //                 continue; // Si no tiene carrera asociada, saltar al siguiente alumno
    //             }

    //             // Obtener el plan asociado a la carrera
    //             $id_plan = CarreraPlan::where('id_carrera', $id_carrera)->value('id_plan');
    //             if (!$id_plan) {
    //                 continue; // Si no hay plan asociado, pasar al siguiente alumno
    //             }

    //             // Verificar si la UC pertenece al plan
    //             $uc_pertenece = UcPlan::where('id_plan', $id_plan)->where('id_uc', $id_uc)->exists();
    //             if (!$uc_pertenece) {
    //                 continue; // Si la UC no pertenece al plan, pasar al siguiente alumno
    //             }

    //              // Obtener el grado asociado a la UC
    //             $id_grado = GradoUC::where('id_uc', $id_uc)->value('id_grado');
    //             if (!$id_grado) {
    //                 continue; // Si no hay grado asociado, pasar al siguiente alumno
    //             }

    //             // Inscribir al alumno en el grado si no está ya inscrito
    //             $existeInscripcion = DB::table('alumno_grado')
    //             ->where('id_alumno', $id_alumno)
    //             ->where('id_grado', $id_grado)
    //             ->exists();

    //             if (!$existeInscripcion) {
    //                 DB::table('alumno_grado')->insert([
    //                     'id_alumno' => $id_alumno,
    //                     'id_grado' => $id_grado,
    //                 ]);
    //             }
    //             // Obtener el plan de estudio para la UC 
    //             $ucPlan = $ucsPlan->firstWhere('id_uc', $id_uc);

    //             if($ucPlan){
    //                 $id_plan = $ucPlan->id_plan;
    //                 Log::info("Plan para UC $id_uc: $id_plan");

    //                 // Obtener los grados asociados a la UC seleccionada
    //                 $gradoUCEntry = $gradoUC->firstWhere('id_uc', $id_uc);

    //                     if ($gradoUCEntry) {
    //                         $id_grado = $gradoUCEntry->id_grado;
    //                         Log::info("Grado para UC $id_uc: $id_grado"); 

    //                         $alumnoGradoExistente = AlumnoGrado::where('id_alumno', $id_alumno)
    //                                                     ->where('id_grado', $id_grado)
    //                                                     ->exists();
    //                         if($alumnoGradoExistente != 2){
    //                             AlumnoGrado::create([
    //                                 'id_alumno' => $id_alumno,
    //                                 'id_grado' => $id_grado
    //                             ]);
    //                         } else{
    //                             $existentesAlumnoGrado->put("$id_alumno.$id_grado", [$id_alumno, $id_grado]);
    //                             Log::info("Ya existe el grado para el alumno $id_alumno y el grado $id_grado");
    //                         }

    //                          // Ahora verificar si las UCs del alumno coinciden con el grado actual
    //                         $ucGradoExistente = AlumnoUC::where('id_alumno', $id_alumno)
    //                         ->where('id_uc', $id_uc)
    //                         ->exists();
    //                         // EN LA INSCRIPCION A LAS MATERIAS SE TIENE QUE ELIMINAR LAS QUE YA ESTAN APROBADAS Y DEJAR LAS DESAPROBADAS

    //                         if (!$ucGradoExistente) {
    //                             // Si la UC no está asignada, asignar al alumno a las UCs del nuevo grado
    //                             AlumnoUC::create([
    //                                 'id_alumno' => $id_alumno,
    //                                 'id_uc' => $id_uc,
    //                                 'id_plan' => $id_plan
    //                             ]);
    //                         }
    //                     }

    //                         // $existentesAlumnoCarrera->each(function ($item, $key) {
    //                         //     Log::info("Key: " . (string)$key . ", Value: " . json_encode($item));
    //                         // });

    //                 }

    //         }
    //             return response()->json([
    //                 'success' => 'Alumno asignado a carrera y grado correctamente',
    //                 'data' => [
    //                     // 'alumnoCarreraExistente' => $existentesAlumnoCarrera->mapWithKeys(function ($item, $key) {
    //                     //     return [$key => $item];
    //                     // }),
    //                     'alumnoGradoExistente' => $existentesAlumnoGrado->mapWithKeys(function ($item, $key) {
    //                         return [$key => $item];
    //                     }),
    //                 ]
    //             ], 200);
    //     }

    public function cambiarGradoRecursante($dni, $id_grado)
    {
        try {
            // Obtener el alumno por DNI pasado
            $alumno = Alumno::where('dni', $dni)->first();

            if (!$alumno) {
                Log::warning("Alumno con DNI: $dni no encontrado");
                return response()->json(['error' => 'Alumno no encontrado'], 404);
            }

            $id_alumno = $alumno->id_alumno;
            Log::info("Alumno encontrado: ID $id_alumno, verificando si es recursante");

            // Validar si el alumno es recursante 
            $esRecursante = AlumnoUC::where('id_alumno', $id_alumno)->exists();

            if (!$esRecursante) {
                Log::warning("El alumno con ID $id_alumno no es recursante. Proceso detenido.");
                return response()->json(['error' => 'El alumno no es recursante'], 403);
            }

            Log::info("El alumno con ID $id_alumno es recursante. Verificando el grado.");


            // Validar que el grado existe y tiene capacidad
            $grado = Grado::find($id_grado);

            if (!$grado) {
                Log::warning("Grado con ID $id_grado no encontrado");
                return response()->json(['error' => 'Grado no encontrado'], 404);
            }

            $capacidad = $grado->capacidad;
            Log::info("Grado encontrado: ID $id_grado con capacidad máxima de $capacidad");

            $alumnosEnGrado = AlumnoGrado::where('id_grado', $id_grado)->count();
            Log::info("Alumnos actualmente en el grado $id_grado: $alumnosEnGrado");

            if ($alumnosEnGrado >= $capacidad) {
                Log::warning("El grado con ID $id_grado ha alcanzado su capacidad máxima");
                return response()->json(['error' => 'El grado ha alcanzado su capacidad máxima'], 400);
            }

            // Cambiar el grado del alumno
            $alumnoGrado = AlumnoGrado::where('id_alumno', $id_alumno)->first();

            if (!$alumnoGrado) {
                Log::warning("El alumno con ID $id_alumno no tiene una comision asignada actualmente");
                return response()->json(['error' => 'El alumno no tiene asignada ninguna comision actualmente'], 404);
            }

            Log::info("Cambiando comision del alumno con ID $id_alumno al grado $id_grado");

            $alumnoGrado->id_grado = $id_grado;
            $alumnoGrado->save();

            Log::info("Comision cambiada exitosamente para el alumno con ID $id_alumno al grado $id_grado");

            return response()->json(['success' => 'Comision cambiada exitosamente'], 200);
        } catch (Exception $e) {
            Log::error("Error al cambiar la comision para el alumno con DNI: $dni al grado $id_grado. Detalles: " . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al cambiar la comision'], 500);
        }
    }
}
