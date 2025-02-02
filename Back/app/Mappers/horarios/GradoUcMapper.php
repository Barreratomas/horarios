<?php

namespace App\Mappers\horarios;

use App\Models\horarios\GradoUC;

class GradoUcMapper
{
    public static function toGradoUC($gradoUCData)
    {
        return new GradoUC([
            'id_carrera_grado' => $gradoUCData['id_carrera_grado'],
            'id_uc' => $gradoUCData['id_uc'],
        ]);
    }
}
