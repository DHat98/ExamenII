<?php

namespace Database\Factories;

use App\Models\Reserva;
use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reserva>
 */
class ReservaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cliente_id' => Cliente::factory(),
            'fecha' => now(),
            'total' => 0,
        ];
    }
}
