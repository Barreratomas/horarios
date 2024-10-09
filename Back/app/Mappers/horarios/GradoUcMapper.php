<?php

namespace App\Mappers\horarios;

use App\Models\horarios\GradoUc;

class GradoUcMapper
{
    public static function toGradoUc($gradoUcData)
    {
        return new GradoUc([
            'id_grado' => $gradoUcData['id_grado'],
            'id_uc' => $gradoUcData['id_uc'],
        ]);
    }

   
    public static function toGradoUcData($gradoUc)
    {
        return [
            'id_grado' => $gradoUc->id_grado,
            'id_uc' => $gradoUc->id_uc,
        ];
    }
}