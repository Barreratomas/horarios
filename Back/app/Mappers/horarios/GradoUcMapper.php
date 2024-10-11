<?php

namespace App\Mappers\horarios;

use App\Models\horarios\GradoUC;

class GradoUCMapper
{
    public static function toGradoUC($gradoUCData)
    {
        return new GradoUC([
            'id_grado' => $gradoUCData['id_grado'],
            'id_UC' => $gradoUCData['id_UC'],
        ]);
    }

   
    public static function toGradoUCData($gradoUC)
    {
        return [
            'id_grado' => $gradoUC->id_grado,
            'id_UC' => $gradoUC->id_UC,
        ];
    }
}