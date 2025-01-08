<?php

namespace App\Http\Requests\horarios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class HorarioRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        Log::info('Se ha solicitado autorización para gestionar un horario.');
        return true; // Cambiar según las necesidades de autorización
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     */
    public function rules(): array
    {
        // Determinar si la solicitud es para creación (POST) o actualización
        $esCreacion = $this->isMethod('post');
        
        Log::info('Método detectado: ' . ($esCreacion ? 'POST (Creación)' : 'Otro método'));
    
        // Reglas generales
        $rules = [
            'id_grado' => $esCreacion ? ['required', 'exists:grado,id_grado'] : ['nullable', 'exists:grado,id_grado'],
            'id_aula' => $esCreacion ? ['required', 'exists:aula,id_aula'] : ['nullable', 'exists:aula,id_aula'],
            'id_uc' => $esCreacion ? ['required', 'exists:unidad_curricular,id_uc'] : ['nullable', 'exists:unidad_curricular,id_uc'],
            'id_disp' => $esCreacion ? ['required', 'exists:disponibilidad,id_disp'] : ['nullable', 'exists:disponibilidad,id_disp'],
            'dia' => $esCreacion ? ['required', 'string', 'max:50'] : ['nullable', 'string', 'max:50'],
            'modulo_inicio' => $esCreacion ? ['required', 'date_format:H:i:s'] : ['nullable', 'date_format:H:i:s'],
            'modulo_fin' => $esCreacion ? ['required', 'date_format:H:i:s', 'after:modulo_inicio'] : ['nullable', 'date_format:H:i:s', 'after:modulo_inicio'],
            'modalidad' => ['nullable', 'string', 'max:50'],
        ];
    
        // Registrar las reglas para depuración
        Log::info('Reglas de validación para horario: ', $rules);
    
        return $rules;
    }
    

    /**
     * Personalización de mensajes de error.
     */
    public function messages(): array
    {
        return [
            'id_grado.required' => 'El grado es obligatorio.',
            'id_grado.exists' => 'El grado no existe.',
            'id_aula.required' => 'El aula es obligatoria.',
            'id_aula.exists' => 'El aula no existe.',
            'id_uc.required' => 'La unidad curricular es obligatoria.',
            'id_uc.exists' => 'La unidad curricular no existe.',
            'id_disp.required' => 'La disponibilidad es obligatoria.',
            'id_disp.exists' => 'La disponibilidad no existe.',
            'dia.required' => 'El día es obligatorio.',
            'dia.max' => 'El día no puede exceder los 50 caracteres.',
            'modulo_inicio.required' => 'El módulo de inicio es obligatorio.',
            'modulo_inicio.date_format' => 'El formato del módulo de inicio debe ser HH:mm.',
            'modulo_fin.required' => 'El módulo de fin es obligatorio.',
            'modulo_fin.date_format' => 'El formato del módulo de fin debe ser HH:mm.',
            'modulo_fin.after' => 'El módulo de fin debe ser posterior al módulo de inicio.',
        ];
    }

    /**
     * Acción a ejecutar después de una validación exitosa.
     */
    public function passedValidation()
    {
        Log::info('La validación de la solicitud ha sido exitosa.');
    }

    /**
     * Acción a ejecutar si la validación falla.
     */
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        Log::error('La validación de la solicitud ha fallado.');
        Log::error('Errores de validación: ' . json_encode($validator->errors()->toArray()));
        parent::failedValidation($validator); // Llamada a la implementación por defecto para manejar errores
    }
}
