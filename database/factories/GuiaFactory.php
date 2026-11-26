<?php

namespace Database\Factories;

use App\Models\Guia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Guia>
 */
class GuiaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->name(),
            'telefono' => fake()->numerify('8###-####'),
        ];
    }
}
