<?php

namespace App\Models;

use App\Models\horarios\Carrera;
use App\Models\horarios\Disponibilidad;
use App\Models\horarios\Grado;
use App\Models\horarios\GradoUC;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    protected $fillable = ['id_carrera', 'id_grado','capacidad'];
    protected $table = 'carrera_grado';
    protected $primaryKey = 'id_carrera_grado';
    public $incrementing = true;
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
     public function disponibilidad():HasMany{
        return $this->hasMany(Disponibilidad::class, 'id_carrera_grado', 'id_carrera_grado');
    }

    // Un grado tiene uno o muchos alumno_grado
    public function alumno_grado():HasMany{
        return $this->hasMany(AlumnoGrado::class, 'id_carrera_grado', 'id_carrera_grado');
    }


    // Un grado tiene uno o muchos grado_uc
    public function grado_uc():HasMany{
        return $this->hasMany(GradoUC::class, 'id_carrera_grado', 'id_carrera_grado');
    }

}
