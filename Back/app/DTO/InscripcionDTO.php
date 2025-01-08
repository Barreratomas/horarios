<?php

namespace App\DTO;

/**
 * @OA\Schema(
 *    schema="InscripcionDTO",
 *  title="InscripcionDTO",
 * description="DTO para una inscripcion",
 * @OA\Property(
 *   property="fechaHora",
 * type="string",
 * description="Fecha y hora de la inscripcion"
 * ),
 * @OA\Property(
 * property="idAlumno",
 * type="integer",
 * description="ID del alumno"
 * ),
 * @OA\Property(
 * property="idCarrera",
 * type="integer",
 * description="ID de la carrera"
 * ),
 * @OA\Property(
 * property="idGrado",
 * type="integer",
 * description="ID del grado"
 * )
 * )
 */
class InscripcionDTO
{
    public function __construct(
        public $fechaHora,
        public $idAlumno,
        public $idCarrera,
        public $idGrado
    ) {

    }
}

