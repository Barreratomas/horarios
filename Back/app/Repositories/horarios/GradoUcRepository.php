<?php

namespace App\Repositories\horarios;

interface GradoUcRepository
{
    public function obtenerTodosGradoUc();
    public function obtenerGradoUcPorIdGrado($id_grado);
    public function obtenerGradoUcPorIdUC($id_UC);
    public function guardarGradoUc($gradoUC);
    public function eliminarGradoUcPorIdGrado($id_grado);
    public function eliminarGradoUcPorIdUC($id_UC);
}
