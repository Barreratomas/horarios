<?php

namespace App\Mappers\horarios;

use App\Models\AlumnoUC;

class AlumnoUCMapper
{
    public static function toAlumnoUC($alumnoUCData)
    {
        return new AlumnoUC([
            'id_alumno' => $alumnoUCData['id_alumno'],
            'id_uc' => $alumnoUCData['id_uc'],
        ]);
    }

}
