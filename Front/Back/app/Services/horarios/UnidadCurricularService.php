<?php

namespace App\Services\horarios;

use App\Repositories\horarios\UnidadCurricularRepository;
use App\Mappers\horarios\UnidadCurricularMapper;
use App\Models\horarios\UnidadCurricular;
use Exception;
use Illuminate\Support\Facades\Log;

class UnidadCurricularService implements UnidadCurricularRepository
{
    private $unidadCurricularMapper;

    public function __construct(UnidadCurricularMapper $unidadCurricularMapper)
    {
        $this->unidadCurricularMapper = $unidadCurricularMapper;
    }


    public function obtenerUnidadCurricular()
    {
        try {
            $unidadCurriculares = UnidadCurricular::all();
            return response()->json($unidadCurriculares, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los unidadCurriculares: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los unidadCurriculares'], 500);
        }
    }

    public function obtenerUnidadCurricularPorId($id)
    {
        $unidadCurricular = UnidadCurricular::find($id);
        if (!$unidadCurricular) {
            return response()->json(['error' => 'unidadCurriculares no encontrada'], 404);
        }
        try {
            return response()->json($unidadCurricular, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el unidadCurricular: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el unidadCurricular'], 500);
        }
    }

    public function guardarUnidadCurricular($request)
    {
        try {
            $unidadCurricularData = $request->all(); 
            $unidadCurricular = new UnidadCurricular($unidadCurricularData); 
            $unidadCurricularModel = $this->unidadCurricularMapper->toUnidadCurricular($unidadCurricular);
            $unidadCurricularModel->save();
            return response()->json($unidadCurricularModel, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar el unidadCurricular: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el unidadCurricular'], 500);
        }
    }


    public function actualizarUnidadCurricular($request, $id)
    {
        $unidadCurricular = UnidadCurricular::find($id);
        if (!$unidadCurricular) {
            return response()->json(['error' => 'unidadCurricular no encontrada'], 404);
        }
        try {
            $unidadCurricular->update($request->all());
            return response()->json($unidadCurricular, 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar el unidadCurricular: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar el unidadCurricular'], 500);
        }
    }



    public function eliminarUnidadCurricular($id)
    {
        try {
            $unidadCurricular = UnidadCurricular::find($id);
            if ($unidadCurricular) {
                $nombreUC=$unidadCurricular->id_uc;
                $unidadCurricular->delete();
                return response()->json(['nombre_uc' => $nombreUC], 200);
            } else {
                return response()->json(['error' => 'No existe el unidadCurricular'], 404);
            }
        } catch (Exception $e) {
            Log::error('Error al eliminar el unidadCurricular: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el unidadCurricular'], 500);
        }
    }
}

