<?php

namespace App\DTO;


class GradoDTO
{

    /**
     * @OA\Schema(
     *      schema="GradoDTO",
     *      title="GradoDTO",
     *      description="GradoDTO model",
     *      @OA\Property(
     *          property="Grado",
     *          type="string",
     *          description="Grado"
     *      ),
     *      @OA\Property(
     *          property="Division",
     *          type="string",
     *          description="Division"
     *      ),
     *      @OA\Property(
     *          property="Detalle",
     *          type="string",
     *          description="Detalle"
     *      ),
     *      @OA\Property(
     *          property="Capacidad",
     *          type="integer",
     *          description="Capacidad"
     *      ),
     *     @OA\Property(
     *         property="carrera_id",
     *        type="integer",
     *     description="ID de la carrera"
     *    )
     * )
     */
    public function __construct(
        public $Grado,
        public $Division,
        public $Detalle,
        public $Capacidad,
        public $carrera_id

    ) {

    }
}

