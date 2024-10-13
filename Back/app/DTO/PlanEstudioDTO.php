<?php

namespace App\DTO;


/**
 * @OA\Schema(
 *     schema="PlanEstudioDTO",
 *     title="PlanEstudioDTO",
 *     description="DTO para un plan de estudio",
 *     @OA\Property(
 *         property="detalle",
 *         type="string",
 *         description="Detalle del plan de estudio"
 *     ),
 *     @OA\Property(
 *         property="fecha_inicio",
 *         type="date",
 *         description="Fecha de inicio del plan de estudio"
 *     ),
 *     @OA\Property(
 *         property="fecha_fin",
 *         type="date",
 *         description="Fecha de fin del plan de estudio"
 *     )
 * )
 */
class PlanEstudioDTO
{
    public function __construct(
        public $detalle,
        public $fecha_inicio,
        public $fecha_fin
    ){

    }
}

