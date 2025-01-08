<?php

namespace App\Repositories\horarios;

interface CambioDocenteRepository
{
    /*
    public function obtenerTodosCambiosDocente();
    public function obtenerCambioDocentePorId($id);
    public function guardarCambioDocente($docente_anterior,$docente_nuevo);
    public function actualizarCambioDocente($id,$docente_anterior,$docente_nuevo);
    public function eliminarCambioDocentePorId($id);

    */
    //---------------------------------------------------------------------------------------------------------
    // Swagger

    public function obtenerTodosCambiosDocente();
    public function obtenerCambioDocentePorId($id);
    public function guardarCambioDocente($Request);
    public function actualizarCambioDocente($Request, $id);
    public function eliminarCambioDocentePorId($id);
}