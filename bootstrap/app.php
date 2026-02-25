<?php

/**
 * ARCHIVO: bootstrap/app.php
 *
 * Añade ->withSchedule() para programar cambio:consultar
 * a las 00:00, 00:30 y 06:00 todos los días.
 *
 * ⚠️  REQUISITO: Añadir este cron en el servidor (una sola línea):
 *
 *   * * * * * cd /ruta-del-proyecto && php artisan schedule:run >> /dev/null 2>&1
 *
 * Para probar en local:
 *   php artisan schedule:work
 */

use App\Models\Cambio;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web:      __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health:   '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {

        // ── 00:00  Consulta principal diaria ─────────────────────────────────
        $schedule->command('cambio:consultar')
                 ->dailyAt('00:00')
                 ->name('cambio-medianoche')
                 ->withoutOverlapping()
                 ->runInBackground();

        // ── 00:30  Primer reintento automático (solo si aún no está ok) ──────
        $schedule->command('cambio:consultar')
                 ->dailyAt('00:30')
                 ->name('cambio-reintento-0030')
                 ->withoutOverlapping()
                 ->runInBackground()
                 ->when(function (): bool {
                     $hoy = Cambio::hoy();
                     return $hoy === null || $hoy->estado !== 'ok';
                 });

        // ── 06:00  Segundo reintento automático (último del día) ─────────────
        $schedule->command('cambio:consultar')
                 ->dailyAt('06:00')
                 ->name('cambio-reintento-0600')
                 ->withoutOverlapping()
                 ->runInBackground()
                 ->when(function (): bool {
                     $hoy = Cambio::hoy();
                     return $hoy === null || $hoy->estado !== 'ok';
                 });
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
