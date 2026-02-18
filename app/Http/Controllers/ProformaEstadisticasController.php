<?php
/**
 * CONTROLADOR: ProformaEstadisticasController.php
 * Ubicación: app/Http/Controllers/ProformaEstadisticasController.php
 *
 * Devuelve estadísticas en JSON para el modal de gráficas del index.
 * Acepta: fecha_desde, fecha_hasta (aplican sobre fecha_creacion)
 *
 * AGREGAR EN web.php (ANTES del Route::resource('proformas')):
 *   Route::get('proformas/estadisticas',
 *       [\App\Http\Controllers\ProformaEstadisticasController::class, '__invoke'])
 *       ->name('proformas.estadisticas');
 */
namespace App\Http\Controllers;

use App\Models\Proforma;
use Illuminate\Http\Request;

class ProformaEstadisticasController extends Controller
{
    public function __invoke(Request $request)
    {
        // ── Query base ───────────────────────────────────────────────────────
        $base = Proforma::query();

        if ($request->filled('fecha_desde')) {
            $base->where('fecha_creacion', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $base->where('fecha_creacion', '<=', $request->fecha_hasta);
        }

        // ── 1. KPIs generales ────────────────────────────────────────────────
        $kpis = (clone $base)->selectRaw('
            COUNT(*)   AS total_proformas,
            MAX(total) AS mayor_total,
            MIN(total) AS menor_total,
            AVG(total) AS promedio_total,
            SUM(total) AS suma_total
        ')->first();

        $mayorProforma = (clone $base)
            ->with('cliente:id,razon')
            ->orderByDesc('total')
            ->first(['id','cliente_id','total','moneda']);

        $menorProforma = (clone $base)
            ->with('cliente:id,razon')
            ->orderBy('total')
            ->first(['id','cliente_id','total','moneda']);

        // ── 2. Conteo por Estado ─────────────────────────────────────────────
        $porEstado = (clone $base)
            ->join('estados', 'proformas.estado_id', '=', 'estados.id')
            ->groupBy('estados.id', 'estados.name')
            ->selectRaw('estados.name AS estado, COUNT(*) AS conteo, SUM(proformas.total) AS suma')
            ->orderByDesc('conteo')
            ->get();

        $sinEstado = (clone $base)->whereNull('estado_id')->count();
        if ($sinEstado > 0) {
            $porEstado->push((object)['estado'=>'Sin estado','conteo'=>$sinEstado,'suma'=>0]);
        }

        // ── 3. Conteo por Temperatura ────────────────────────────────────────
        $porTemperatura = (clone $base)
            ->join('temperaturas', 'proformas.temperatura_id', '=', 'temperaturas.id')
            ->groupBy('temperaturas.id', 'temperaturas.name')
            ->selectRaw('temperaturas.name AS temperatura, COUNT(*) AS conteo')
            ->orderByDesc('conteo')
            ->get();

        $sinTemp = (clone $base)->whereNull('temperatura_id')->count();
        if ($sinTemp > 0) {
            $porTemperatura->push((object)['temperatura'=>'Sin temperatura','conteo'=>$sinTemp]);
        }

        // ── 4. Top 5 vendedores con cotizaciones "ganadas" ───────────────────
        $palabras = ['ganada','facturada','vendida','exito','éxito','exitosa'];
        $likes    = collect($palabras)
            ->map(fn($p) => "LOWER(estados.name) LIKE '%{$p}%'")
            ->implode(' OR ');

        $topVendedores = (clone $base)
            ->join('estados', 'proformas.estado_id', '=', 'estados.id')
            ->join('users',   'proformas.user_id',   '=', 'users.id')
            ->whereRaw("({$likes})")
            ->groupBy('users.id','users.name','users.codigo')
            ->selectRaw('
                users.id,
                users.name,
                users.codigo,
                COUNT(*)             AS cotizaciones_ganadas,
                SUM(proformas.total) AS monto_ganado
            ')
            ->orderByDesc('cotizaciones_ganadas')
            ->limit(5)
            ->get();

        // ── 5. Evolución mensual ─────────────────────────────────────────────
        $evolucion = (clone $base)
            ->selectRaw("
                DATE_FORMAT(fecha_creacion,'%Y-%m') AS mes,
                COUNT(*)    AS cantidad,
                SUM(total)  AS total_mes
            ")
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // ── Respuesta JSON ───────────────────────────────────────────────────
        return response()->json([
            'kpis' => [
                'total_proformas' => (int)   ($kpis->total_proformas ?? 0),
                'mayor_total'     => (float) ($kpis->mayor_total     ?? 0),
                'menor_total'     => (float) ($kpis->menor_total     ?? 0),
                'promedio_total'  => (float) ($kpis->promedio_total  ?? 0),
                'suma_total'      => (float) ($kpis->suma_total      ?? 0),
                'mayor_proforma'  => $mayorProforma ? [
                    'id'    => $mayorProforma->id,
                    'razon' => $mayorProforma->cliente->razon ?? '—',
                    'total' => (float) $mayorProforma->total,
                ] : null,
                'menor_proforma'  => $menorProforma ? [
                    'id'    => $menorProforma->id,
                    'razon' => $menorProforma->cliente->razon ?? '—',
                    'total' => (float) $menorProforma->total,
                ] : null,
            ],
            'por_estado'      => $porEstado,
            'por_temperatura' => $porTemperatura,
            'top_vendedores'  => $topVendedores,
            'evolucion'       => $evolucion,
        ]);
    }
}
