<?php

namespace App\Repositories\horarios;

interface UnidadCurricularRepository
{
    public function obtenerUnidadCurricular();
    public function obtenerUnidadCurricularPorId($id);
    public function guardarUnidadCurricular($unidadCurricular);
    public function actualizarUnidadCurricular($unidadCurricular, $id);
    public function eliminarUnidadCurricular($id);
}
