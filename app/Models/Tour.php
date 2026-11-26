<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Tour 1 <>--- 0..* Salida (composicion, bidireccional).
 * Las salidas se eliminan junto con el tour (cascadeOnDelete en la migracion).
 */
class Tour extends Model
{
    /** @use HasFactory<\Database\Factories\TourFactory> */
    use HasFactory;

    protected $fillable = ['nombre', 'precio_por_persona', 'duracion_horas'];

    protected function casts(): array
    {
        return [
            'precio_por_persona' => 'decimal:2',
            'duracion_horas' => 'integer',
        ];
    }

    public function salidas(): HasMany
    {
        return $this->hasMany(Salida::class);
    }
}
