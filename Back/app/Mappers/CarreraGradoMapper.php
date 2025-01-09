<?php

namespace App\Mappers;

use App\Models\CarreraGrado;

class CarreraGradoMapper
{

    public static function toCarreraGrado($id_carrera, $id_grado, $capacidad)
    {
        return new CarreraGrado([
            'id_carrera' => $id_carrera,
            'id_grado' => $id_grado,
            'capacidad' => $capacidad
        ]);
    }
}
