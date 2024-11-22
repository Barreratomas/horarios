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
                    'email' => $alumnoGrado->alumno->email,
                    'telefono' => $alumnoGrado->alumno->telefono,
                    'genero' => $alumnoGrado->alumno->genero,
                    'fecha_nac' => $alumnoGrado->alumno->fecha_nac,
                    'nacionalidad' => $alumnoGrado->alumno->nacionalidad,
                    'direccion' => $alumnoGrado->alumno->direccion,
                    'id_localidad' => $alumnoGrado->alumno->id_localidad,
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

    public function eliminarAlumnoGradoPorIdAlumnoYIdGrado($id_alumno, $id_grado)
    {
        try {
            // Usamos el Query Builder para realizar la eliminación con la clave compuesta
            $deleted = DB::table('alumno_grado')
                ->where('id_alumno', $id_alumno)
                ->where('id_grado', $id_grado)
                ->delete();
    
            if ($deleted) {
                return response()->json(['success' => 'AlumnoGrado eliminado correctamente'], 200);
            } else {
                return response()->json(['error' => 'AlumnoGrado no encontrado'], 404);
            }
        } catch (Exception $e) {
            // Si ocurre un error, lo registramos
            Log::error('Error al eliminar el alumnoGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el alumnoGrado'], 500);
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


    public function asignarAlumnosACarreras()
    {
        $alumnosUC = AlumnoUC::all();   // Relación alumnos con UCs
        $ucsPlan = UCPlan::all();        // Relación UCs con Planes
        $carrerasPlan = CarreraPlan::all();  // Relación Planes con Carreras
        $gradoUC = GradoUC::all();  // Relación entre grados y UCs

        $existentesAlumnoCarrera = collect(); // Son diccionarios con los alumnos que ya tienen asignada una carrera y su id
        $existentesAlumnoGrado = collect();  // Son diccionarios con los alumnos que ya tienen asignado un grado y su id

        foreach ($alumnosUC as $alumnoUC) {
            $id_alumno = $alumnoUC->id_alumno;
            $id_uc = $alumnoUC->id_uc;

            
            $ucPlan = $ucsPlan->firstWhere('id_uc', $id_uc);
            if($ucPlan){
                $id_plan = $ucPlan->id_plan;
                Log::info("Plan para UC $id_uc: $id_plan");

                $carreraPlan = $carrerasPlan->firstWhere('id_plan', $id_plan);
                if ($carreraPlan) {
                    $id_carrera = $carreraPlan->id_carrera;
                    Log::info("Carrera para Plan $id_plan: $id_carrera");


                    $alumnoCarreraExistente = AlumnoCarrera::where('id_alumno', $id_alumno)
                                                        ->where('id_carrera', $id_carrera)
                                                        ->exists();
                    if($alumnoCarreraExistente != 2){
                        AlumnoCarrera::create([
                            'id_alumno' => $id_alumno,
                            'id_carrera' => $id_carrera
                        ]);
                    } else {
                        $existentesAlumnoCarrera->put("$id_alumno.$id_carrera", [$id_alumno, $id_carrera]);
                        Log::info("Ya existe la carrera para el alumno $id_alumno y la carrera $id_carrera");
                    }
                    
                    $gradoUCEntry = $gradoUC->firstWhere('id_uc', $id_uc);
                        if ($gradoUCEntry) {
                            $id_grado = $gradoUCEntry->id_grado;
                            Log::info("Grado para UC $id_uc: $id_grado"); 

                            $alumnoGradoExistente = AlumnoGrado::where('id_alumno', $id_alumno)
                                                        ->where('id_grado', $id_grado)
                                                        ->exists();
                            if($alumnoGradoExistente != 2){
                                AlumnoGrado::create([
                                    'id_alumno' => $id_alumno,
                                    'id_grado' => $id_grado
                                ]);
                            } else{
                                $existentesAlumnoGrado->put("$id_alumno.$id_grado", [$id_alumno, $id_grado]);
                                Log::info("Ya existe el grado para el alumno $id_alumno y el grado $id_grado");
                            }
                            
                        }

                        $existentesAlumnoCarrera->each(function ($item, $key) {
                            Log::info("Key: " . (string)$key . ", Value: " . json_encode($item));
                        });
                        
                }
            }
        }
            return response()->json([
                'success' => 'Alumno asignado a carrera y grado correctamente',
                'data' => [
                    'alumnoCarreraExistente' => $existentesAlumnoCarrera->mapWithKeys(function ($item, $key) {
                        return [$key => $item];
                    }),
                    'alumnoGradoExistente' => $existentesAlumnoGrado->mapWithKeys(function ($item, $key) {
                        return [$key => $item];
                    }),
                ]
            ], 200);
    }

<<<<<<< HEAD
=======
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
    

>>>>>>> 8dba832801b4f05b180fbc80571d9f64cb6b460d
}
