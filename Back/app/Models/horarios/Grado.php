<?php

namespace App\Models\horarios;

use App\Models\horarios\Disponibilidad;
use App\Models\horarios\Horario;
use App\Models\AlumnoGrado;
use App\Models\horarios\GradoUC;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * @OA\Schema(
 *     schema="Grado",
 *     title="Grado",
 *     description="Grado model",
 *     @OA\Property(
 *         property="id_grado",
 *         type="integer",
 *         description="ID del grado"
 *     ),
 *     @OA\Property(
 *         property="grado",
 *         type="string",
 *         description="Grado"
 *     ),
 *     @OA\Property(
 *         property="division",
 *         type="string",
 *         description="Division"
 *     ),
 *     @OA\Property(
 *         property="detalle",
 *         type="string",
 *         description="Detalle"
 *     ),
 *     @OA\Property(
 *         property="capacidad",
 *         type="integer",
 *         description="Capacidad"
 *     ),
 *    @OA\Property(
 *      property="carrera_id",
 *      type="integer",
 *      description="ID de la carrera"
 *    )
 * )
 */
class Grado extends Model
{
    use HasFactory;

    protected $fillable = ['grado', 'division', 'detalle', 'capacidad', 'carrera_id'];
    protected $table = 'grado';
    protected $primaryKey = 'id_grado';

    public $incrementing = true;

    public $timestamps = false;

    /*
    // Un grado tiene uno o muchos inscripciones
    public function inscripciones():HasMany{
        return $this->hasMany(Inscripcion::class, 'Id_Grado', 'Id_Grado');
    }
    */
      
    /*
    // Un grado tiene uno o muchos inscripcion_aspirante
    public function inscripcion_aspirante():HasMany{
        return $this->hasMany(inscripcion_aspirante::class, 'Id_Grado', 'Id_Grado');
    }
    */

    // Un grado tiene uno o muchos disponibilidad
    public function disponibilidad():HasMany{
        return $this->hasMany(Disponibilidad::class, 'Id_Grado', 'Id_Grado');
    }

    // Un grado tiene uno o muchos horarios
    public function horarios():HasMany{
        return $this->hasMany(Horario::class, 'Id_Grado', 'Id_Grado');
    }

    // Un grado tiene uno o muchos alumno_grado
    public function alumno_grado():HasMany{
        return $this->hasMany(AlumnoGrado::class, 'Id_Grado', 'Id_Grado');
    }

    // Un grado tiene uno o muchos grado_uc
    public function grado_uc():HasMany{
        return $this->hasMany(GradoUC::class, 'Id_Grado', 'Id_Grado');
    }

    // Un grado pertenece a una carrera
    public function carrera():BelongsTo{
        return $this->belongsTo(Carrera::class, 'carrera_id', 'Id_Carrera');
    }

/*
    // Un grado tiene uno o muchos inscripcion_aspirante
    public function inscripcion_aspirante():HasMany{
        return $this->hasMany(inscripcion_aspirante::class, 'Id_Grado', 'Id_Grado');
    }
*/
}
