@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
<style>
/* ══════════════════════════════════════════════════════════════
   DASHBOARD — Sistema de Gestión de Proformas
   Paleta: azul índigo profundo + slate + acentos ámbar/esmeralda
   Tipografía: DM Sans (display) + sistema
══════════════════════════════════════════════════════════════ */
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap');

:root {
    --dash-bg:        #f1f5f9;
    --dash-surface:   #ffffff;
    --dash-border:    #e2e8f0;
    --dash-text:      #0f172a;
    --dash-muted:     #64748b;
    --dash-accent:    #3b82f6;
    --dash-accent2:   #6366f1;
    --dash-success:   #10b981;
    --dash-warning:   #f59e0b;
    --dash-danger:    #ef4444;
    --dash-info:      #06b6d4;
    --dash-radius:    12px;
    --dash-shadow:    0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.06);
    --dash-shadow-lg: 0 4px 6px rgba(0,0,0,.07), 0 10px 40px rgba(0,0,0,.10);
}

body { background: var(--dash-bg); font-family: 'DM Sans', sans-serif; }

/* ── Layout ── */
.dash-wrap { padding: 1.5rem 1.75rem; }

/* ── Header greeting ── */
.dash-header {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 60%, #6366f1 100%);
    border-radius: var(--dash-radius);
    padding: 1.6rem 2rem;
    color: #fff;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}
.dash-header::before {
    content: '';
    position: absolute;
    right: -40px; top: -60px;
    width: 240px; height: 240px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
}
.dash-header::after {
    content: '';
    position: absolute;
    right: 60px; bottom: -80px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,.04);
}
.dash-header .badge-date {
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    color: #fff;
    font-size: 0.72rem;
    font-weight: 500;
    padding: 4px 10px;
    border-radius: 20px;
    backdrop-filter: blur(4px);
}
.dash-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0; }
.dash-header p  { opacity: .8; margin: 0; font-size: .88rem; }
.dash-header .highlight { color: #fbbf24; font-weight: 600; }

/* ── KPI Cards ── */
.kpi-card {
    background: var(--dash-surface);
    border-radius: var(--dash-radius);
    box-shadow: var(--dash-shadow);
    padding: 1.25rem 1.4rem;
    border: 1px solid var(--dash-border);
    transition: transform .2s, box-shadow .2s;
    height: 100%;
}
.kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--dash-shadow-lg);
}
.kpi-icon {
    width: 46px; height: 46px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}
.kpi-label { font-size: .73rem; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: var(--dash-muted); margin-bottom: 2px; }
.kpi-value { font-size: 1.75rem; font-weight: 700; color: var(--dash-text); line-height: 1.1; }
.kpi-sub   { font-size: .75rem; color: var(--dash-muted); margin-top: 3px; }
.kpi-badge { font-size: .7rem; padding: 2px 8px; border-radius: 20px; font-weight: 600; }

/* colores de íconos */
.icon-blue   { background: #eff6ff; color: #2563eb; }
.icon-green  { background: #f0fdf4; color: #16a34a; }
.icon-amber  { background: #fffbeb; color: #d97706; }
.icon-violet { background: #f5f3ff; color: #7c3aed; }
.icon-cyan   { background: #ecfeff; color: #0891b2; }
.icon-rose   { background: #fff1f2; color: #e11d48; }

/* ── Card general ── */
.dash-card {
    background: var(--dash-surface);
    border-radius: var(--dash-radius);
    box-shadow: var(--dash-shadow);
    border: 1px solid var(--dash-border);
    overflow: hidden;
}
.dash-card-header {
    padding: .85rem 1.25rem;
    border-bottom: 1px solid var(--dash-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--dash-surface);
}
.dash-card-title {
    font-size: .82rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--dash-text);
    margin: 0;
}
.dash-card-body { padding: 1.25rem; }

/* ── Chart containers ── */
.chart-wrap { position: relative; }
.chart-wrap canvas { display: block; }

/* ── Tabla últimas proformas ── */
.dash-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
.dash-table th {
    padding: .55rem .85rem;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: var(--dash-muted);
    border-bottom: 1px solid var(--dash-border);
    white-space: nowrap;
}
.dash-table td {
    padding: .65rem .85rem;
    border-bottom: 1px solid #f1f5f9;
    color: var(--dash-text);
    vertical-align: middle;
}
.dash-table tbody tr:hover { background: #f8fafc; }
.dash-table tbody tr:last-child td { border-bottom: none; }

/* monospace para código */
.code-chip {
    font-family: 'DM Mono', monospace;
    font-size: .72rem;
    background: #f1f5f9;
    color: #475569;
    padding: 2px 7px;
    border-radius: 5px;
}

/* ── Estado badges ── */
.estado-badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 600;
    white-space: nowrap;
}
.estado-ganada    { background: #d1fae5; color: #065f46; }
.estado-cotizado  { background: #dbeafe; color: #1e40af; }
.estado-perdida   { background: #ffe4e6; color: #9f1239; }
.estado-revision  { background: #fef3c7; color: #92400e; }
.estado-default   { background: #f1f5f9; color: #475569; }

/* ── Top vendedores / clientes list ── */
.rank-list { list-style: none; padding: 0; margin: 0; }
.rank-item {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .6rem 0;
    border-bottom: 1px solid #f1f5f9;
}
.rank-item:last-child { border-bottom: none; }
.rank-num {
    width: 24px; height: 24px;
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: .7rem;
    font-weight: 700;
    flex-shrink: 0;
}
.rank-1 { background: #fef3c7; color: #92400e; }
.rank-2 { background: #f1f5f9; color: #374151; }
.rank-3 { background: #fde8d8; color: #9a3412; }
.rank-n { background: #f1f5f9; color: #64748b; }
.rank-name { flex: 1; font-size: .81rem; font-weight: 500; color: var(--dash-text); }
.rank-sub  { font-size: .7rem; color: var(--dash-muted); }
.rank-val  { font-size: .82rem; font-weight: 700; color: var(--dash-text); white-space: nowrap; }

/* ── Bajo stock ── */
.stock-item {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .55rem 0;
    border-bottom: 1px solid #f1f5f9;
}
.stock-item:last-child { border-bottom: none; }
.stock-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}
.stock-0  { background: #ef4444; }
.stock-lo { background: #f59e0b; }
.stock-ok { background: #10b981; }
.stock-name { flex: 1; font-size: .79rem; font-weight: 500; }
.stock-badge {
    font-size: .7rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
}

/* ── Tasa conversión ring ── */
.conv-ring {
    width: 90px; height: 90px;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: conic-gradient(#10b981 0% var(--pct), #e2e8f0 var(--pct) 100%);
    position: relative;
}
.conv-ring::before {
    content: '';
    position: absolute;
    inset: 12px;
    background: white;
    border-radius: 50%;
}
.conv-ring .conv-val {
    position: relative;
    z-index: 1;
    font-size: 1rem;
    font-weight: 700;
    color: var(--dash-text);
    line-height: 1;
}
.conv-ring .conv-lbl {
    position: relative;
    z-index: 1;
    font-size: .55rem;
    color: var(--dash-muted);
    font-weight: 500;
}

/* ── Progress bars ── */
.prog-bar {
    height: 5px;
    background: #e2e8f0;
    border-radius: 3px;
    overflow: hidden;
    margin-top: 4px;
}
.prog-fill {
    height: 100%;
    border-radius: 3px;
    transition: width .8s ease;
}

/* ── Responsive tweaks ── */
@media (max-width: 768px) {
    .dash-wrap { padding: 1rem; }
    .kpi-value { font-size: 1.4rem; }
}
</style>
@endpush

@section('content')
@php
    use Carbon\Carbon;
    $hoy = Carbon::now('America/Lima');

    /* Helpers de formato */
    $fmtMonto = fn($v, $dec=0) => number_format($v, $dec, '.', ',');

    /* Colores para estados en charts */
    $estadoColores = [
        'Ganada'   => '#10b981',
        'Cotizado' => '#3b82f6',
        'Perdida'  => '#ef4444',
        'En Revisión' => '#f59e0b',
        'Sin estado'  => '#94a3b8',
    ];

    /* Función helper de badge de estado */
    $estadoClass = fn($estado) => match(true) {
        str_contains(strtolower($estado ?? ''), 'ganada')   => 'estado-ganada',
        str_contains(strtolower($estado ?? ''), 'cotiz')    => 'estado-cotizado',
        str_contains(strtolower($estado ?? ''), 'perdida')  => 'estado-perdida',
        str_contains(strtolower($estado ?? ''), 'revisi')   => 'estado-revision',
        default => 'estado-default',
    };

    /* Calcular max para barras de top clientes */
    $maxMontoCliente = $topClientes->max('monto') ?: 1;

    /* Evolución: llenar 12 meses aunque no haya datos */
    $meses12 = collect();
    for ($i = 11; $i >= 0; $i--) {
        $key = Carbon::now()->subMonths($i)->format('Y-m');
        $found = $evolucion12m->firstWhere('mes', $key);
        $meses12->push([
            'mes'      => Carbon::now()->subMonths($i)->locale('es')->isoFormat('MMM'),
            'cantidad' => $found ? (int)$found->cantidad : 0,
            'monto'    => $found ? (float)$found->monto  : 0,
        ]);
    }

    /* Suma total ganada (sin filtro mes) */
    $sumaGanada = $proformasPorEstado->firstWhere('estado', 'Ganada')?->suma ?? 0;
@endphp

<div class="dash-wrap">

    {{-- ══════════════════════════════════════════════════
         HEADER GREETING
    ══════════════════════════════════════════════════ --}}
    <div class="dash-header mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <span class="badge-date mb-2 d-inline-block">
                    <i class="bi bi-calendar3 me-1"></i>{{ $hoy->locale('es')->isoFormat('dddd, D [de] MMMM YYYY') }}
                </span>
                <h1>Bienvenido, <span class="highlight">{{ Auth::user()->name }}</span> 👋</h1>
                <p>Panel de control — Sistema de Gestión de Proformas</p>
            </div>
            <div class="text-end d-none d-md-block">
                <div style="font-size:2.5rem; line-height:1; opacity:.25;">📊</div>
            </div>
        </div>

        {{-- Mini stats en header --}}
        <div class="row g-2 mt-2">
            @haspermission('view proformas')
            <div class="col-auto">
                <div style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:8px;padding:6px 14px;">
                    <div style="font-size:.65rem;opacity:.7;text-transform:uppercase;letter-spacing:.06em;">Mis proformas este mes</div>
                    <div style="font-size:1.1rem;font-weight:700;">{{ $misProformasMes }}</div>
                </div>
            </div>
            @endhaspermission
            @hasanyrole('Administrador|Gerente|Vendedor')
            <div class="col-auto">
                <div style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:8px;padding:6px 14px;">
                    <div style="font-size:.65rem;opacity:.7;text-transform:uppercase;letter-spacing:.06em;">Mis ventas ganadas</div>
                    <div style="font-size:1.1rem;font-weight:700;">S/. {{ $fmtMonto($misMontosGanados, 0) }}</div>
                </div>
            </div>

            <div class="col-auto">
                <div style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:8px;padding:6px 14px;">
                    <div style="font-size:.65rem;opacity:.7;text-transform:uppercase;letter-spacing:.06em;">Clientes nuevos (30d)</div>
                    <div style="font-size:1.1rem;font-weight:700;">{{ $clientesNuevos30d }}</div>
                </div>
            </div>
            @endhasanyrole
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════
         FILA 1 – KPI CARDS (6 tarjetas)
    ══════════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-2 col-lg-4 col-md-4 col-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="kpi-icon icon-blue"><i class="bi bi-file-earmark-text"></i></div>
                    <span class="kpi-badge bg-primary bg-opacity-10 text-primary">Total</span>
                </div>
                <div class="kpi-label">Proformas</div>
                <div class="kpi-value">{{ number_format($totalProformas) }}</div>
                <div class="kpi-sub"><i class="bi bi-arrow-up-short text-success"></i>{{ $proformasMesActual }} este mes</div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-4 col-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="kpi-icon icon-green"><i class="bi bi-people"></i></div>
                    <span class="kpi-badge bg-success bg-opacity-10 text-success">Activos</span>
                </div>
                <div class="kpi-label">Clientes</div>
                <div class="kpi-value">{{ number_format($totalClientes) }}</div>
                <div class="kpi-sub"><i class="bi bi-person-plus-fill text-success"></i> +{{ $clientesNuevos30d }} últimos 30d</div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-4 col-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="kpi-icon icon-amber"><i class="bi bi-box-seam"></i></div>
                    <span class="kpi-badge bg-warning bg-opacity-10 text-warning">Catálogo</span>
                </div>
                <div class="kpi-label">Productos</div>
                <div class="kpi-value">{{ number_format($totalProductos) }}</div>
                <div class="kpi-sub">
                    @if($productosBajoStock->count() > 0)
                        <span class="text-danger"><i class="bi bi-exclamation-circle"></i> {{ $productosBajoStock->count() }} bajo stock</span>
                    @else
                        <span class="text-success"><i class="bi bi-check-circle"></i> Stock OK</span>
                    @endif
                </div>
            </div>
        </div>

        @hasanyrole('Administador|Gerente')
        <div class="col-xl-2 col-lg-4 col-md-4 col-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="kpi-icon icon-violet"><i class="bi bi-currency-dollar"></i></div>
                    <span class="kpi-badge bg-primary bg-opacity-10 text-primary">Mes</span>
                </div>
                <div class="kpi-label">Ventas Mes</div>
                <div class="kpi-value" style="font-size:1.3rem;">S/. {{ $fmtMonto($ventasMesActual, 0) }}</div>
                <div class="kpi-sub">Proformas ganadas</div>
            </div>
        </div>


        <div class="col-xl-2 col-lg-4 col-md-4 col-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="kpi-icon icon-cyan"><i class="bi bi-graph-up-arrow"></i></div>
                    <span class="kpi-badge bg-info bg-opacity-10 text-info">Global</span>
                </div>
                <div class="kpi-label">Total Ganado</div>
                <div class="kpi-value" style="font-size:1.2rem;">S/. {{ $fmtMonto($sumaGanada, 0) }}</div>
                <div class="kpi-sub">Histórico acumulado</div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-4 col-md-4 col-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="kpi-icon icon-rose"><i class="bi bi-bullseye"></i></div>
                    <span class="kpi-badge bg-danger bg-opacity-10 text-danger">Ratio</span>
                </div>
                <div class="kpi-label">Tasa Conversión</div>
                <div class="kpi-value">{{ $tasaConversion }}%</div>
                <div class="kpi-sub">Cotizadas → Ganadas</div>
            </div>
        </div>
        @endhasanyrole

    </div>

    {{-- ══════════════════════════════════════════════════
         FILA 2 – Evolución mensual (line) + Estados (doughnut)
    ══════════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">

        {{-- Evolución 12 meses --}}
        <div class="col-xl-8">
            <div class="dash-card h-100">
                <div class="dash-card-header">
                    <h6 class="dash-card-title">📈 Evolución de Proformas — Últimos 12 meses</h6>
                    <div class="d-flex gap-2">
                        <span style="font-size:.72rem;color:var(--dash-muted);">Cantidad <span style="display:inline-block;width:14px;height:3px;background:#3b82f6;border-radius:2px;vertical-align:middle;"></span></span>
                        <span style="font-size:.72rem;color:var(--dash-muted);">Monto <span style="display:inline-block;width:14px;height:3px;background:#10b981;border-radius:2px;vertical-align:middle;"></span></span>
                    </div>
                </div>
                <div class="dash-card-body">
                    <div class="chart-wrap" style="height:260px;">
                        <canvas id="chartEvolucion"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Estados doughnut --}}
        <div class="col-xl-4">
            <div class="dash-card h-100">
                <div class="dash-card-header">
                    <h6 class="dash-card-title">🗂 Proformas por Estado</h6>
                </div>
                <div class="dash-card-body">
                    <div class="chart-wrap d-flex justify-content-center" style="height:160px;">
                        <canvas id="chartEstados"></canvas>
                    </div>
                    <ul class="rank-list mt-2">
                        @foreach($proformasPorEstado as $pe)
                        <li class="rank-item">
                            <span class="estado-badge {{ $estadoClass($pe->estado) }}">{{ $pe->estado }}</span>
                            <span class="rank-name" style="font-size:.75rem;">{{ $pe->total }} proformas</span>
                            <span class="rank-val" style="font-size:.75rem;">S/. {{ $fmtMonto($pe->suma, 0) }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════
         FILA 3 – Top Clientes (bar) + Top Vendedores + Bajo Stock
    ══════════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">

        @hasanyrole('Administador|Gerente')
        {{-- Top Clientes bar chart + lista --}}
        <div class="col-xl-5">
            <div class="dash-card h-100">
                <div class="dash-card-header">
                    <h6 class="dash-card-title">🏆 Top Clientes por Monto Ganado</h6>
                    <span style="font-size:.7rem;color:var(--dash-muted);">Histórico · Proformas Ganadas</span>
                </div>
                <div class="dash-card-body">
                    @if($topClientes->count() > 0)
                        <div class="chart-wrap mb-3" style="height:200px;">
                            <canvas id="chartTopClientes"></canvas>
                        </div>
                        <ul class="rank-list">
                            @foreach($topClientes->take(5) as $i => $cl)
                            <li class="rank-item">
                                <span class="rank-num {{ $i === 0 ? 'rank-1' : ($i === 1 ? 'rank-2' : ($i === 2 ? 'rank-3' : 'rank-n')) }}">
                                    {{ $i + 1 }}
                                </span>
                                <div class="flex-fill">
                                    <div class="rank-name">{{ Str::limit($cl->razon, 28) }}</div>
                                    <div class="prog-bar">
                                        <div class="prog-fill" style="width:{{ round(($cl->monto/$maxMontoCliente)*100) }}%;background:linear-gradient(90deg,#3b82f6,#6366f1);"></div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="rank-val">S/. {{ $fmtMonto($cl->monto, 0) }}</div>
                                    <div class="rank-sub">{{ $cl->cant }} prf.</div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-4 text-muted" style="font-size:.82rem;">Sin datos de proformas ganadas aún.</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Top Vendedores --}}
        <div class="col-xl-3">
            <div class="dash-card h-100">
                <div class="dash-card-header">
                    <h6 class="dash-card-title">🥇 Top Vendedores</h6>
                    <span style="font-size:.7rem;color:var(--dash-muted);">Prf. Ganadas</span>
                </div>
                <div class="dash-card-body">
                    @if($topVendedores->count() > 0)
                        <ul class="rank-list">
                            @foreach($topVendedores as $i => $v)
                            <li class="rank-item">
                                <span class="rank-num {{ $i === 0 ? 'rank-1' : ($i === 1 ? 'rank-2' : ($i === 2 ? 'rank-3' : 'rank-n')) }}">
                                    {{ $i + 1 }}
                                </span>
                                <div class="flex-fill">
                                    <div class="rank-name">{{ Str::limit($v->name, 20) }}</div>
                                    <div class="rank-sub">{{ $v->codigo ?? '—' }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="rank-val">{{ $v->ganadas }}</div>
                                    <div class="rank-sub">S/. {{ $fmtMonto($v->monto, 0) }}</div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        {{-- Mini doughnut vendedores --}}
                        <div class="chart-wrap d-flex justify-content-center mt-2" style="height:120px;">
                            <canvas id="chartVendedores"></canvas>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted" style="font-size:.82rem;">Sin datos aún.</div>
                    @endif
                </div>
            </div>
        </div>
        @endhasanyrole
        {{-- Bajo Stock + Moneda --}}
        <div class="col-xl-4">
            <div class="dash-card mb-3">
                <div class="dash-card-header">
                    <h6 class="dash-card-title">⚠️ Productos con Bajo Stock</h6>
                    <a href="{{ route('productos.index') }}" style="font-size:.72rem;color:var(--dash-accent);text-decoration:none;">Ver todos →</a>
                </div>
                <div class="dash-card-body" style="padding:.75rem 1.25rem;">
                    @forelse($productosBajoStock as $prod)
                    <div class="stock-item">
                        <span class="stock-dot {{ $prod->stock == 0 ? 'stock-0' : 'stock-lo' }}"></span>
                        <div class="flex-fill">
                            <div class="stock-name">{{ Str::limit($prod->nombre, 26) }}</div>
                            <div style="font-size:.68rem;color:var(--dash-muted);">{{ $prod->marca }} · {{ $prod->codigo_p ?? $prod->codigo_e }}</div>
                        </div>
                        <span class="stock-badge {{ $prod->stock == 0 ? 'bg-danger text-white' : 'bg-warning text-dark' }}">
                            {{ $prod->stock }} uds
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-3" style="font-size:.82rem;color:var(--dash-muted);">
                        <i class="bi bi-check-circle-fill text-success"></i> Todo el stock está bien
                    </div>
                    @endforelse
                </div>
            </div>
            @hasanyrole('Administador|Gerente')
            {{-- Moneda split --}}
            <div class="dash-card">
                <div class="dash-card-header">
                    <h6 class="dash-card-title">💱 Proformas por Moneda</h6>
                </div>
                <div class="dash-card-body d-flex gap-3 justify-content-center align-items-center flex-wrap">
                    @foreach($proformasPorMoneda as $pm)
                    <div class="text-center" style="min-width:80px;">
                        <div style="font-size:1.5rem;font-weight:700;color:{{ $pm->moneda === 'Dolares' ? '#16a34a' : '#2563eb' }};">
                            {{ $pm->total }}
                        </div>
                        <div style="font-size:.72rem;color:var(--dash-muted);font-weight:600;">{{ $pm->moneda }}</div>
                        <div style="font-size:.68rem;color:var(--dash-muted);">{{ $pm->moneda === 'Dolares' ? '$' : 'S/.' }} {{ $fmtMonto($pm->monto, 0) }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endhasanyrole
        </div>

    </div>

    @haspermission('view proformas')
    {{-- ══════════════════════════════════════════════════
         FILA 4 – Últimas proformas
    ══════════════════════════════════════════════════ --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h6 class="dash-card-title">🕐 Últimas Proformas Creadas</h6>
                    <a href="{{ route('proformas.index') }}" style="font-size:.78rem;color:var(--dash-accent);text-decoration:none;font-weight:600;">
                        Ver todas <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div style="overflow-x:auto;">
                    <table class="dash-table">
                        <thead>
                            <tr>
                                <th>NTC</th>
                                <th>Cliente</th>
                                <th>Vendedor</th>
                                <th>Fecha Emisión</th>
                                <th>Moneda</th>
                                <th class="text-end">Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ultimasProformas as $pf)
                            <tr>
                                <td>
                                    <span class="code-chip">NTC-{{ str_pad($pf->id, 8, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td style="max-width:180px;">
                                    <div style="font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $pf->cliente->razon ?? '—' }}
                                    </div>
                                </td>
                                <td>{{ $pf->user->name ?? '—' }}</td>
                                <td style="white-space:nowrap;">{{ $pf->fecha_creacion->format('d/m/Y') }}</td>
                                <td>
                                    <span style="font-size:.72rem;font-weight:600;color:{{ $pf->moneda === 'Dolares' ? '#16a34a' : '#2563eb' }};">
                                        {{ $pf->moneda === 'Dolares' ? 'USD' : 'PEN' }}
                                    </span>
                                </td>
                                <td class="text-end" style="font-family:'DM Mono',monospace;font-size:.8rem;font-weight:600;">
                                    {{ $pf->moneda === 'Dolares' ? '$' : 'S/.' }} {{ $fmtMonto($pf->total, 2) }}
                                </td>
                                <td>
                                    <span class="estado-badge {{ $estadoClass($pf->estado->name ?? '') }}">
                                        {{ $pf->estado->name ?? 'Sin estado' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('proformas.show', $pf->id) }}"
                                       class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:.75rem;">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('proformas.pdf.preview', $pf->id) }}"
                                       class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size:.75rem;" target="_blank">
                                        <i class="bi bi-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5" style="color:var(--dash-muted);font-size:.85rem;">
                                    <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>
                                    No hay proformas registradas aún.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endhaspermission

</div><!-- /dash-wrap -->
@endsection

@push('scripts')
{{-- Chart.js desde CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
// ── Datos desde PHP → JS ──────────────────────────────────────────
const evoMeses     = @json($meses12->pluck('mes'));
const evoCantidad  = @json($meses12->pluck('cantidad'));
const evoMonto     = @json($meses12->pluck('monto'));

const estadoLabels = @json($proformasPorEstado->pluck('estado'));
const estadoTotals = @json($proformasPorEstado->pluck('total'));
const estadoColors = estadoLabels.map(e => {
    const m = {
        'Ganada':      '#10b981',
        'Cotizado':    '#3b82f6',
        'Perdida':     '#ef4444',
        'En Revisión': '#f59e0b',
        'Sin estado':  '#94a3b8',
    };
    return m[e] ?? '#cbd5e1';
});

const topClienteLabels = @json($topClientes->pluck('razon')->map(fn($r) => Str::limit($r, 18)));
const topClienteMontos = @json($topClientes->pluck('monto'));

const vendLabels  = @json($topVendedores->pluck('name')->map(fn($n) => Str::limit($n, 14)));
const vendGanadas = @json($topVendedores->pluck('ganadas'));

// ── Defaults globales Chart.js ────────────────────────────────────
Chart.defaults.font.family = "'DM Sans', sans-serif";
Chart.defaults.font.size   = 11;
Chart.defaults.color       = '#64748b';

// ── 1. EVOLUCIÓN 12 MESES (line) ─────────────────────────────────
const ctxEvo = document.getElementById('chartEvolucion');
if (ctxEvo) {
    new Chart(ctxEvo, {
        type: 'line',
        data: {
            labels: evoMeses,
            datasets: [
                {
                    label: 'Cantidad',
                    data: evoCantidad,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#3b82f6',
                    pointRadius: 3,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'yCant',
                },
                {
                    label: 'Monto (S/.)',
                    data: evoMonto,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,.06)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#10b981',
                    pointRadius: 3,
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'yMonto',
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#94a3b8',
                    bodyColor: '#f8fafc',
                    padding: 10,
                    callbacks: {
                        label: ctx => ctx.datasetIndex === 0
                            ? ` ${ctx.raw} proformas`
                            : ` S/. ${ctx.raw.toLocaleString('es-PE', {minimumFractionDigits:0})}`
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, border: { display: false } },
                yCant: {
                    type: 'linear', position: 'left',
                    grid: { color: '#f1f5f9' }, border: { display: false },
                    ticks: { stepSize: 1, precision: 0 },
                },
                yMonto: {
                    type: 'linear', position: 'right',
                    grid: { display: false }, border: { display: false },
                    ticks: {
                        callback: v => 'S/. ' + (v/1000).toFixed(0) + 'k'
                    }
                },
            }
        }
    });
}

// ── 2. ESTADOS DOUGHNUT ───────────────────────────────────────────
const ctxEst = document.getElementById('chartEstados');
if (ctxEst && estadoLabels.length > 0) {
    new Chart(ctxEst, {
        type: 'doughnut',
        data: {
            labels: estadoLabels,
            datasets: [{
                data: estadoTotals,
                backgroundColor: estadoColors,
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    bodyColor: '#f8fafc',
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.raw} prf.`
                    }
                }
            }
        }
    });
}

// ── 3. TOP CLIENTES BAR ───────────────────────────────────────────
const ctxCli = document.getElementById('chartTopClientes');
if (ctxCli && topClienteLabels.length > 0) {
    new Chart(ctxCli, {
        type: 'bar',
        data: {
            labels: topClienteLabels,
            datasets: [{
                label: 'Monto Ganado',
                data: topClienteMontos,
                backgroundColor: topClienteLabels.map((_, i) =>
                    `rgba(${[59,99,131,79,16,168,99,59][i%8]},${[130,102,163,130,185,162,102,130][i%8]},${[246,241,230,246,129,163,241,246][i%8]},.75)`
                ),
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    bodyColor: '#f8fafc',
                    callbacks: {
                        label: ctx => ` S/. ${Number(ctx.raw).toLocaleString('es-PE')}`
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { font: { size: 9 } } },
                y: {
                    grid: { color: '#f1f5f9' }, border: { display: false },
                    ticks: { callback: v => 'S/.' + (v/1000).toFixed(0)+'k', font: { size: 10 } }
                },
            }
        }
    });
}

// ── 4. VENDEDORES DOUGHNUT ────────────────────────────────────────
const ctxVend = document.getElementById('chartVendedores');
if (ctxVend && vendLabels.length > 0) {
    const vendColors = ['#3b82f6','#10b981','#f59e0b','#6366f1','#ef4444'];
    new Chart(ctxVend, {
        type: 'doughnut',
        data: {
            labels: vendLabels,
            datasets: [{
                data: vendGanadas,
                backgroundColor: vendColors,
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    labels: { boxWidth: 10, font: { size: 9 }, padding: 6 }
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    bodyColor: '#f8fafc',
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.raw} ganadas`
                    }
                }
            }
        }
    });
}
</script>
@endpush
