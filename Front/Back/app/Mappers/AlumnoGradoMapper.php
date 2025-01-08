<?php

namespace App\Mappers;

use App\Models\AlumnoGrado;

class AlumnoGradoMapper
{

    public static function toAlumnoGrado($id_alumno, $id_grado)
    {
        return new AlumnoGrado([
            'id_alumno' => $id_alumno,
            'id_grado' => $id_grado,
        ]);
    }

}