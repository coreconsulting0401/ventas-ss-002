<?php

/**
 * ARCHIVO: routes/web.php
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CreditoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DireccionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\DescuentoController;
use App\Http\Controllers\ProformaController;
use App\Http\Controllers\ProformaPDFController;
use App\Http\Controllers\TransaccionController;
use App\Http\Controllers\TemperaturaController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\VirtualController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProformaEstadisticasController;
use App\Http\Controllers\ApiExternaController;
use App\Http\Controllers\UbigeoController;
use App\Http\Controllers\ClienteBusquedaController;
use App\Http\Controllers\ClienteDireccionesController;
use App\Http\Controllers\CambioController;
use App\Http\Controllers\ProductoImportController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Ubigeo ───────────────────────────────────────────────────────────────
    Route::prefix('ubigeo')->name('ubigeo.')->group(function () {
        Route::get('provincias/{departamento_id}', [UbigeoController::class, 'provincias'])->name('provincias');
        Route::get('distritos/{provincia_id}',     [UbigeoController::class, 'distritos'])->name('distritos');
    });

    // ── Contactos ────────────────────────────────────────────────────────────
    Route::get('contactos/buscar-dni/{dni}', [ContactoController::class, 'buscarPorDni'])
         ->name('contactos.buscar-dni');

    // ── Clientes ─────────────────────────────────────────────────────────────
    Route::get('clientes/verificar-ruc/{ruc}', [ClienteController::class, 'verificarRuc'])
         ->name('clientes.verificar-ruc');

    // ── API Externa ──────────────────────────────────────────────────────────
    Route::prefix('api-externa')->group(function () {
        Route::get('consultar-ruc/{ruc}', [ApiExternaController::class, 'consultarRuc'])->name('api.consultar-ruc');
        Route::get('consultar-dni/{dni}', [ApiExternaController::class, 'consultarDni'])->name('api.consultar-dni');
    });

    // ── Proformas ─────────────────────────────────────────────────────────────
    Route::get('proformas/{proforma}/pdf',         [ProformaPDFController::class, 'generarPDF'])->name('proformas.pdf');
    Route::get('proformas/{proforma}/pdf/preview', [ProformaPDFController::class, 'previsualizarPDF'])->name('proformas.pdf.preview');
    Route::get('proformas/estadisticas',           [ProformaEstadisticasController::class, '__invoke'])->name('proformas.estadisticas');

    // ── API Interna ───────────────────────────────────────────────────────────
    Route::prefix('api')->group(function () {
        Route::get('clientes/buscar',               [ClienteBusquedaController::class, 'buscar'])->name('api.clientes.buscar');
        Route::get('clientes/{id}',                 [ClienteBusquedaController::class, 'obtener'])->name('api.clientes.obtener');
        Route::get('clientes/{cliente}/direcciones', ClienteDireccionesController::class)->name('api.clientes.direcciones');
    });

    // ── Tipo de Cambio ────────────────────────────────────────────────────────
    Route::prefix('cambios')->name('cambios.')->group(function () {
        Route::get('/',                              [CambioController::class, 'index'])->name('index');
        Route::get('/{cambio}',                      [CambioController::class, 'show'])->name('show');
        Route::get('/{cambio}/incremento',           [CambioController::class, 'editIncremento'])->name('edit-incremento');
        Route::patch('/{cambio}/incremento',         [CambioController::class, 'updateIncremento'])->name('update-incremento');
        Route::post('/consultar-hoy',                [CambioController::class, 'consultarHoy'])
             ->name('consultar-hoy')
             ->middleware('role:Administrador');
    });

    // ── Importación de Productos ─────────────────────────────────────────────
    // Rutas de importación masiva Excel
    Route::get('/productos/import/template', [ProductoImportController::class, 'downloadTemplate'])
        ->name('productos.import.template');

    Route::post('/productos/import', [ProductoImportController::class, 'import'])
        ->name('productos.import');

    // ── Resources ─────────────────────────────────────────────────────────────
    Route::resource('contactos',     ContactoController::class);
    Route::resource('creditos',      CreditoController::class);
    Route::resource('categorias',    CategoriaController::class);
    Route::resource('direcciones',   DireccionController::class);
    Route::resource('productos',     ProductoController::class);
    Route::resource('descuentos',    DescuentoController::class);
    Route::resource('proformas',     ProformaController::class);
    Route::resource('clientes',      ClienteController::class);
    Route::resource('transacciones', TransaccionController::class)->parameters(['transacciones' => 'transaccion']);
    Route::resource('temperaturas',  TemperaturaController::class);
    Route::resource('estados',       EstadoController::class);
    Route::resource('virtuals',      VirtualController::class);
    Route::resource('proveedores',   ProveedorController::class);

    Route::middleware(['role:Administrador'])->group(function () {
        Route::resource('users', \App\Http\Controllers\UserController::class);
        Route::resource('roles', \App\Http\Controllers\RoleController::class);
    });

    // Múltiples roles
    //Route::middleware(['role:Administrador|Vendedor'])->group(function () {
        // ...
    //});

});

require __DIR__ . '/auth.php';
