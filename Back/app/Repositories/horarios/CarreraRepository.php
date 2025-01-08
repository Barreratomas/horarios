<?php

namespace App\Repositories\horarios;

interface CarreraRepository
{
  /*
    public function obtenerTodasCarreras();
    public function obtenerCarreraPorId($id);
    public function guardarCarrera($nombre);
    public function actualizarCarrera($nombre,$carrera);
    public function eliminarCarreraPorId($carrera);
*/
      //---------------------------------------------------------------------------------------------------------
    // Swagger

    public function obtenerTodosCarrera();
    public function obtenerCarreraPorId($id);
    public function guardarCarrera($Request);
    public function actualizarCarrera($Request, $id);
    public function eliminarCarreraPorId($id);
}