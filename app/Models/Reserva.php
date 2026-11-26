<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * - Pertenece a un Cliente (bidireccional).
 * - Reserva 0..* ---> 1..* Salida, con ItemReserva como clase asociacion.
 *   Navegable solo de Reserva hacia Salida. El minimo de 1 salida lo garantizan
 *   la validacion del controlador y ServicioReservas.
 */
class Reserva extends Model
{
    /** @use HasFactory<\Database\Factories\ReservaFactory> */
    use HasFactory;

    protected $fillable = ['cliente_id', 'fecha', 'total'];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
            'total' => 'decimal:2',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function salidas(): BelongsToMany
    {
        return $this->belongsToMany(Salida::class, 'item_reservas')
            ->using(ItemReserva::class)
            ->withPivot(['id', 'cantidad_personas', 'precio_unitario'])
            ->withTimestamps();
    }

    public function items(): HasMany
    {
        return $this->hasMany(ItemReserva::class);
    }
}
