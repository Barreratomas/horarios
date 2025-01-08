<?php

namespace App\Mappers\horarios;

use App\Models\horarios\DocenteUC;

class DocenteUCMapper
{
    public static function toDocenteUC($docenteUCData)
    {
        return new DocenteUC([
            'id_docente' => $docenteUCData['id_docente'],
            'id_uc' => $docenteUCData['id_uc']
        ]);
    }

}
