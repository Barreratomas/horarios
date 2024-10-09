<?php

namespace App\Models\horarios;

use Illuminate\Database\Eloquent\Model;

class GradoUC extends Model
{
    protected $table = 'grado_uc';
    protected $primaryKey = ['id_grado', 'id_uc'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_grado',
        'id_uc'
    ];

    // Una o muchas Grado_uc tienen un grado
    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado');
    }

    /*
    // Una o muchas Grado_uc tienen una UnidadCurricular
    public function unidadCurricular()
    {
        return $this->belongsTo(UnidadCurricular::class, 'id_uc');
    }
    */
}