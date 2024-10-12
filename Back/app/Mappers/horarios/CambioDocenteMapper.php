<?php

namespace App\Mappers\horarios;

use App\Models\horarios\CambioDocente;

class CambioDocenteMapper
{
    public static function toCambioDocente(CambioDocente $cambioDocente)
    {
        return new CambioDocente([
            'id_docente_anterior' => $cambioDocente['id_docente_anterior'],
            'id_docente_nuevo' => $cambioDocente['id_docente_nuevo']
        ]);
    }
}
