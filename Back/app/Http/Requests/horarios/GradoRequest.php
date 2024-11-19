<?php

namespace App\Http\Requests\horarios;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

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
        // Verificamos si la solicitud es un POST
        $esCreacion = $this->isMethod('post');
        
        // Logueamos la decisión del método
        Log::info('Método de solicitud: ' . $this->getMethod());
        Log::info('Es una creación (POST): ' . ($esCreacion ? 'Sí' : 'No'));

        // Verificamos los valores de los campos
        Log::info('Valor de grado: ' . $this->grado);
        Log::info('Valor de división: ' . $this->division);
        Log::info('Valor de detalle: ' . $this->detalle);
        Log::info('Valor de capacidad: ' . $this->capacidad);
        Log::info('Valor de id_carrera: ' . $this->id_carrera);

        // Validaciones para cada campo
        $gradoRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $divisionRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $detalleRules = $esCreacion ? ['required', 'string', 'max:70'] : ['nullable', 'string', 'max:70'];
        $capacidadRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];
        $carreraRules = $esCreacion ? ['required', 'integer'] : ['nullable', 'integer'];

        // Logueamos las reglas
        Log::info('Reglas de validación para grado: ', $gradoRules);
        Log::info('Reglas de validación para división: ', $divisionRules);
        Log::info('Reglas de validación para detalle: ', $detalleRules);
        Log::info('Reglas de validación para capacidad: ', $capacidadRules);
        Log::info('Reglas de validación para carrera: ', $carreraRules);

        return [
            'grado' => $gradoRules,
            'division' => $divisionRules,
            'detalle' => $detalleRules,
            'capacidad' => $capacidadRules,
            'id_carrera' => $carreraRules
        ];
    }
}
