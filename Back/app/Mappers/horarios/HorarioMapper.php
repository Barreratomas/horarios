<?php

namespace App\Mappers\horarios;

use App\Models\horarios\Horario;

class HorarioMapper
{
    public static function toHorario($horarioData)
    {
        return new Horario([
            'dia' => $horarioData['dia'],
            'modulo_inicio' => $horarioData['modulo_inicio'],
            'modulo_fin' => $horarioData['modulo_fin'],
            'modalidad' => $horarioData['modalidad'],
            'id_disp' => $horarioData['id_disp'],
            'id_uc' => $horarioData['id_uc'],
            'id_aula' => $horarioData['id_aula'],
            'id_grado' => $horarioData['id_grado']
        ]);
    }
}
