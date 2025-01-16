<?php

namespace App\Http\Requests\horarios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\LogsRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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



        $esCreacion = $this->isMethod('post');
        $logsRequest = new LogsRequest();

        // Obtener reglas de LogsRequest
        $logsRules = $logsRequest->rules($esCreacion);


        $unidadCurricularRules = $esCreacion ? ['required', 'string', 'max:60', Rule::unique('unidad_curricular')]  : ['required', 'string', 'max:60', Rule::unique('unidad_curricular')->ignore($this->route('id'), 'id_uc')];
        $tipoRules = $esCreacion ? ['required', 'string', 'max:20'] : ['required', 'string', 'max:20'];
        $horasSemRules = $esCreacion ? ['required', 'integer', 'min:1'] : ['required', 'integer', 'min:1'];
        $horasAnualRules = $esCreacion ? ['required', 'integer', 'min:1'] : ['required', 'integer', 'min:1'];

        $formatoRules = $esCreacion ? ['required', 'string', 'max:20'] : ['required', 'string', 'max:20'];

        Log::info('Método de solicitud: ' . $this->getMethod());
        Log::info('Es una creación (POST): ' . ($esCreacion ? 'Sí' : 'No'));

        Log::info('Valor de unidad_curricular: ' . $this->unidad_curricular);
        Log::info('Valor de tipo: ' . $this->tipo);
        Log::info('Valor de horas_sem: ' . $this->horas_sem);
        Log::info('Valor de horas_anual: ' . $this->horas_anual);
        Log::info('Valor de formato: ' . $this->formato);

        Log::info('Reglas de validación para unidad_curricular: ', $unidadCurricularRules);
        Log::info('Reglas de validación para tipo: ', $tipoRules);
        Log::info('Reglas de validación para horas_sem: ', $horasSemRules);
        Log::info('Reglas de validación para horas_anual: ', $horasAnualRules);
        Log::info('Reglas de validación para formato: ', $formatoRules);

        return array_merge(
            $logsRules,  // Reglas de LogsRequest
            [
                'unidad_curricular' => $unidadCurricularRules,
                'tipo' => $tipoRules,
                'horas_sem' => $horasSemRules,
                'horas_anual' => $horasAnualRules,
                'formato' => $formatoRules
            ]
        );
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()->all(),
            'message' => 'Error de validación en los datos enviados.',
        ], 422));
    }
}
