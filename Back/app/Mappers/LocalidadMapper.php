<?php

namespace App\Mappers;

use App\Models\Localidad;

class LocalidadMapper
{
    public static function toLocalidad($localidadData)
    {
        return new Localidad([
            'id_localidad' => $localidadData['id_localidad'],
            'localidad' => $localidadData['localidad']
        ]);
    }
}
