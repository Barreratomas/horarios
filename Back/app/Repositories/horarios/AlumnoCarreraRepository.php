<?php

namespace App\Repositories\horarios;

interface AlumnoCarreraRepository
{
    public function obtenerTodosAlumnoCarrera();
    public function obtenerAlumnoCarreraPorIdAlumno($id_alumno);
    public function obtenerAlumnoCarreraPorIdCarrera($id_carrera);
    public function guardarAlumnoCarrera($alumnoCarrera);
    public function eliminarAlumnoCarreraPorIdAlumno($id_alumno);
    public function eliminarAlumnoCarreraPorIdCarrera($id_carrera);
}
