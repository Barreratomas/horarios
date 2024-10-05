<?php

namespace App\Mappers\horarios;

use App\Models\horarios\Aula;

class AulaMapper
{
    public static function toAula($aulaData)
    {
        return new Aula([
            'nombre' => $aulaData['nombre'],
            'capacidad' => $aulaData['capacidad'],
            'tipo_aula' => $aulaData['tipo_aula']
        ]);
    }

    public static function toAulaData($aula)
    {
        return [
            'id_aula' => $aula->id_aula,
            'nombre' => $aula->nombre,
            'capacidad' => $aula->capacidad,
            'tipo_aula' => $aula->tipo_aula
        ];
    }

}
