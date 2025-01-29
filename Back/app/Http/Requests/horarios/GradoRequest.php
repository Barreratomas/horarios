<?php

namespace App\Http\Requests\horarios;

use App\Http\Requests\LogsRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GradoRequest extends FormRequest
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


        Log::info('Método de solicitud: ' . $this->getMethod());
        Log::info('Es una creación (POST): ' . ($esCreacion ? 'Sí' : 'No'));

        Log::info('Valor de grado: ' . $this->grado);
        Log::info('Valor de división: ' . $this->division);
        Log::info('Valor de detalle: ' . $this->detalle);
        Log::info('Valor de capacidad: ' . $this->capacidad);
        Log::info('Valor de id_carrera: ' . $this->id_carrera);
        Log::info('Valor de materias: ' . json_encode($this->materias));


        $gradoRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $divisionRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $detalleRules = $esCreacion ? ['required', 'string', 'max:70'] : ['nullable', 'string', 'max:70'];
        $capacidadRules = $esCreacion ? ['required', 'integer', 'min:0'] : ['required', 'integer', 'min:1'];
        $carreraRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $materiasRules = $esCreacion ? ['required', 'array', 'min:1'] : ['required', 'array'];

        Log::info('Reglas de validación para grado: ', $gradoRules);
        Log::info('Reglas de validación para división: ', $divisionRules);
        Log::info('Reglas de validación para detalle: ', $detalleRules);
        Log::info('Reglas de validación para capacidad: ', $capacidadRules);
        Log::info('Reglas de validación para carrera: ', $carreraRules);
        Log::info('Reglas de validación para materias: ', $materiasRules);


        return array_merge(
            $logsRules,  // Reglas de LogsRequest
            [
                'grado' => $gradoRules,
                'division' => $divisionRules,
                'detalle' => $detalleRules,
                'capacidad' => $capacidadRules,
                'id_carrera' => $carreraRules,
                'materias' => $materiasRules
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
