<?php

namespace App\Repositories;

interface LocalidadRepository
{
    public function obtenerTodasLocalidades();
    public function obtenerLocalidadPorId($id_localidad);
    public function guardarLocalidad($localidadData);
    public function eliminarLocalidadPorId($id_localidad);
}
