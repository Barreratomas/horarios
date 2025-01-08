<?php

namespace App\DTO;



/**
 * @OA\Schema(
 *     schema="DisponibilidadDTO",
 *     title="DisponibilidadDTO",
 *     description="DTO para una disponibilidad",
 *     @OA\Property(
 *         property="id_uc",
 *         type="integer",
 *         description="Id de la unidad curricular"
 *     ),
 *     @OA\Property(
 *         property="id_docente",
 *         type="integer",
 *         description="Id del docente"
 *     ),
 *     @OA\Property(
 *         property="id_h_p_d",
 *         type="integer",
 *         description="Id de la hora por dia"
 *     ),
 *     @OA\Property(
 *         property="id_aula",
 *         type="integer",
 *         description="Id del aula"
 *     ),
 *     @OA\Property(
 *         property="id_grado",
 *         type="integer",
 *         description="Id del grado"
 *     ),
 *     @OA\Property(
 *         property="dia",
 *         type="string",
 *         description="Dia de la semana"
 *     ),
 *     @OA\Property(
 *         property="modulo_inicio",
 *         type="string",
 *         format="time",
 *         description="Modulo de inicio en formato HH:mm:ss"
 *     ),
 *     @OA\Property(
 *         property="modulo_fin",
 *         type="string",
 *         format="time",
 *         description="Modulo de fin en formato HH:mm:ss"
 *     )
 * )
 */
class DisponibilidadDTO
{
    public function __construct(
        public $id_uc,
        public $id_docente,
        public $id_h_p_d,
        public $id_aula,
        public $id_grado,
        public $dia,
        public $modulo_inicio,
        public $modulo_fin
    ){

    }
}

