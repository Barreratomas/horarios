<?php

namespace App\Services;

use App\Models\CarreraGrado;
use Illuminate\Support\Facades\Log;
use App\Repositories\CarreraGradoRepository;
use App\Services\horarios\GradoService;
use App\Services\horarios\CarreraService;
use App\Mappers\CarreraGradoMapper;
use Exception;

class CarreraGradoService implements CarreraGradoRepository
{

    protected $carreraGradoMapper;
    protected $gradoService;
    protected $carreraService;

    public function __construct(CarreraGradoMapper $carreraGradoMapper, GradoService $gradoService, CarreraService $carreraService)
    {
        $this->carreraGradoMapper = $carreraGradoMapper;
        $this->gradoService = $gradoService;
        $this->carreraService = $carreraService;
    }

    public function obtenerTodosCarreraGrado()
    {
        try {
            // devolver las las carreras y grados
            $carrerasGrados = CarreraGrado::with(['carrera', 'grado'])->get();
            return response()->json($carrerasGrados, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los carrerasGrados: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los carrerasGrados'], 500);
        }
    }

    public function obtenerCarreraGradoPorIdCarrera($id_carrera)
{
    try {
        // Buscar registros con los detalles de las relaciones
        $carreraGrado = CarreraGrado::with(['carrera', 'grado'])
            ->where('id_carrera', $id_carrera)
            ->get();

        // Verificar si hay resultados
        if ($carreraGrado->isEmpty()) {
            return response()->json(['error' => 'CarreraGrado no encontrado'], 404);
        }

        return response()->json($carreraGrado, 200);
    } catch (Exception $e) {
        Log::error('Error al obtener el carreraGrado: ' . $e->getMessage());
        return response()->json(['error' => 'Hubo un error al obtener el carreraGrado'], 500);
    }
}


public function obtenerCarreraGradoPorIdGrado($id_grado)
{
    try {
        // Buscar registros con los detalles de las relaciones
        $carreraGrado = CarreraGrado::with(['carrera', 'grado'])
            ->where('id_grado', $id_grado)
            ->get();

        // Verificar si hay resultados
        if ($carreraGrado->isEmpty()) {
            return response()->json(['error' => 'CarreraGrado no encontrado'], 404);
        }

        return response()->json($carreraGrado, 200);
    } catch (Exception $e) {
        Log::error('Error al obtener el carreraGrado: ' . $e->getMessage());
        return response()->json(['error' => 'Hubo un error al obtener el carreraGrado'], 500);
    }
}

    public function guardarCarreraGrado($id_carrera, $id_grado)
    {
        $grado = $this->gradoService->obtenerGradoPorId($id_grado);
        $carrera = $this->carreraService->obtenerCarreraPorId($id_carrera);
        if (!$grado || !$carrera) {
            return ['error' => 'No se encontró el grado o la carrera'];
        }

        try{
            $carreraGrado = $this->carreraGradoMapper->toCarreraGrado($id_carrera, $id_grado);
            $carreraGrado->save();
            return response()->json($carreraGrado, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar la carreraGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar la carreraGrado'], 500);
        }
    }

    

    public function eliminarCarreraGradoPorIdGradoYCarrera($id_carrera, $id_grado)
    {
        $carreraGrado = CarreraGrado::where('id_grado', $id_grado)->where('id_carrera', $id_carrera)->first();
        if (!$carreraGrado) {
            return response()->json(['error' => 'CarreraGrado no encontrado'], 404);
        }
        try {
            $carreraGrado->delete();
            return response()->json($carreraGrado, 200);
        } catch (Exception $e) {
            Log::error('Error al eliminar la carreraGrado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar la carreraGrado'], 500);
        }
    }


}
