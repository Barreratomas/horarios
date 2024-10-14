<?php

namespace App\Models;

use App\Models\horarios\Grado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumnoGrado extends Model
{
    use HasFactory;

    protected $fillable= ['id_alumno', 'id_grado'];
    protected $table = 'alumno_grado';
    
    public $timestamps = false;

     # Uno o muchos Alumno_grado pertenece a un Alumno.   
     public function alumno():BelongsTo{
        return $this->belongsTo(Alumno::class, 'id_alumno');
    }

    
    # Uno o muchos Alumno_grado pertenece a un Grado.
    public function grado():BelongsTo{
        return $this->belongsTo(Grado::class, 'id_grado');
    }

}
