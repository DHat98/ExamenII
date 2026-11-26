<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salidas', function (Blueprint $table) {
            $table->id();
            // Composicion: la salida no existe sin su tour (se elimina con el)
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete();
            // Multiplicidad 0..1: una salida puede no tener guia asignado
            $table->foreignId('guia_id')->nullable()->constrained('guias')->nullOnDelete();
            $table->date('fecha');
            $table->unsignedInteger('cupos_disponibles');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salidas');
    }
};
