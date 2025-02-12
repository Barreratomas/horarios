<?php

namespace App\DTO;


/**
 * @OA\Schema(
 *     schema="CursadaDTO",
 *     title="CursadaDTO",
 *     description="Esquema del objeto CursadaDTO",
 *     @OA\Property(
 *         property="inicio",
 *         type="string",
 *         format="date",
 *         description="Inicio de la cursada"
 *     ),
 *     @OA\Property(
 *         property="fin",
 *         type="string",
 *         format="date",
 *         description="Fin de la cursada"
 *     )
 * )
 * 
 */
class CursadaDTO
{
    public function __construct(
        public $inicio,
        public $fin
    ){

    }
}

