<?php

namespace App\Services;

use App\Repositories\AlumnoPlanRepository;
use App\Mappers\AlumnoPlanMapper;
use App\Models\AlumnoPlan;
use Exception;
use Illuminate\Support\Facades\Log;

class AlumnoPlanService implements AlumnoPlanRepository
{
    protected $alumnoPlanMapper;

    public function __construct(AlumnoPlanMapper $alumnoPlanMapper)
    {
        $this->alumnoPlanMapper = $alumnoPlanMapper;
    }

    public function obtenerTodosAlumnoPlan()
    {
        try {
            $alumnosPlan = AlumnoPlan::all();
            return response()->json($alumnosPlan, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los alumnosPlan: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los alumnosPlan'], 500);
        }
    }

    public function obtenerAlumnoPlanPorIdAlumno($id_alumno)
    {
        $alumnoPlan = AlumnoPlan::where('id_alumno', $id_alumno)->first();
        if (!$alumnoPlan) {
            return response()->json(['error' => 'AlumnoPlan no encontrado'], 404);
        }
        try {
            return response()->json($alumnoPlan, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el alumnoPlan: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el alumnoPlan'], 500);
        }
    }

    public function obtenerAlumnoPlanPorIdPlan($id_plan)
    {
        $alumnoPlan = AlumnoPlan::where('id_plan', $id_plan)->first();
        if (!$alumnoPlan) {
            return response()->json(['error' => 'AlumnoPlan no encontrado'], 404);
        }
        try {
            return response()->json($alumnoPlan, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el alumnoPlan: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el alumnoPlan'], 500);
        }
    }

    public function guardarAlumnoPlan($request)
    {
        try {
            $alumnoPlanData = $request->all();
            $alumnoPlanModel = $this->alumnoPlanMapper->toAlumnoUC($alumnoPlanData);
            $alumnoPlanModel->save();
            return response()->json($alumnoPlanModel, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar el alumnoPlan: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el alumnoPlan'], 500);
        }
    }

    public function eliminarAlumnoPlanPorIdAlumno($id_alumno)
    {
        $alumnoPlan = AlumnoPlan::where('id_alumno', $id_alumno)->first();
        if (!$alumnoPlan) {
            return response()->json(['error' => 'AlumnoPlan no encontrado'], 404);
        }
        try {
            $alumnoPlan->delete();
            return response()->json(['success' => 'AlumnoPlan eliminado correctamente'], 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar el alumnoPlan: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el alumnoPlan'], 500);
        }
    }

    public function eliminarAlumnoPlanPorIdPlan($id_plan)
    {
        $alumnoPlan = AlumnoPlan::where('id_plan', $id_plan)->first();
        if (!$alumnoPlan) {
            return response()->json(['error' => 'AlumnoPlan no encontrado'], 404);
        }
        try {
            $alumnoPlan->delete();
            return response()->json(['success' => 'AlumnoPlan eliminado correctamente'], 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar el alumnoPlan: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el alumnoPlan'], 500);
        }
    }
}