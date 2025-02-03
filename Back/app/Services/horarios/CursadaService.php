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

    public function obtenerCursadasPorId($id)
    {
        try {
            $cursada = Cursada::find($id);
            if ($cursada) {
                return response()->json($cursada, 200);
            } else {
                return response()->json(['error' => 'No existe la cursada'], 404);
            }
        } catch (Exception $e) {
            Log::error('Error al obtener la cursada: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener la cursada'], 500);
        }
    }

    public function guardarCursadas($request)
    {
        try {
            $cursadaData = $request->all(); 
            $cursada = new Cursada($cursadaData); 
            $cursadaModel = $this->cursadaMapper->toCursada($cursada);
            $cursadaModel->save();
            return response()->json($cursadaModel, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar la cursada: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar la cursada'], 500);
        }
    }


    public function actualizarCursadas($request, $id)
    {
        $cursada = Cursada::find($id);
        if (!$cursada) {
            return response()->json(['error' => 'Cursada no encontrada'], 404);
        }
        try {
            $cursada->update($request->all());
            return response()->json($cursada, 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar la cursada: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar la cursada'], 500);
        }
    }



    public function eliminarCursadas($id)
    {
        $cursada = Cursada::find($id);
        if (!$cursada) {
            return response()->json(['error' => 'Cursada no encontrada'], 404);
        }
        try {
            $cursada->delete();
            return response()->json(['message' => 'Cursada eliminada correctamente'], 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar la cursada: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar la cursada'], 500);
        }
    }

}

