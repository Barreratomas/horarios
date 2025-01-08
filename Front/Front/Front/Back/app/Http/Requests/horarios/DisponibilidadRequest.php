<?php

namespace App\Http\Requests\horarios;

use Illuminate\Foundation\Http\FormRequest;

class DisponibilidadRequest extends FormRequest
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

        $idUCRules = $esCreacion ? ['required','integer'] : ['nullable','integer'];
        $idDocenteRules = $esCreacion ? ['required','integer'] : ['nullable','integer'];
        $idHPD = $esCreacion ? ['required','integer'] : ['nullable','integer'];
        $idAulaRules = $esCreacion ? ['required','integer'] : ['nullable','integer'];
        $idGradoRules = $esCreacion ? ['required','integer'] : ['nullable','integer'];
        $diaRules = $esCreacion ? ['required','string'] : ['nullable','string'];
        $moduloInicioRules = $esCreacion ? ['required', 'date_format:H:i:s'] : ['nullable', 'date_format:H:i:s'];
        $moduloFinRules = $esCreacion ? ['required', 'date_format:H:i:s'] : ['nullable', 'date_format:H:i:s'];

        return [
            'id_uc' => $idUCRules,
            'id_docente' => $idDocenteRules,
            'id_h_p_d' => $idHPD,
            'id_aula' => $idAulaRules,
            'id_grado' => $idGradoRules,
            'dia' => $diaRules,
            'modulo_inicio' => $moduloInicioRules,
            'modulo_fin' => $moduloFinRules
        ];
    }
}
