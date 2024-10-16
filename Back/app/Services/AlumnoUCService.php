<?php

namespace App\Services;

use App\Repositories\AlumnoUCRepository;
use App\Mappers\AlumnoUCMapper;
use App\Models\AlumnoUC;
use Exception;
use Illuminate\Support\Facades\Log;

class AlumnoUCService implements AlumnoUCRepository
{
    protected $alumnoUCMapper;

    public function __construct(AlumnoUCMapper $alumnoUCMapper)
    {
        $this->alumnoUCMapper = $alumnoUCMapper;
    }

    public function obtenerTodosAlumnoUC()
    {
        try {
            $alumnosUC = AlumnoUC::all();
            return response()->json($alumnosUC, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los alumnosUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los alumnosUC'], 500);
        }
    }

    public function obtenerAlumnoUCPorIdAlumno($id_alumno)
    {
        $alumnoUC = AlumnoUC::where('id_alumno', $id_alumno)->first();
        if (!$alumnoUC) {
            return response()->json(['error' => 'AlumnoUC no encontrado'], 404);
        }
        try {
            return response()->json($alumnoUC, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el alumnoUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el alumnoUC'], 500);
        }
    }

    public function obtenerAlumnoUCPorIdUC($id_uc)
    {
        $alumnoUC = AlumnoUC::where('id_uc', $id_uc)->first();
        if (!$alumnoUC) {
            return response()->json(['error' => 'AlumnoUC no encontrado'], 404);
        }
        try {
            return response()->json($alumnoUC, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el alumnoUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el alumnoUC'], 500);
        }
    }

    public function guardarAlumnoUC($request)
    {
        try {
            $alumnoUCData = $request->all();
            $alumnoUCModel = $this->alumnoUCMapper->toAlumnoUC($alumnoUCData);
            $alumnoUCModel->save();
            return response()->json($alumnoUCModel, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar el alumnoUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el alumnoUC'], 500);
        }
    }

    public function eliminarAlumnoUCPorIdAlumno($id_alumno)
    {
        $alumnoUC = AlumnoUC::where('id_alumno', $id_alumno)->first();
        if (!$alumnoUC) {
            return response()->json(['error' => 'AlumnoUC no encontrado'], 404);
        }
        try {
            $alumnoUC->delete();
            return response()->json(['success' => 'AlumnoUC eliminada correctamente'], 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar el alumnoUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el alumnoUC'], 500);
        }
    }

    public function eliminarAlumnoUCPorIdUC($id_uc)
    {
        $alumnoUC = AlumnoUC::where('id_uc', $id_uc)->first();
        if (!$alumnoUC) {
            return response()->json(['error' => 'AlumnoUC no encontrado'], 404);
        }
        try {
            $alumnoUC->delete();
            return response()->json(['success' => 'AlumnoUC eliminada correctamente'], 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar el alumnoUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el alumnoUC'], 500);
        }
    }
}
