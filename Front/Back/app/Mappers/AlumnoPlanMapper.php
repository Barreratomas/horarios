<?php

namespace App\Mappers;

use App\Models\AlumnoPlan;

class AlumnoPlanMapper
{
    public static function toAlumnoPlan($alumnoPlanData)
    {
        return new AlumnoPlan([
            'id_plan' => $alumnoPlanData['id_plan'],
            'id_alumno' => $alumnoPlanData['id_alumno'],
            
        ]);
    }

}