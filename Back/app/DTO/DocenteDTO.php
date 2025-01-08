<?php

namespace App\DTO;


/**
 * @OA\Schema(
 *     schema="DocenteDTO",
 *     title="DocenteDTO",
 *     description="DTO para un docente",
 *     @OA\Property(
 *         property="dni",
 *         type="string",
 *         description="DNI del docente"
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         description="Nombre del docente"
 *     ),
 *     @OA\Property(
 *         property="apellido",
 *         type="string",
 *         description="Apellido del docente"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="Email del docente"
 *     ),
 *     @OA\Property(
 *         property="telefono",
 *         type="string",
 *         description="Telefono del docente"
 *     ),
 *     @OA\Property(
 *         property="genero",
 *         type="string",
 *         description="Genero del docente"
 *     ),
 *     @OA\Property(
 *         property="fecha_nac",
 *         type="string",
 *         description="Fecha de nacimiento del docente"
 *     ),
 *     @OA\Property(
 *         property="nacionalidad",
 *         type="string",
 *         description="Nacionalidad del docente"
 *     ),
 *     @OA\Property(
 *         property="direccion",
 *         type="string",
 *         description="Direccion del docente"
 *     ),
 *     @OA\Property(
 *         property="id_localidad",
 *         type="integer",
 *         description="Id de la localidad del docente"
 *     )
 * )
 */
class DocenteDTO
{
    public function __construct(
        public $dni,
        public $nombre,
        public $apellido,
        public $email,
        public $telefono,
        public $genero,
        public $fecha_nac,
        public $nacionalidad,
        public $direccion,
        public $id_localidad
    ){

    }
}

