<?php

namespace App\Http\Requests\horarios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarreraRequest extends FormRequest
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


        $carreraRules = $esCreacion ? ['required', 'string', 'max:70', Rule::unique('carrera')] : ['nullable', 'string', 'max:70', Rule::unique('carrera')];
        $cupoRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];


        return [
            'carrera' => $carreraRules,
            'cupo' => $cupoRules
        ];
    }
}
