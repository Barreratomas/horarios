<?php

namespace App\Models\horarios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\horarios\Grado;
use App\Models\horarios\UnidadCurricular;

/**
 * @OA\Schema(
 *     title="GradoUC",
 *     description="GradoUC model",
 *     @OA\Property(
 *         property="id_grado",
 *         type="integer",
 *         description="ID del grado"
 *     ),
 *     @OA\Property(
 *         property="id_uc",
 *         type="integer",
 *         description="ID de la unidad curricular"
 *     )
 * )
 */
class GradoUC extends Model
{
    use HasFactory;

    protected $table = 'grado_uc';
    protected $primaryKey = ['id_grado', 'id_uc'];
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_grado',
        'id_uc'
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