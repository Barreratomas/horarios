<?php

namespace App\Repositories;

interface CarreraGradoRepository
{
    public function obtenerTodosCarreraGrado();
    public function obtenerCarreraGradoPorIdCarrera($id_grado);

    public function obtenerCarreraGradoPorIdGrado($id_UC);
    public function guardarCarreraGrado($id_grado, $id_carrera, $capacidad);
    public function eliminarCarreraGradoPorIdGradoYCarrera($id_carrera_grado);
}
