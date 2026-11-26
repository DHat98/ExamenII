<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Salida;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservaTest extends TestCase
{
    use RefreshDatabase;

    public function test_reserva_valida_con_dos_salidas_se_registra_y_descuenta_cupos(): void
    {
        $cliente = Cliente::factory()->create();
        $canopy = Tour::factory()->create(['precio_por_persona' => 50]);
        $tubing = Tour::factory()->create(['precio_por_persona' => 30]);
        $salida1 = Salida::factory()->for($canopy)->create(['cupos_disponibles' => 10]);
        $salida2 = Salida::factory()->for($tubing)->create(['cupos_disponibles' => 5]);

        $response = $this->postJson('/reservas', [
            'cliente_id' => $cliente->id,
            'items' => [
                ['salida_id' => $salida1->id, 'cantidad_personas' => 3],
                ['salida_id' => $salida2->id, 'cantidad_personas' => 2],
            ],
        ]);

        $response->assertCreated();

        $this->assertDatabaseCount('reservas', 1);
        $this->assertDatabaseHas('reservas', ['cliente_id' => $cliente->id, 'total' => 210]); // 3*50 + 2*30
        $this->assertDatabaseCount('item_reservas', 2);
        $this->assertDatabaseHas('item_reservas', [
            'salida_id' => $salida1->id, 'cantidad_personas' => 3, 'precio_unitario' => 50,
        ]);
        $this->assertDatabaseHas('item_reservas', [
            'salida_id' => $salida2->id, 'cantidad_personas' => 2, 'precio_unitario' => 30,
        ]);

        $this->assertSame(7, $salida1->fresh()->cupos_disponibles);
        $this->assertSame(3, $salida2->fresh()->cupos_disponibles);
    }

    public function test_si_la_segunda_salida_no_tiene_cupos_no_se_registra_nada(): void
    {
        $cliente = Cliente::factory()->create();
        $salida1 = Salida::factory()->create(['cupos_disponibles' => 10]);
        $salida2 = Salida::factory()->create(['cupos_disponibles' => 1]);

        $response = $this->postJson('/reservas', [
            'cliente_id' => $cliente->id,
            'items' => [
                ['salida_id' => $salida1->id, 'cantidad_personas' => 3],
                ['salida_id' => $salida2->id, 'cantidad_personas' => 2],
            ],
        ]);

        $response->assertStatus(422);

        $this->assertDatabaseCount('reservas', 0);
        $this->assertDatabaseCount('item_reservas', 0);
        $this->assertSame(10, $salida1->fresh()->cupos_disponibles);
        $this->assertSame(1, $salida2->fresh()->cupos_disponibles);
    }
}
