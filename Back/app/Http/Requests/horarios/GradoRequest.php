<?php

namespace App\Http\Requests\horarios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GradoRequest extends FormRequest
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

        $esCreacion = $this->url() == 'http://127.0.0.1:8000/grado/crear-grado';


        $gradoRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $divisionRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $detalleRules = $esCreacion ? ['required', 'string', 'max:70'] : ['nullable', 'string', 'max:70'];
        $capacidadRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $carreraIdRules = $esCreacion ? ['required', 'integer', 'min:1'] : ['nullable', 'integer', 'min:1'];


        return [
            'grado' => $gradoRules,
            'division' => $divisionRules,
            'detalle' => $detalleRules,
            'capacidad' => $capacidadRules,
            'carrera_id' => $carreraIdRules
        ];
    }
}
