<?php

namespace App\Http\Requests\horarios;

use App\Http\Requests\LogsRequest;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\Log;

class HorarioPrevioDocenteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        Log::info('Se ha realizado una solicitud de autorización para el horario previo del docente');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Determinar si la solicitud es para creación (POST) o actualización (otros métodos)
        $esCreacion = $this->isMethod('post');
        
        Log::info('Se ha detectado el método de solicitud: ' . ($esCreacion ? 'POST (Creación)' : 'Otro método'));

        // Cargar las reglas de LogsRequest
        $logsRequest = new LogsRequest();
        $logsRules = $logsRequest->rules($esCreacion);

        // Reglas para 'id_docente' dependiendo de si es creación o actualización
        $idDocenteRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];

        // Reglas para 'dia' y 'hora'
        $rules = [
            'id_docente' => $idDocenteRules,
            'dia' => $esCreacion ? ['required', 'array', 'min:1'] : ['nullable', 'string'],
            'hora' => $esCreacion ? ['required', 'array', 'min:1'] : ['nullable', 'string'],
            'dia.*' => ['required', 'in:lunes,martes,miercoles,jueves,viernes'],
            'hora.*' => ['required', 'before:22:30'],
        ];

        // Registrar las reglas de validación
        Log::info('Reglas de validación para el horario previo del docente: ', $rules);

        // Combinamos las reglas de LogsRequest con las reglas específicas para los horarios
        return array_merge($rules, $logsRules);
    }

    /**
     * Se ejecuta después de la validación.
     *
     * @return void
     */
    public function passedValidation()
    {
        Log::info('La validación de la solicitud ha sido exitosa para el horario previo del docente');
    }

    /**
     * Se ejecuta si la validación falla.
     *
     * @return void
     */
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Log::error('La validación de la solicitud ha fallado para el horario previo del docente');
        Log::error('Errores de validación: ' . json_encode($validator->errors()->toArray()));
    }
}
