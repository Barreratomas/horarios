<?php

namespace App\Services\horarios;

use App\Repositories\horarios\UCPlanRepository;
use App\Mappers\horarios\UCPlanMapper;
use App\Models\horarios\UCPlan;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UCPlanService implements UCPlanRepository
{
    private $uCPlanMapper;

    public function __construct(UCPlanMapper $uCPlanMapper)
    {
        $this->uCPlanMapper = $uCPlanMapper;
    }


    public function obtenerUCPlan()
    {
        try {
            $uCPlanes = UCPlan::all();
            return response()->json($uCPlanes, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los uCPlanes: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los uCPlanes'], 500);
        }
    }

    public function obtenerUCPlanPorId($id)
    {
        $uCPlan = UCPlan::find($id);
        if (!$uCPlan) {
            return response()->json(['error' => 'uCPlanes no encontrada'], 404);
        }
        try {
            return response()->json($uCPlan, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el uCPlan: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el uCPlan'], 500);
        }
    }

    public function guardarUCPlan($id_plan, $materias)
    {
        try {
            // Comienza una transacción para asegurar que todas las materias se guarden correctamente
            DB::beginTransaction();

            foreach ($materias as $materiaId) {
                $uCPlan = new UCPlan();
                $uCPlan->id_plan = $id_plan;  
                $uCPlan->id_uc = $materiaId;     

                $uCPlan->save();
            }

            // Si todo salió bien, confirma la transacción
            DB::commit();

            return response()->json(['message' => 'Materias asociadas con éxito'], 201);
        } catch (Exception $e) {
            // Si hay un error, se cancela la transacción
            DB::rollBack();

            Log::error('Error al guardar las materias del plan de estudio: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar las materias del plan de estudio'], 500);
        }
    }



    public function actualizarUCPlan($request, $id)
    {
        $uCPlan = UCPlan::find($id);
        if (!$uCPlan) {
            return response()->json(['error' => 'uCPlan no encontrada'], 404);
        }
        try {
            $uCPlan->update($request->all());
            return response()->json($uCPlan, 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar el uCPlan: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar el uCPlan'], 500);
        }
    }



    public function eliminarUCPlan($id)
    {
        try {
            $uCPlan = UCPlan::find($id);
            
            if ($uCPlan) {
                $uCPlan->delete();
                return response()->json(['success' => 'Se eliminó el uCPlan'], 200);
            } else {
                return response()->json(['error' => 'No existe el uCPlan'], 404);
            }
        } catch (Exception $e) {
            Log::error('Error al eliminar el uCPlan: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el uCPlan'], 500);
        }
    }
}

