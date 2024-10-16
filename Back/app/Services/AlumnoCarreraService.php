<?php

namespace App\Services;

use App\Repositories\AlumnoCarreraRepository;
use App\Mappers\AlumnoCarreraMapper;
use App\Models\AlumnoCarrera;
use Exception;
use Illuminate\Support\Facades\Log;

class AlumnoCarreraService implements AlumnoCarreraRepository
{
    protected $alumnoCarreraMapper;

    public function __construct(AlumnoCarreraMapper $alumnoCarreraMapper)
    {
        $this->alumnoCarreraMapper = $alumnoCarreraMapper;
    }

    public function obtenerTodosAlumnoCarrera()
    {
        try {
            $alumnosCarrera = AlumnoCarrera::all();
            return response()->json($alumnosCarrera, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los alumnosCarrera: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los alumnosCarrera'], 500);
        }
    }

    public function obtenerAlumnoCarreraPorIdAlumno($id_alumno)
    {
        $alumnoCarrera = AlumnoCarrera::where('id_alumno', $id_alumno)->first();
        if (!$alumnoCarrera) {
            return response()->json(['error' => 'AlumnoCarrera no encontrado'], 404);
        }
        try {
            return response()->json($alumnoCarrera, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el alumnoCarrera: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el alumnoCarrera'], 500);
        }
    }

    public function obtenerAlumnoCarreraPorIdCarrera($id_carrera)
    {
        $alumnoCarrera = AlumnoCarrera::where('id_carrera', $id_carrera)->first();
        if (!$alumnoCarrera) {
            return response()->json(['error' => 'AlumnoCarrera no encontrado'], 404);
        }
        try {
            return response()->json($alumnoCarrera, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el alumnoCarrera: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el alumnoCarrera'], 500);
        }
    }

    public function guardarAlumnoCarrera($request)
    {
        try {
            $alumnoCarreraData = $request->all();
            $alumnoCarreraModel = $this->alumnoCarreraMapper->toAlumnoCarrera($alumnoCarreraData);
            $alumnoCarreraModel->save();
            return response()->json($alumnoCarreraModel, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar el alumnoCarrera: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el alumnoCarrera'], 500);
        }
    }

    public function eliminarAlumnoCarreraPorIdAlumno($id_alumno)
    {
        $alumnoCarrera = AlumnoCarrera::where('id_alumno', $id_alumno)->first();
        if (!$alumnoCarrera) {
            return response()->json(['error' => 'AlumnoCarrera no encontrado'], 404);
        }
        try {
            $alumnoCarrera->delete();
            return response()->json(['success' => 'AlumnoCarrera eliminado correctamente'], 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar el alumnoCarrera: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el alumnoCarrera'], 500);
        }
    }

    public function eliminarAlumnoCarreraPorIdCarrera($id_carrera)
    {
        $alumnoCarrera = AlumnoCarrera::where('id_carrera', $id_carrera)->first();
        if (!$alumnoCarrera) {
            return response()->json(['error' => 'AlumnoCarrera no encontrado'], 404);
        }
        try {
            $alumnoCarrera->delete();
            return response()->json(['success' => 'AlumnoCarrera eliminado correctamente'], 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar el alumnoCarrera: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el alumnoCarrera'], 500);
        }
    }
}
