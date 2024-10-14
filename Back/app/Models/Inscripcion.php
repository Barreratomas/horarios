<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Alumno;
use App\Models\horarios\Carrera;
use App\Models\horarios\Grado;


/**
 * @OA\Schema(
 *     schema="Inscripcion",
 *     title="Inscripcion",
 *     description="Inscripcion model",
 *     @OA\Property(
 *         property="id_inscripcion",
 *         type="integer",
 *         description="ID de la inscripcion"
 *     ),
 *     @OA\Property(
 *         property="FechaHora",
 *         type="string",
 *         format="date-time",
 *         description="Fecha y hora de la inscripcion"
 *     ),
 *     @OA\Property(
 *         property="id_alumno",
 *         type="integer",
 *         description="ID del alumno"
 *     ),
 *     @OA\Property(
 *         property="id_carrera",
 *         type="integer",
 *         description="ID de la carrera"
 *     ),
 *     @OA\Property(
 *         property="id_grado",
 *         type="integer",
 *         description="ID del grado"
 *     )
 * )
 */
class Inscripcion extends Model
{
    use HasFactory;

    protected $fillable = ['FechaHora', 'id_alumno', 'id_carrera', 'id_grado'];
    protected $table = 'inscripcion';
    protected $primaryKey = 'id_inscripcion';
    public $incrementing = true;
    public $timestamps = false;

    // Una inscripcion pertenece a un alumno
    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class, 'id_alumno', 'id_alumno');
    }
    // Una inscripcion pertenece a una carrera
    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }

    // Una inscripcion pertenece a un grado
    public function grado(): BelongsTo
    {
        return $this->belongsTo(Grado::class, 'id_grado', 'id_grado');
    }

}
