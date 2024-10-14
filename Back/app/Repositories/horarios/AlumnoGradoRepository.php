<?php

namespace App\Repositories\horarios;

interface AlumnoGradoRepository
{
    public function obtenerTodosAlumnoGrado();
    public function obtenerAlumnoGradoPorIdAlumno($id_alumno);
    public function obtenerAlumnoGradoPorIdGrado($id_grado);
    public function guardarAlumnoGrado($alumnoGrado);
    public function eliminarAlumnoGradoPorIdAlumno($id_alumno);
    public function eliminarAlumnoGradoPorIdGrado($id_grado);
}
