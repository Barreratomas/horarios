<?php

namespace App\Models\horarios;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
   *     schema="Cursada",
    *     title="Cursada",
    *     description="Esquema del objeto Cursada",
    *     @OA\Property(
    *         property="id_cursada",
    *        type="integer",
    *        description="ID de la cursada"
    *    ),
    *     @OA\Property(
    *          property="inicio",
    *          type="date",
    *          description="Inicio de la cursada"
    *     ),
    *     @OA\Property(
    *          property="fin",
    *          type="date",
    *          description="Fin de la cursada"
    *     ),
    * )
**/
class Cursada extends Model
{
    use HasFactory;

    protected $fillable = ['inicio', 'fin'];
    protected $table = 'cursada';
    protected $primaryKey = 'id_cursada';

    public $incrementing = true;

    public $timestamps = false;

}
