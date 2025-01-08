<?php

namespace App\Services;

use App\Repositories\LocalidadRepository;
use App\Mappers\LocalidadMapper;
use App\Models\Localidad;
use Exception;
use Illuminate\Support\Facades\Log;

class LocalidadService implements LocalidadRepository
{
    private $localidadMapper;

    public function __construct(LocalidadMapper $localidadMapper)
    {
        $this->localidadMapper = $localidadMapper;
    }

    public function obtenerTodasLocalidades()
    {
        try {
            $localidades = Localidad::all();
            return response()->json($localidades, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener las localidades: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener las localidades'], 500);
        }
    }

    public function obtenerLocalidadPorId($id)
    {
        $localidad = Localidad::find($id);
        if (!$localidad) {
            return response()->json(['error' => 'Localidad no encontrada'], 404);
        }
        try {
            return response()->json($localidad, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener la localidad: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener la localidad'], 500);
        }
    }

    public function guardarLocalidad($request)
    {
        try {
            $localidadData = $request->all();
            $localidad = $this->localidadMapper->toLocalidad($localidadData);
            $localidad->save();
            return response()->json($localidad, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar la localidad: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar la localidad'], 500);
        }
    }

    public function actualizarLocalidad($request, $id)
    {
        $localidad = Localidad::find($id);
        if (!$localidad) {
            return response()->json(['error' => 'Localidad no encontrada'], 404);
        }
        try {
            $localidad->update($request->all());
            return response()->json($localidad, 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar la localidad: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar la localidad'], 500);
        }
    }

    public function eliminarLocalidadPorId($id)
    {
        try {
            $localidad = Localidad::find($id);
            if ($localidad) {
                $localidad->delete();
                return response()->json(['success' => 'Localidad eliminada con éxito'], 200);
            } else {
                return response()->json(['error' => 'Localidad no encontrada'], 404);
            }
        } catch (Exception $e) {
            Log::error('Error al eliminar la localidad: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar la localidad'], 500);
        }
    }
}
