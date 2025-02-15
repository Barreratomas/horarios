<?php

namespace App\Models;

use App\Models\horarios\CambioDocente;
use App\Models\horarios\Disponibilidad;
use App\Models\horarios\DocenteUC;
use App\Models\horarios\HorarioPrevioDocente;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @OA\Schema(
 *     schema="Docente",
 *     title="Docente",
 *     description="Esquema del objeto Docente",
 *     @OA\Property(
 *         property="id_docente",
 *         type="integer",
 *         description="ID del docente"
 *     ),
 *     @OA\Property(
 *         property="DNI",
 *         type="string",
 *         description="DNI del docente"
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         description="Nombre del docente"
 *     ),
 *     @OA\Property(
 *         property="apellido",
 *         type="string",
 *         description="Apellido del docente"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="Email del docente"
 *     ),
 *     @OA\Property(
 *         property="telefono",
 *         type="string",
 *         description="Telefono del docente"
 *     ),
 *     @OA\Property(
 *         property="genero",
 *         type="string",
 *         description="Genero del docente"
 *     ),
 *     @OA\Property(
 *         property="fecha_nac",
 *         type="string",
 *         description="Fecha de nacimiento del docente"
 *     ),
 *     @OA\Property(
 *         property="nacionalidad",
 *         type="string",
 *         description="Nacionalidad del docente"
 *     ),
 *     @OA\Property(
 *         property="direccion",
 *         type="string",
 *         description="Direccion del docente"
 *     ),
 *     @OA\Property(
 *         property="id_localidad",
 *         type="integer",
 *         description="ID de la localidad del docente"
 *     )
 * )
 */
class Docente extends Model
{
    use HasFactory;
    protected $fillable = ['DNI', 'nombre', 'apellido', 'email', 'telefono', 'genero', 'fecha_nac', 'direccion', 'id_localidad', 'estado', 'ExpProfecional', 'DispHoraria', 'estudios'];
    protected $table = 'docente';
    protected $primaryKey = 'id_docente';
    public $incrementing = true;
    public $timestamps = false;

    // Un docente pertenece a una localidad
    public function localidad(): BelongsTo
    {
        return $this->belongsTo(Localidad::class, 'id_localidad', 'id_localidad');
    }

    // Un docente tiene una o muchas disponibilidades
    public function disponibilidades(): HasMany
    {
        return $this->hasMany(Disponibilidad::class, 'id_docente', 'id_docente');
    }

    // Un docente tiene una o muchos cambios docentes
    public function cambios_docente(): HasMany
    {
        return $this->hasMany(CambioDocente::class, 'id_docente', 'id_docente');
    }

    // Un docente tiene una o muchos docente_uc
    public function docente_uc(): HasMany
    {
        return $this->hasMany(DocenteUC::class, 'id_docente', 'id_docente');
    }

    // Un docente tiene una o muchos horario_previo_docente
    public function horario_previo_docente(): HasMany
    {
        return $this->hasMany(HorarioPrevioDocente::class, 'id_docente', 'id_docente');
    }


    /*
     // Un docente tiene una o muchos examenes
     public function examenes():HasMany{
        return $this->hasMany(Examen::class, 'id_docente', 'id_docente');
    }
    */
}
