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
        Schema::create('igvs', function (Blueprint $table) {
            $table->id();
            $table->decimal('porcentaje', 5, 2)->default(18.00); // Ejemplo: 18.00
            $table->boolean('activo')->default(true); // Para manejar versiones del impuesto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('igvs');
    }
};
