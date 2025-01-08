<?php

namespace App\Repositories\horarios;


interface DocenteUCRepository
{
    public function obtenerTodosDocentesUC();
    public function obtenerDocenteUCPorIdDocente($id_docente);
    public function obtenerDocenteUCPorIdUC($id_uc);
    public function guardarDocenteUC($docenteUC);
    public function actualizarDocenteUCPorIdDocente($docenteUC, $id);
    public function eliminarDocenteUCPorIdDocente($id_docente);
    public function eliminarDocenteUCPorIdUC($id_uc);

}
