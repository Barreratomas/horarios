<?php

namespace App\Repositories\horarios;


interface HorarioPrevioDocenteRepository
{
    public function obtenerTodosHorariosPreviosDocentes();
    public function obtenerHorarioPrevioDocentePorId($id_h_p_d);
    public function guardarHorarioPrevioDocente($id_docente,$dia,$hora);
    public function actualizarHorarioPrevioDocente($dia,$hora,$h_p_d);
    public function  eliminarHorarioPrevioDocentePorId($h_p_d);
}
