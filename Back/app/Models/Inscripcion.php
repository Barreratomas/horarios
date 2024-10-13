<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;

    protected $fillable = ['id_inscripcion', 'id_alumno', 'id_carrera', 'id_plan', 'fecha_inscripcion', 'fecha_egreso', 'fecha_baja', 'motivo_baja', 'observaciones'];
}
