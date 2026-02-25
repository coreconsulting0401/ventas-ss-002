<?php
// database/migrations/2026_02_25_100057_create_empresas_table.php
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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social', 200);
            $table->string('ruc', 11)->nullable(); // Integrado aquí
            $table->string('direccion', 300)->nullable();
            $table->string('pagina_web', 200)->nullable();

            // Imágenes y documentos
            $table->string('uri_img_logo', 255)->nullable();
            $table->string('uri_img_publicidad', 255)->nullable();
            $table->string('uri_img_condiciones', 255)->nullable();
            $table->string('uri_cuentas_bancarias', 255)->nullable(); // Integrado aquí

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
