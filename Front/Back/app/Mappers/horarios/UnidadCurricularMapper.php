<?php

namespace App\Mappers\horarios;

use App\Models\horarios\UnidadCurricular;

class UnidadCurricularMapper
{
    public static function toUnidadCurricular($unidadCurricularData)
    {
        return new UnidadCurricular([
            'unidad_curricular' => $unidadCurricularData['unidad_curricular'],
            'tipo' => $unidadCurricularData['tipo'],
            'horas_sem' => $unidadCurricularData['horas_sem'],
            'horas_anual' => $unidadCurricularData['horas_anual'],
            'formato' => $unidadCurricularData['formato']
        ]);
    }
}
