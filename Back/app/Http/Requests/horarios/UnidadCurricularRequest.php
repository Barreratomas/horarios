<?php

namespace App\Http\Requests\horarios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\LogsRequest;

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

        // Definir reglas de validación para UnidadCurricular
        $unidadCurricularRules = $esCreacion ? ['required', 'string', 'max:60', Rule::unique('unidad_curricular')]  : ['nullable', 'string', 'max:60', Rule::unique('unidad_curricular')->ignore($this->route('id'), 'id_uc')];
        $tipoRules = $esCreacion ? ['required', 'string', 'max:20'] : ['nullable', 'string', 'max:20'];
        $horasSemRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $horasAnualRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $formatoRules = $esCreacion ? ['required', 'string', 'max:20'] : ['nullable', 'string', 'max:20'];

        Log::info('Método de solicitud: ' . $this->getMethod());
        Log::info('Es una creación (POST): ' . ($esCreacion ? 'Sí' : 'No'));

        Log::info('Valor de unidad_curricular: ' . $this->unidad_curricular);
        Log::info('Valor de tipo: ' . $this->tipo);
        Log::info('Valor de horas_sem: ' . $this->horas_sem);
        Log::info('Valor de horas_anual: ' . $this->horas_anual);
        Log::info('Valor de formato: ' . $this->formato);

        // Logueamos las reglas de validación
        Log::info('Reglas de validación para unidad_curricular: ', $unidadCurricularRules);
        Log::info('Reglas de validación para tipo: ', $tipoRules);
        Log::info('Reglas de validación para horas_sem: ', $horasSemRules);
        Log::info('Reglas de validación para horas_anual: ', $horasAnualRules);
        Log::info('Reglas de validación para formato: ', $formatoRules);

        // Retornamos las reglas combinadas con las de LogsRequest
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
}
