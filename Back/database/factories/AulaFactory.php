<?php

namespace Database\Factories;

use App\Models\Horarios\Aula;
use Illuminate\Database\Eloquent\Factories\Factory;

class AulaFactory extends Factory
{
    protected $model = Aula::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word, // Genera un nombre aleatorio
            'capacidad' => $this->faker->numberBetween(20, 100), // Genera una capacidad entre 20 y 100
            'tipo_aula' => $this->faker->randomElement(['Salón', 'Laboratorio', 'Auditorio']), // Tipo de aula aleatorio
        ];
    }
}