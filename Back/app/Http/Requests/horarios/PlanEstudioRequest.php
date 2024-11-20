<?php

namespace App\Http\Requests\horarios;

use Illuminate\Foundation\Http\FormRequest;

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

        $detalleRules = $esCreacion ? ['required', 'string', 'max:255']  : ['nullable', 'string', 'max:255'];
        $carreraRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $fechaInicioRules = $esCreacion  ? ['required', 'date']  : ['nullable', 'date'];
        $fechaFinRules = $esCreacion  ? ['required', 'date', 'after_or_equal:fecha_inicio']  : ['nullable', 'date', 'after_or_equal:fecha_inicio'];
        $materiasRules = $esCreacion ? ['required', 'array', 'min:1']  : ['nullable', 'array'];
        $materiasItemRules = ['integer'];

        return [
            'detalle' => $detalleRules,
            'carrera_id' => $carreraRules,
            'fecha_inicio' => $fechaInicioRules,
            'fecha_fin' => $fechaFinRules,
            'materias' => $materiasRules,
            'materias.*' => $materiasItemRules,
        ];
    }

}
