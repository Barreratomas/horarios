<?php

namespace App\Models;

use App\Models\horarios\PlanEstudio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumnoPlan extends Model
{
    use HasFactory;

    protected $fillable = ['id_plan', 'id_alumno'];
    protected $table = 'alumno_plan';
     
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