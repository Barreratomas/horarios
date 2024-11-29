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
    public function obtenerGradoUcPorIdGradoConRelaciones($id_grado)
    {
        try {
            // Obtén los registros de grado_uc junto con las relaciones grado y unidadCurricular
            $gradoUC = GradoUC::with(['grado', 'unidadCurricular'])
                ->where('id_grado', $id_grado)
                ->get();
    
            if ($gradoUC->isEmpty()) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }
    
            return response()->json($gradoUC, 200);
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
    
    public function actualizarGradoUC($id_grado, array $materias)
    {
        try {

    
            // Recuperar las materias actuales asociadas al grado
            $materiasExistentes = GradoUC::where('id_grado', $id_grado)->pluck('id_uc')->toArray();
    
            // Determinar qué materias deben actualizarse
            $materiasAEliminar = array_diff($materiasExistentes, $materias);
            $materiasAInsertar = array_diff($materias, $materiasExistentes);
    
            // Actualizar las materias asociadas al grado sin eliminar las existentes
            foreach ($materiasAEliminar as $materiaId) {
                // Eliminar la materia de la relación
                GradoUC::where('id_grado', $id_grado)
                    ->where('id_uc', $materiaId)
                    ->delete();
            }
    
            foreach ($materiasAInsertar as $materiaId) {
                // Insertar las nuevas materias
                GradoUC::create([
                    'id_grado' => $id_grado,
                    'id_uc' => $materiaId
                ]);
            }
    
         
    
            return response()->json(['message' => 'Materias actualizadas para el grado exitosamente'], 200);
    
        } catch (\Exception $e) {
        
            Log::error("Error al actualizar el registro GradoUC: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar el registro'], 500);
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
