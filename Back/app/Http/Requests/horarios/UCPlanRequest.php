<?php

namespace App\Http\Requests\horarios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UCPlanRequest extends FormRequest
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

        $idUcRules = $esCreacion ? ['required','integer',Rule::exists('unidad_curricular','id')] : ['nullable','integer',Rule::exists('unidad_curricular','id')];
        $idPlanRules = $esCreacion ? ['required','integer',Rule::exists('plan_estudio','id')] : ['nullable','integer',Rule::exists('plan_estudio','id')];
        
        return [
            'id_uc' => $idUcRules,
            'id_plan' => $idPlanRules
        ];
    }
}
