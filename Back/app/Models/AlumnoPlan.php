<?php

namespace App\Models;

use App\Models\horarios\PlanEstudio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @OA\Schema(
 *     schema="AlumnoPlan",
 *     title="AlumnoPlan",
 *     description="Esquema del objeto AlumnoPlan",
 *     @OA\Property(
 *         property="id_plan",
 *         type="integer",
 *         description="ID del plan"
 *     ),
 *     @OA\Property(
 *         property="id_alumno",
 *         type="integer",
 *         description="ID del alumno"
 *     )
 * )
 */
class AlumnoPlan extends Model
{
    use HasFactory;

    protected $fillable = ['id_plan', 'id_alumno'];
    protected $table = 'alumno_plan';
    public $autoincrement = false;
     
    public $timestamps = false; 

     # Una o muchas Alumno_uc pertenece a un Alumno.   
     public function plan_estudio():BelongsTo{
        return $this->belongsTo(PlanEstudio::class, 'id_plan');
    }

     # Una o muchas Alumno_plan pertenece a un Alumno.   
     public function alumno():BelongsTo{
        return $this->belongsTo(Alumno::class, 'id_alumno');
    }

}