<?php

namespace App\Repositories\horarios;

interface AlumnoPlanRepository
{
    public function obtenerTodosAlumnoPlan();
    public function obtenerAlumnoPlanPorIdAlumno($id_alumno);
    public function obtenerAlumnoPlanPorIdPlan($id_plan);
    public function guardarAlumnoPlan($alumnoPlan);
    public function eliminarAlumnoPlanPorIdAlumno($id_alumno);
    public function eliminarAlumnoPlanPorIdPlan($id_plan);
}