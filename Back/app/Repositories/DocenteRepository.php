<?php

namespace App\Repositories;


interface DocenteRepository
{
    
    // Swagger
    public function obtenerTodosLosDocente();
    public function obtenerDocentePorId($id);
    public function guardarDocentes($docente);
    public function actualizarDocentes($docente, $id);
    public function eliminarDocentes($id);
}
