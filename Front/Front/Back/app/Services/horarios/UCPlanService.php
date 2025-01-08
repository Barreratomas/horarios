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
            DB::beginTransaction();
    
    
            // Verificar si materias es un arreglo y no está vacío
            if (!is_array($materias) || empty($materias)) {
                return response()->json(['error' => 'Las materias deben ser un arreglo no vacío'], 400);
            }
    
            // Guardar cada UCPlan directamente en la tabla correspondiente
            foreach ($materias as $materia) {
                // Puedes crear la relación directamente
                DB::table('uc_plan')->insert([
                    'id_plan' => $id_plan,
                    'id_uc' => $materia, // ID de la materia
                ]);
            }
    
            DB::commit();
    
           
    
            return response()->json(['message' => 'Materias asociadas con éxito'], 201);
        } catch (Exception $e) {
            DB::rollBack();
    
            Log::error('Error al guardar las materias del plan de estudio: ' . $e->getMessage());
    
            return response()->json(['error' => 'Hubo un error al guardar las materias del plan de estudio'], 500);
        }
    }
    




    public function actualizarUCPlan($materias, $id_plan)
{
    // Obtener las relaciones actuales de UCPlan para el plan dado
    $currentUCPlans = UCPlan::where('id_plan', $id_plan)->get();

    // Verificar si se enviaron materias en la request
    $materiasFromRequest = $materias;  //  materias es un array de IDs que recibes desde el controlador

    // Eliminar las relaciones que ya no están en la solicitud
    foreach ($currentUCPlans as $ucPlan) {
        if (!in_array($ucPlan->id_uc, $materiasFromRequest)) {
            // Si la materia no está en la solicitud, eliminar la relación
            UCPlan::where('id_uc', $ucPlan->id_uc)
                  ->where('id_plan', $id_plan)
                  ->delete();  // Eliminar usando las claves correctas
        }
    }

    // Ahora, agregamos las nuevas relaciones de materias que no existen
    foreach ($materiasFromRequest as $id_uc) {
        // Verificar si la relación entre la materia (id_uc) y el plan (id_plan) ya existe
        $exists = UCPlan::where('id_uc', $id_uc)->where('id_plan', $id_plan)->exists();

        if (!$exists) {
            // Si no existe, crear una nueva relación entre la materia y el plan
            UCPlan::create([
                'id_uc' => $id_uc,
                'id_plan' => $id_plan,
            ]);
        }
    }

    // Responder con un mensaje de éxito si todo fue bien
    return response()->json(['success' => 'Relaciones de UCPlan actualizadas correctamente'], 200);
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

