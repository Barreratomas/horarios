<?php

namespace App\Services\horarios;

use App\Repositories\horarios\DocenteUCRepository;
use App\Mappers\horarios\DocenteUCMapper;
use App\Models\horarios\Docente;
use App\Models\horarios\DocenteUC;
use Exception;
use Illuminate\Support\Facades\Log;

class DocenteUCService implements DocenteUCRepository
{

    protected $docenteUCMapper;

    public function __construct(DocenteUCMapper $docenteUCMapper)
    {
        $this->docenteUCMapper = $docenteUCMapper;
    }


    public function obtenerTodosDocentesUC()
    {
        try {
            $docentesUC = DocenteUC::all();
            return response()->json($docentesUC, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los docentesUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los docentesUC'], 500);
        }
    }
    public function obtenerDocenteUCPorIdDocente($id_docente)
    {
        $docenteUC = DocenteUC::where('id_docente', $id_docente)->first();
        if (!$docenteUC) {
            return response()->json(['error' => 'DocenteUC no encontrada'], 404);
        }
        try {
            return response()->json($docenteUC, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el docenteUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el docenteUC'], 500);
        }
    }
    public function obtenerDocenteUCPorIdUC($id_uc)
    {
        $docenteUC = DocenteUC::where('id_uc', $id_uc)->first();
        if (!$docenteUC) {
            return response()->json(['error' => 'DocenteUC no encontrada'], 404);
        }
        try {
            return response()->json($docenteUC, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el docenteUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el docenteUC'], 500);
        }
    }
    public function guardarDocenteUC($request)
    {
        try {
            $docenteUCData = $request->all();
            $docenteUC = new DocenteUC($docenteUCData);
            $docenteUCModel = $this->docenteUCMapper->toDocenteUC($docenteUC);
            $docenteUCModel->save();
            return response()->json($docenteUCModel, 200);
        } catch (Exception $e) {
            Log::error('Error al guardar la docenteUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar la docenteUC'], 500);
        }
    }
    public function actualizarDocenteUCPorIdDocente($request, $id_docente)
    {
        $docenteUC = DocenteUC::where('id_docente', $id_docente)->first();
        if (!$docenteUC) {
            return response()->json(['error' => 'DocenteUC no encontrada'], 404);
        }
        try {
            $docenteUC->update($request->all());
            return response()->json($docenteUC, 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar la docenteUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar la docenteUC'], 500);
        }
    }

    public function actualizarDocenteUCPorIdUC($request, $id_uc)
    {
        $docenteUC = DocenteUC::where('id_uc', $id_uc)->first();
        if (!$docenteUC) {
            return response()->json(['error' => 'DocenteUC no encontrada'], 404);
        }
        try {
            $docenteUC->update($request->all());
            return response()->json($docenteUC, 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar la docenteUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar la docenteUC'], 500);
        }
    }

    public function eliminarDocenteUCPorIdDocente($id_docente)
    {
        $docenteUC = DocenteUC::where('id_docente', $id_docente)->first();
        if (!$docenteUC) {
            return response()->json(['error' => 'DocenteUC no encontrada'], 404);
        }
        try {
            $docenteUC->delete();
            return response()->json(['success' => 'DocenteUC eliminada correctamente'], 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar la docenteUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar la docenteUC'], 500);
        }
    }

    public function eliminarDocenteUCPorIdUC($id_uc)
    {
        $docenteUC = DocenteUC::where('id_uc', $id_uc)->first();
        if (!$docenteUC) {
            return response()->json(['error' => 'DocenteUC no encontrada'], 404);
        }
        try {
            $docenteUC->delete();
            return response()->json(['success' => 'DocenteUC eliminada correctamente'], 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar la docenteUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar la docenteUC'], 500);
        }
    }
}
