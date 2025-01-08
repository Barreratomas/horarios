<?php

namespace App\Http\Requests\horarios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Requests\LogsRequest;

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
        $logsRequest= new LogsRequest();
        $logsRules = $logsRequest->rules($esCreacion);

        $carreraRules = $esCreacion ? ['required', 'string', 'max:70'] : ['nullable', 'string', 'max:70'];
        $cupoRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];


      
        return array_merge(
            $logsRules,  // Reglas de LogsRequest
            [
               'carrera' => $carreraRules,
                'cupo' => $cupoRules
            ]
        );
    }
}
