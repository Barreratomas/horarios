<?php

namespace App\Models\horarios;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Docente;



/**
 * @OA\Schema(
 *      schema="CambioDocente",
 *      title="CambioDocente",
 *      description="Esquema del objeto CambioDocente",
 *      @OA\Property(
 *          property="id_cambio",
 *          type="integer",
 *          description="ID del cambio de docente"
 *      ),
 *      @OA\Property(
 *          property="id_docente_anterior",
 *          type="integer",
 *          description="DNI del docente anterior"
 *      ),
 *      @OA\Property(
 *          property="id_docente_nuevo",
 *          type="integer",
 *          description="DNI del docente nuevo"
 *      )
 * )
 */
class CambioDocente extends Model
{
    use HasFactory;
    protected $fillable = ['id_docente_anterior','id_docente_nuevo'];
    protected $table = 'cambio_docente';
    protected $primaryKey = 'id_cambio';

    public $incrementing = true;

    public $timestamps = false;




    // Un cambio de docente pertenece a un docente anterior y a un docente nuevo
    public function docenteAnterior():BelongsTo{
        return $this->belongsTo(Docente::class, 'docente_anterior', 'dni');
    }

    public function docenteNuevo():BelongsTo{
        return $this->belongsTo(Docente::class, 'docente_nuevo', 'dni');
    }

}
