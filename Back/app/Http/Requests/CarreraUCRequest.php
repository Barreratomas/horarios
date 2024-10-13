<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarreraUCRequest extends FormRequest
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
        
        
        $esCreacion = $this->url() == 'http://127.0.0.1:8000/horarios/carreraUC/guardar';

        $idCarreaRules = $esCreacion ? ['required','integer',Rule::exists('carrera','id')] : ['nullable','integer',Rule::exists('carrera','id')];
        $idUCRules = $esCreacion ? ['required','integer',Rule::exists('unidad_curricular','id')] : ['nullable','integer',Rule::exists('unidad_curricular','id')];
        
        return [
            'id_carrera' => $idCarreaRules,
            'id_uc' => $idUCRules
        ];
    }
}
