<?php

namespace App\Repositories\horarios;


interface HorarioPrevioDocenteRepository
{
    public function obtenerTodosHorariosPreviosDocentes();
    public function obtenerHorarioPrevioDocentePorIdDocente($id_docente);
    public function guardarHorarioPrevioDocente($id_docente,$dia,$hora);
    public function actualizarHorarioPrevioDocente($id_h_p_d, $dia, $hora);
    public function  eliminarHorarioPrevioDocentePorId($id_h_p_d);
}
