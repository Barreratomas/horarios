<?php

namespace App\Models;

use App\Models\horarios\UnidadCurricular;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumnoUC extends Model
{
    use HasFactory;

    protected $fillable = ['id_alumno', 'id_uc'];
    protected $table = 'alumno_uc';
     
    public $timestamps = false; 

    # Una o muchas Alumno_uc pertenece a un Alumno.   
    public function alumno():BelongsTo{
        return $this->belongsTo(Alumno::class, 'id_alumno');
    }
   
    # Una o muchas Alumno_uc pertenece a una unidad curricular.   
     public function unidad_curricular():BelongsTo{
        return $this->belongsTo(UnidadCurricular::class, 'id_uc');
    }

}
