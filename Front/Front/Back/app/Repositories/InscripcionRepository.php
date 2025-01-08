<?php

namespace App\Repositories;

interface InscripcionRepository
{
    public function obtenerTodosInscripcion();
    public function obtenerInscripcionPorId($id);
    public function guardarInscripcion($inscripcion);
    public function actualizarInscripcion($inscripcion, $id);
    public function eliminarInscripcion($id);
}
