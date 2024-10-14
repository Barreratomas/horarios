<?php

namespace App\Services\horarios;

use App\Repositories\horarios\AlumnoGradoRepository;
use App\Mappers\horarios\AlumnoGradoMapper;
use App\Models\AlumnoGrado;
use Exception;
use Illuminate\Support\Facades\Log;

class AlumnoGradoService implements AlumnoGradoRepository
{
    protected $alumnoGradoMapper;

    public function __construct(AlumnoGradoMapper $alumnoGradoMapper)
    {
        $this->alumnoGradoMapper = $alumnoGradoMapper;
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
        $alumnoGrado = AlumnoGrado::where('id_alumno', $id_alumno)->first();
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
        $alumnoGrado = AlumnoGrado::where('id_grado', $id_grado)->first();
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

    public function guardarAlumnoGrado($request)
    {
        try {
            $alumnoGradoData = $request->all();
            $alumnoGradoModel = $this->alumnoGradoMapper->toAlumnoGrado($alumnoGradoData);
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
}
