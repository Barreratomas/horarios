<?php

namespace App\Mappers;

use App\Models\CarreraUC;

class CarreraUCMapper
{
    public static function toCarreraUC($carreraUCData)
    {
        return new CarreraUC([
            'id_carrera' => $carreraUCData['id_carrera'],
            'id_uc' => $carreraUCData['id_uc']
        ]);
    }
}
