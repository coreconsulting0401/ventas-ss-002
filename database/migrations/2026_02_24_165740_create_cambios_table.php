<?php

/**
 * MIGRACIÓN: create_cambios_table.php
 * Ubicación: database/migrations/2026_02_24_000001_create_cambios_table.php
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cambios', function (Blueprint $table) {
            $table->id();

            // ── Campos de la API (SOLO LECTURA para el usuario) ──────────────
            $table->string('origen', 20)->nullable();           // "SUNAT"
            $table->decimal('compra', 10, 4)->nullable();       // precio compra USD
            $table->decimal('venta',  10, 4)->nullable();       // precio venta oficial
            $table->string('moneda', 10)->nullable();           // "USD"
            $table->date('fecha')->unique();                    // un registro por día

            // ── Campos configurables por el usuario ──────────────────────────
            $table->decimal('incremento', 6, 4)->default(0.0200); // ajuste sobre venta
            $table->decimal('venta_mas', 10, 4)->nullable();       // venta + incremento

            // ── Control de proceso / reintentos ──────────────────────────────
            $table->enum('estado', ['pendiente', 'ok', 'error'])->default('pendiente');
            $table->tinyInteger('intentos')->default(0);        // reintentos realizados
            $table->text('error_mensaje')->nullable();          // detalle del último error

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cambios');
    }
};
