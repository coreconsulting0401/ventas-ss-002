<?php
// ══════════════════════════════════════════════════════════════════
// ARCHIVO: database/migrations/2026_02_26_000001_add_fecha_fin_update_count_to_proformas.php
//
// INSTRUCCIÓN: php artisan migrate
// ══════════════════════════════════════════════════════════════════

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Contador de modificaciones a fecha_fin por Vendedor ──────────
        Schema::table('proformas', function (Blueprint $table) {
            $table->unsignedSmallInteger('fecha_fin_update_count')
                  ->default(0)
                  ->after('fecha_fin')
                  ->comment('Veces que un Vendedor modificó fecha_fin en esta proforma');
        });

        // ── 2. Tabla estándar de notificaciones de Laravel ──────────────────
        //    Si ya ejecutaste: php artisan notifications:table → omitir este bloque
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::table('proformas', function (Blueprint $table) {
            $table->dropColumn('fecha_fin_update_count');
        });
        // No dropeamos 'notifications' para evitar borrar datos de otras notificaciones
    }
};
