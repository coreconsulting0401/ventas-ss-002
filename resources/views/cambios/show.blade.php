@extends('layouts.app')

@section('title', 'Tipo de Cambio — ' . \Carbon\Carbon::parse($cambio->fecha)->format('d/m/Y'))

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-currency-exchange"></i>
        Tipo de Cambio — {{ \Carbon\Carbon::parse($cambio->fecha)->format('d/m/Y') }}
    </h2>
    <div class="btn-group">
        <a href="{{ route('cambios.edit-incremento', $cambio) }}" class="btn btn-warning">
            <i class="bi bi-sliders"></i> Ajustar Incremento
        </a>
        <a href="{{ route('cambios.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">

    {{-- ── Columna principal ──────────────────────────────────────────────── --}}
    <div class="col-md-8">

        {{-- Datos de la API (SOLO LECTURA) --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-lock-fill text-secondary"></i>
                    Datos de la API SUNAT
                    <small class="text-muted fw-normal">(solo lectura — generados automáticamente)</small>
                </h5>
            </div>
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-4"><strong>Fecha</strong></div>
                    <div class="col-8">
                        <span class="badge bg-primary fs-6">
                            {{ \Carbon\Carbon::parse($cambio->fecha)->format('d/m/Y') }}
                        </span>
                        @if($cambio->fecha->isToday())
                            <span class="badge bg-success ms-1">Hoy</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-4"><strong>Origen</strong></div>
                    <div class="col-8">
                        <span class="badge bg-secondary">{{ $cambio->origen ?? '—' }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-4"><strong>Moneda</strong></div>
                    <div class="col-8">
                        <span class="badge bg-info">{{ $cambio->moneda ?? '—' }}</span>
                        <small class="text-muted ms-1">
                            @if($cambio->moneda === 'USD') Dólares Americanos @endif
                        </small>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-4"><strong>Compra</strong></div>
                    <div class="col-8">
                        <span class="fs-5 fw-bold text-primary">
                            S/. {{ $cambio->compra ? number_format($cambio->compra, 4) : '—' }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-4"><strong>Venta Oficial</strong></div>
                    <div class="col-8">
                        <span class="fs-5 fw-bold text-danger">
                            S/. {{ $cambio->venta ? number_format($cambio->venta, 4) : '—' }}
                        </span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Datos calculados (venta_mas editable mediante incremento) --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header" style="background:#fff8e1;">
                <h5 class="mb-0">
                    <i class="bi bi-calculator-fill text-warning"></i>
                    Precio de Venta Ajustado (Venta+)
                </h5>
            </div>
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-4"><strong>Venta Oficial</strong></div>
                    <div class="col-8">S/. {{ $cambio->venta ? number_format($cambio->venta, 4) : '—' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-4">
                        <strong>Incremento</strong>
                        <a href="{{ route('cambios.edit-incremento', $cambio) }}"
                           class="btn btn-xs btn-outline-warning btn-sm ms-2"
                           title="Editar incremento">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                    <div class="col-8">
                        <span class="badge bg-warning text-dark fs-6">
                            + S/. {{ number_format($cambio->incremento, 4) }}
                        </span>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-4"><strong>Venta+</strong></div>
                    <div class="col-8">
                        <span class="fs-3 fw-bold text-success">
                            S/. {{ $cambio->venta_mas ? number_format($cambio->venta_mas, 4) : '—' }}
                        </span>
                        <div class="text-muted small mt-1">
                            = Venta ({{ number_format($cambio->venta, 4) }})
                            + Incremento ({{ number_format($cambio->incremento, 4) }})
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Estado del proceso --}}
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-activity"></i> Estado del Proceso
                </h5>
            </div>
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-4"><strong>Estado</strong></div>
                    <div class="col-8">
                        <span class="badge bg-{{ $cambio->estadoBadgeClass() }} fs-6 px-3 py-2">
                            {{ strtoupper($cambio->estado) }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-4"><strong>Intentos</strong></div>
                    <div class="col-8">{{ $cambio->intentos }}</div>
                </div>

                @if($cambio->error_mensaje)
                <div class="row mb-3">
                    <div class="col-4"><strong>Último error</strong></div>
                    <div class="col-8">
                        <div class="alert alert-danger mb-0 py-2 small">
                            <i class="bi bi-bug"></i> {{ $cambio->error_mensaje }}
                        </div>
                    </div>
                </div>
                @endif

                <div class="row mb-3">
                    <div class="col-4"><strong>Creado</strong></div>
                    <div class="col-8">{{ $cambio->created_at->format('d/m/Y H:i:s') }}</div>
                </div>

                <div class="row">
                    <div class="col-4"><strong>Actualizado</strong></div>
                    <div class="col-8">{{ $cambio->updated_at->format('d/m/Y H:i:s') }}</div>
                </div>

            </div>
        </div>

    </div>

    {{-- ── Columna lateral ────────────────────────────────────────────────── --}}
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-currency-exchange" style="font-size:4rem;color:#6c5dd3;"></i>
                <h5 class="mt-3">Resumen</h5>
                <ul class="list-unstyled text-start small mt-3">
                    <li><i class="bi bi-check-circle text-success"></i> <strong>Origen:</strong> {{ $cambio->origen ?? 'N/A' }}</li>
                    <li><i class="bi bi-check-circle text-success"></i> <strong>Moneda:</strong> {{ $cambio->moneda ?? 'N/A' }}</li>
                    <li><i class="bi bi-check-circle text-success"></i> <strong>Compra:</strong> S/. {{ $cambio->compra ? number_format($cambio->compra, 4) : 'N/A' }}</li>
                    <li><i class="bi bi-check-circle text-success"></i> <strong>Venta:</strong> S/. {{ $cambio->venta ? number_format($cambio->venta, 4) : 'N/A' }}</li>
                    <li><i class="bi bi-check-circle text-warning"></i> <strong>Incremento:</strong> S/. {{ number_format($cambio->incremento, 4) }}</li>
                    <li><i class="bi bi-check-circle text-primary"></i> <strong>Venta+:</strong> S/. {{ $cambio->venta_mas ? number_format($cambio->venta_mas, 4) : 'N/A' }}</li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="{{ route('cambios.edit-incremento', $cambio) }}" class="btn btn-warning w-100">
                    <i class="bi bi-sliders"></i> Editar Incremento
                </a>
            </div>
        </div>

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i>
                    <strong>Nota:</strong> Los campos <em>Origen, Compra, Venta, Moneda y Fecha</em>
                    son obtenidos automáticamente de la API de SUNAT y no pueden ser editados manualmente.
                    <br><br>
                    Solo el <strong>Incremento</strong> es configurable por el usuario para ajustar el
                    precio <strong>Venta+</strong>.
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
