<?php

namespace App\Mappers;

use App\Models\AlumnoGrado;

class AlumnoGradoMapper
{

    public static function toAlumnoGrado($id_alumno, $id_carrera_grado)
    {
        return new AlumnoGrado([
            'id_alumno' => $id_alumno,
            'id_carrera_grado' => $id_carrera_grado,
        ]);
    }
}
