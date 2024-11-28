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
            Log::info('Obteniendo todos los horarios previos de los docentes.');
            return HorarioPrevioDocente::all();
        } catch (Exception $e) {
            Log::error('Error al obtener los horarios previos de los docentes: ' . $e->getMessage());
            return ['error' => 'Hubo un error al obtener los horarios previos de los docentes.'];
        }
    }

   /**
 * Obtener un horario previo de docente por su ID.
 */
    public function obtenerHorarioPrevioDocentePorIdDocente($id_docente) 
    {
    Log::info('Buscando horario previo del docente con ID: ' . $id_docente);

    $horarioPrevioDocente = HorarioPrevioDocente::where('id_docente', $id_docente)->first(); 
    if (is_null($horarioPrevioDocente)) {
        Log::warning('No se encontró el Horario Previo del Docente con ID: ' . $id_docente);
        return ['error' => 'No se encontró el Horario Previo del Docente.'];
        }

    Log::info('Horario Previo del Docente encontrado:', ['horario' => $horarioPrevioDocente]);
    return $horarioPrevioDocente;
    }

    public function guardarHorarioPrevioDocente($id_docente, $dia, $hora)
    {
        try {
            Log::info('Recibiendo datos para guardar el horario previo del docente.', [
                'id_docente' => $id_docente,
                'dia' => $dia,
                'hora' => $hora
            ]);

            $horarioPrevioDocente = new HorarioPrevioDocente();

            // Asignar valores
            $horarioPrevioDocente->id_docente = $id_docente;
            $horarioPrevioDocente->dia = $dia;
            $horarioPrevioDocente->hora = $hora;

            $horarioPrevioDocente->save();

            Log::info('Horario Previo del Docente guardado correctamente.', [
                'id_h_p_d' => $horarioPrevioDocente->id_h_p_d
            ]);

            return ['success' => 'Horario Previo del Docente guardado correctamente'];
        } catch (Exception $e) {
            Log::error('Error al guardar el Horario Previo del Docente: ' . $e->getMessage());
            return ['error' => 'Hubo un error al guardar el Horario Previo del Docente: ' . $e->getMessage()];
        }
    }

    public function actualizarHorarioPrevioDocente($id_h_p_d, $dia, $hora)
    {
        // Obtener el horario previo del docente por $id_h_p_d 
        $h_p_d = HorarioPrevioDocente::find($id_h_p_d);
    
        if (!$h_p_d) {
            Log::warning('Horario Previo del Docente no encontrado para actualizar.');
            return ['error' => 'El Horario Previo del Docente no fue encontrado.'];
        }
    
        try {
            Log::info('Actualizando el horario previo del docente.', [
                'id_h_p_d' => $h_p_d->id_h_p_d,
                'nuevo_dia' => $dia,
                'nueva_hora' => $hora
            ]);
    
            // Actualizar valores si no son nulos
            $h_p_d->dia = $dia ?? $h_p_d->dia;
            $h_p_d->hora = $hora ?? $h_p_d->hora;
    
            $h_p_d->save();
    
            Log::info('Horario Previo del Docente actualizado correctamente.', ['id_h_p_d' => $h_p_d->id_h_p_d]);
    
            return ['success' => 'Horario Previo del docente actualizado correctamente.'];
        } catch (Exception $e) {
            Log::error('Error al actualizar el Horario Previo del Docente: ' . $e->getMessage());
            return ['error' => 'Hubo un error al actualizar el Horario Previo del Docente: ' . $e->getMessage()];
        }
    }
    
    public function eliminarHorarioPrevioDocentePorId($id_h_p_d)
    {
        // Buscar el HorarioPrevioDocente por su id_h_p_d
        $h_p_d = HorarioPrevioDocente::find($id_h_p_d);
    
        if (!$h_p_d) {
            Log::warning('Horario Previo del Docente no encontrado para eliminar.');
            return ['error' => 'El Horario Previo del Docente no fue encontrado.'];
        }
    
        try {
            Log::info('Eliminando el horario previo del docente con Id_h_p_d: ' . $h_p_d->id_h_p_d);
    
            // Eliminar 
            $h_p_d->delete();
    
            Log::info('Horario Previo del Docente eliminado correctamente.', ['id_h_p_d' => $h_p_d->id_h_p_d]);
    
            return ['success' => 'Horario Previo del Docente eliminado correctamente.'];
        } catch (Exception $e) {
            Log::error('Error al eliminar el Horario Previo del Docente: ' . $e->getMessage());
            return ['error' => 'Hubo un error al eliminar el Horario Previo del Docente: ' . $e->getMessage()];
        }
    }
    
}