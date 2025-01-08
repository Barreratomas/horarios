<?php

namespace App\Models\horarios;

use App\Models\Docente;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * @OA\Schema(
 *     title="DocenteUC",
 *    description="DocenteUC model",
 *   @OA\Property(
 *         property="id_docente",
 *        type="integer",
 *      description="ID del docente"
 *    ),
 *   @OA\Property(
 *        property="id_uc",
 *       type="integer",
 *     description="ID de la unidad curricular"
 *  )
 * )
 */
class DocenteUC extends Model
{
    use HasFactory;
    protected $fillable = ['id_docente','id_uc'];
    protected $table = 'docente_uc';
    public $timestamps = false;

    // Un docente_uc pertenece a una unidad curricular
    public function unidadCurricular():BelongsTo{
        return $this->belongsTo(UnidadCurricular::class, 'id_uc', 'id_uc');
    }

    // Un docente_uc pertenece a un docente
    public function docente():BelongsTo{
        return $this->belongsTo(Docente::class, 'id_docente', 'id_docente');
    }

}
