<?php

namespace App\Http\Requests\horarios;

use Illuminate\Foundation\Http\FormRequest;

class CambioDocenteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return T_REQUIRE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $esCreacion = $this->url() == 'http://127.0.0.1:8000/cambioDocente/crear-cambioDocente';

        $docenteAnteriorRules = $esCreacion ? ['required','string','max:255'] : ['nullable','string','max:255'];
        $docenteNuevoRules = $esCreacion ? ['required','string','max:255'] : ['nullable','string','max:255'];


        return [
            'docente_anterior' => $docenteAnteriorRules,
            'docente_nuevo' => $docenteNuevoRules
        ];
    }
}
