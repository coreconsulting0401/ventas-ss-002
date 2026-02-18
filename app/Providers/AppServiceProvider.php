<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use App\Models\Igv;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Solo intentamos compartir la variable si no estamos en consola
        // y si la tabla realmente existe en la base de datos
        if (!app()->runningInConsole() && Schema::hasTable('igvs')) {
            view()->share('igv_global', Igv::actual());
        } else {
            // Valor por defecto para evitar errores en vistas si la tabla no existe aún
            view()->share('igv_global', 18);
        }
    }
}
