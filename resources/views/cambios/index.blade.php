@extends('layouts.app')

@section('title', 'Tipo de Cambio')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-currency-exchange"></i> Tipo de Cambio USD / PEN
    </h2>
    @role('Administrador')
    <form action="{{ route('cambios.consultar-hoy') }}" method="POST" class="d-inline"
          onsubmit="return confirm('¿Ejecutar consulta manual a la API SUNAT ahora?');">
        @csrf
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-cloud-download"></i> Consultar Hoy Manualmente
        </button>
    </form>
    @endrole
</div>
@endsection

@section('content')

{{-- ── Tipo de cambio del DÍA ─────────────────────────────────────────────── --}}
@if($hoy)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm"
                 style="background: linear-gradient(135deg,#6c5dd3 0%,#8b7de8 100%); color:white;">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-1 text-center">
                            <i class="bi bi-currency-dollar" style="font-size:2.5rem;"></i>
                        </div>
                        <div class="col-md-2">
                            <div class="small opacity-75">Fecha</div>
                            <div class="fw-bold fs-5">{{ \Carbon\Carbon::parse($hoy->fecha)->format('d/m/Y') }}</div>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="small opacity-75">Compra</div>
                            <div class="fw-bold fs-4">S/. {{ number_format($hoy->compra, 4) }}</div>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="small opacity-75">Venta Oficial</div>
                            <div class="fw-bold fs-4">S/. {{ number_format($hoy->venta, 4) }}</div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="small opacity-75">
                                Venta+ (inc. S/. {{ number_format($hoy->incremento, 4) }})
                            </div>
                            <div class="fw-bold fs-3" style="color:#ffe066;">
                                S/. {{ number_format($hoy->venta_mas, 4) }}
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <span class="badge bg-{{ $hoy->estadoBadgeClass() }} fs-6 px-3 py-2">
                                {{ strtoupper($hoy->estado) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-warning d-flex align-items-center mb-4">
        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
        <div>No hay tipo de cambio registrado para hoy.
            @role('Administrador')
                Usa el botón <strong>"Consultar Hoy Manualmente"</strong> para obtenerlo ahora.
            @endrole
        </div>
    </div>
@endif

{{-- ── Mensajes flash ──────────────────────────────────────────────────────── --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-x-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── Filtros ─────────────────────────────────────────────────────────────── --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('cambios.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small">Desde</label>
                <input type="date" name="fecha_desde" class="form-control"
                       value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Hasta</label>
                <input type="date" name="fecha_hasta" class="form-control"
                       value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="ok"        {{ request('estado') === 'ok'        ? 'selected' : '' }}>OK</option>
                    <option value="error"     {{ request('estado') === 'error'     ? 'selected' : '' }}>Error</option>
                    <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Filtrar
                </button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('cambios.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-x-circle"></i> Limpiar
                </a>
            </div>
        </form>
    </div>
</div>

{{-- ── Tabla histórico ─────────────────────────────────────────────────────── --}}
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-table"></i> Histórico de Tipos de Cambio
            <span class="badge bg-secondary ms-2">{{ $cambios->total() }} registros</span>
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fecha</th>
                        <th class="text-center">Origen</th>
                        <th class="text-center">Moneda</th>
                        <th class="text-end">Compra</th>
                        <th class="text-end">Venta</th>
                        <th class="text-end">Incremento</th>
                        <th class="text-end text-warning">Venta+</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cambios as $cambio)
                    <tr>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($cambio->fecha)->format('d/m/Y') }}</strong>
                            @if($cambio->fecha->isToday())
                                <span class="badge bg-primary ms-1">Hoy</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $cambio->origen ?? '—' }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $cambio->moneda ?? '—' }}</span>
                        </td>
                        <td class="text-end">
                            @if($cambio->compra)
                                S/. {{ number_format($cambio->compra, 4) }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if($cambio->venta)
                                S/. {{ number_format($cambio->venta, 4) }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-end text-muted small">
                            + {{ number_format($cambio->incremento, 4) }}
                        </td>
                        <td class="text-end fw-bold text-success">
                            @if($cambio->venta_mas)
                                S/. {{ number_format($cambio->venta_mas, 4) }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-{{ $cambio->estadoBadgeClass() }}">
                                {{ strtoupper($cambio->estado) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('cambios.show', $cambio) }}"
                                   class="btn btn-info" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('cambios.edit-incremento', $cambio) }}"
                                   class="btn btn-warning" title="Ajustar incremento">
                                    <i class="bi bi-sliders"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size:3rem;color:#ccc;"></i>
                            <p class="mt-2 text-muted">No hay registros de tipo de cambio</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end">
        {{ $cambios->links() }}
    </div>
</div>
@endsection
