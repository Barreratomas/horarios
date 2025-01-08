<?php

namespace App\DTO;

/**
 * @OA\Schema(
 *     schema="CambioDocenteDTO",
 *     title="CambioDocenteDTO",
 *     description="DTO para un cambio de docente",
 *     @OA\Property(
 *         property="id_docente_anterior",
 *         type="integer",
 *         description="DNI del docente anterior"
 *     ),
 *     @OA\Property(
 *         property="id_docente_nuevo",
 *         type="integer",
 *         description="DNI del docente nuevo"
 *     )
 * )
 */
class CambioDocenteDTO
{
    public function __construct(
        public $id_docente_anterior,
        public $id_docente_nuevo
    ){

    }
}