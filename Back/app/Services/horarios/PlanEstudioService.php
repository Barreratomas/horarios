<?php

namespace App\Services\horarios;

use App\Repositories\horarios\PlanEstudioRepository;
use App\Mappers\horarios\PlanEstudioMapper;
use App\Models\horarios\PlanEstudio;
use Exception;
use Illuminate\Support\Facades\Log;

class PlanEstudioService implements PlanEstudioRepository
{
    private $planEstudioMapper;

    public function __construct(PlanEstudioMapper $planEstudioMapper)
    {
        $this->planEstudioMapper = $planEstudioMapper;
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

    public function guardarPlanEstudio($request)
    {
        try {
            $planEstudioData = $request->all(); 
            $planEstudio = new PlanEstudio($planEstudioData); 
            $planEstudioModel = $this->planEstudioMapper->toPlanEstudio($planEstudio);
            $planEstudioModel->save();
            return response()->json($planEstudioModel, 201);
        } catch (Exception $e) {
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

