<?php

namespace App\DTO;


/**
 * @OA\Schema(
 *     schema="AulaDTO",
 *     title="AulaDTO",
 *     description="DTO para una aula",
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         description="Nombre del aula"
 *     ),
 *     @OA\Property(
 *         property="capacidad",
 *         type="integer",
 *         description="Capacidad del aula"
 *     ),
 *     @OA\Property(
 *         property="tipo_aula",
 *         type="string",
 *         description="Tipo de aula (e.g., laboratorio, salón)"
 *     )
 * )
 */
class AulaDTO
{
    public function __construct(
        public $nombre,
        public $capacidad,
        public $tipo_aula
    ){

    }
}

