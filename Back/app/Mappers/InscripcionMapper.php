<?php

namespace App\Mappers;

use App\Models\Inscripcion;

class InscripcionMapper
{
    public static function toInscripcion($inscripcionData)
    {
        return new Inscripcion([
            'FechaHora' => $inscripcionData['FechaHora'],
            'id_alumno' => $inscripcionData['id_alumno'],
            'id_carrera' => $inscripcionData['id_carrera'],
            'id_grado' => $inscripcionData['id_grado']
        ]);
    }
}
