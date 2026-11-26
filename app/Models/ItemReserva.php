<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Clase asociacion entre Reserva y Salida.
 * Guarda la cantidad de personas y el precio por persona vigente al reservar.
 */
class ItemReserva extends Pivot
{
    /** @use HasFactory<\Database\Factories\ItemReservaFactory> */
    use HasFactory;

    protected $table = 'item_reservas';

    public $incrementing = true;

    protected $fillable = ['reserva_id', 'salida_id', 'cantidad_personas', 'precio_unitario'];

    protected function casts(): array
    {
        return [
            'cantidad_personas' => 'integer',
            'precio_unitario' => 'decimal:2',
        ];
    }

    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class);
    }

    public function salida(): BelongsTo
    {
        return $this->belongsTo(Salida::class);
    }
}
