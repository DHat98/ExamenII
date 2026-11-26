<?php

namespace Database\Factories;

use App\Models\ItemReserva;
use App\Models\Reserva;
use App\Models\Salida;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ItemReserva>
 */
class ItemReservaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reserva_id' => Reserva::factory(),
            'salida_id' => Salida::factory(),
            'cantidad_personas' => fake()->numberBetween(1, 4),
            'precio_unitario' => fake()->randomFloat(2, 20, 150),
        ];
    }
}
