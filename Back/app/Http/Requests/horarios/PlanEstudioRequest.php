<?php

namespace App\Http\Requests\horarios;

use App\Models\Aula;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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


        $esCreacion = $this->url() == 'http://127.0.0.1:8000/planEstudio/crear-planEstudio';




        $detalleRules = $esCreacion ? ['required', 'string', 'max:255', Rule::unique('plan_estudio')] : ['nullable', 'string', 'max:255', Rule::unique('plan_estudio')];
        $fechaInicioRules = $esCreacion ? ['required ', 'date'] : ['nullable ', 'date'];
        $fechaFinRules = $esCreacion ? ['required ', 'date'] : ['nullable ', 'date'];


        return [
            'detalle' => $detalleRules,
            'fecha_inicio' => $fechaInicioRules,
            'fecha_fin' => $fechaFinRules
        ];
    }
}
