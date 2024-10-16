<?php

namespace App\Services;

use App\Repositories\DocenteRepository;
use App\Mappers\DocenteMapper;
use App\Models\Docente;
use Exception;
use Illuminate\Support\Facades\Log;

class DocenteService implements DocenteRepository
{
   
    protected $docenteMapper;

    public function __construct(DocenteMapper $docenteMapper)
    {
        $this->docenteMapper = $docenteMapper;
    }


     //---------------------------------------------------------------------------------------------------------
    // Swagger


    public function obtenerTodosLosDocente(){
        try {
            $docente = Docente::all();
            return response()->json($docente, 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los docentes: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener los docentes'], 500);
        }
    }
    public function obtenerDocentePorId($id){
        try {
            $docente = Docente::find($id);
            if ($docente) {
                return response()->json($docente, 200);
            }
            return response()->json(['error' => 'No existe el docente'], 404);
        } catch (Exception $e) {
            Log::error('Error al obtener el docente: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al obtener el docente'], 500);
        }
    }
    public function guardarDocentes($docente){
        try {
            $docente = $this->docenteMapper->toDocente($docente);
            $docente->save();
            return response()->json($docente, 201);
        } catch (Exception $e) {
            Log::error('Error al guardar el docente: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al guardar el docente'], 500);
        }
    }
    public function actualizarDocentes($docente, $id){
        $docente = Docente::find($id);
        if (!$docente) {
            return response()->json(['error' => 'No existe el docente'], 404);
        }
        try {
            $docente->update($this->docenteMapper->toDocente($docente));
            return response()->json($docente, 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar el docente: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al actualizar el docente'], 500);
        }
    }
    public function eliminarDocentes($id)
    {
        try {
            $docente = Docente::find($id);
            if ($docente) {
                $docente->delete();
                return response()->json(['success' => 'Se eliminó el docente'], 200);
            } else {
                return response()->json(['error' => 'No existe el docente'], 404);
            }
        } catch (Exception $e) {
            Log::error('Error al eliminar el docente: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el docente'], 500);
        }
    }
}
