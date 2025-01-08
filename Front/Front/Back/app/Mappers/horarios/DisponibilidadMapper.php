<?php


namespace App\Mappers\horarios;

use App\Models\horarios\Disponibilidad;

class DisponibilidadMapper
{
    public static function toDisponibilidad($disponibilidadData)
    {
        return new Disponibilidad([
            'id_uc' => $disponibilidadData['id_uc'],
            'id_docente' => $disponibilidadData['id_docente'],
            'id_h_p_d' => $disponibilidadData['id_h_p_d'],
            'id_aula' => $disponibilidadData['id_aula'],
            'id_grado' => $disponibilidadData['id_grado'],
            'dia' => $disponibilidadData['dia'],
            'modulo_inicio' => $disponibilidadData['modulo_inicio'],
            'modulo_fin' => $disponibilidadData['modulo_fin']
]);
    }
}
