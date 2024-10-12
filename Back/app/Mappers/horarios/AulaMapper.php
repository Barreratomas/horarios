<?php

namespace App\Mappers\horarios;

use App\Models\horarios\Aula;

class AulaMapper
{
    public static function toAula(Aula $aulaData)
    {
        return new Aula([
            'nombre' => $aulaData['nombre'],
            'capacidad' => $aulaData['capacidad'],
            'tipo_aula' => $aulaData['tipo_aula']
        ]);
    }
}
