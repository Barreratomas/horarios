<?php

namespace App\Models\horarios;

use App\Models\AlumnoCarrera;
use App\Models\CarreraUC;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *      schema="Carrera",
 *      title="Carrera",
 *      description="Esquema del objeto Carrera",
 *      @OA\Property(
 *          property="Id_Carrera",
 *          type="integer",
 *          description="ID de la carrera"
 *      ),
 *      @OA\Property(
 *          property="Carrera",
 *          type="string",
 *          description="Nombre de la carrera"
 *      ),
 *      @OA\Property(
 *          property="Cupo",
 *          type="integer",
 *          description="Cupo de la carrera"
 *      )
 * )
 */
class Carrera extends Model
{
    use HasFactory;

    protected $fillable = ['Carrera', 'Cupo'];
    protected $table = 'carrera';
    protected $primaryKey = 'Id_Carrera';

    /*
    // Una carrera tiene uno o muchos cupos
    public function cupos():HasMany{
        return $this->hasMany(Cupo::class, 'Id_Carrera', 'Id_Carrera');
    }
    

    // Una carrera tiene uno o muchos inscripciones aspirantes
    public function inscripcion_aspirante():HasMany{
        return $this->hasMany(inscripcion_aspirante::class, 'Id_Carrera', 'Id_Carrera');
    }

    // Una carrera tiene uno o muchos inscripciones
    public function inscripciones():HasMany{
        return $this->hasMany(Inscripcion::class, 'Id_Carrera', 'Id_Carrera');
    }

    // Una carrera tiene uno o muchos notas
    public function notas():HasMany{
        return $this->hasMany(Nota::class, 'Id_Carrera', 'Id_Carrera');
    }

    // Una carrera tiene uno o muchos carrera_plan
    public function carrera_plan():HasMany{
        return $this->hasMany(carrera_plan::class, 'Id_Carrera', 'Id_Carrera');
    }
    
    
    // Una carrera tiene uno o muchos correlatividades
    public function correlatividades():HasMany{
        return $this->hasMany(Correlatividad::class, 'Id_Carrera', 'Id_Carrera');
    }
    */

    // Una carrera tiene uno o muchos alumno_carrera
    public function alumno_carrera():HasMany{
        return $this->hasMany(AlumnoCarrera::class, 'Id_Carrera', 'Id_Carrera');
    }

     // Una carrera tiene uno o muchos carrera_uc
     public function carrera_uc():HasMany{
        return $this->hasMany(CarreraUC::class, 'Id_Carrera', 'Id_Carrera');
    }

}
