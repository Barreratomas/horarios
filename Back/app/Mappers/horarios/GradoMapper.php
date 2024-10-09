<?php

namespace App\Mappers\horarios;

use App\Models\horarios\Grado;

class GradoMapper
{
    public static function toGrado($gradoData)
    {
        return new Grado([
            'id_grado' => $gradoData['id_grado'],
            'Grado' => $gradoData['Grado'],
            'Division' => $gradoData['Division'],
            'Detalle' => $gradoData['Detalle'],
            'Capacidad' => $gradoData['Capacidad']
        ]);
    }

    public static function toGradoData($grado)
    {
        return [
            'id_grado' => $grado->id_grado,
            'Grado' => $grado->Grado,
            'Division' => $grado->Division,
            'Detalle' => $grado->Detalle,
            'Capacidad' => $grado->Capacidad
        ];
    }
}
