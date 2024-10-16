<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Localidad extends Model
{
    use HasFactory;

    protected $fillable = ['id_localidad', 'localidad'];
    protected $table = 'localidad';
    protected $primaryKey = 'id_localidad';

    public $timestamps = false; 

    // Una localidad Pertenece a un administrador
    public function administrador():BelongsTo{
        return $this->belongsTo(Administrador::class, 'id_localidad', 'id_localidad');
    }
    // Una localidad pertenece a un docente
    public function Docente():BelongsTo{
        return $this->belongsTo(Docente::class, 'id_localidad', 'id_localidad');
    }

    // Una localidad pertenece a un alumno
    public function alumno():BelongsTo{
        return $this->belongsTo(Alumno::class, 'id_localidad', 'id_localidad');
    }

}
