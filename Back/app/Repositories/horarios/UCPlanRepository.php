<?php

namespace App\Repositories\horarios;

interface UCPlanRepository
{
    public function obtenerUCPlan();
    public function obtenerUCPlanPorId($id);
    public function guardarUCPlan($uCPlan);
    public function actualizarUCPlan($uCPlan, $id);
    public function eliminarUCPlan($id);
}
