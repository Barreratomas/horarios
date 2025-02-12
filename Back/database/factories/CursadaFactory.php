<?php

namespace Database\Factories;

use App\Models\horarios\Cursada;
use Illuminate\Database\Eloquent\Factories\Factory;

class CursadaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cursada::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'inicio' => $this->faker->date(), // Fecha aleatoria
            'fin' => $this->faker->date(),    // Fecha aleatoria
        ];
    }
}