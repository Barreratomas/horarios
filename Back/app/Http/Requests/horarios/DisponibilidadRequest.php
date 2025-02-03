<?php

namespace App\Http\Requests\horarios;

use App\Models\horarios\Disponibilidad;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DisponibilidadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        // Autorización siempre permitida
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        // Si el método es DELETE, aplicamos reglas específicas
        if ($this->isMethod('delete')) {

            return [
                'disponibilidades' => ['required', 'array'],
                'disponibilidades.*.dia' => ['required', 'string'],
                'disponibilidades.*.modulo' => ['required', 'integer'],
                'disponibilidades.*.id_disp' => ['required', 'integer', function ($attribute, $value, $fail) {
                    $this->validateDisponibilidad($attribute, $value, $fail);
                }],
            ];
        }

        $esCreacion = $this->isMethod('post');
        Log::info('Método POST detectado, generando reglas para la creación.');

        $idUCRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $idDocenteRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $idHPD = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $idAulaRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $idGradoRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $diaRules = $esCreacion ? ['required', 'string'] : ['nullable', 'string'];
        $moduloInicioRules = $esCreacion ? ['required', 'date_format:H:i:s'] : ['nullable', 'date_format:H:i:s'];
        $moduloFinRules = $esCreacion ? ['required', 'date_format:H:i:s'] : ['nullable', 'date_format:H:i:s'];

        return [
            'id_uc' => $idUCRules,
            'id_docente' => $idDocenteRules,
            'id_h_p_d' => $idHPD,
            'id_aula' => $idAulaRules,
            'id_grado' => $idGradoRules,
            'dia' => $diaRules,
            'modulo_inicio' => $moduloInicioRules,
            'modulo_fin' => $moduloFinRules
        ];
    }

    private function validateDisponibilidad($attribute, $value, $fail)
    {

        $input = collect($this->input('disponibilidades'));

        foreach ($input as $disponibilidad) {

            $disponibilidadDB = DB::table('disponibilidad')
                ->where('id_disp', $disponibilidad['id_disp'])
                ->first();

            if (!$disponibilidadDB) {
                Log::warning("La disponibilidad con ID {$disponibilidad['id_disp']} no existe.");
                $fail("La disponibilidad con ID {$disponibilidad['id_disp']} no existe.");
                continue;
            }

            // Verificar si el día coincide
            if ($disponibilidad['dia'] !== $disponibilidadDB->dia) {
                Log::warning("El día de la disponibilidad con ID {$disponibilidad['id_disp']} no coincide.");
                $fail("El día de la disponibilidad con ID {$disponibilidad['id_disp']} no coincide.");
                continue;
            }

            // Verificar si el módulo está dentro del rango de módulo_inicio y módulo_fin
            $modulo = $disponibilidad['modulo'];
            $moduloInicio = (int) $disponibilidadDB->modulo_inicio;
            $moduloFin = (int) $disponibilidadDB->modulo_fin;

            if ($modulo < $moduloInicio || $modulo > $moduloFin) {
                Log::warning("El módulo {$modulo} de la disponibilidad con ID {$disponibilidad['id_disp']} no está dentro del rango de módulos permitidos.");
                $fail("El módulo {$modulo} de la disponibilidad con ID {$disponibilidad['id_disp']} no está dentro del rango de módulos permitidos.");
            }
        }
    }

    protected function failedValidation(Validator $validator)
    {
        Log::error('Error de validación de los datos: ' . implode(', ', $validator->errors()->all()));

        throw new HttpResponseException(response()->json([
            'success' => false,
            'error' => $validator->errors()->all(),
            'message' => 'Error de validación en los datos enviados.',
        ], 422));
    }
}
