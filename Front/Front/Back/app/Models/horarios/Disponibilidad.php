<?php

namespace App\Models\horarios;

use App\Models\CarreraGrado;
use App\Models\Docente;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;



/**
 * @OA\Schema(
 *      title="Disponibilidad",
 *      description="Disponibilidad model",
 *      @OA\Property(
 *          property="id_disp",
 *          type="integer",
 *          description="ID de la disponibilidad"
 *      ),
 *      @OA\Property(
 *          property="id_uc",
 *          type="integer",
 *          description="ID de la unidad curricular"
 *      ),
 *      @OA\Property(
 *          property="id_docente",
 *          type="integer",
 *          description="ID del docente"
 *      ),
 *      @OA\Property(
 *          property="id_h_p_d",
 *          type="integer",
 *          description="ID del horario previo docente"
 *      ),
 *      @OA\Property(
 *          property="id_aula",
 *          type="integer",
 *          description="ID del aula"
 *      ),
 *      @OA\Property(
 *          property="id_grado",
 *          type="integer",
 *          description="ID del grado"
 *      ),
 *      @OA\Property(
 *          property="dia",
 *          type="string",
 *          description="Dia de la disponibilidad"
 *      ),
 *      @OA\Property(
 *         property="modulo_inicio",
 *         type="string",
 *         format="time",
 *         description="Modulo de inicio en formato HH:mm:ss"
 *     ),
 *     @OA\Property(
 *         property="modulo_fin",
 *         type="string",
 *         format="time",
 *         description="Modulo de fin en formato HH:mm:ss"
 *     )
 * )
 */
class Disponibilidad extends Model
{
    use HasFactory;

    protected $fillable = ['id_uc', 'id_docente', 'id_h_p_d', 'id_aula', 'id_carrera_grado', 'dia', 'modulo_inicio', 'modulo_fin'];
    protected $table = 'disponibilidad';
    protected $primaryKey = 'id_disp';

    public $incrementing = true;

    public $timestamps = false;

    // Una disponibilidad pertenece a una unidad curricular
    public function unidadCurricular(): BelongsTo
    {
        return $this->belongsTo(UnidadCurricular::class, 'id_uc', 'id_uc');
    }

    // Una disponibilidad pertenece a una grado
    public function carreraGrado(): BelongsTo
    {
        return $this->belongsTo(CarreraGrado::class, 'id_carrera_grado', 'id_carrera_grado');
    }

    // Una disponibilidad pertenece a un aula
    public function aula(): BelongsTo
    {
        return $this->belongsTo(Aula::class, 'id_aula', 'id_aula');
    }

    // Una disponibilidad pertenece a un docente
    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'id_docente', 'id_docente');
    }

    // Una disponibilidad pertenece a un horario previo docente
    public function horarioPrevioDocente(): BelongsTo
    {
        return $this->belongsTo(HorarioPrevioDocente::class, 'id_h_p_d', 'id_h_p_d');
    }


}