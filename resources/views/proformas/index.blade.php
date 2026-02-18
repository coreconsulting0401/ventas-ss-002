@extends('layouts.app')

@section('title', 'Proformas')

@section('page-title')
<div class="section-header">
    <h2><i class="bi bi-file-earmark-text"></i> Gestión de Proformas</h2>
    <a href="{{ route('proformas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Proforma
    </a>
</div>
@endsection

@section('content')

<div id="js-config"
     data-url="{{ url('/proformas') }}"
     data-stats-url="{{ route('proformas.estadisticas') }}"
     data-fc-desde="{{ request('fecha_creacion_desde') }}"
     data-fc-hasta="{{ request('fecha_creacion_hasta') }}"
     data-ff-desde="{{ request('fecha_fin_desde') }}"
     data-ff-hasta="{{ request('fecha_fin_hasta') }}"
     style="display:none;"></div>

@php
    $fcActivo   = request('fecha_creacion_desde') || request('fecha_creacion_hasta');
    $ffActivo   = request('fecha_fin_desde')      || request('fecha_fin_hasta');
    $hayFiltros = request()->hasAny(['id','razon','nombre','estado','temperatura',
        'fecha_creacion_desde','fecha_creacion_hasta','fecha_fin_desde','fecha_fin_hasta']);
    $numFiltros = collect(['id','razon','nombre','estado','temperatura',
        'fecha_creacion_desde','fecha_creacion_hasta','fecha_fin_desde','fecha_fin_hasta'])
        ->filter(fn($k) => request()->filled($k))->count();
@endphp

{{-- ════════════════════════════════════════
     PANEL DE FILTROS
════════════════════════════════════════ --}}
<div class="card shadow-sm mb-4">
    <ul class="nav nav-pills px-3 pt-3 mb-0 border-bottom gap-1">
        <li class="nav-item">
            <button type="button" class="nav-link py-1 px-3" id="btnAbrirEstadisticas">
                <i class="bi bi-bar-chart-fill"></i> Gráficas y Estadísticas
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link py-1 px-3" onclick="filtrarRapido('mes')">
                <i class="bi bi-file-earmark-bar-graph"></i> Mes Actual
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link py-1 px-3" onclick="filtrarRapido('anio')">
                <i class="bi bi-file-earmark-bar-graph-fill"></i> Año Actual
            </button>
        </li>
    </ul>

    <div class="card-body pb-3">
        <div class="row g-2 mb-2 align-items-end">
            <div class="col-12 col-md-2">
                <label class="form-label small mb-1 fw-semibold"><i class="bi bi-upc"></i> N° Cotización</label>
                <input type="text" id="f_id" class="form-control form-control-sm"
                       value="{{ request('id') }}" placeholder="NCT-... ó número" autocomplete="off">
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label small mb-1 fw-semibold"><i class="bi bi-people"></i> Cliente</label>
                <input type="text" id="f_razon" class="form-control form-control-sm"
                       value="{{ request('razon') }}" placeholder="Razón social o RUC" autocomplete="off">
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label small mb-1 fw-semibold"><i class="bi bi-person-fill"></i> Usuario</label>
                <input type="text" id="f_nombre" class="form-control form-control-sm"
                       value="{{ request('nombre') }}" placeholder="Nombre, DNI o código" autocomplete="off">
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label small mb-1 fw-semibold"><i class="bi bi-flag"></i> Estado</label>
                <input type="text" id="f_estado" class="form-control form-control-sm"
                       value="{{ request('estado') }}" placeholder="Nombre del estado" autocomplete="off">
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label small mb-1 fw-semibold"><i class="bi bi-thermometer-sun"></i> Temperatura</label>
                <input type="text" id="f_temperatura" class="form-control form-control-sm"
                       value="{{ request('temperatura') }}" placeholder="Nombre temperatura" autocomplete="off">
            </div>
            <div class="col-12 col-md-2">
                <button type="button" id="btnBuscar" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <small class="text-muted"><i class="bi bi-funnel"></i> Filtrar por fecha:</small>

            <button type="button" id="btnFechaCreacion"
                    class="btn btn-sm {{ $fcActivo ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="bi bi-calendar-date"></i> Fecha Creación
                @if($fcActivo)
                <span class="badge bg-white text-primary ms-1">
                    @if(request('fecha_creacion_desde') && request('fecha_creacion_hasta'))
                        {{ \Carbon\Carbon::parse(request('fecha_creacion_desde'))->format('d/m/Y') }} – {{ \Carbon\Carbon::parse(request('fecha_creacion_hasta'))->format('d/m/Y') }}
                    @elseif(request('fecha_creacion_desde'))desde {{ \Carbon\Carbon::parse(request('fecha_creacion_desde'))->format('d/m/Y') }}
                    @else hasta {{ \Carbon\Carbon::parse(request('fecha_creacion_hasta'))->format('d/m/Y') }}@endif
                </span>
                @endif
            </button>

            <button type="button" id="btnFechaFin"
                    class="btn btn-sm {{ $ffActivo ? 'btn-success' : 'btn-outline-success' }}">
                <i class="bi bi-calendar-check"></i> Fecha Fin
                @if($ffActivo)
                <span class="badge bg-white text-success ms-1">
                    @if(request('fecha_fin_desde') && request('fecha_fin_hasta'))
                        {{ \Carbon\Carbon::parse(request('fecha_fin_desde'))->format('d/m/Y') }} – {{ \Carbon\Carbon::parse(request('fecha_fin_hasta'))->format('d/m/Y') }}
                    @elseif(request('fecha_fin_desde'))desde {{ \Carbon\Carbon::parse(request('fecha_fin_desde'))->format('d/m/Y') }}
                    @else hasta {{ \Carbon\Carbon::parse(request('fecha_fin_hasta'))->format('d/m/Y') }}@endif
                </span>
                @endif
            </button>

            @if($hayFiltros)
            <button type="button" onclick="irA()" class="btn btn-sm btn-outline-secondary ms-auto">
                <i class="bi bi-x-circle"></i> Limpiar
                <span class="badge bg-secondary ms-1">{{ $numFiltros }}</span>
            </button>
            @endif
        </div>

        @if($fcActivo || $ffActivo)
        <div class="d-flex flex-wrap gap-2 mt-2">
            @if($fcActivo)
            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary fw-normal px-2 py-1" style="font-size:.8rem;">
                <i class="bi bi-calendar-date"></i> <strong>Creación:</strong>
                @if(request('fecha_creacion_desde') && request('fecha_creacion_hasta'))
                    {{ \Carbon\Carbon::parse(request('fecha_creacion_desde'))->format('d/m/Y') }} al {{ \Carbon\Carbon::parse(request('fecha_creacion_hasta'))->format('d/m/Y') }}
                @elseif(request('fecha_creacion_desde'))desde {{ \Carbon\Carbon::parse(request('fecha_creacion_desde'))->format('d/m/Y') }}
                @else hasta {{ \Carbon\Carbon::parse(request('fecha_creacion_hasta'))->format('d/m/Y') }}@endif
                &nbsp;<span onclick="quitarFecha('creacion')" style="cursor:pointer;">✕</span>
            </span>
            @endif
            @if($ffActivo)
            <span class="badge bg-success bg-opacity-10 text-success border border-success fw-normal px-2 py-1" style="font-size:.8rem;">
                <i class="bi bi-calendar-check"></i> <strong>Fecha Fin:</strong>
                @if(request('fecha_fin_desde') && request('fecha_fin_hasta'))
                    {{ \Carbon\Carbon::parse(request('fecha_fin_desde'))->format('d/m/Y') }} al {{ \Carbon\Carbon::parse(request('fecha_fin_hasta'))->format('d/m/Y') }}
                @elseif(request('fecha_fin_desde'))desde {{ \Carbon\Carbon::parse(request('fecha_fin_desde'))->format('d/m/Y') }}
                @else hasta {{ \Carbon\Carbon::parse(request('fecha_fin_hasta'))->format('d/m/Y') }}@endif
                &nbsp;<span onclick="quitarFecha('fin')" style="cursor:pointer;">✕</span>
            </span>
            @endif
        </div>
        @endif
    </div>
</div>

{{-- ════════════════════════════════════════
     TABLA
════════════════════════════════════════ --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Listado de Proformas</h5>
        <span class="badge bg-secondary">{{ $proformas->total() }} registros</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>N° Cotización</th><th>Cliente</th><th>Usuario</th>
                        <th>Fecha Creación</th><th>Fecha Fin</th><th>Estado</th>
                        <th>Temperatura</th><th>Total</th>
                        <th class="text-center" style="width:150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proformas as $proforma)
                    <tr>
                        <td><span class="badge bg-primary">NCT-{{ str_pad($proforma->id,11,'0',STR_PAD_LEFT) }}</span></td>
                        <td>
                            <strong>{{ Str::limit($proforma->cliente->razon,30) }}</strong><br>
                            <small class="text-muted">{{ $proforma->cliente->ruc }}</small>
                        </td>
                        <td>
                            @if($proforma->user)
                                <strong>{{ $proforma->user->name }}</strong><br>
                                <small class="text-muted"><i class="bi bi-person-badge"></i> {{ $proforma->user->codigo ?? '—' }}</small>
                            @else <span class="badge bg-secondary">Sin usuario</span> @endif
                        </td>
                        <td><i class="bi bi-calendar-plus text-primary"></i> {{ $proforma->fecha_creacion->format('d/m/Y') }}</td>
                        <td>
                            @php $hoy=\Carbon\Carbon::today();$fin=$proforma->fecha_fin;$diff=$hoy->diffInDays($fin,false); @endphp
                            <i class="bi bi-calendar-x {{ $diff<0?'text-danger':($diff<=7?'text-warning':'text-success') }}"></i>
                            {{ $fin->format('d/m/Y') }}
                            @if($diff<0)<br><small class="text-danger fw-semibold">Vencido</small>
                            @elseif($diff===0)<br><small class="text-warning fw-semibold">Vence hoy</small>
                            @elseif($diff<=7)<br><small class="text-warning">{{ $diff }}d restantes</small>@endif
                        </td>
                        <td>
                            @if($proforma->estado)<span class="badge bg-info">{{ $proforma->estado->name }}</span>
                            @else <span class="badge bg-secondary">Sin estado</span>@endif
                        </td>
                        <td>
                            @if($proforma->temperatura)<span class="badge bg-warning text-dark">{{ $proforma->temperatura->name }}</span>
                            @else <span class="badge bg-secondary">—</span>@endif
                        </td>
                        <td>
                            <strong class="text-success">
                                {{ $proforma->moneda==='Dolares'?'$':'S/.' }} {{ number_format($proforma->total,2) }}
                            </strong>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('proformas.show',$proforma->id) }}" class="btn btn-info" title="Ver"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('proformas.pdf',$proforma->id) }}" class="btn btn-primary" title="PDF" target="_blank"><i class="bi bi-file-pdf"></i></a>
                                <a href="{{ route('proformas.edit',$proforma->id) }}" class="btn btn-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                                <button type="button" class="btn btn-danger" title="Eliminar"
                                        data-del-url="{{ route('proformas.destroy',$proforma->id) }}"
                                        data-del-token="{{ csrf_token() }}"
                                        onclick="eliminarProforma(this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size:3rem;color:#ccc;"></i>
                            <p class="mt-2 text-muted">No se encontraron proformas</p>
                            @if($hayFiltros)
                                <button type="button" onclick="irA()" class="btn btn-outline-secondary btn-sm mt-1">
                                    <i class="bi bi-x-circle"></i> Limpiar filtros
                                </button>
                            @else
                                <a href="{{ route('proformas.create') }}" class="btn btn-primary btn-sm mt-1">
                                    <i class="bi bi-plus-circle"></i> Nueva Proforma
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <p class="mb-0 text-muted small">
                Mostrando {{ $proformas->firstItem()??0 }}–{{ $proformas->lastItem()??0 }}
                de <strong>{{ $proformas->total() }}</strong> proformas
            </p>
            {{ $proformas->links() }}
        </div>
    </div>
</div>


{{-- ════════════════════════════════════════
     MODAL: RANGO DE FECHAS (tabla)
════════════════════════════════════════ --}}
<div class="modal fade" id="modalFecha" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white" id="mfHeader">
                <h5 class="modal-title" id="mfTitulo"><i class="bi bi-calendar-range"></i> Filtrar por fecha</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-1"><i class="bi bi-lightning-charge"></i> Accesos rápidos:</p>
                <div class="d-flex flex-wrap gap-1 mb-3">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-quick="hoy">Hoy</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-quick="ayer">Ayer</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-quick="semana">Esta semana</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-quick="semana_ant">Sem. anterior</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-quick="mes">Este mes</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-quick="mes_ant">Mes anterior</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-quick="anio">Este año</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-quick="anio_ant">Año anterior</button>
                </div>
                <hr class="my-2">
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label small fw-semibold"><i class="bi bi-calendar-event"></i> Desde</label>
                        <input type="date" id="mfDesde" class="form-control form-control-sm">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold"><i class="bi bi-calendar-event"></i> Hasta</label>
                        <input type="date" id="mfHasta" class="form-control form-control-sm">
                    </div>
                </div>
                <div id="mfPreview" class="mt-2 text-center small text-muted d-none">
                    <i class="bi bi-arrow-left-right"></i> <span id="mfPreviewTxt"></span>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-sm btn-outline-danger" id="mfBtnLimpiar">
                    <i class="bi bi-trash"></i> Quitar filtro
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-sm btn-primary" id="mfBtnAplicar">
                        <i class="bi bi-check2"></i> Aplicar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- ════════════════════════════════════════
     MODAL: ESTADÍSTICAS Y GRÁFICAS
════════════════════════════════════════ --}}
<div class="modal fade" id="modalEstadisticas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
         style="max-width:min(1140px,96vw);">
        <div class="modal-content" style="border-radius:16px;border:none;overflow:hidden;">

            <div class="modal-header text-white border-0"
                 style="background:linear-gradient(135deg,#6c5dd3,#4f8ef7);padding:1.25rem 1.5rem;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:42px;height:42px;background:rgba(255,255,255,.2);border-radius:10px;
                                display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-bar-chart-line-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 fw-bold">Estadísticas de Proformas</h5>
                        <div id="estRangoLabel" class="opacity-75" style="font-size:.78rem;"></div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>

            <div class="px-4 py-3 border-bottom" style="background:#f8f9ff;">
                <div class="d-flex flex-wrap gap-3 align-items-end">
                    <div>
                        <small class="text-muted fw-semibold d-block mb-1">
                            <i class="bi bi-calendar-range"></i> Rango de análisis
                        </small>
                        <div class="d-flex gap-1 flex-wrap">
                            <button type="button" class="btn btn-sm btn-outline-secondary estQuick active" data-q="mes">Este mes</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary estQuick" data-q="trimestre">Trimestre</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary estQuick" data-q="anio">Este año</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary estQuick" data-q="todo">Todo el tiempo</button>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted fw-semibold d-block mb-1">Personalizado</small>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <input type="date" id="estDesde" class="form-control form-control-sm" style="width:140px;">
                            <span class="text-muted small">→</span>
                            <input type="date" id="estHasta" class="form-control form-control-sm" style="width:140px;">
                            <button type="button" id="estBtnFiltrar" class="btn btn-primary btn-sm">
                                <i class="bi bi-search"></i> Analizar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-body p-0" style="background:#f4f6fb;overflow-y:auto;max-height:72vh;">

                <div id="estSpinner" class="d-flex justify-content-center align-items-center"
                     style="min-height:320px;">
                    <div class="text-center">
                        <div class="spinner-border mb-3"
                             style="width:3rem;height:3rem;color:#6c5dd3;" role="status"></div>
                        <p class="text-muted">Calculando estadísticas…</p>
                    </div>
                </div>

                <div id="estContenido" class="d-none p-4">

                    {{-- KPIs --}}
                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm text-center p-3 h-100" style="border-radius:14px;">
                                <div class="mx-auto mb-2 d-flex align-items-center justify-content-center"
                                     style="width:44px;height:44px;border-radius:12px;background:#eef2ff;">
                                    <i class="bi bi-files fs-5 text-primary"></i>
                                </div>
                                <div class="fw-bold fs-3 text-primary lh-1 mb-1" id="kpiConteo">—</div>
                                <div class="text-muted small">Total proformas</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm text-center p-3 h-100" style="border-radius:14px;">
                                <div class="mx-auto mb-2 d-flex align-items-center justify-content-center"
                                     style="width:44px;height:44px;border-radius:12px;background:#e8f5e9;">
                                    <i class="bi bi-graph-up-arrow fs-5 text-success"></i>
                                </div>
                                <div class="fw-bold fs-3 text-success lh-1 mb-1" id="kpiSuma">—</div>
                                <div class="text-muted small">Sumatoria total</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm text-center p-3 h-100" style="border-radius:14px;">
                                <div class="mx-auto mb-2 d-flex align-items-center justify-content-center"
                                     style="width:44px;height:44px;border-radius:12px;background:#fff8e1;">
                                    <i class="bi bi-calculator fs-5 text-warning"></i>
                                </div>
                                <div class="fw-bold fs-3 text-warning lh-1 mb-1" id="kpiPromedio">—</div>
                                <div class="text-muted small">Promedio</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius:14px;">
                                <div class="text-muted small mb-2 fw-semibold">
                                    <i class="bi bi-arrow-up-down me-1"></i>Extremos
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge" style="background:#e8f5e9;color:#2e7d32;">MAX</span>
                                    <span class="fw-semibold small" id="kpiMayor">—</span>
                                </div>
                                <div class="text-muted mb-2" style="font-size:.7rem;" id="kpiMayorCliente"></div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge" style="background:#fce4ec;color:#c62828;">MIN</span>
                                    <span class="fw-semibold small" id="kpiMenor">—</span>
                                </div>
                                <div class="text-muted" style="font-size:.7rem;" id="kpiMenorCliente"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Fila: Evolución + Estados --}}
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-lg-7">
                            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius:14px;">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-activity text-primary me-1"></i>Evolución mensual
                                </h6>
                                <div style="position:relative;height:230px;">
                                    <canvas id="chartEvolucion"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-5">
                            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius:14px;">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-flag text-info me-1"></i>Proformas por Estado
                                </h6>
                                <div style="position:relative;height:230px;">
                                    <canvas id="chartEstados"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fila: Temperaturas + Top Vendedores --}}
                    <div class="row g-3">
                        <div class="col-12 col-lg-4">
                            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius:14px;">
                                <h6 class="fw-bold mb-3">
                                    <i class="bi bi-thermometer-sun text-warning me-1"></i>Por Temperatura
                                </h6>
                                <div style="position:relative;height:230px;">
                                    <canvas id="chartTemperaturas"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-8">
                            <div class="card border-0 shadow-sm p-3 h-100" style="border-radius:14px;">
                                <h6 class="fw-bold mb-1">
                                    <i class="bi bi-trophy text-warning me-1"></i>Top 5 Vendedores — Cotizaciones Ganadas
                                </h6>
                                <p class="text-muted mb-3" style="font-size:.73rem;">
                                    Estados con: <em>ganada, facturada, vendida, éxito</em>
                                </p>
                                <div id="topVendedoresLista"></div>
                            </div>
                        </div>
                    </div>

                </div>{{-- fin estContenido --}}
            </div>

            <div class="modal-footer border-0" style="background:#f8f9ff;">
                <small class="text-muted me-auto">
                    <i class="bi bi-info-circle"></i> Datos en tiempo real · se actualizan al cambiar el rango.
                </small>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
/* ── Config desde PHP ── */
const _cfg     = document.getElementById('js-config').dataset;
const BASE_URL = _cfg.url;
const STATS_URL = _cfg.statsUrl;
const FEC = { fc_desde:_cfg.fcDesde||'', fc_hasta:_cfg.fcHasta||'',
              ff_desde:_cfg.ffDesde||'', ff_hasta:_cfg.ffHasta||'' };

/* ── Helpers ── */
const pad2  = n=>String(n).padStart(2,'0');
const toISO = d=>`${d.getFullYear()}-${pad2(d.getMonth()+1)}-${pad2(d.getDate())}`;
const toDMY = s=>{if(!s)return'';const[y,m,d]=s.split('-');return`${d}/${m}/${y}`;};
const fmtN  = (n,d=2)=>new Intl.NumberFormat('es-PE',{minimumFractionDigits:d,maximumFractionDigits:d}).format(n);
function lunDeSem(d){const r=new Date(d);r.setDate(r.getDate()-(r.getDay()===0?6:r.getDay()-1));return r;}
const QR={
    hoy:()=>{const h=new Date();return[h,h];},
    ayer:()=>{const d=new Date();d.setDate(d.getDate()-1);return[d,d];},
    semana:()=>{const s=lunDeSem(new Date());const e=new Date(s);e.setDate(s.getDate()+6);return[s,e];},
    semana_ant:()=>{const s=lunDeSem(new Date());s.setDate(s.getDate()-7);const e=new Date(s);e.setDate(e.getDate()+6);return[s,e];},
    mes:()=>{const h=new Date();return[new Date(h.getFullYear(),h.getMonth(),1),new Date(h.getFullYear(),h.getMonth()+1,0)];},
    mes_ant:()=>{const h=new Date();return[new Date(h.getFullYear(),h.getMonth()-1,1),new Date(h.getFullYear(),h.getMonth(),0)];},
    anio:()=>{const y=new Date().getFullYear();return[new Date(y,0,1),new Date(y,11,31)];},
    anio_ant:()=>{const y=new Date().getFullYear()-1;return[new Date(y,0,1),new Date(y,11,31)];},
};

/* ── Navegación ── */
function irA(extra){
    const p=new URLSearchParams();
    const vals={
        id:(document.getElementById('f_id')?.value||'').trim(),
        razon:(document.getElementById('f_razon')?.value||'').trim(),
        nombre:(document.getElementById('f_nombre')?.value||'').trim(),
        estado:(document.getElementById('f_estado')?.value||'').trim(),
        temperatura:(document.getElementById('f_temperatura')?.value||'').trim(),
        fecha_creacion_desde:FEC.fc_desde, fecha_creacion_hasta:FEC.fc_hasta,
        fecha_fin_desde:FEC.ff_desde,      fecha_fin_hasta:FEC.ff_hasta,
    };
    if(extra) Object.assign(vals,extra);
    Object.entries(vals).forEach(([k,v])=>{if(v)p.set(k,v);});
    window.location.href=BASE_URL+(p.toString()?'?'+p.toString():'');
}
function filtrarRapido(t){const[d,h]=QR[t]();irA({fecha_creacion_desde:toISO(d),fecha_creacion_hasta:toISO(h),fecha_fin_desde:'',fecha_fin_hasta:''});}
function quitarFecha(t){if(t==='creacion')irA({fecha_creacion_desde:'',fecha_creacion_hasta:''});else irA({fecha_fin_desde:'',fecha_fin_hasta:''});}
function eliminarProforma(btn){
    if(!confirm('¿Eliminar esta proforma? No se puede deshacer.'))return;
    const tok=btn.dataset.delToken;
    fetch(btn.dataset.delUrl,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`_method=DELETE&_token=${encodeURIComponent(tok)}`})
        .then(r=>{if(r.ok||r.redirected)window.location.href=BASE_URL;else alert('Error HTTP '+r.status);})
        .catch(()=>alert('Error de red.'));
}

/* ── Paleta gráficas ── */
const PAL=['#6c5dd3','#4f8ef7','#00c5a1','#ffa940','#f7536b','#a855f7','#06b6d4','#84cc16','#f59e0b','#ef4444'];
const PALB=PAL.map(c=>c+'cc');
const MESES=['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
const labelMes=ym=>{const[y,m]=ym.split('-');return MESES[+m-1]+' '+y.slice(2);};
const charts={};
const dChart=id=>{if(charts[id]){charts[id].destroy();delete charts[id];}};

/* ── Init DOM ── */
document.addEventListener('DOMContentLoaded',function(){

    /* Modal de fechas (tabla) */
    const mfEl=document.getElementById('modalFecha');
    const mfBS=bootstrap.Modal.getOrCreateInstance(mfEl);
    const mfH=document.getElementById('mfHeader');
    const mfT=document.getElementById('mfTitulo');
    const mfD=document.getElementById('mfDesde');
    const mfHa=document.getElementById('mfHasta');
    const mfPrev=document.getElementById('mfPreview');
    const mfPT=document.getElementById('mfPreviewTxt');
    const mfAp=document.getElementById('mfBtnAplicar');
    const mfLi=document.getElementById('mfBtnLimpiar');
    let tipoF=null;

    const MFC={
        creacion:{t:'<i class="bi bi-calendar-date"></i> Fecha de Creación',h:'modal-header bg-primary text-white',b:'btn btn-sm btn-primary',
                  gD:()=>FEC.fc_desde,gH:()=>FEC.fc_hasta,sD:v=>FEC.fc_desde=v,sH:v=>FEC.fc_hasta=v,x:{fecha_creacion_desde:'',fecha_creacion_hasta:''}},
        fin:     {t:'<i class="bi bi-calendar-check"></i> Fecha de Vencimiento',h:'modal-header bg-success text-white',b:'btn btn-sm btn-success',
                  gD:()=>FEC.ff_desde,gH:()=>FEC.ff_hasta,sD:v=>FEC.ff_desde=v,sH:v=>FEC.ff_hasta=v,x:{fecha_fin_desde:'',fecha_fin_hasta:''}},
    };

    function mfUpd(){const d=mfD.value,h=mfHa.value;if(d||h){mfPrev.classList.remove('d-none');mfPT.textContent=(d&&h)?`${toDMY(d)} → ${toDMY(h)}`:d?`Desde ${toDMY(d)}`:`Hasta ${toDMY(h)}`;}else mfPrev.classList.add('d-none');}
    function rstQ(){document.querySelectorAll('[data-quick]').forEach(b=>b.className='btn btn-outline-secondary btn-sm');}

    function abrMF(t){tipoF=t;const c=MFC[t];mfT.innerHTML=c.t;mfH.className=c.h;mfAp.className=c.b;mfD.value=c.gD();mfHa.value=c.gH();rstQ();mfUpd();mfBS.show();}
    document.getElementById('btnFechaCreacion')?.addEventListener('click',()=>abrMF('creacion'));
    document.getElementById('btnFechaFin')?.addEventListener('click',()=>abrMF('fin'));
    document.querySelectorAll('[data-quick]').forEach(b=>{b.addEventListener('click',function(){const[d,h]=QR[this.dataset.quick]();mfD.value=toISO(d);mfHa.value=toISO(h);rstQ();this.className='btn btn-secondary btn-sm active';mfUpd();});});
    mfD.addEventListener('input',mfUpd); mfHa.addEventListener('input',mfUpd);
    mfHa.addEventListener('change',function(){if(mfD.value&&this.value&&this.value<mfD.value){alert('"Hasta" no puede ser anterior a "Desde".');this.value='';mfUpd();}});
    mfAp.addEventListener('click',function(){if(!mfD.value&&!mfHa.value){alert('Seleccione al menos una fecha.');return;}const c=MFC[tipoF];c.sD(mfD.value);c.sH(mfHa.value);mfBS.hide();irA();});
    mfLi.addEventListener('click',function(){const c=MFC[tipoF];c.sD('');c.sH('');mfBS.hide();irA(c.x);});
    mfEl.addEventListener('hidden.bs.modal',()=>{mfPrev.classList.add('d-none');rstQ();});

    /* Buscar / Enter */
    document.getElementById('btnBuscar')?.addEventListener('click',()=>irA());
    ['f_id','f_razon','f_nombre','f_estado','f_temperatura'].forEach(id=>{
        document.getElementById(id)?.addEventListener('keydown',e=>{if(e.key==='Enter'){e.preventDefault();irA();}});
    });

    /* ════════════════════════════════════════
       MODAL ESTADÍSTICAS
    ════════════════════════════════════════ */
    const estEl=document.getElementById('modalEstadisticas');
    const estBS=bootstrap.Modal.getOrCreateInstance(estEl);
    let estF={desde:'',hasta:''};

    function calcQ(q){
        if(q==='mes'){const[d,e]=QR.mes();return{desde:toISO(d),hasta:toISO(e)};}
        if(q==='trimestre'){const h=new Date();const ini=new Date(h.getFullYear(),Math.floor(h.getMonth()/3)*3,1);const fin=new Date(h.getFullYear(),Math.floor(h.getMonth()/3)*3+3,0);return{desde:toISO(ini),hasta:toISO(fin)};}
        if(q==='anio'){const[d,e]=QR.anio();return{desde:toISO(d),hasta:toISO(e)};}
        return{desde:'',hasta:''};
    }

    function updLabel(){
        const lbl=document.getElementById('estRangoLabel');if(!lbl)return;
        if(estF.desde&&estF.hasta)lbl.textContent=`${toDMY(estF.desde)} → ${toDMY(estF.hasta)}`;
        else if(estF.desde)lbl.textContent=`Desde ${toDMY(estF.desde)}`;
        else if(estF.hasta)lbl.textContent=`Hasta ${toDMY(estF.hasta)}`;
        else lbl.textContent='Todo el tiempo';
    }

    async function cargar(){
        const sp=document.getElementById('estSpinner');
        const cn=document.getElementById('estContenido');
        sp.classList.remove('d-none'); cn.classList.add('d-none');
        sp.innerHTML='<div class="d-flex justify-content-center align-items-center" style="min-height:320px;"><div class="text-center"><div class="spinner-border mb-3" style="width:3rem;height:3rem;color:#6c5dd3;" role="status"></div><p class="text-muted">Calculando estadísticas…</p></div></div>';
        const p=new URLSearchParams();
        if(estF.desde)p.set('fecha_desde',estF.desde);
        if(estF.hasta)p.set('fecha_hasta',estF.hasta);
        try{
            const r=await fetch(STATS_URL+(p.toString()?'?'+p.toString():''));
            const data=await r.json();
            render(data);
        }catch(e){
            console.error(e);
            document.getElementById('estSpinner').innerHTML='<div class="text-center p-5"><i class="bi bi-exclamation-triangle text-danger fs-1"></i><p class="text-danger mt-2">Error al cargar datos.<br><small>Verifique la ruta o reintente.</small></p></div>';
        }
    }

    function render(data){
        const{kpis,por_estado,por_temperatura,top_vendedores,evolucion}=data;

        /* KPIs */
        document.getElementById('kpiConteo').textContent=fmtN(kpis.total_proformas,0);
        document.getElementById('kpiSuma').textContent='S/. '+fmtN(kpis.suma_total);
        document.getElementById('kpiPromedio').textContent='S/. '+fmtN(kpis.promedio_total);
        if(kpis.mayor_proforma){
            document.getElementById('kpiMayor').textContent='S/. '+fmtN(kpis.mayor_proforma.total);
            document.getElementById('kpiMayorCliente').textContent='NCT-'+String(kpis.mayor_proforma.id).padStart(11,'0')+' · '+(kpis.mayor_proforma.razon||'').substring(0,24);
        }
        if(kpis.menor_proforma){
            document.getElementById('kpiMenor').textContent='S/. '+fmtN(kpis.menor_proforma.total);
            document.getElementById('kpiMenorCliente').textContent='NCT-'+String(kpis.menor_proforma.id).padStart(11,'0')+' · '+(kpis.menor_proforma.razon||'').substring(0,24);
        }

        /* Evolución */
        dChart('ev');
        charts['ev']=new Chart(document.getElementById('chartEvolucion').getContext('2d'),{
            type:'bar',
            data:{labels:evolucion.map(e=>labelMes(e.mes)),
                  datasets:[
                      {label:'Cantidad',data:evolucion.map(e=>e.cantidad),backgroundColor:'#6c5dd3cc',borderColor:'#6c5dd3',borderWidth:2,borderRadius:6,yAxisID:'y'},
                      {label:'Total S/.',data:evolucion.map(e=>parseFloat(e.total_mes)),type:'line',borderColor:'#4f8ef7',backgroundColor:'#4f8ef722',borderWidth:2.5,pointRadius:4,tension:.4,fill:true,yAxisID:'y2'},
                  ]},
            options:{responsive:true,maintainAspectRatio:false,
                     plugins:{legend:{position:'top',labels:{boxWidth:12,font:{size:11}}}},
                     scales:{y:{position:'left',grid:{color:'#f0f0f0'},ticks:{font:{size:10}}},
                             y2:{position:'right',grid:{display:false},ticks:{font:{size:10},callback:v=>'S/.'+fmtN(v,0)}}}},
        });

        /* Estados */
        dChart('est');
        charts['est']=new Chart(document.getElementById('chartEstados').getContext('2d'),{
            type:'doughnut',
            data:{labels:por_estado.map(e=>e.estado),
                  datasets:[{data:por_estado.map(e=>e.conteo),backgroundColor:PALB.slice(0,por_estado.length),borderColor:PAL.slice(0,por_estado.length),borderWidth:2,hoverOffset:8}]},
            options:{responsive:true,maintainAspectRatio:false,cutout:'60%',
                     plugins:{legend:{position:'right',labels:{boxWidth:12,font:{size:11}}},
                              tooltip:{callbacks:{label:ctx=>` ${ctx.label}: ${ctx.raw}`}}}},
        });

        /* Temperaturas */
        dChart('temp');
        charts['temp']=new Chart(document.getElementById('chartTemperaturas').getContext('2d'),{
            type:'pie',
            data:{labels:por_temperatura.map(t=>t.temperatura),
                  datasets:[{data:por_temperatura.map(t=>t.conteo),
                             backgroundColor:['#ffa940cc','#f7536bcc','#00c5a1cc','#4f8ef7cc','#a855f7cc','#6c5dd3cc'],
                             borderColor:['#ffa940','#f7536b','#00c5a1','#4f8ef7','#a855f7','#6c5dd3'],borderWidth:2,hoverOffset:6}]},
            options:{responsive:true,maintainAspectRatio:false,
                     plugins:{legend:{position:'bottom',labels:{boxWidth:11,font:{size:11}}},
                              tooltip:{callbacks:{label:ctx=>` ${ctx.label}: ${ctx.raw}`}}}},
        });

        /* Top Vendedores */
        const lista=document.getElementById('topVendedoresLista');
        if(!top_vendedores.length){
            lista.innerHTML='<p class="text-muted text-center py-3"><i class="bi bi-person-x me-1"></i>No hay cotizaciones con estados de éxito en este período.</p>';
        }else{
            const mx=Math.max(...top_vendedores.map(v=>v.cotizaciones_ganadas));
            lista.innerHTML=top_vendedores.map((v,i)=>{
                const pct=Math.round(v.cotizaciones_ganadas/mx*100);
                const med=['🥇','🥈','🥉','4️⃣','5️⃣'][i]||i+1;
                return`<div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center gap-2">
                            <span style="font-size:1.1rem;">${med}</span>
                            <div>
                                <div class="fw-semibold small">${v.name}</div>
                                <div class="text-muted" style="font-size:.72rem;">${v.codigo?'Cód: '+v.codigo+' · ':''}<span class="text-success">S/. ${fmtN(parseFloat(v.monto_ganado))}</span></div>
                            </div>
                        </div>
                        <span class="badge rounded-pill px-3 py-1 fw-bold"
                              style="background:${PAL[i]}22;color:${PAL[i]};border:1px solid ${PAL[i]}55;">
                            ${v.cotizaciones_ganadas}
                        </span>
                    </div>
                    <div class="progress" style="height:6px;border-radius:99px;">
                        <div class="progress-bar" style="width:${pct}%;background:${PAL[i]};border-radius:99px;transition:width .8s ease;"></div>
                    </div>
                </div>`;
            }).join('');
        }

        document.getElementById('estSpinner').classList.add('d-none');
        document.getElementById('estContenido').classList.remove('d-none');
        updLabel();
    }

    /* Abrir modal estadísticas */
    document.getElementById('btnAbrirEstadisticas')?.addEventListener('click',function(){
        if(!estF.desde&&!estF.hasta){const r=calcQ('mes');estF.desde=r.desde;estF.hasta=r.hasta;
            document.getElementById('estDesde').value=r.desde;document.getElementById('estHasta').value=r.hasta;}
        estBS.show(); cargar();
    });

    /* Quick buttons */
    document.querySelectorAll('.estQuick').forEach(btn=>{
        btn.addEventListener('click',function(){
            document.querySelectorAll('.estQuick').forEach(b=>b.classList.remove('active'));
            this.classList.add('active');
            const r=calcQ(this.dataset.q);estF.desde=r.desde;estF.hasta=r.hasta;
            document.getElementById('estDesde').value=r.desde;document.getElementById('estHasta').value=r.hasta;
            cargar();
        });
    });

    /* Personalizado */
    document.getElementById('estBtnFiltrar')?.addEventListener('click',function(){
        const d=document.getElementById('estDesde').value;const h=document.getElementById('estHasta').value;
        if(d&&h&&h<d){alert('"Hasta" no puede ser anterior a "Desde".');return;}
        estF.desde=d;estF.hasta=h;
        document.querySelectorAll('.estQuick').forEach(b=>b.classList.remove('active'));
        cargar();
    });

    /* Limpiar al cerrar */
    estEl.addEventListener('hidden.bs.modal',function(){
        ['ev','est','temp'].forEach(dChart);
        document.getElementById('estSpinner').classList.remove('d-none');
        document.getElementById('estContenido').classList.add('d-none');
    });

}); // fin DOMContentLoaded
</script>
@endpush
