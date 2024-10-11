<?php

namespace App\Models\horarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Grado;
use App\Models\UnidadCurricular;

class GradoUC extends Model
{
    use HasFactory;

    protected $table = 'grado_UC';
    protected $primaryKey = ['id_grado', 'id_UC'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_grado',
        'id_UC'
    ];

    // GradoUC pertenece a un Grado
    public function grado():BelongsTo{
        return $this->BelongsTo(Grado::class, 'id_grado');
    }

    
    // GradoUC pertenece a una UnidadCurricular
    public function unidadCurricular():BelongsTo{
        return $this->BelongsTo(UnidadCurricular::class, 'id_uc');
    }
    
}