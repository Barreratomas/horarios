<?php

namespace App\Services;

use App\Mappers\CarreraPlanMapper;
use App\Models\CarreraPlan;

use Illuminate\Support\Facades\Log;
use App\Repositories\CarreraPlanRepository;
use App\Services\horarios\CarreraService;
use Exception;

class CarreraPlanService
{
    protected $carreraPlanMapper;
    protected $carreraService;

    public function __construct(
        CarreraPlanMapper $carreraPlanMapper,
        CarreraService $carreraService
    ) {
        $this->carreraPlanMapper = $carreraPlanMapper;
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

    public function guardarCarreraPlan( $id_plan,$id_carrera)
    {
        $carrera = $this->carreraService->obtenerCarreraPorId($id_carrera);

        if (!$carrera) {
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

    public function eliminarCarreraPlanPorIdPlan($id_plan)
    {
        try {
            // Eliminar todos los registros de CarreraPlan que corresponden al id_plan
            $deletedCount = CarreraPlan::where('id_plan', $id_plan)->delete();
    
            // Si no se eliminaron registros, retornar un mensaje informativo
            if ($deletedCount === 0) {
                return response()->json(['message' => 'No se encontraron relaciones de CarreraPlan para eliminar'], 404);
            }
    
            return response()->json(['success' => 'Se eliminaron las relaciones de CarreraPlan'], 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar las relaciones de CarreraPlan para el plan de estudio ' . $id_plan . ': ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar las relaciones de CarreraPlan'], 500);
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
