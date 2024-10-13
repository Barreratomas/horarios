<?php

namespace App\Repositories;

interface CarreraUCRepository
{
    public function obtenerTodosCarreraUC();
    public function obtenerCarreraUCPorIdCarrera($id_grado);
    public function obtenerCarreraUCPorIdUC($id_UC);
    public function guardarCarreraUC($gradoUC);
    public function eliminarCarreraUCPorIdCarrera($id_grado);
    public function eliminarCarreraUCPorIdUC($id_UC);
}
