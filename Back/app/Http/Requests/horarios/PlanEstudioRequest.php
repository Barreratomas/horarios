<?php

namespace App\Http\Requests\horarios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PlanEstudioRequest extends FormRequest
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

        // Logueamos la decisión del método
        Log::info('Método de solicitud: ' . $this->getMethod());
        Log::info('Es una creación (POST): ' . ($esCreacion ? 'Sí' : 'No'));

        // Verificamos los valores de los campos
        Log::info('Valor de detalle: ' . $this->detalle);
        Log::info('Valor de id_carrera: ' . $this->id_carrera);
        Log::info('Valor de fecha_fin: ' . $this->fecha_fin);
        Log::info('Valor de materia: ' . json_encode($this->materias));

        $detalleRules = $esCreacion ? ['required', 'string', 'max:255'] : ['nullable', 'string', 'max:255'];
        $carreraRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $fechaInicioRules = $esCreacion  ? ['required', 'date']  : ['nullable', 'date'];
        $fechaFinRules = $esCreacion  ? ['required', 'date', 'after_or_equal:fecha_inicio']  : ['nullable', 'date', 'after_or_equal:fecha_inicio'];
        $materiasRules = $esCreacion ? ['required', 'array', 'min:1']  : ['nullable', 'array'];
        $materiasItemRules = ['integer'];

        $rules = [
            'detalle' => $detalleRules,
            'id_carrera' => $carreraRules,
            'fecha_inicio' => $fechaInicioRules,
            'fecha_fin' => $fechaFinRules,
            'materias' => $materiasRules,
            'materias.*' => $materiasItemRules,
        ];

        return $rules;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // Si deseas lanzar una excepción personalizada
        $errors = $validator->errors();

        // Puedes crear una excepción personalizada
        throw new ValidationException(
            $validator, 
            response()->json([
                'error' => 'Datos no válidos',
                'messages' => $errors->messages()
            ], 422)
        );
    }
}
