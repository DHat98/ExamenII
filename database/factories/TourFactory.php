<?php

namespace Database\Factories;

use App\Models\Tour;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tour>
 */
class TourFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => fake()->randomElement(['Canopy', 'Cabalgata', 'Tubing', 'Aguas termales']),
            'precio_por_persona' => fake()->randomFloat(2, 20, 150),
            'duracion_horas' => fake()->numberBetween(1, 8),
        ];
    }
}
