<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\horarios\UnidadCurricular;
use App\Models\horarios\Carrera;


/**
 * @OA\Schema(
 *     schema="CarreraUC",
 *     title="CarreraUC",
 *     description="CarreraUC model",
 *     @OA\Property(
 *         property="id_carrera",
 *         type="integer",
 *         description="ID de la carrera"
 *     ),
 *     @OA\Property(
 *         property="id_uc",
 *         type="integer",
 *         description="ID de la unidad curricular"
 *     )
 * )
 */
class CarreraUC extends Model
{
    use HasFactory;

    protected $fillable = ['id_carrera', 'id_uc'];

    protected $table = 'carrera_uc';

    public $incrementing = false;
    public $timestamps = false;

    // Una carrera_uc pertenece a una carrera
    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }

    // Una carrera_uc pertenece a una unidad curricular
    public function unidadCurricular(): BelongsTo
    {
        return $this->belongsTo(UnidadCurricular::class, 'id_uc', 'id_uc');
    }
}
