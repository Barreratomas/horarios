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
            $planEstudios = PlanEstudio::all();
            return response()->json($planEstudios, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los planEstudios: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los planEstudios'], 500);
        }
    }
    public function obtenerPlanEstudioConRelaciones()
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
        try {
            $planEstudios = PlanEstudio::find($id);
            return response()->json($planEstudios, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el planEstudio: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el planEstudio'], 500);
        }
    }
    public function obtenerPlanEstudioPorIdConRelaciones($id)
{
    try {
        // Buscar el plan de estudio por id, con sus relaciones
        $planEstudio = PlanEstudio::with(['ucPlan.unidadCurricular', 'carreraPlan.carrera'])
                                  ->find($id);

        if (!$planEstudio) {
            return response()->json(['error' => 'Plan de estudio no encontrado'], 404);
        }

        // Transformar los datos para devolver la estructura combinada
        $result = [
            'id_plan' => $planEstudio->id_plan,
            'detalle' => $planEstudio->detalle,
            'fecha_inicio' => $planEstudio->fecha_inicio,
            'fecha_fin' => $planEstudio->fecha_fin,
            'unidades_curriculares' => $planEstudio->ucPlan->map(function ($ucPlan) {
                return $ucPlan->unidadCurricular;
            }),
            'carreras' => $planEstudio->carreraPlan->map(function ($carreraPlan) {
                return $carreraPlan->carrera;
            }),
        ];

        return response()->json($result, 200);
    } catch (Exception $e) {
        Log::error('Error al obtener el plan de estudio con detalles: ' . $e->getMessage());
        return response()->json(['error' => 'Hubo un error al obtener el plan de estudio'], 500);
    }
}


    public function guardarPlanEstudio($data)
    {
        try {
            $planEstudio = new PlanEstudio($data);  
            
            $planEstudioModel = $this->planEstudioMapper->toPlanEstudio($planEstudio);
            
            $planEstudioModel->save();
            
            return response()->json($planEstudioModel, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar el planEstudio: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el planEstudio'], 500);
        }
    }


    public function actualizarPlanEstudio($data, $id)
    {
        $planEstudio = PlanEstudio::find($id);
        if (!$planEstudio) {
            return response()->json(['error' => 'Plan Estudio no encontrado'], 404);
        }
    
        try {
            $planEstudio->fill($data); 
    
            $planEstudio->save();
    
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

