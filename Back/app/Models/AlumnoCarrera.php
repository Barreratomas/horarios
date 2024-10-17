<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\horarios\Carrera;


/**
 * @OA\Schema(
 *     schema="AlumnoCarrera",
 *     title="AlumnoCarrera",
 *     description="Esquema del objeto AlumnoCarrera",
 *     @OA\Property(
 *         property="id_alumno",
 *         type="integer",
 *         description="ID del alumno"
 *     ),
 *     @OA\Property(
 *         property="id_carrera",
 *         type="integer",
 *         description="ID de la carrera"
 *     )
 * )
 */
class AlumnoCarrera extends Model
{
    use HasFactory;

    protected $fillable = ['id_alumno', 'id_carrera'];
    protected $table = 'alumno_carrera';
    public $autoincrement = false;

    public $timestamps = false;

    # Un Alumno_Carrera pertenece a un Alumno.   
    public function alumno():BelongsTo{
        return $this->belongsTo(Alumno::class, 'id_alumno');
    }

    
    # Un Alumno_Carrera pertenece a una Carrera.
    public function carrera():BelongsTo{
        return $this->belongsTo(Carrera::class, 'id_carrera');
    }
}
