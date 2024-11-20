<?php

namespace App\Repositories\horarios;

interface UCPlanRepository
{
    public function obtenerUCPlan();
    public function obtenerUCPlanPorId($id);
    public function guardarUCPlan($id_plan,$materias);
    public function actualizarUCPlan($uCPlan, $id);
    public function eliminarUCPlan($id);
}
