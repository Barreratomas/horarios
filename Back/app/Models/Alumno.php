<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @OA\Schema(
 *     schema="Alumno",
 *     title="Alumno",
 *     description="Esquema del objeto Alumno",
 *     @OA\Property(
 *         property="Id_Alumno",
 *         type="integer",
 *         description="ID del alumno"
 *     ),
 *     @OA\Property(
 *         property="DNI",
 *         type="string",
 *         description="DNI del alumno"
 *     ),
 *     @OA\Property(
 *         property="Nombre",
 *         type="string",
 *         description="Nombre del alumno"
 *     ),
 *     @OA\Property(
 *         property="Apellido",
 *         type="string",
 *         description="Apellido del alumno"
 *     ),
 *     @OA\Property(
 *         property="Email",
 *         type="string",
 *         description="Email del alumno"
 *     ),
 *     @OA\Property(
 *         property="Telefono",
 *         type="string",
 *         description="Telefono del alumno"
 *     ),
 *     @OA\Property(
 *         property="Genero",
 *         type="string",
 *         description="Genero del alumno"
 *     ),
 *     @OA\Property(
 *         property="Fecha_Nac",
 *         type="string",
 *         description="Fecha de nacimiento del alumno"
 *     ),
 *     @OA\Property(
 *         property="Nacionalidad",
 *         type="string",
 *         description="Nacionalidad del alumno"
 *     ),
 *     @OA\Property(
 *         property="Direccion",
 *         type="string",
 *         description="Direccion del alumno"
 *     ),
 *     @OA\Property(
 *         property="id_localidad",
 *         type="integer",
 *         description="ID de la localidad"
 *     )
 * )
 */
class Alumno extends Model
{
    use HasFactory;

    protected $fillable = ['DNI', 'Nombre', 'Apellido', 'Email', 'Telefono', 'Genero', 'Fecha_Nac', 'Nacionalidad', 'Direccion', 'id_localidad'];
    protected $table = 'alumno';
    protected $primaryKey = 'id_alumno';

    // Un alumno pertenece a una localidad
    public function localidad():BelongsTo{
        return $this->belongsTo(Localidad::class, 'id_localidad', 'id_localidad');
    }

    // Un alumno tiene una o muchas alumno_grado
    public function alumno_grado():HasMany{
        return $this->hasMany(AlumnoGrado::class, 'id_alumno', 'id_alumno');
    }

    // Un alumno tiene una o muchos alumno_carrera
    public function alumno_carrera():HasMany{
        return $this->hasMany(AlumnoCarrera::class, 'id_alumno', 'id_alumno');
    }

    // Un alumno tiene una o muchas alumno_uc
    public function alumno_uc():HasMany{
        return $this->hasMany(AlumnoUC::class, 'id_alumno', 'id_alumno');
    }
    
    // Un alumno tiene una o muchas alumno_plan
    public function alumno_plan():HasMany{
        return $this->hasMany(AlumnoPlan::class, 'id_alumno', 'id_alumno');
    }

    // Un alumno tiene una o muchas inscripcion
    public function inscripcion():HasMany{
        return $this->hasMany(Inscripcion::class, 'id_alumno', 'id_alumno');
    }

    /*
    // Un alumno tiene una o muchas notas
    public function notas():HasMany{
        return $this->hasMany(Nota::class, 'Id_Alumno', 'Id_Alumno');
    }

    // Un alumno tiene una o muchas inscripcion_examenes
    public function inscripcion_examenes():HasMany{
        return $this->hasMany(inscripcion_examenes::class, 'Id_Alumno', 'Id_Alumno');
    }

    // Un alumno tiene una o muchas asistencia
    public function asistencia():HasMany{
        return $this->hasMany(Asistencia::class, 'Id_Alumno', 'Id_Alumno');
    }

    
    // Un alumno tiene una o muchas solicitudes
    public function solicitudes():HasMany{
        return $this->hasMany(Solicitud::class, 'Id_Alumno', 'Id_Alumno');
    }
    */


}
