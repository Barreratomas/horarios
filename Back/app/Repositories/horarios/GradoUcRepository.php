<?php

namespace App\Repositories\horarios;

interface GradoUcRepository
{
    public function obtenerTodosGradoUc();
    public function obtenerGradoUcPorId($id_grado, $id_UC);
    public function guardarGradoUc($gradoUC);
    public function eliminarGradoUc($id_grado, $id_UC);
}
