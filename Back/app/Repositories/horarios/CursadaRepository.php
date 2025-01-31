<?php

namespace App\Repositories\horarios;

interface CursadaRepository
{
    public function obtenerCursadas();
    public function obtenerCursadasPorId($id);
    public function guardarCursadas($aula);
    public function actualizarCursadas($aula, $id);
    public function eliminarCursadas($id);
}
