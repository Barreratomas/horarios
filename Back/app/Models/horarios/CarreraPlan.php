<?php

namespace App\Models\horarios;

use App\Models\AlumnoCarrera;
use App\Models\CarreraUC;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\horarios\PlanEstudio;


class CarreraPlan extends Model
{
    use HasFactory;

    protected $fillable = ['id_plan', 'id_carrera'];
    protected $table = 'carrera_plan';

    public $incrementing = false;

    public $timestamps = false;

    // Una carrera_plan pertenece a una carrera
    public function carrera():HasMany{
        return $this->hasMany(Carrera::class, 'id_carrera', 'id_carrera');
    }

    // Una carrera_plan pertenece a un plan de estudio
    public function planEstudio():HasMany{
        return $this->hasMany(PlanEstudio::class, 'id_plan', 'id_plan');
    }

}
