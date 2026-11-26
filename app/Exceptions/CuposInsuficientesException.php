<?php

namespace App\Exceptions;

use RuntimeException;

class CuposInsuficientesException extends RuntimeException
{
    public function __construct(public readonly int $salidaId, int $solicitados, int $disponibles)
    {
        parent::__construct(
            "La salida {$salidaId} no tiene cupos suficientes (solicitados: {$solicitados}, disponibles: {$disponibles})."
        );
    }
}
