<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class LogsRequest extends FormRequest
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
    public function rules($esCreacion = false): array
    {

        // Logueamos los valores de los campos
        Log::info('Valor de usuario: ' . $this->usuario);
        Log::info('Valor de detalles: ' . $this->detalles);

        $usuarioRules = $esCreacion ? ['nullable', 'string', 'max:50'] : ['required', 'string', 'max:50'];
        $detallesRules = $esCreacion ? ['nullable', 'string', 'max:255'] : ['required', 'string', 'max:255'];


        // Logueamos las reglas
        Log::info('Reglas de validación para usuario: ', $usuarioRules);
        Log::info('Reglas de validación para detalles: ', $detallesRules);

        return [
            'usuario' => $usuarioRules,
            'detalles' => $detallesRules,
        ];
    }
}
