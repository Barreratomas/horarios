<?php

namespace App\Mappers\horarios;

use App\Models\horarios\UnidadCurricular;

class UnidadCurricularMapper
{
    public static function toUnidadCurricular($unidadCurricularData)
    {
        return new UnidadCurricular([
            'Unidad_Curricular' => $unidadCurricularData['Unidad_Curricular'],
            'Tipo' => $unidadCurricularData['Tipo'],
            'HorasSem' => $unidadCurricularData['HorasSem'],
            'HorasAnual' => $unidadCurricularData['HorasAnual'],
            'Formato' => $unidadCurricularData['Formato']
        ]);
    }
}
