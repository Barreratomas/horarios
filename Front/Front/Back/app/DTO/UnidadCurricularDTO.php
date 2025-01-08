<?php

namespace App\DTO;



class UnidadCurricularDTO
{


    /**
     * @OA\Schema(
     *    schema="UnidadCurricularDTO",
     *   title="UnidadCurricularDTO",
     *  description="DTO para una unidad curricular",
     * @OA\Property(
     *    property="unidad_curricular",
     *  type="string",
     * description="Nombre de la unidad curricular"
     * ),
     * @OA\Property(
     *   property="tipo",
     * type="string",
     * description="Tipo de la unidad curricular"
     * ),
     * @OA\Property(
     *  property="horas_sem",
     * type="integer",
     * description="Horas semanales de la unidad curricular"
     * ),
     * @OA\Property(
     *  property="horas_anual",
     * type="integer",
     * description="Horas anuales de la unidad curricular"
     * ),
     * @OA\Property(
     * property="formato",
     * type="string",
     * description="Formato de la unidad curricular"
     * )
     * )
     */
    public function __construct(
        public $unidadCurricular,
        public $tipo,
        public $horasSem,
        public $horasAnual,
        public $formato
    ) {

    }
}

