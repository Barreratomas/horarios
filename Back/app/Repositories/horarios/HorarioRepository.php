<?php

namespace App\Repositories\horarios;

interface HorarioRepository
{

    public function obtenerTodosHorarios();
    public function obtenerHorarioPorId($id);
    public function guardarHorarios($dia, $modulo_inicio, $modulo_fin, $id_disp);
    public function actualizarHorarios($horario, $id);
    public function eliminarHorarios($id);

    //------------------------------------------------------------------------------------------------------------------
    public function obtenerTodosHorariosSwagger();
    public function obtenerHorarioPorIdSwagger($id);
    public function guardarHorariosSwagger($horario);
    public function actualizarHorariosSwagger($horario, $id);
    public function eliminarHorariosSwagger($id);
}
