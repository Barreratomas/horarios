<?php

namespace App\Repositories\horarios;

interface PlanEstudioRepository
{
    public function obtenerPlanEstudio();
    public function obtenerPlanEstudioConRelaciones();
    public function obtenerPlanEstudioPorId($id);
    public function obtenerPlanEstudioPorIdConRelaciones($id);
    public function guardarPlanEstudio($planEstudio);
    public function actualizarPlanEstudio($planEstudio, $id);
    public function eliminarPlanEstudio($id);
}
