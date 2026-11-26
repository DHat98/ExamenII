<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Cliente 1 --- 0..* Reserva (asociacion bidireccional).
 */
class Cliente extends Model
{
    /** @use HasFactory<\Database\Factories\ClienteFactory> */
    use HasFactory;

    protected $fillable = ['nombre', 'correo'];

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class);
    }
}
