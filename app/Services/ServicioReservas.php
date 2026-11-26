<?php

namespace App\Services;

use App\Exceptions\CuposInsuficientesException;
use App\Models\Cliente;
use App\Models\Reserva;
use App\Models\Salida;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Clase de control: coordina la creacion de una reserva y sus items.
 */
class ServicioReservas
{
    /**
     * Registra una reserva de forma atomica.
     *
     * Reglas del caso:
     *  - Se guarda el precio por persona vigente del tour en cada item.
     *  - Se descuentan los cupos de cada salida.
     *  - Si alguna salida no tiene cupos suficientes, no se registra nada.
     *  - Dos clientes pueden competir por los ultimos cupos: se bloquean las
     *    salidas (lockForUpdate) y el descuento es condicional en el UPDATE.
     *
     * @param  array<int, array{salida_id:int, cantidad_personas:int}>  $items
     *
     * @throws CuposInsuficientesException
     */
    public function reservar(int $clienteId, array $items): Reserva
    {
        if (empty($items)) {
            throw new InvalidArgumentException('Una reserva debe incluir al menos una salida.');
        }

        return DB::transaction(function () use ($clienteId, $items) {
            $cliente = Cliente::findOrFail($clienteId);

            // Bloquea las salidas involucradas (en orden de id para evitar interbloqueos).
            $salidas = Salida::with('tour')
                ->whereIn('id', array_column($items, 'salida_id'))
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // 1. Verificar todas las salidas antes de escribir cualquier cosa.
            foreach ($items as $item) {
                $salida = $salidas->get($item['salida_id']);
                if ($salida === null) {
                    throw new InvalidArgumentException("La salida {$item['salida_id']} no existe.");
                }
                if ($salida->cupos_disponibles < $item['cantidad_personas']) {
                    throw new CuposInsuficientesException(
                        $salida->id, $item['cantidad_personas'], $salida->cupos_disponibles
                    );
                }
            }

            // 2. Crear la reserva, sus items y descontar los cupos.
            $reserva = $cliente->reservas()->create(['fecha' => now(), 'total' => 0]);
            $total = 0;

            foreach ($items as $item) {
                $salida = $salidas->get($item['salida_id']);
                $cantidad = (int) $item['cantidad_personas'];
                $precioUnitario = $salida->tour->precio_por_persona;

                // Descuento condicional: si otro proceso tomo los cupos, no afecta filas.
                $afectadas = Salida::whereKey($salida->id)
                    ->where('cupos_disponibles', '>=', $cantidad)
                    ->decrement('cupos_disponibles', $cantidad);

                if ($afectadas === 0) {
                    throw new CuposInsuficientesException(
                        $salida->id, $cantidad, (int) $salida->fresh()->cupos_disponibles
                    );
                }

                $reserva->items()->create([
                    'salida_id' => $salida->id,
                    'cantidad_personas' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                ]);

                $total += $cantidad * $precioUnitario;
            }

            $reserva->update(['total' => $total]);

            return $reserva->load('items.salida', 'cliente');
        });
    }
}
