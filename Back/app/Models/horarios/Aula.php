<?php

namespace App\Models\horarios;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @OA\Schema(
 *     schema="Aula",
 *     title="Aula",
 *     description="Esquema del objeto Aula",
 *     @OA\Property(
 *         property="id_aula",
 *        type="integer",
 *        description="ID del aula"
 *    ),
 *     @OA\Property(
 *          property="nombre",
 *          type="string",
 *          description="Nombre del aula"
 *     ),
 *     @OA\Property(
 *          property="capacidad",
 *          type="integer",
 *          description="Capacidad del aula"
 *     ),
 *     @OA\Property(
 *          property="tipo_aula",
 *          type="string",
 *          description="Tipo de aula"
 *     )
 * )
 */
class Aula extends Model
{
    use HasFactory;


    protected $fillable = ['id_aula', 'nombre', 'capacidad','tipo_aula'];
    protected $table = 'aula';
    protected $primaryKey = 'id_aula';

    public $incrementing = true;

    public $timestamps = false;


    // Un aula tiene una o muchas disponibilidad
    public function disponibilidad():HasMany{
        return $this->hasMany(Disponibilidad::class, 'id_aula', 'id_aula');
    }

    // Un aula tiene uno o muchos horarios
    public function horarios():HasMany{
        return $this->hasMany(Horario::class, 'id_aula', 'id_aula');
    }


}
