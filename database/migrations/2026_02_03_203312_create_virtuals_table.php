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
        Schema::create('virtuals', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('precio_compra', 12)->unique();
            $table->decimal('precio_venta', 10, 3);
            $table->string('marca', 50);
            $table->integer('stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtuals');
    }
};
