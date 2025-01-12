<?php

namespace App\Models\horarios;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @OA\Schema(
 *     schema="Horario",
 *     title="Horario",
 *     description="Esquema del objeto Horario",
 *     @OA\Property(
 *         property="id_horario",
 *         type="integer",
 *         description="ID del horario"
 *     ),
 *     @OA\Property(
 *         property="dia",
 *         type="string",
 *         description="Dia del horario"
 *     ),
 *     @OA\Property(
 *         property="modulo_inicio",
 *         type="string",
 *         format="time",
 *         description="Modulo de inicio del horario"
 *     ),
 *     @OA\Property(
 *         property="modulo_fin",
 *         type="string",
 *         format="time",
 *         description="Modulo de fin del horario"
 *     ),
 *     @OA\Property(
 *         property="modalidad",
 *         type="string",
 *         description="Modalidad del horario"
 *     ),
 *     @OA\Property(
 *         property="id_disp",
 *         type="integer",
 *         description="ID de la disponibilidad"
 *     ),
 *     @OA\Property(
 *         property="id_uc",
 *         type="integer",
 *         description="ID de la unidad curricular"
 *     ),
 *     @OA\Property(
 *         property="id_aula",
 *         type="integer",
 *         description="ID del aula"
 *     ),
 *     @OA\Property(
 *         property="id_grado",
 *         type="integer",
 *         description="ID del grado"
 *     )
 * )
 */
class Horario extends Model
{
    use HasFactory;

    protected $fillable = ['dia', 'modulo_inicio', 'modulo_fin', 'modalidad', 'id_disp'];
    protected $table = 'horario';
    protected $primaryKey = 'id_horario';

    public $timestamps = false;

    // Un horario pertenece a una disponibilidad
    public function disponibilidad(): BelongsTo
    {
        return $this->belongsTo(Disponibilidad::class, 'id_disp', 'id_disp');
    }

    // Un horario pertenece a una unidad curricular
    public function unidadCurricular(): BelongsTo
    {
        return $this->belongsTo(UnidadCurricular::class, 'id_uc', 'id_uc');
    }

    // Un horario pertenece a un aula
    public function aula(): BelongsTo
    {
        return $this->belongsTo(Aula::class, 'id_aula', 'id_aula');
    }

    // Un horario pertenece a un grado
    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class, 'id_grado', 'id_grado');
    }
}
