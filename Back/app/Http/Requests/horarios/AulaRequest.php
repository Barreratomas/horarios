<?php

namespace App\Http\Requests\horarios;

use App\Http\Requests\LogsRequest;

use App\Models\Aula;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class AulaRequest extends FormRequest
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


        $nombreRules = $esCreacion ? ['required', 'string', 'max:255', Rule::unique('aula')] : ['required', 'string', 'max:255', Rule::unique('aula')->ignore($this->route('id'), 'id_aula')];
        $tipoAulaRules = $esCreacion ? ['required ', ' string'] : ['required ', ' string'];
        $capacidadRules = $esCreacion ? ['required', 'integer', 'min:1']  : ['required', 'integer', 'min:1'];


        return array_merge(
            $logsRules,  // Reglas de LogsRequest
            [
                'nombre' => $nombreRules,
                'tipo_aula' => $tipoAulaRules,
                'capacidad' => $capacidadRules,

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
