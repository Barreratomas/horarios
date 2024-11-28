<?php

namespace App\Services\horarios;

use App\Models\horarios\GradoUC;
use App\Repositories\horarios\GradoUcRepository;
use App\Mappers\horarios\GradoUcMapper;
use Illuminate\Support\Facades\Log;

class GradoUcService implements GradoUcRepository
{
    protected $gradoUCMapper;
    public function __construct(GradoUcMapper $gradoUCMapper)
    {
        $this->gradoUCMapper = $gradoUCMapper;
    }

    public function obtenerTodosGradoUC()
    {
        try {
            return GradoUC::all();
        } catch (\Exception $e) {
            Log::error("Error al obtener todos los registros de GradoUC: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los registros'], 500);
        }
    }

    public function obtenerGradoUcPorIdGrado($id_grado)
    {
        try {
            $gradoUC = GradoUC::where('id_grado', $id_grado)->get();
            if (!$gradoUC) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }
            return $gradoUC;
        } catch (\Exception $e) {
            Log::error("Error al obtener el registro GradoUC: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el registro'], 500);
        }
    }

    public function obtenerGradoUcPorIdUC($id_uc)
    {
        try {
            $gradoUC = GradoUC::where('id_uc', $id_uc)->get();
            if (!$gradoUC) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }
            return $gradoUC;
        } catch (\Exception $e) {
            Log::error("Error al obtener el registro GradoUC: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el registro'], 500);
        }
    }

    public function guardarGradoUC($id_grado, array $materias)
    {
        try {
            foreach ($materias as $materiaId) {
                // Preparar los datos para el mapeo
                $gradoUCData = [
                    'id_grado' => $id_grado,
                    'id_uc' => $materiaId
                ];
    
                // Usar el mapper para convertir los datos al modelo
                $gradoUCModel = $this->gradoUCMapper->toGradoUC($gradoUCData);
    
                // Guardar la relación en la base de datos
                $gradoUCModel->save();
            }
    
            return response()->json(['message' => 'Materias asignadas al grado exitosamente'], 201);
        } catch (\Exception $e) {
            Log::error("Error al guardar el registro GradoUC: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el registro'], 500);
        }
    }
    

    public function eliminarGradoUcPorIdGrado($id_grado)
    {
        try {
            $gradoUC = GradoUC::where('id_grado', $id_grado)->first();
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

    public function eliminarGradoUcPorIdUC($id_uc)
    {
        try {
            $gradoUC = GradoUC::where('id_uc', $id_uc)->first();
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
