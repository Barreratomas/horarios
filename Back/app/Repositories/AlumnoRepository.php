<?php

namespace App\Repositories;

interface AlumnoRepository
{

    public function obtenerTodosAlumnos();
    public function obtenerAlumnoPorId($id);
    public function guardarAlumno($data);
    public function actualizarAlumno($data, $id);
    public function eliminarAlumnoPorId($id);
    
}