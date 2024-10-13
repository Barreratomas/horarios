<?php

namespace App\Models\horarios;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\horarios\UnidadCurricular;
use App\Models\horarios\PlanEstudio;


/**
 * @OA\Schema(
 *     schema="UCPlan",
 *     title="UCPlan",
 *     description="UCPlan model",
 *     @OA\Property(
 *         property="id_uc",
 *         type="integer",
 *         description="ID de la unidad curricular"
 *     ),
 *     @OA\Property(
 *         property="id_plan",
 *         type="integer",
 *         description="ID del plan de estudio"
 *     )
 * )
 */
class UCPlan extends Model
{
    use HasFactory;

    protected $fillable = ['id_uc', 'id_plan'];
    protected $table = 'uc_plan';

    public $incrementing = false;
    public $timestamps = false;

    // Una uc_plan pertenece a una unidad curricular
    public function unidadCurricular(): BelongsTo
    {
        return $this->belongsTo(UnidadCurricular::class, 'id_uc', 'id_uc');
    }

    // Una uc_plan pertenece a un plan de estudio
    public function planEstudio(): BelongsTo
    {
        return $this->belongsTo(PlanEstudio::class, 'id_plan', 'id_plan');
    }
}
