<?php

namespace App\Models;

use App\Models\horarios\Carrera;
use App\Models\horarios\Grado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="CarreraGrado",
 *     title="CarreraGrado",
 *     description="Esquema del objeto CarreraGrado",
 *     @OA\Property(
 *         property="id_carrera",
 *         type="integer",
 *         description="ID de la carrera"
 *     ),
 *     @OA\Property(
 *         property="id_grado",
 *         type="integer",
 *         description="ID del grado"
 *     )
 * )
 */
class CarreraGrado extends Model
{
    protected $fillable = ['id_carrera', 'id_grado'];
    protected $table = 'carrera_grado';
    public $incrementing = false;
    public $timestamps = false;


    //   CarreraGrado pertenece a una Carrera.

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'id_carrera');
    }


    //   CarreraGrado pertenece a un Grado.

    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class, 'id_grado');
    }
}
