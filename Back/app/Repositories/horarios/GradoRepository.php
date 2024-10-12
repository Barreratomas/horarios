<?php

namespace App\Repositories\horarios;

interface GradoRepository
{
    public function obtenerGrados();
    public function obtenerGradoPorId($id);
    //public function obtenerGradoPorNombre($nombre);
    //public function obtenerGradoPorDivision($division);
    public function guardarGrados($grado);
    public function actualizarGrados($grado, $id);
    public function eliminarGrados($id);
    //public function eliminarGradosPorNombre($nombre);
}