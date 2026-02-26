<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proformas', function (Blueprint $table) {
            $table->id();
            $table->uuid('codigo')->unique();

            // --- Relaciones Principales ---
            $table->foreignId('cliente_id')
                  ->constrained('clientes')
                  ->onDelete('cascade');

            $table->foreignId('direccion_id')
                  ->nullable()
                  ->constrained('direccions') // Asegúrate que la tabla se llame 'direccions' o 'direcciones'
                  ->nullOnDelete();

            // ✅ Campo integrado desde la segunda migración
            $table->foreignId('contacto_id')
                  ->nullable()
                  ->constrained('contactos')
                  ->nullOnDelete();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // --- Relaciones de Parámetros ---
            $table->foreignId('transaccion_id')
                  ->nullable()
                  ->constrained('transaccions')
                  ->onDelete('set null');

            $table->foreignId('temperatura_id')
                  ->nullable()
                  ->constrained('temperaturas')
                  ->onDelete('set null');

            $table->foreignId('estado_id')
                  ->nullable()
                  ->constrained('estados')
                  ->onDelete('set null');

            // --- Datos de la Proforma ---
            $table->string('nota', 200)->nullable();
            $table->string('orden', 20)->nullable();
            $table->string('moneda', 20)->default('Soles');
            $table->decimal('sub_total', 10, 2);
            $table->decimal('monto_igv', 10, 2);
            $table->decimal('total', 10, 2);
            $table->date('fecha_creacion');
            $table->date('fecha_fin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proformas');
    }
};
