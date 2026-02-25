<?php

/**
 * CONTROLADOR: CambioController.php
 * Ubicación: app/Http/Controllers/CambioController.php
 *
 * Acciones disponibles:
 *   index            → lista paginada de registros (solo lectura)
 *   show             → detalle de un registro
 *   editIncremento   → formulario para editar solo el campo incremento
 *   updateIncremento → guarda el nuevo incremento y recalcula venta_mas
 *   consultarHoy     → dispara el comando manualmente desde la UI (admin)
 */

namespace App\Http\Controllers;

use App\Models\Cambio;
use App\Http\Requests\CambioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CambioController extends Controller
{
    /**
     * Listado paginado de tipos de cambio registrados.
     */
    public function index(Request $request)
    {
        $query = Cambio::query();

        if ($request->filled('fecha_desde')) {
            $query->where('fecha', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha', '<=', $request->fecha_hasta);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $cambios = $query->orderByDesc('fecha')->paginate(20)->withQueryString();
        $hoy     = Cambio::hoy();

        return view('cambios.index', compact('cambios', 'hoy'));
    }

    /**
     * Detalle de un registro.
     */
    public function show(Cambio $cambio)
    {
        return view('cambios.show', compact('cambio'));
    }

    /**
     * Formulario de edición del incremento (campo único editable).
     */
    public function editIncremento(Cambio $cambio)
    {
        return view('cambios.edit_incremento', compact('cambio'));
    }

    /**
     * Guarda el nuevo incremento y recalcula venta_mas.
     */
    public function updateIncremento(CambioRequest $request, Cambio $cambio)
    {
        $cambio->incremento = $request->validated()['incremento'];
        $cambio->recalcularVentaMas();

        return redirect()
            ->route('cambios.show', $cambio)
            ->with('success', 'Incremento actualizado. Venta Más recalculada a S/. ' . number_format($cambio->venta_mas, 4));
    }

    /**
     * Dispara el comando de consulta manualmente (solo Administrador).
     * Útil para forzar una reconsulta sin esperar al cron.
     */
    public function consultarHoy()
    {
        $exitCode = Artisan::call('cambio:consultar');

        $output = trim(Artisan::output());

        if ($exitCode === 0) {
            return redirect()
                ->route('cambios.index')
                ->with('success', 'Consulta ejecutada. ' . $output);
        }

        return redirect()
            ->route('cambios.index')
            ->with('error', 'La consulta finalizó con errores. ' . $output);
    }
}
