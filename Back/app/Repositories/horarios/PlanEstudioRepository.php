<?php

namespace App\Repositories\horarios;

interface PlanEstudioRepository
{
    public function obtenerPlanEstudio();
    public function obtenerPlanEstudioPorId($id);
    public function guardarPlanEstudio($planEstudio);
    public function actualizarPlanEstudio($planEstudio, $id);
    public function eliminarPlanEstudio($id);
}
