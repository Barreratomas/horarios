<?php

namespace App\Http\Requests\horarios;
use App\Http\Requests\LogsRequest;

use App\Models\Aula;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AulaRequest extends FormRequest
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

        $logsRequest= new LogsRequest();
        $logsRules = $logsRequest->rules($esCreacion);


        $nombreRules = $esCreacion ? ['required','string','max:255',Rule::unique('aula')] : ['nullable','string','max:255',Rule::unique('aula')];
        $tipoAulaRules = $esCreacion ? ['required ',' string' ]:[ 'nullable ',' string'];
        $capacidadRules = $esCreacion ? ['required ',' integer' ]:[ 'nullable ',' integer'];


      
        return array_merge(
            $logsRules,  // Reglas de LogsRequest
            [
                'nombre' => $nombreRules,
                'tipo_aula' => $tipoAulaRules,
                'capacidad' => $capacidadRules,
             
            ]
        );
    }
}
