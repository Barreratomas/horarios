<?php

namespace App\Services;

use App\Repositories\InscripcionRepository;
use App\Mappers\InscripcionMapper;
use App\Models\Inscripcion;
use Exception;
use Illuminate\Support\Facades\Log;

class InscripcionService implements InscripcionRepository
{
    private $inscripcionMapper;

    public function __construct(InscripcionMapper $inscripcionMapper)
    {
        $this->inscripcionMapper = $inscripcionMapper;
    }


    public function obtenerTodosInscripcion()
    {
        try {
            $inscripciones = Inscripcion::all();
            return response()->json($inscripciones, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener las inscripciones: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener las inscripciones'], 500);
        }
    }

    public function obtenerInscripcionPorId($id_carrera)
    {
        $inscripcion = Inscripcion::find($id_carrera);
        if (!$inscripcion) {
            return response()->json(['error' => 'inscripcion no encontrada'], 404);
        }
        try {
            return response()->json($inscripcion, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el inscripcion: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el inscripcion'], 500);
        }
       
    }

    public function guardarInscripcion($request)
    {
        try {
            $inscripcionData = $request->all();
            $inscripcion = new Inscripcion($inscripcionData);
            $inscripcionModel = $this->inscripcionMapper->toInscripcion($inscripcion);
            $inscripcionModel->save();
            return response()->json($inscripcionModel, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar el inscripcion: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el inscripcion'], 500);
        }
    }

    public function actualizarInscripcion($request, $id)
    {
        $inscripcion = Inscripcion::find($id);
        if (!$inscripcion) {
            return response()->json(['error' => 'Inscripcion no encontrada'], 404);
        }
        try {
            $inscripcion->update($request->all());
            return response()->json($inscripcion, 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar el Inscripcion: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar el Inscripcion'], 500);
        }
    }

    public function eliminarInscripcion($id)
    {
        try {
            $inscripcion = Inscripcion::find($id);
            if ($inscripcion) {
                $inscripcion->delete();
                return response()->json(['success' => 'Se eliminó el Inscripcion'], 200);
            } else {
                return response()->json(['error' => 'No existe el Inscripcion'], 404);
            }
        } catch (Exception $e) {
            Log::error('Error al eliminar el Inscripcion: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el Inscripcion'], 500);
        }
    }
}

