<?php

namespace App\Services\horarios;

use App\Repositories\horarios\CarreraRepository;
use App\Mappers\horarios\CarreraMapper;
use App\Models\horarios\Carrera;
use Exception;
use Illuminate\Support\Facades\Log;

class CarreraService implements CarreraRepository
{
    protected $carreraMapper;

    public function __construct(CarreraMapper $carreraMapper)
    {
        $this->carreraMapper = $carreraMapper;
    }

    /*

    public function obtenerTodasCarreras()
    {

        $carreras = Carrera::all();
        return $carreras;
      
    }

    public function obtenerCarreraPorId($id)
    {
        
        $carrera = Carrera::find($id);
        if (is_null($carrera)) {
            return [];
        }
        return $carrera;
        
    }

    public function guardarCarrera($nombre)
    {
        try {
            $carrera = new Carrera();
            $carrera->nombre=$nombre;
            $carrera->save();
            return ['success'=>'Carrera guardada correctamente'];
        } catch (Exception $e) {
            
            return ['error'=>'Hubo un error al guardar la carrera'];
        }
    }

    public function actualizarCarrera($nombre,$carrera)
    {
        if (!$carrera) {
            return ['error'=>'hubo un error al buscar carrera'];
        }
        try {   
            
            $carrera->nombre=$nombre;
            $carrera->save();
            return ['success'=>'Carrera actualizada correctamente'];
        } catch (Exception $e) {
            return ['error'=>'Hubo un error al actualizar la carrera'];
        }
    }

    public function eliminarCarreraPorId($carrera)
    {
        if (!$carrera) {
            return ['error'=>'hubo un error al buscar  carrera'];

        }
        try {
            
            $carrera->delete();
            return ['success'=>'Carrera eliminada correctamente'];
        } catch (Exception $e) {
            // Manejar el error aquí
            return ['error'=>'Hubo un error al eliminar la carrera'];
        }
    }

    */

    //---------------------------------------------------------------------------------------------------------
    // Swagger

    public function obtenerTodosCarrera()
    {
        try {
            $carerras = Carrera::all();
            return $carerras;
        } catch (Exception $e) {
            Log::error('Error al obtener las carreras: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener las carreras'], 500);
        }
    }
    public function obtenerCarreraPorId($id)
    {
        try {
            $carrera = Carrera::find($id);
            if ($carrera) {
                return $carrera;
            }
            return response()->json(['error' => 'No existe la carrera'], 404);
        } catch (Exception $e) {
            Log::error('Error al obtener la carrera: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener la carrera'], 500);
        }
    }
    public function guardarCarrera($request)
    {
        try {
            $carreraData = $request->all();
            $carrera = new Carrera($carreraData);
            $carreraModel = $this->carreraMapper->toCarrera($carrera);
            $carreraModel->save();
            return response()->json($carrera, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar la carrera: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar la carrera'], 500);
        }
    }
    public function actualizarCarrera($request, $id)
    {
        $carerra = Carrera::find($id);
        if (!$carerra) {
            return response()->json(['error' => 'No existe la carrera'], 404);
        }
        try {
            $carerra->update($request->all());
            return response()->json($carerra, 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar la carrera: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar la carrera'], 500);
        }
    }
    public function eliminarCarreraPorId($id)
    {
        try {
            $carrera = Carrera::find($id);
            if ($carrera) {
                $carrera->delete();
                return response()->json(['success' => 'Se eliminó la carrera'], 200);
            } else {
                return response()->json(['error' => 'No existe la carrera'], 404);
            }
        } catch (Exception $e) {
            Log::error('Error al eliminar la carrera: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar la carrera'], 500);
        }
    }
}



