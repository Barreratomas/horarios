<?php

namespace App\Mappers\horarios;

use App\Models\AlumnoGrado;

class AlumnoGradoMapper
{

    public static function toAlumnoGrado($alumnoGradoData)
    {
        return new AlumnoGrado([
            'id_alumno' => $alumnoGradoData['id_alumno'],
            'id_grado' => $alumnoGradoData['id_grado'],
        ]);
    }

}