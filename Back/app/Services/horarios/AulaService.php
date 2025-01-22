<?php

namespace App\Services\horarios;

use App\Repositories\horarios\AulaRepository;
use App\Mappers\horarios\AulaMapper;
use App\Models\horarios\Aula;
use Exception;
use Illuminate\Support\Facades\Log;
use App\DTO\AulaDTO;

class AulaService implements AulaRepository
{
    private $aulaMapper;

    public function __construct(AulaMapper $aulaMapper)
    {
        $this->aulaMapper = $aulaMapper;
    }


    public function obtenerAulas()
    {
        try {
            $aulas = Aula::all();
            return response()->json($aulas, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener las aulas: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener las aulas'], 500);
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

    public function obtenerAulaPorNombre($nombre)
    {
        $aula = Aula::where('nombre', $nombre)->first();
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

    public function obtenerAulaMayorCapacidad()
    {
        try {
            $aula = Aula::orderBy('capacidad', 'desc')->first();
            return response()->json($aula, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el aula: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el aula'], 500);
        }
    }

    public function obtenerAulaMenorCapacidad()
    {
        try {
            $aula = Aula::orderBy('capacidad', 'asc')->first();
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
            $aula = Aula::find(400);
            if ($aula) {
                $nombreAula = $aula->nombre;
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

    public function eliminarAulasPorNombre($nombre)
    {
        try {
            $aula = Aula::where('nombre', $nombre)->first();
            if ($aula) {
                $aula->delete();
                return response()->json(['success' => 'Se eliminó el aula'], 200);
            } else {
                return response()->json(['error' => 'No existe el aula'], 404);
            }
        } catch (Exception $e) {
            Log::error('Error al eliminar el aula: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el aula'], 500);
        }
    }
}
