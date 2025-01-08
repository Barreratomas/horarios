<?php

namespace App\DTO;


/*
    * @OA\Schema(
    *      schema="HorarioDTO",
    *      title="HorarioDTO",
    *      description="HorarioDTO model",
    *      @OA\Property(
    *          property="dia",
    *          type="string",
    *          description="dia"
    *      ),
    *      @OA\Property(
    *         property="modulo_inicio",
    *         type="string",
    *         format="time",
    *         description="Modulo de inicio del horario"
    *     ),
    *     @OA\Property(
    *         property="modulo_fin",
    *         type="string",
    *         format="time",
    *         description="Modulo de fin del horario"
    *     ),
    *      @OA\Property(
    *          property="modalidad",
    *          type="string",
    *          description="modalidad"
    *      ),
    *      @OA\Property(
    *          property="id_disp",
    *          type="integer",
    *          description="id_disp"
    *      ),
    *      @OA\Property(
    *          property="id_uc",
    *          type="integer",
    *          description="id_uc"
    *      ),
    *      @OA\Property(
    *          property="id_aula",
    *          type="integer",
    *          description="id_aula"
    *      ),
    *      @OA\Property(
    *          property="id_grado",
    *          type="integer",
    *          description="id_grado"
    *      )
    * )
    */
class HorarioDTO
{
    public function __construct(
        public $dia,
        public $modulo_inicio,
        public $modulo_fin,
        public $modalidad,
        public $id_disp,
        public $id_uc,
        public $id_aula,
        public $id_grado
    ){

    }
}

