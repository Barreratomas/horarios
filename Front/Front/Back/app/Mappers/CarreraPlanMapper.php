<?php

namespace App\Mappers;

use App\Models\CarreraPlan;


class CarreraPlanMapper
{

    public static function toCarreraPlan($id_carrera, $id_plan)
    {
        return new CarreraPlan([
            'id_plan' => $id_plan,
            'id_carrera' => $id_carrera
        ]);
    }   
    

}