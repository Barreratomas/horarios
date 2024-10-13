<?php

namespace App\Models\horarios;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\UnidadCurricular;
use App\Models\horarios\CarreraPlan;
use App\Models\AlumnoPlan;


/*
    * @OA\Schema(
    *     schema="PlanEstudio",
    *     title="PlanEstudio",
    *     description="PlanEstudio model",
    *     @OA\Property(
    *         property="id_plan",
    *         type="integer",
    *         description="ID del plan de estudio"
    *     ),
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
class PlanEstudio extends Model
{
    use HasFactory;

    protected $fillable = ['detalle',  'fecha_inicio', 'fecha_fin'];
    protected $table = 'plan_estudio';
    protected $primaryKey = 'id_plan';

    public $incrementing = true;

    public $timestamps = false;

    // Un plan de estudio tiene muchas uc_plan
    public function ucPlan():HasMany{
        return $this->hasMany(UCPlan::class, 'id_plan', 'id_plan');
    }

    // Un plan de estudio tiene muchas carrera_plan
    public function carreraPlan():HasMany{
        return $this->hasMany(CarreraPlan::class, 'id_plan', 'id_plan');
    }

    // Un plan de estudio tiene muchas alumno_plan
    public function alumnoPlan():HasMany{
        return $this->hasMany(AlumnoPlan::class, 'id_plan', 'id_plan');
    }
}
