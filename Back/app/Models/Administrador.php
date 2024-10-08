<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @OA\Schema(
 *     schema="Administrador",
 *     title="Administrador",
 *     description="Esquema del objeto Administrador",
 *     @OA\Property(
 *         property="id_admin",
 *         type="integer",
 *         description="ID del administrador"
 *     ),
 *     @OA\Property(
 *         property="DNI",
 *         type="string",
 *         description="DNI del administrador"
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         description="Nombre del administrador"
 *     ),
 *     @OA\Property(
 *         property="apellido",
 *         type="string",
 *         description="Apellido del administrador"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="Email del administrador"
 *     ),
 *     @OA\Property(
 *         property="telefono",
 *         type="string",
 *         description="Telefono del administrador"
 *     ),
 *     @OA\Property(
 *         property="genero",
 *         type="string",
 *         description="Genero del administrador"
 *     ),
 *     @OA\Property(
 *         property="fecha_nac",
 *         type="string",
 *         description="Fecha de nacimiento del administrador"
 *     ),
 *     @OA\Property(
 *         property="nacionalidad",
 *         type="string",
 *         description="Nacionalidad del administrador"
 *     ),
 *     @OA\Property(
 *         property="direccion",
 *         type="string",
 *         description="Direccion del administrador"
 *     ),
 *     @OA\Property(
 *         property="id_localidad",
 *         type="integer",
 *         description="ID de la localidad"
 *     )
 * )
 */
class Administrador extends Model
{
    use HasFactory;

    protected $fillable = ['DNI','nombre','apellido','email','telefono','genero','fecha_nac', 'nacionalidad','direccion','id_localidad'];
    protected $table = 'administrador';
    protected $primaryKey = 'id_admin';

    // Un administrador pertenece a una localidad
    public function localidad():BelongsTo{
        return $this->belongsTo(Localidad::class, 'id_localidad', 'id_localidad');
    }

}
