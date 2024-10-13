<?php

namespace App\Http\Requests\horarios;

use App\Models\Carrera;
use App\Models\Comision;
use App\Models\horarios\Grado;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HorarioRequest extends FormRequest
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
        $id_primer_grado = Grado::orderBy('id_grado')->first()->id_grado;
        $id_ultimo_grado = Grado::orderBy('id_grado', 'desc')->first()->id_grado;
        
       

        return [
            'grado' => [
                'required',
                'integer',
                Rule::exists('grado', 'id_grado'), // Utiliza la regla exists para validar que el valor proporcionado para 'grado' existe en la columna 'id_grado' de la tabla 'grado'
                'min:' . $id_primer_grado,
                'max:' . $id_ultimo_grado
                
                
            ],
          
        ];
    }
}
