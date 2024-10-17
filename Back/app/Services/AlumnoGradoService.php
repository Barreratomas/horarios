<?php

namespace App\Services;

use App\Http\Controllers\horarios\GradoController;
use App\Models\horarios\UCPlan;
use App\Repositories\AlumnoGradoRepository;
use App\Mappers\AlumnoGradoMapper;
use App\Models\AlumnoGrado;
use App\Services\horarios\GradoService;
use App\Models\horarios\Grado;
use App\Models\AlumnoUC;
use App\Models\horarios\CarreraPlan;
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

    public function eliminarAlumnoGradoPorIdAlumno($id_alumno)
    {
        $alumnoGrado = AlumnoGrado::where('id_alumno', $id_alumno)->first();
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

    //asignar todos los alumnos a sus respectivas carreras partiendo de la tabla de alumnos_uc
    public function asignarAlumnosACarreras()
    {
        $alumnosUC = AlumnoUC::all();   // Relación alumnos con UCs
        $ucsPlan = UCPlan::all();        // Relación UCs con Planes
        $carrerasPlan = CarreraPlan::all();  // Relación Planes con Carreras
        $gradoUC = GradoUC::all();  // Relación entre grados y UCs

        $dataToInsertAlumnoCarrera = [];
        $dataToInsertAlumnoGrado = [];  // Para asociar alumnos con grados

        // Recorrer los alumnosUC y sus relaciones
        foreach ($alumnosUC as $alumnoUC) {
            $id_alumno = $alumnoUC->id_alumno;
            $id_uc = $alumnoUC->id_uc;

            // Buscar el id_plan relacionado con el id_uc
            $ucPlan = $ucsPlan->firstWhere('id_uc', $id_uc);

            if ($ucPlan) {
                $id_plan = $ucPlan->id_plan;
                Log::info("Plan para UC $id_uc: $id_plan");

                // Buscar la carrera relacionada con ese plan
                $carreraPlan = $carrerasPlan->firstWhere('id_plan', $id_plan);

                if ($carreraPlan) {
                    $id_carrera = $carreraPlan->id_carrera;
                    Log::info("Carrera para Plan $id_plan: $id_carrera");

                    // Almacenar los datos para insertar en alumno_carrera
                    $dataToInsertAlumnoCarrera[] = [
                        'id_alumno' => $id_alumno,
                        'id_carrera' => $id_carrera
                    ];

                    // Buscar el grado asociado a la UC del alumno
                    $gradoUCEntry = $gradoUC->firstWhere('id_uc', $id_uc);

                    if ($gradoUCEntry) {
                        $id_grado = $gradoUCEntry->id_grado;
                        Log::info("Grado para UC $id_uc: $id_grado");

                        // Crear una nueva instancia de AlumnoGrado y guardarla
                        AlumnoGrado::create([
                            'id_alumno' => $id_alumno,
                            'id_grado' => $id_grado
                        ]);

                        Log::info("Alumno $id_alumno asignado al grado $id_grado.");
                    } else {
                        Log::warning("No se encontró grado para UC: $id_uc");
                    }
                } else {
                    Log::warning("No se encontró carrera para Plan: $id_plan");
                }
            } else {
                Log::warning("No se encontró plan para UC: $id_uc");
            }
        }

        // Insertar en la tabla alumno_carrera (aquí mantenemos la inserción en bloque)
        if (!empty($dataToInsertAlumnoCarrera)) {
            DB::table('alumno_carrera')->insert($dataToInsertAlumnoCarrera);
            Log::info("Datos insertados en la tabla alumno_carrera correctamente.");
        } else {
            Log::warning("No se encontraron datos para insertar en alumno_carrera.");
        }
    }
}
