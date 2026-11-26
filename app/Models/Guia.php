<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Salida 0..* ---> 0..1 Guia: la navegabilidad es solo de Salida hacia Guia,
 * por eso Guia no declara una relacion hacia sus salidas.
 */
class Guia extends Model
{
    /** @use HasFactory<\Database\Factories\GuiaFactory> */
    use HasFactory;

    protected $table = 'guias';

    protected $fillable = ['nombre', 'telefono'];
}
