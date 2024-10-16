<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



/**
 * @OA\Schema(
 *     schema="Localidad",
 *     title="Localidad",
 *     description="Esquema del objeto Localidad",
 *     @OA\Property(
 *         property="id_localidad",
 *         type="integer",
 *         description="ID de la localidad"
 *     ),
 *     @OA\Property(
 *         property="localidad",
 *         type="string",
 *         description="Nombre de la localidad"
 *     )
 * )
 */
class Localidad extends Model
{
    use HasFactory;

    protected $fillable = ['id_localidad', 'localidad'];
    protected $table = 'localidad';
    protected $primaryKey = 'id_localidad';

    public $autoincrement = false; 

    public $timestamps = false; 

    // Una localidad Pertenece a un administrador
    public function administrador():BelongsTo{
        return $this->belongsTo(Administrador::class, 'id_localidad', 'id_localidad');
    }
    // Una localidad pertenece a un docente
    public function Docente():BelongsTo{
        return $this->belongsTo(Docente::class, 'id_localidad', 'id_localidad');
    }

    // Una localidad pertenece a un alumno
    public function alumno():BelongsTo{
        return $this->belongsTo(Alumno::class, 'id_localidad', 'id_localidad');
    }

}
