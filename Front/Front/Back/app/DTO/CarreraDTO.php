<?php

namespace App\DTO;


/**
 * @OA\Schema(
 *     schema="CarreraDTO",
 *     title="CarreraDTO",
 *     description="DTO para una carrera",
 *     @OA\Property(
 *         property="carrera",
 *         type="string",
 *         description="Nombre de la carrera"
 *     ),
 *     @OA\Property(
 *         property="cupo",
 *         type="integer",
 *         description="Cupo de la carrera"
 *     )
 * )
 */
class CarreraDTO
{
    public function __construct(
        public $carrera,
        public $cupo
    ){

    }
}