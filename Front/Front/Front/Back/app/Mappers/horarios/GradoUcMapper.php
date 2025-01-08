<?php

namespace App\Mappers\horarios;

use App\Models\horarios\GradoUC;

class GradoUcMapper
{
    public static function toGradoUC($gradoUCData)
    {
        return new GradoUC([
            'id_grado' => $gradoUCData['id_grado'],
            'id_uc' => $gradoUCData['id_uc'],
        ]);
    }
}