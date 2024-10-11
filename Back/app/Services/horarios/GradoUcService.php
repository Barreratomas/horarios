<?php

namespace App\Services\horarios;

use App\Models\horarios\GradoUC;
use App\Repositories\horarios\GradoUCRepository;
use Illuminate\Support\Facades\Log;

class GradoUCService implements GradoUCRepository
{
    public function obtenerTodosGradoUC()
    {
        try {
            return GradoUC::all();
        } catch (\Exception $e) {
            Log::error("Error al obtener todos los registros de GradoUC: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los registros'], 500);
        }
    }

    public function obtenerGradoUCPorId($id_grado, $id_UC)
    {
        try {
            $gradoUC = GradoUC::where('id_grado', $id_grado)->where('id_uc', $id_UC)->first();
            if (!$gradoUC) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }
            return $gradoUC;
        } catch (\Exception $e) {
            Log::error("Error al obtener el registro GradoUC: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el registro'], 500);
        }
    }

    public function guardarGradoUC($gradoUCData)
    {
        try {
            $gradoUC = new GradoUC($gradoUCData);
            $gradoUC->save();
            return response()->json($gradoUC, 201);
        } catch (\Exception $e) {
            Log::error("Error al guardar el registro GradoUC: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el registro'], 500);
        }
    }

    public function eliminarGradoUC($id_grado, $id_UC)
    {
        try {
            $gradoUC = GradoUC::where('id_grado', $id_grado)->where('id_UC', $id_UC)->first();
            if (!$gradoUC) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }
            $gradoUC->delete();
            return response()->json(['message' => 'Registro eliminado con éxito'], 200);
        } catch (\Exception $e) {
            Log::error("Error al eliminar el registro GradoUC: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el registro'], 500);
        }
    }
}
