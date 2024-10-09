<?php

namespace App\Services\horarios;

use App\Models\horarios\GradoUc;
use App\Repositories\horarios\GradoUcRepository;
use Illuminate\Support\Facades\Log;

class GradoUcService implements GradoUcRepository
{
    public function obtenerTodosGradoUc()
    {
        try {
            return GradoUc::all();
        } catch (\Exception $e) {
            Log::error("Error al obtener todos los registros de GradoUc: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los registros'], 500);
        }
    }

    public function obtenerGradoUcPorId($id_grado, $id_uc)
    {
        try {
            $gradoUc = GradoUc::where('id_grado', $id_grado)->where('id_uc', $id_uc)->first();
            if (!$gradoUc) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }
            return $gradoUc;
        } catch (\Exception $e) {
            Log::error("Error al obtener el registro GradoUc: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el registro'], 500);
        }
    }

    public function guardarGradoUc($gradoUcData)
    {
        try {
            $gradoUc = new GradoUc($gradoUcData);
            $gradoUc->save();
            return response()->json($gradoUc, 201);
        } catch (\Exception $e) {
            Log::error("Error al guardar el registro GradoUc: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el registro'], 500);
        }
    }

    public function eliminarGradoUc($id_grado, $id_uc)
    {
        try {
            $gradoUc = GradoUc::where('id_grado', $id_grado)->where('id_uc', $id_uc)->first();
            if (!$gradoUc) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }
            $gradoUc->delete();
            return response()->json(['message' => 'Registro eliminado con éxito'], 200);
        } catch (\Exception $e) {
            Log::error("Error al eliminar el registro GradoUc: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el registro'], 500);
        }
    }
}
