<?php

namespace App\Mappers\horarios;

use App\Models\AlumnoCarrera;

class AlumnoCarreraMapper
{

    public static function toAlumnoCarrera($alumnoCarreraData)
    {
        return new AlumnoCarrera([
            'id_alumno' => $alumnoCarreraData['id_alumno'],
            'id_carrera' => $alumnoCarreraData['id_carrera'],
        ]);
    }

}