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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_e', 17)->unique()->nullable();
            $table->string('codigo_p', 17)->unique()->nullable();
            $table->string('nombre', 150);
            $table->string('marca', 50);
            $table->string('ubicacion', 20)->nullable();
            $table->decimal('precio_lista', 10, 3); // 7 enteros + 2 decimales
            $table->integer('stock');
            $table->foreignId('descuento_id')->nullable()->constrained('descuentos')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
