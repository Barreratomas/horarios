<?php

namespace App\Mappers\horarios;

use App\DTO\AulaDTO;
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

    public static function toAulaDTO(AulaDTO $aula)
    {
        return new AulaDTO(
            $aula->nombre,
            $aula->capacidad,
            $aula->tipo_aula
        );
    }

}
