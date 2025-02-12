<?php

namespace App\Repositories\horarios;


interface DisponibilidadRepository
{
    /*
    public function obtenerTodasDisponibilidades();
    public function obtenerDisponibilidadPorId($id);
    */
    public function horaPrevia($id_h_p_d);
    public function modulosRepartidos($modulos_semanales, $moduloPrevio, $id_uc, $id_grado, $diaInstituto);
    public function verificarModulosDia($dia, $modulo_inicio, $modulo_fin, $id_uc, $id_grado, $id_materia, $modulos_semanales, $modulos_semanales_o);

    public function guardarDisponibilidad($params);
    public function actualizarDisponibilidad($disponibilidades);
    public function  eliminarDisponibilidad($disponibilidades);


    //------------------------------------------------------------------------------------------------------------------
    // swagger

    /*
    public function obtenerTodasDisponibilidades();
    public function obtenerDisponibilidadPorId($id);
    public function guardarDisponibilidadSwagger($disponibilidad);
    public function actualizarDisponibilidadSwagger($disponibilidad, $id);
    public function  eliminarDisponibilidadPorIdSwagger($id);
    */
}
