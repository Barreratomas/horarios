<?php

namespace App\Models\horarios;

use App\Models\AlumnoUC;
use App\Models\CarreraUC;
use App\Models\horarios\GradoUC;
use App\Models\InscripcionesUC;
use App\Models\horarios\UCPlan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * @OA\Schema(
 *     schema="UnidadCurricular",
 *     title="UnidadCurricular",
 *     description="Esquema del objeto UnidadCurricular",
 *     @OA\Property(
 *         property="id_uc",
 *         type="integer",
 *         description="ID de la unidad curricular"
 *     ),
 *     @OA\Property(
 *         property="Unidad_Curricular",
 *         type="string",
 *         description="Nombre de la unidad curricular"
 *     ),
 *     @OA\Property(
 *         property="Tipo",
 *         type="string",
 *         description="Tipo de la unidad curricular"
 *     ),
 *     @OA\Property(
 *         property="HorasSem",
 *         type="integer",
 *         description="Horas semanales de la unidad curricular"
 *     ),
 *     @OA\Property(
 *         property="HorasAnual",
 *         type="integer",
 *         description="Horas anuales de la unidad curricular"
 *     ),
 *     @OA\Property(
 *         property="Formato",
 *         type="string",
 *         description="Formato de la unidad curricular"
 *     )
 * )
 */
class UnidadCurricular extends Model
{
    use HasFactory;
    protected $fillable = ['Unidad_Curricular','Tipo', 'HorasSem', 'HorasAnual', 'Formato'];
    protected $table = 'unidad_curricular';
    protected $primaryKey = 'id_uc';

    public $incrementing = true;
    public $timestamps = false;

    /*
    // Una unidad curricular tiene uno o muchas notas
    public function notas():HasMany{
        return $this->hasMany(Nota::class, 'Id_UC', 'Id_UC');
    }

    // Una unidad curricular tiene uno o muchos examenes
    public function examenes():HasMany{
        return $this->hasMany(Examen::class, 'Id_UC', 'Id_UC');
    }

    // Una unidad curricular tiene uno o muchas correlatividades
    public function correlatividades():HasMany{
        return $this->hasMany(Correlatividad::class, 'Id_UC', 'Id_UC');
    }

    // Una unidad curricular tiene uno o muchas asistencia
    public function asistencia():HasMany{
        return $this->hasMany(Asistencia::class, 'Id_UC', 'Id_UC');
    }
    */

    // Una unidad curricular tiene uno o muchas carrera_uc
    public function carrera_uc():HasMany{
        return $this->hasMany(CarreraUC::class, 'id_uc', 'id_uc');
    }

    // Una unidad curricular tiene uno o muchas inscripciones_uc
    public function inscripciones_uc():HasMany{
        return $this->hasMany(InscripcionesUC::class, 'id_uc', 'id_uc');
    }

    // Una unidad curricular tiene uno o muchas uc_plan
    public function uc_plan():HasMany{
        return $this->hasMany(UCPlan::class, 'id_uc', 'id_uc');
    }

    // Una unidad curricular tiene uno o muchas docente_uc
    public function docente_uc():HasMany{
        return $this->hasMany(DocenteUC::class, 'id_uc', 'id_uc');
    }

    // Una unidad curricular tiene uno o muchas grado_uc
    public function grado_uc():HasMany{
        return $this->hasMany(GradoUC::class, 'id_uc', 'id_uc');
    }

    // Una unidad curricular tiene uno o muchas alumno_uc
    public function alumno_uc():HasMany{
        return $this->hasMany(AlumnoUC::class, 'id_uc', 'id_uc');
    }
    
    // Una unidad curricular tiene uno o muchas disponibilidad
    public function disponibilidad():HasMany{
        return $this->hasMany(Disponibilidad::class, 'id_uc', 'id_uc');
    }

    // Una unidad curricular tiene uno o muchas horarios
    public function horarios():HasMany{
        return $this->hasMany(Horario::class, 'id_uc', 'id_uc');
    }
    

}
