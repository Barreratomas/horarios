<?php

namespace App\Services;

use App\Http\Controllers\horarios\GradoController;
use App\Repositories\AlumnoGradoRepository;
use App\Mappers\AlumnoGradoMapper;
use App\Models\AlumnoGrado;
use App\Services\horarios\GradoService;
use Exception;
use Illuminate\Support\Facades\Log;

class AlumnoGradoService implements AlumnoGradoRepository
{
    protected $alumnoGradoMapper;
    public $gradoService;
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
        
        //$gradoResponse = $this->gradoService->obtenerGradoPorId($id_grado);
        $gradoResponse = $this->gradoService->obtenerGradoPorId($id_grado);
        /*
        $grado = $gradoResponse->getData();
        $capacidad = $grado->capacidad;
        
        $alumnosGrados = $this->obtenerAlumnoGradoPorIdGrado($id_grado);
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
            */
            return response()->json($gradoResponse, 201);
        
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

    //asignar todos los alumnos a sus respectivos grados.
    public function asignarAlumnosGrados($alumnos, $grados)
    {
        try {
            foreach ($alumnos as $alumno) {
                foreach ($grados as $grado) {
                    $alumnoGrado = new AlumnoGrado();
                    $alumnoGrado->id_alumno = $alumno->id_alumno;
                    $alumnoGrado->id_grado = $grado->id_grado;
                    $alumnoGrado->save();
                }
            }
            return response()->json(['success' => 'Alumnos asignados a sus respectivos grados'], 200);
        } catch (Exception $e) {
            Log::error('Error al asignar los alumnos a sus respectivos grados: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al asignar los alumnos a sus respectivos grados'], 500);
        }
    }

}
