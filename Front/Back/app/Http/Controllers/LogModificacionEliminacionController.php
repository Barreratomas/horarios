<?php

namespace App\Http\Controllers;

use App\Models\LogModificacionEliminacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogModificacionEliminacionController extends Controller
{


    

    public function index()
    {
        try {
            $logs = LogModificacionEliminacion::all();
            return response()->json($logs, 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener logs', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'No se pudieron obtener los logs'], 500);
        }
    }
    


    public function store($accion, $usuario, $detalles)
    {
      
    
        try {
            $log = new LogModificacionEliminacion();
    
            $log->accion = $accion;
            $log->usuario = $usuario;
            $log->fecha_accion = now(); 
            $log->detalles = $detalles;
    
            $log->save();
    
           
    
            return response()->json([
                'message' => 'Log registrado correctamente.',
                'log' => $log,
            ], 201);
    
        } catch (\Exception $e) {
            Log::error('Error al registrar el log', [
                'error' => $e->getMessage(),
                'accion' => $accion,
                'usuario' => $usuario,
                'detalles' => $detalles,
            ]);
    
            return response()->json([
                'error' => 'Hubo un error al registrar el log.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
   
    public function destroy($id)
    {
        $log = LogModificacionEliminacion::findOrFail($id);
        $log->delete();

        return response()->json([
            'message' => 'Log eliminado exitosamente'
        ]);
    }
}
