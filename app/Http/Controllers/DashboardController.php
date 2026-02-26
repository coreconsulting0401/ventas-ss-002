<?php

/**
 * CONTROLADOR: DashboardController.php
 * Ubicación: app/Http/Controllers/DashboardController.php
 *
 * Provee todos los datos necesarios para el dashboard principal.
 * Agregar en web.php:
 *   use App\Http\Controllers\DashboardController;
 *   Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 */

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Proforma;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // ── KPIs GLOBALES ─────────────────────────────────────────────────────
        $totalClientes   = Cliente::count();
        $totalProformas  = Proforma::count();
        $totalProductos  = Producto::count();
        $totalVendedores = User::count();

        // ── TOTALES MONETARIOS (mes actual) ───────────────────────────────────
        $mesActual   = now()->format('Y-m');
        $iniciMes    = now()->startOfMonth()->toDateString();
        $finMes      = now()->endOfMonth()->toDateString();

        $ventasMesActual = Proforma::join('estados', 'proformas.estado_id', '=', 'estados.id')
            ->where('estados.name', 'Ganada')
            ->whereBetween('proformas.fecha_creacion', [$iniciMes, $finMes])
            ->sum('proformas.total');

        $proformasMesActual = Proforma::whereBetween('fecha_creacion', [$iniciMes, $finMes])->count();

        // ── PROFORMAS POR ESTADO (para doughnut) ──────────────────────────────
        $proformasPorEstado = Proforma::join('estados', 'proformas.estado_id', '=', 'estados.id')
            ->groupBy('estados.id', 'estados.name')
            ->selectRaw('estados.name as estado, COUNT(*) as total, SUM(proformas.total) as suma')
            ->orderByDesc('total')
            ->get();

        // ── EVOLUCIÓN MENSUAL últimos 12 meses (line chart) ───────────────────
        $evolucion12m = Proforma::selectRaw("
                DATE_FORMAT(fecha_creacion, '%Y-%m') AS mes,
                COUNT(*) AS cantidad,
                SUM(total) AS monto
            ")
            ->where('fecha_creacion', '>=', now()->subMonths(11)->startOfMonth()->toDateString())
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // ── TOP 8 CLIENTES por monto total ganado ─────────────────────────────
        $topClientes = DB::table('proformas')
            ->join('estados',  'proformas.estado_id',  '=', 'estados.id')
            ->join('clientes', 'proformas.cliente_id', '=', 'clientes.id')
            ->where('estados.name', 'Ganada')
            ->groupBy('clientes.id', 'clientes.razon')
            ->selectRaw('clientes.razon, SUM(proformas.total) as monto, COUNT(*) as cant')
            ->orderByDesc('monto')
            ->limit(8)
            ->get();

        // ── TOP 5 VENDEDORES por proformas ganadas ────────────────────────────
        $topVendedores = DB::table('proformas')
            ->join('estados', 'proformas.estado_id', '=', 'estados.id')
            ->join('users',   'proformas.user_id',   '=', 'users.id')
            ->where('estados.name', 'Ganada')
            ->groupBy('users.id', 'users.name', 'users.codigo')
            ->selectRaw('users.name, users.codigo, COUNT(*) as ganadas, SUM(proformas.total) as monto')
            ->orderByDesc('ganadas')
            ->limit(5)
            ->get();

        // ── PROFORMAS DEL VENDEDOR ACTUAL (este mes) ──────────────────────────
        $misProformasMes = Proforma::where('user_id', $userId)
            ->whereBetween('fecha_creacion', [$iniciMes, $finMes])
            ->count();

        $misMontosGanados = Proforma::join('estados', 'proformas.estado_id', '=', 'estados.id')
            ->where('proformas.user_id', $userId)
            ->where('estados.name', 'Ganada')
            ->whereBetween('proformas.fecha_creacion', [$iniciMes, $finMes])
            ->sum('proformas.total');

        // ── PRODUCTOS CON BAJO STOCK (stock <= 5) ────────────────────────────
        $productosBajoStock = Producto::where('stock', '<=', 5)
            ->orderBy('stock')
            ->limit(6)
            ->get(['id', 'nombre', 'marca', 'codigo_p', 'codigo_e', 'stock', 'precio_lista']);

        // ── CLIENTES NUEVOS últimos 30 días ───────────────────────────────────
        $clientesNuevos30d = Cliente::where('created_at', '>=', now()->subDays(30))->count();

        // ── ÚLTIMAS 8 PROFORMAS ───────────────────────────────────────────────
        $ultimasProformas = Proforma::with(['cliente:id,razon', 'user:id,name', 'estado:id,name'])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get(['id', 'codigo', 'cliente_id', 'user_id', 'estado_id', 'total', 'moneda', 'fecha_creacion', 'created_at']);

        // ── PROFORMAS POR MONEDA ──────────────────────────────────────────────
        $proformasPorMoneda = Proforma::groupBy('moneda')
            ->selectRaw('moneda, COUNT(*) as total, SUM(total) as monto')
            ->get();

        // ── TASA CONVERSIÓN (Cotizadas → Ganadas) ────────────────────────────
        $totalCotizadas = Proforma::join('estados', 'proformas.estado_id', '=', 'estados.id')
            ->where('estados.name', 'Cotizado')->count();
        $totalGanadas = Proforma::join('estados', 'proformas.estado_id', '=', 'estados.id')
            ->where('estados.name', 'Ganada')->count();
        $tasaConversion = $totalCotizadas > 0
            ? round(($totalGanadas / ($totalCotizadas + $totalGanadas)) * 100, 1)
            : 0;

        return view('dashboard', compact(
            'totalClientes',
            'totalProformas',
            'totalProductos',
            'totalVendedores',
            'ventasMesActual',
            'proformasMesActual',
            'proformasPorEstado',
            'evolucion12m',
            'topClientes',
            'topVendedores',
            'misProformasMes',
            'misMontosGanados',
            'productosBajoStock',
            'clientesNuevos30d',
            'ultimasProformas',
            'proformasPorMoneda',
            'tasaConversion',
        ));
    }
}
