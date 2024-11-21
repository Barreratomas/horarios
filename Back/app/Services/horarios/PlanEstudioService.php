<?php

namespace App\Services\horarios;

use App\Repositories\horarios\PlanEstudioRepository;
use App\Mappers\horarios\PlanEstudioMapper;
use App\Models\horarios\PlanEstudio;
use App\Services\CarreraPlanService;
use Exception;
use Illuminate\Support\Facades\Log;

class PlanEstudioService implements PlanEstudioRepository
{
    private $planEstudioMapper;
    private $CarreraPlanService;


    public function __construct(PlanEstudioMapper $planEstudioMapper,CarreraPlanService $CarreraPlanService)
    {
        $this->planEstudioMapper = $planEstudioMapper;
        $this->CarreraPlanService = $CarreraPlanService;

    }


    public function obtenerPlanEstudio()
{
    try {
        // Traer los planes con unidades curriculares y carreras
        $planes = PlanEstudio::with(['ucPlan.unidadCurricular', 'carreraPlan.carrera'])->get();

        // Transformar datos para devolver la estructura combinada
        $result = $planes->map(function ($plan) {
            return [
                'id_plan' => $plan->id_plan,
                'detalle' => $plan->detalle,
                'fecha_inicio' => $plan->fecha_inicio,
                'fecha_fin' => $plan->fecha_fin,
                'unidades_curriculares' => $plan->ucPlan->map(function ($ucPlan) {
                    return $ucPlan->unidadCurricular;
                }),
                'carreras' => $plan->carreraPlan->map(function ($carreraPlan) {
                    return $carreraPlan->carrera;
                }),
            ];
        });

        return response()->json($result, 200);
    } catch (Exception $e) {
        Log::error('Error al obtener los planes con detalles: ' . $e->getMessage());
        return response()->json(['error' => 'Hubo un error al obtener los datos'], 500);
    }
}

    public function obtenerPlanEstudioPorId($id)
    {
        $planEstudio = PlanEstudio::find($id);
        if (!$planEstudio) {
            return response()->json(['error' => 'planEstudio no encontrada'], 404);
        }
        try {
            return response()->json($planEstudio, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el planEstudio: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el planEstudio'], 500);
        }
    }

    public function guardarPlanEstudio($data)
    {
        try {
            // $data es el array con 'detalle', 'fecha_inicio', 'fecha_fin'
            $planEstudio = new PlanEstudio($data);  // Aquí usamos el array de datos directamente
            
            // Si tienes un mapper para convertir el modelo, lo usas aquí
            $planEstudioModel = $this->planEstudioMapper->toPlanEstudio($planEstudio);
            
            // Guardar el modelo en la base de datos
            $planEstudioModel->save();
            
            // Retornar la respuesta con el modelo guardado
            return response()->json($planEstudioModel, 201);
        } catch (Exception $e) {
            // Loguear el error si ocurre
            Log::error('Error al guardar el planEstudio: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el planEstudio'], 500);
        }
    }


    public function actualizarPlanEstudio($request, $id)
    {
        $planEstudio = PlanEstudio::find($id);
        if (!$planEstudio) {
            return response()->json(['error' => 'planEstudio no encontrada'], 404);
        }
        try {
            $planEstudio->update($request->all());
            return response()->json($planEstudio, 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar el planEstudio: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar el planEstudio'], 500);
        }
    }



    public function eliminarPlanEstudio($id)
    {
        try {
            $planEstudio = PlanEstudio::find($id);

            if ($planEstudio) {
                $this->CarreraPlanService->eliminarCarreraPlanPorIdPlan($id);
                $planEstudio->delete();
                return response()->json(['success' => 'Se eliminó el planEstudio'], 200);
            } else {
                return response()->json(['error' => 'No existe el planEstudio'], 404);
            }
        } catch (Exception $e) {
            Log::error('Error al eliminar el planEstudio: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el planEstudio'], 500);
        }
    }
}

