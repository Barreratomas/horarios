<?php

namespace App\Services;

use App\Mappers\CarreraPlanMapper;
use App\Models\horarios\CarreraPlan;

use Illuminate\Support\Facades\Log;
use App\Repositories\CarreraPlanRepository;
use App\Services\horarios\PlanEstudioService;
use App\Services\horarios\CarreraService;
use Exception;

class CarreraPlanService
{
    protected $carreraPlanMapper;
    protected $planEstudioService;
    protected $carreraService;

    public function __construct(
        CarreraPlanMapper $carreraPlanMapper,
        PlanEstudioService $planEstudioService,
        CarreraService $carreraService
    ) {
        $this->carreraPlanMapper = $carreraPlanMapper;
        $this->planEstudioService = $planEstudioService;
        $this->carreraService = $carreraService;
    }


    public function obtenerTodosCarreraPlan()
    {
        try {
            $carrerasPlanes = CarreraPlan::with(['carrera', 'plan'])->get();
            return response()->json($carrerasPlanes, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los carrerasPlanes: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los carrerasPlanes'], 500);
        }
    }

    public function obtenerCarreraPlanPorIdCarrera($id_carrera)
    {
        try {
            $carreraPlan = CarreraPlan::with(['carrera', 'plan'])
                ->where('id_carrera', $id_carrera)
                ->get();

            if ($carreraPlan->isEmpty()) {
                return response()->json(['error' => 'CarreraPlan no encontrado'], 404);
            }

            return response()->json($carreraPlan, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el carreraPlan: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el carreraPlan'], 500);
        }
    }

    public function obtenerCarreraPlanPorIdPlan($id_plan)
    {
        try {
            $carreraPlan = CarreraPlan::with(['carrera', 'plan'])
                ->where('id_plan', $id_plan)
                ->get();

            if ($carreraPlan->isEmpty()) {
                return response()->json(['error' => 'CarreraPlan no encontrado'], 404);
            }

            return response()->json($carreraPlan, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el carreraPlan: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el carreraPlan'], 500);
        }
    }

    public function guardarCarreraPlan($id_carrera, $id_plan)
    {
        $plan = $this->planEstudioService->obtenerPlanEstudioPorId($id_plan);
        $carrera = $this->carreraService->obtenerCarreraPorId($id_carrera);

        if (!$plan || !$carrera) {
            return response()->json(['error' => 'No se encontró el plan de estudio o la carrera'], 404);
        }

        try {
            $carreraPlan = $this->carreraPlanMapper->toCarreraPlan($id_carrera, $id_plan);
            $carreraPlan->save();
            return response()->json($carreraPlan, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar la carreraPlan: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar la carreraPlan'], 500);
        }
    }

    public function eliminarCarreraPlanPorIdCarreraYPlan($id_carrera, $id_plan)
    {
        $carreraPlan = CarreraPlan::where('id_carrera', $id_carrera)
            ->where('id_plan', $id_plan)
            ->first();

        if (!$carreraPlan) {
            return response()->json(['error' => 'CarreraPlan no encontrado'], 404);
        }

        try {
            $carreraPlan->delete();
            return response()->json($carreraPlan, 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar la carreraPlan: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar la carreraPlan'], 500);
        }
    }
}
