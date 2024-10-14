<?php

namespace App\Repositories\horarios;

interface AlumnoUCRepository
{
    public function obtenerTodosAlumnoUC();
    public function obtenerAlumnoUCPorIdAlumno($id_alumno);
    public function obtenerAlumnoUCPorIdUC($id_uc);
    public function guardarAlumnoUC($alumnoUC);
    public function eliminarAlumnoUCPorIdAlumno($id_alumno);
    public function eliminarAlumnoUCPorIdUC($id_uc);
}