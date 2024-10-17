<?php

namespace App\Models;

use App\Models\horarios\Carrera;
use App\Models\horarios\Grado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarreraGrado extends Model
{
    protected $table = 'carrera_grado';

    public $timestamps = false;

    protected $primaryKey = ['id_carrera', 'id_grado'];
    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = ['id_carrera', 'id_grado'];


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
}
