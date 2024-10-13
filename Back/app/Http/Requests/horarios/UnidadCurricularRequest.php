<?php

namespace App\Http\Requests\horarios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnidadCurricularRequest extends FormRequest
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
        
        
        $esCreacion = $this->url() == 'http://127.0.0.1:8000/unidadCurricular/crear-unidadCurricular';

        $unidadCurricularRules = $esCreacion ? ['required','string','max:60',Rule::unique('unidad_curricular')] : ['nullable','string','max:60',Rule::unique('unidad_curricular')];
        $tipoRules = $esCreacion ? ['required','string','max:20'] : ['nullable','string','max:20'];
        $horasSemRules = $esCreacion ? ['required','integer'] : ['nullable','integer'];
        $horasAnualRules = $esCreacion ? ['required','integer'] : ['nullable','integer'];
        $formatoRules = $esCreacion ? ['required','string','max:20'] : ['nullable','string','max:20'];


        return [
            'Unidad_Curricular' => $unidadCurricularRules,
            'Tipo' => $tipoRules,
            'HorasSem' => $horasSemRules,
            'HorasAnual' => $horasAnualRules,
            'Formato' => $formatoRules
        ];
    }
}
