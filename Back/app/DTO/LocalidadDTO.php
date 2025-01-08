<?php

namespace App\DTO;




/**
 * @OA\Schema(
 *     schema="LocalidadDTO",
 *     title="LocalidadDTO",
 *     description="DTO para una localidad",
 *     @OA\Property(
 *         property="localidad",
 *         type="string",
 *         description="Nombre de la localidad"
 *     )
 * )
 */
class LocalidadDTO
{
    public function __construct(
        public $localidad
    ){

    }
}

