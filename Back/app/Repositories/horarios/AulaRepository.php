<?php

namespace App\Repositories\horarios;

interface AulaRepository
{
    public function obtenerAulas();
    public function obtenerAulaPorId($id);
    public function obtenerAulaPorNombre($nombre);
    public function obtenerAulaMayorCapacidad();
    public function obtenerAulaMenorCapacidad();
    public function guardarAulas($aula);
    public function actualizarAulas($aula, $id);
    public function eliminarAulas($id);
    public function eliminarAulasPorNombre($nombre);
}
