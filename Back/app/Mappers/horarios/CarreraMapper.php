<?php

namespace App\Mappers\horarios;

use App\Models\horarios\Carrera;

class CarreraMapper
{
    public static function toCarrera($carrera)
    {
        return new Carrera([
            'carrera' => $carrera->carrera,
            'cupo' => $carrera->cupo
        ]);
    }
}
