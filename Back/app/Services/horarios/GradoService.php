<?php

namespace App\Services\horarios;

use App\Repositories\horarios\GradoRepository;
use App\Mappers\horarios\GradoMapper;
use App\Models\horarios\Grado;
use Exception;
use Illuminate\Support\Facades\Log;

class GradoService implements GradoRepository
{
    private $gradoMapper;

    public function __construct(GradoMapper $gradoMapper)
    {
        $this->gradoMapper = $gradoMapper;
    }

    public function obtenerGrados()
    {
        try {
            $grados = Grado::all();
            return response()->json($grados, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los grados: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los grados'], 500);
        }
    }

    public function obtenerGradoPorId($id)
    {
        $grado = Grado::find($id);
        if (!$grado) {
            return response()->json(['error' => 'Grado no encontrado'], 404);
        }
        try {
            return response()->json($grado, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el grado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el grado'], 500);
        }
    }

    public function obtenerGradoPorNombre($nombre)
    {
        $grado = Grado::where('Grado', $nombre)->first();
        if (!$grado) {
            return response()->json(['error' => 'Grado no encontrado'], 404);
        }
        try {
            return response()->json($grado, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el grado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el grado'], 500);
        }
    }

    public function obtenerGradoPorDivision($division)
    {
        $grado = Grado::where('Division', $division)->first();
        if (!$grado) {
            return response()->json(['error' => 'Grado no encontrado'], 404);
        }
        try {
            return response()->json($grado, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener el grado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el grado'], 500);
        }
    }

    public function guardarGrados($request)
    {
        try {
            $gradoData = $request;

            // Verificar si ya existe un grado con la misma division y grado
            $existingGrado = Grado::where('grado', $gradoData['grado'])
                ->where('division', $gradoData['division'])
                ->first();

            // Si ya existe, retornar un mensaje indicando que no se puede crear
            if ($existingGrado) {
                return response()->json($existingGrado, 409);
            }

            // Si no existe, proceder a guardar el nuevo grado
            $grado = new Grado($gradoData);
            $gradoModel = $this->gradoMapper->toGrado($grado);
            $gradoModel->save();

            return response()->json($gradoModel, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar el grado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el grado'], 500);
        }
    }


    public function actualizarGrados($request, $id)
    {
        $grado = Grado::find($id);
        if (!$grado) {
            return response()->json(['error' => 'Grado no encontrado'], 404);
        }
        try {
            $grado->update($request->all());
            return response()->json($grado, 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar el grado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar el grado'], 500);
        }
    }

    public function eliminarGrados($id)
    {
        try {
            $grado = Grado::find($id);
            if ($grado) {
                $nombreGrado = $grado->detalle;

                $grado->delete();
                return response()->json([
                    'nombre_grado' => $nombreGrado,
                ], 200);
            } else {
                return response()->json(['error' => 'No existe el grado'], 404);
            }
        } catch (Exception $e) {
            Log::error('Error al eliminar el grado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el grado'], 500);
        }
    }

    public function eliminarGradosPorNombre($nombre)
    {
        try {
            $grado = Grado::where('Grado', $nombre)->first();
            if ($grado) {
                $grado->delete();
                return response()->json(['success' => 'Se eliminó el grado'], 200);
            } else {
                return response()->json(['error' => 'No existe el grado'], 404);
            }
        } catch (Exception $e) {
            Log::error('Error al eliminar el grado: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el grado'], 500);
        }
    }
}
