<?php

namespace App\Repositories\horarios;


interface HorarioPrevioDocenteRepository
{
    public function obtenerTodosHorariosPreviosDocentes();
    public function obtenerHorarioPrevioDocente($id_h_p_d);
    public function guardarHorarioPrevioDocente($id_docente,$dias,$horas);
    public function actualizarHorarioPrevioDocente($id_h_p_d, $dia, $hora);
    public function  eliminarHorarioPrevioDocentePorId($id_h_p_d);
}
