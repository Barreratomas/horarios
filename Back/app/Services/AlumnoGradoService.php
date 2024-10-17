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
use Exception;
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
        // Pasamos primero de alumno_uc a unidadCurricular
        $alumnosUC = AlumnoUC::all();
        $ucsPlan = UCPlan::all();

        foreach ($alumnosUC as $alumnoUC) {
            $id_alumno = $alumnoUC->id_alumno;
            $id_uc = $alumnoUC->id_uc;
            $id_carrera = $id_uc->id_plan->id_carrera;
            Log::info('Alumno: ' . $id_alumno . ' UC: ' . $id_uc . ' Carrera: ' . $id_carrera);

            
/*
            $alumnoGrado = new AlumnoGrado();
            $alumnoGrado->id_alumno = $id_alumno;
            $alumnoGrado->id_grado = $id_uc;
            $alumnoGrado->save();
            */
        }
        


    }

}
