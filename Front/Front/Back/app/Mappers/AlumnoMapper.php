<?php

namespace App\Mappers;

use App\Models\Alumno;

class AlumnoMapper
{
    public static function toAlumno($alumnoData)
    {
        return new Alumno([
            'DNI' => $alumnoData['DNI'],
            'Nombre' => $alumnoData['Nombre'],
            'Apellido' => $alumnoData['Apellido'],
            'Email' => $alumnoData['Email'],
            'Telefono' => $alumnoData['Telefono'],
            'Genero' => $alumnoData['Genero'],
            'Fecha_Nac' => $alumnoData['Fecha_Nac'],
            'Nacionalidad' => $alumnoData['Nacionalidad'],
            'Direccion' => $alumnoData['Direccion'],
            'id_localidad' => $alumnoData['id_localidad']
        ]);
    }

}