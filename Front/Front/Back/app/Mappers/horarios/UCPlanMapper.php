<?php

namespace App\Mappers\horarios;

use App\Models\horarios\UCPlan;

class UCPlanMapper
{
    public static function toUCPlan($uCPlanData)
    {
        return new UCPlan([
            'id_uc' => $uCPlanData['id_uc'],
            'id_plan' => $uCPlanData['id_plan']
        ]);
    }
}
