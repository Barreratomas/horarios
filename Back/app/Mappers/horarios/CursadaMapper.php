<?php

namespace App\Mappers\horarios;

use App\Models\horarios\Cursada;

class CursadaMapper
{
    public static function toCursada(Cursada $cursadaData)
    {
        return new Cursada([
            'inicio' => $cursadaData['inicio'],
            'fin' => $cursadaData['fin']
        ]);
    }
}
