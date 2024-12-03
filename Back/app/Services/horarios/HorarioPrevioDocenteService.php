<?php

namespace App\Services\horarios;

use App\Repositories\horarios\HorarioPrevioDocenteRepository;
use App\Models\horarios\HorarioPrevioDocente;
use Illuminate\Support\Facades\Log;
use Exception;

class HorarioPrevioDocenteService implements HorarioPrevioDocenteRepository
{

    public function obtenerTodosHorariosPreviosDocentes()
    {
        try {
            // Traemos los horarios previos con la relación docente
            return HorarioPrevioDocente::with('docente')->get();
        } catch (Exception $e) {
            Log::error('Error al obtener los horarios previos de los docentes: ' . $e->getMessage());
            return ['error' => 'Hubo un error al obtener los horarios previos de los docentes.'];
        }
    }

   /**
 * Obtener un horario previo de docente por su ID.
 */
    public function obtenerHorarioPrevioDocente($id_h_p_d) 
    {

    $horarioPrevioDocente = HorarioPrevioDocente::where('id_h_p_d', $id_h_p_d)->first(); 
    if (is_null($horarioPrevioDocente)) {
        Log::warning('No se encontró el Horario Previo del Docente con ID: ' . $id_h_p_d);
        return ['error' => 'No se encontró el Horario Previo del Docente.'];
        }

    return $horarioPrevioDocente;
    }


    public function guardarHorarioPrevioDocente($id_docente, $dias, $horas)
    {
        try {
            // Iteramos sobre los días y horas para guardar cada combinación
            foreach ($dias as $index => $dia) {
                $horarioPrevioDocente = new HorarioPrevioDocente();
    
                // Asignamos valores
                $horarioPrevioDocente->id_docente = $id_docente;
                $horarioPrevioDocente->dia = $dia;
    
                // Convertimos la hora al formato 'H:i' si es válida, si no asignamos '17:00'
                $hora = \DateTime::createFromFormat('H:i', $horas[$index]);
                if ($hora) {
                    $horarioPrevioDocente->hora = $hora->format('H:i');  // Convertimos al formato H:i
                } else {
                    $horarioPrevioDocente->hora = '17:00';  // Hora predeterminada si no es válida
                }
    
                // Guardamos el registro
                $horarioPrevioDocente->save();
            }
    
            return response()->json([
                'message' => 'Horarios previos del docente guardados correctamente.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al guardar el Horario Previo del Docente: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    public function actualizarHorarioPrevioDocente($id_h_p_d, $dia, $hora)
    {
        try {
            // Obtener el horario previo del docente por $id_h_p_d
            $horarioPrevioDocente = HorarioPrevioDocente::find($id_h_p_d);
    
            if (!$horarioPrevioDocente) {
                Log::warning('Horario Previo del Docente no encontrado para actualizar.');
                return response()->json(['error' => 'El Horario Previo del Docente no fue encontrado.'], 404);
            }
    
            // Asignamos los nuevos valores, si se proporcionan
            $horarioPrevioDocente->dia = $dia ?? $horarioPrevioDocente->dia;
    
            // Validar y formatear la hora
            if ($hora) {
                // Validar el formato de la hora, si no es válida se asigna la hora por defecto
                $hora = \DateTime::createFromFormat('H:i', $hora);
                if ($hora) {
                    $horarioPrevioDocente->hora = $hora->format('H:i');  // Convertir al formato H:i
                } else {
                    $horarioPrevioDocente->hora = '17:00';  // Hora predeterminada si no es válida
                }
            }
    
            // Guardar el registro actualizado
            $horarioPrevioDocente->save();
    
            return response()->json([
                'succes' => 'Horario Previo del Docente actualizado correctamente.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar el Horario Previo del Docente: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    
    public function eliminarHorarioPrevioDocentePorId($id_h_p_d)
    {
        // Buscar el HorarioPrevioDocente por su id_h_p_d
        $h_p_d = HorarioPrevioDocente::find($id_h_p_d);
    
        if (!$h_p_d) {
            Log::warning('Horario Previo del Docente no encontrado para eliminar.');
            return response()->json(['error' => 'El Horario Previo del Docente no fue encontrado.'], 404); 
        }
    
        try {
            $h_p_d->delete();
    
            return response()->json(['success' => 'Horario Previo del Docente eliminado correctamente.'], 200); 
        } catch (Exception $e) {
            Log::error('Error al eliminar el Horario Previo del Docente: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un error al eliminar el Horario Previo del Docente: ' . $e->getMessage()], 500);
        }
    }
    
    
}