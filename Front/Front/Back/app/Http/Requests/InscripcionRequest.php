<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InscripcionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        
        
        $esCreacion = $this->isMethod('post');

        $fechaHoraRules = $esCreacion ? ['required', 'date', 'date_format:Y-m-d H:i:s'] : ['nullable', 'date', 'date_format:Y-m-d H:i:s'];
        $idAlumnoRules = $esCreacion ? ['required','integer',Rule::exists('alumno','id_alumno')] : ['nullable','integer',Rule::exists('alumno','id_alumno')];
        $idCarreraRules = $esCreacion ? ['required','integer',Rule::exists('carrera','id_carrera')] : ['nullable','integer',Rule::exists('carrera','id_carrera')];
        $idGradoRules = $esCreacion ? ['required','integer',Rule::exists('grado','id_grado')] : ['nullable','integer',Rule::exists('grado','id_grado')];


        return [
            'FechaHora' => $fechaHoraRules,
            'id_alumno' => $idAlumnoRules,
            'id_carrera' => $idCarreraRules,
            'id_grado' => $idGradoRules
        ];
    }
}
