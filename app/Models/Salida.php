<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * - Pertenece a exactamente un Tour (composicion, bidireccional).
 * - Tiene 0..1 Guia (navegable solo de Salida hacia Guia).
 * - No declara relacion hacia Reserva: la navegabilidad es de Reserva hacia Salida.
 */
class Salida extends Model
{
    /** @use HasFactory<\Database\Factories\SalidaFactory> */
    use HasFactory;

    protected $fillable = ['tour_id', 'guia_id', 'fecha', 'cupos_disponibles'];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'cupos_disponibles' => 'integer',
        ];
    }

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function guia(): BelongsTo
    {
        return $this->belongsTo(Guia::class);
    }
}
