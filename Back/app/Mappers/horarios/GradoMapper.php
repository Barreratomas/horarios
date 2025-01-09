<?php

namespace App\Mappers\horarios;

use App\Models\horarios\Grado;

class GradoMapper
{
    public static function toGrado($gradoData)
    {
        return new Grado([
            'grado' => $gradoData['grado'],
            'division' => $gradoData['division'],
            'detalle' => $gradoData['detalle'],
            'carrera_id' => $gradoData['carrera_id']
        ]);
    }
}
