<?php

namespace App\Http\Requests\horarios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocenteUCRequest extends FormRequest
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

        $id_docenteRules = $esCreacion ? ['required', 'integer', 'min:1', Rule::unique('docente_uc', 'id_docente')] : [];
        $id_ucRules=$esCreacion ? ['required', 'integer', 'min:1', Rule::unique('docente_uc', 'id_uc')] : [];

        return [
            'id_docente' =>$id_docenteRules,
            'id_uc' => $id_ucRules
        ];
    }
}
