<?php

namespace App\Services;

use App\Repositories\CarreraUCRepository;
use App\Mappers\CarreraUCMapper;
use App\Models\CarreraUC;
use Exception;
use Illuminate\Support\Facades\Log;

class CarreraUCService implements CarreraUCRepository
{
    private $carreraUCMapper;

    public function __construct(CarreraUCMapper $carreraUCMapper)
    {
        $this->carreraUCMapper = $carreraUCMapper;
    }


    public function obtenerTodosCarreraUC()
    {
        try {
            $carreraUCs = CarreraUC::all();
            return response()->json($carreraUCs, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener las carreraUCs: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener las carreraUCs'], 500);
        }
    }

    public function obtenerCarreraUCPorIdCarrera($id_carrera)
    {
        $carreraUC = CarreraUC::where('id_carrera', $id_carrera)->first();
        if (!$carreraUC) {
            return response()->json(['error' => 'carreraUC no encontrada'], 404);
        }
        try {
            return response()->json($carreraUC, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el carreraUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el carreraUC'], 500);
        }
       
    }

    public function obtenerCarreraUCPorIdUC($id_UC)
    {
        $carreraUC = CarreraUC::where('id_UC', $id_UC)->first();
        if (!$carreraUC) {
            return response()->json(['error' => 'carreraUC no encontrada'], 404);
        }
        try {
            return response()->json($carreraUC, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el carreraUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el carreraUC'], 500);
        }
    }

    public function guardarCarreraUC($request)
    {
        try {
            $carreraUCData = $request->all();
            $carreraUC = new CarreraUC($carreraUCData);
            $carreraUCModel = $this->carreraUCMapper->toCarreraUC($carreraUC);
            $carreraUCModel->save();
            return response()->json($carreraUCModel, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar el carreraUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el carreraUC'], 500);
        }
    }

    public function eliminarCarreraUCPorIdCarrera($id_carrera)
    {
        try {
            $carreraUC = CarreraUC::where('id_carrera', $id_carrera)->first();
            if ($carreraUC) {
                $carreraUC->delete();
                return response()->json(['success' => 'Se eliminó el carreraUC'], 200);
            } else {
                return response()->json(['error' => 'No existe el carreraUC'], 404);
            }
        } catch (Exception $e) {
            Log::error('Error al eliminar el carreraUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el carreraUC'], 500);
        }
    }

    public function eliminarCarreraUCPorIdUC($id_UC)
    {
        try {
            $carreraUC = CarreraUC::where('id_UC', $id_UC)->first();
            if ($carreraUC) {
                $carreraUC->delete();
                return response()->json(['success' => 'Se eliminó el carreraUC'], 200);
            } else {
                return response()->json(['error' => 'No existe el carreraUC'], 404);
            }
        } catch (Exception $e) {
            Log::error('Error al eliminar el carreraUC: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el carreraUC'], 500);
        }
    }
}

