<?php

namespace App\Repositories\horarios;

interface GradoUcRepository
{
    public function obtenerTodosGradoUc();
    public function obtenerGradoUcPorIdGrado($id_grado);
    public function obtenerGradoUcPorIdUC($id_UC);
    public function guardarGradoUc($id_grado, array $materias);
    public function actualizarGradoUC($id_grado, array $materias);
    public function eliminarGradoUcPorIdGrado($id_grado);
    public function eliminarGradoUcPorIdUC($id_UC);
}
