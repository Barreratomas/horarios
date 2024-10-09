<?php

namespace App\Repositories\horarios;

interface GradoUcRepository
{
    public function obtenerTodosGradoUc();
    public function obtenerGradoUcPorId($id_grado, $id_uc);
    public function guardarGradoUc($gradoUc);
    public function eliminarGradoUc($id_grado, $id_uc);
}
