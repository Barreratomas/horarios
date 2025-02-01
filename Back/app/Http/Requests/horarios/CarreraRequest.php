<?php

namespace App\Http\Requests\horarios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Requests\LogsRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
        $logsRequest = new LogsRequest();
        $logsRules = $logsRequest->rules($esCreacion);

        $carreraRules = $esCreacion ? ['required', 'string', 'max:70'] : ['required', 'string', 'max:70'];
        $cupoRules = $esCreacion ? ['required', 'integer', 'min:1'] : ['required', 'integer', 'min:1'];



        return array_merge(
            $logsRules,  // Reglas de LogsRequest
            [
                'carrera' => $carreraRules,
                'cupo' => $cupoRules
            ]
        );
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'error' => $validator->errors()->all(),
            'message' => 'Error de validación en los datos enviados.',
        ], 422));
    }
}
