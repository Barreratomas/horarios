<?php

namespace App\Services\horarios;

use App\Repositories\horarios\CursadaRepository;
use App\Mappers\horarios\CursadaMapper;
use App\Models\horarios\Cursada;
use Exception;
use Illuminate\Support\Facades\Log;

class CursadaService implements CursadaRepository
{
    private $cursadaMapper;

    public function __construct(CursadaMapper $cursadaMapper)
    {
        $this->cursadaMapper = $cursadaMapper;
    }


    public function obtenerCursadas()
    {
        try {
            $cursadas = Cursada::all();
            return response()->json($cursadas, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener las cursadas: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener las cursadas'], 500);
        }
    }

    public function obtenerAulaPorId($id)
    {
        $aula = Aula::find($id);
        if (!$aula) {
            return response()->json(['error' => 'Aula no encontrada'], 404);
        }
        try {
            return response()->json($aula, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el aula: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el aula'], 500);
        }
    }

    public function guardarAulas($request)
    {
        try {
            $aulaData = $request->all(); 
            $aula = new Aula($aulaData); 
            $aulaModel = $this->aulaMapper->toAula($aula);
            $aulaModel->save();
            return response()->json($aulaModel, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar el aula: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el aula'], 500);
        }
    }


    public function actualizarAulas($request, $id)
    {
        $aula = Aula::find($id);
        if (!$aula) {
            return response()->json(['error' => 'Aula no encontrada'], 404);
        }
        try {
            $aula->update($request->all());
            return response()->json($aula, 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar el aula: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar el aula'], 500);
        }
    }



    public function eliminarAulas($id)
    {
        try {
            $aula = Aula::find($id);
            if ($aula) {
                $nombreAula=$aula->nombre;
                $aula->delete();
                return response()->json(['nombre_aula' => $nombreAula], 200);
            } else {
                return response()->json(['error' => 'No existe el aula'], 404);
            }
        } catch (Exception $e) {
            Log::error('Error al eliminar el aula: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el aula'], 500);
        }
    }

}

