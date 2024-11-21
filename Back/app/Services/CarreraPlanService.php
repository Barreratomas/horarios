<?php

namespace App\Services;

use App\Mappers\CarreraPlanMapper;
use App\Models\CarreraPlan;

use Illuminate\Support\Facades\Log;
use App\Repositories\CarreraPlanRepository;
use App\Services\horarios\CarreraService;
use Exception;
use Illuminate\Support\Facades\DB;

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

    public function actualizarCarreraPlan($id_carrera, $id_plan)
    {
        $id_carrera = (int) $id_carrera;
        $id_plan = (int) $id_plan;
        
        
    
        // Buscar la relación CarreraPlan existente solo por id_plan
        $carreraPlan = CarreraPlan::where('id_plan', $id_plan)->first();
    
      
    
        if (!$carreraPlan) {
            return response()->json(['error' => 'Relación Carrera-Plan no encontrada'], 404);
        }
    
        // Verificar si id_carrera que viene por parámetro es igual al id_carrera en la base de datos
        if ($id_carrera == $carreraPlan->id_carrera) {
            // Si son iguales, no realizamos la actualización y solo devolvemos un 200
            return response()->json(['message' => 'Los valores de id_carrera son iguales, no se realiza actualización'], 200);
        }
    
        try {
            // Si son diferentes, actualizamos la relación
            $updated = DB::table('carrera_plan')
                ->where('id_plan', $id_plan)  // aseguramos que estamos actualizando el registro correcto
                ->update(['id_carrera' => $id_carrera]);  // actualizamos solo el id_carrera
    
            if ($updated) {
                return response()->json(['success' => 'Relación actualizada correctamente']);
            } else {
                return response()->json(['error' => 'No se encontró la relación a actualizar'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error al actualizar la relación Carrera-Plan: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar la relación Carrera-Plan'], 500);
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
