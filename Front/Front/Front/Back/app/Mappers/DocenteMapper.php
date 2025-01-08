<?php

namespace App\Mappers;

use App\Models\Docente;

class DocenteMapper
{
    public static function toDocente($docenteData)
    {
        return new Docente([
            'DNI' => $docenteData['DNI'],
            'nombre' => $docenteData['nombre'],
            'apellido' => $docenteData['apellido'],
            'email' => $docenteData['email'],
            'telefono' => $docenteData['telefono'],
            'genero' => $docenteData['genero'],
            'fecha_nac' => $docenteData['fecha_nac'],
            'nacionalidad' => $docenteData['nacionalidad'],
            'direccion' => $docenteData['direccion'],
            'id_localidad' => $docenteData['id_localidad']
        ]);
    }

}
