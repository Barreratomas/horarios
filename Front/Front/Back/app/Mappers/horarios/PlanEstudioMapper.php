<?php

namespace App\Mappers\horarios;

use App\Models\horarios\PlanEstudio;

class PlanEstudioMapper
{
    public static function toPlanEstudio($planEstudioData)
    {
        return new PlanEstudio([
            'detalle' => $planEstudioData['detalle'],
            'fecha_inicio' => $planEstudioData['fecha_inicio'],
            'fecha_fin' => $planEstudioData['fecha_fin']
        ]);
    }
}
