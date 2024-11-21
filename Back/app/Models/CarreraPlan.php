<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\horarios\Carrera; 
use App\Models\horarios\PlanEstudio; 


/**
 * @OA\Schema(
 *     schema="CarreraPlan",
 *     title="CarreraPlan",
 *     description="Modelo CarreraPlan",
 *     @OA\Property(
 *         property="id_carrera",
 *         type="integer",
 *         description="ID de la carrera"
 *     ),
 *     @OA\Property(
 *         property="id_plan",
 *         type="integer",
 *         description="ID del plan de estudio"
 *     )
 * )
 */
class CarreraPlan extends Model
{
    use HasFactory;

    protected $fillable = ['id_plan', 'id_carrera'];
    protected $table = 'carrera_plan'; 

    public $incrementing = false;
    public $timestamps = false;

    // Especificar las claves compuestas
    protected $primaryKey = ['id_carrera', 'id_plan'];

    // Una carrera_plan pertenece a una carrera
    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }

    // Una carrera_plan pertenece a un plan de estudio
    public function planEstudio(): BelongsTo
    {
        return $this->belongsTo(PlanEstudio::class, 'id_plan', 'id_plan');
    }
    

    
}
