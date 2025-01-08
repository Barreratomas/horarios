<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogModificacionEliminacion extends Model
{
    use HasFactory;

    protected $table = 'logs_modificaciones_eliminaciones';

    public $timestamps = false;

    protected $fillable = [
        'accion',
        'usuario',
        'fecha_accion',
        'detalles',
    ];

    
}
