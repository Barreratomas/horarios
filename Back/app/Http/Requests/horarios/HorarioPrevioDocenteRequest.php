<?php

namespace App\Http\Requests\horarios;

use Illuminate\Foundation\Http\FormRequest;

class HorarioPrevioDocenteRequest extends FormRequest
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
        $trabajaInstitucion = $this->input('trabajaInstitucion') == 'si';

        // Definir las reglas de validación basadas en la condición
        $rules = [];

        if ($trabajaInstitucion) {
            $diaRules = $esCreacion ? ['required', 'array', 'min:1'] : ['nullable', 'array'];
            $horaRules = $esCreacion ? ['required', 'array', 'min:1'] : ['nullable', 'array'];

            // Regla para cada elemento de los arrays de días y horas
            $rules = [
                'dia' => array_merge($diaRules, ['in:lunes,martes,miercoles,jueves,viernes']),
                'hora' => array_merge($horaRules, ['date_format:H:i', 'before:22:30']),
            ];

            // Reglas de validación para cada día y hora en el array
            $rules['dia.*'] = ['in:lunes,martes,miercoles,jueves,viernes'];
            $rules['hora.*'] = ['date_format:H:i', 'before:22:30'];
        }

        return $rules;
    }
}
