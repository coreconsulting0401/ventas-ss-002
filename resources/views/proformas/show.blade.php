@extends('layouts.app')
@section('title', 'Detalle de Proforma')

@section('page-title')
<div class="section-header">
    <h2><i class="bi bi-file-earmark-text"></i> Detalle de Proforma</h2>
    <div class="btn-group">
        <a href="{{ route('proformas.pdf', $proforma->id) }}"
           class="btn btn-primary" target="_blank">
            <i class="bi bi-file-pdf"></i> Generar PDF
        </a>
        <a href="{{ route('proformas.edit', $proforma->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('proformas.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>
@endsection

@section('content')

@php
    // Símbolo y código de moneda para toda la vista
    $sym     = $proforma->simboloMoneda();          // '$' o 'S/.'
    $esDolar = $proforma->esDolares();
@endphp

<div class="row">
    <div class="col-md-8">
        <!-- Información General -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text"></i> Información de la Proforma
                </h5>
            </div>
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-4"><strong>N° Cotización:</strong></div>
                    <div class="col-md-8">
                        <span class="badge bg-primary fs-5">
                            NCT-{{ str_pad($proforma->id, 11, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Cliente:</strong></div>
                    <div class="col-md-8">
                        <h5 class="mb-0">{{ $proforma->cliente->razon }}</h5>
                        <small class="text-muted">{{ $proforma->cliente->ruc }}</small>
                    </div>
                </div>

                <!-- Dirección -->
                <div class="row mb-3">
                    <div class="col-md-4"><strong><i class="bi bi-geo-alt"></i> Dir. entrega:</strong></div>
                    <div class="col-md-8">
                        @if($proforma->direccion)
                            <span class="badge bg-info mb-1">Agencia</span>
                            <div>{{ $proforma->direccion->direccion }}</div>
                            @if($proforma->direccion->distrito)
                                <small class="text-muted">
                                    {{ $proforma->direccion->distrito->nombre }}
                                    @if($proforma->direccion->distrito->provincia)
                                        · {{ $proforma->direccion->distrito->provincia->nombre }}
                                        @if($proforma->direccion->distrito->provincia->departamento)
                                            · {{ $proforma->direccion->distrito->provincia->departamento->nombre }}
                                        @endif
                                    @endif
                                </small>
                            @endif
                        @else
                            <span class="badge bg-secondary mb-1">Principal</span>
                            <div>{{ $proforma->cliente->direccion }}</div>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Nota:</strong></div>
                    <div class="col-md-8">{{ $proforma->nota ?? 'Sin nota' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>N° Orden:</strong></div>
                    <div class="col-md-8">{{ $proforma->orden ?? 'Sin orden' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Fecha Creación:</strong></div>
                    <div class="col-md-8">
                        <i class="bi bi-calendar-check"></i> {{ $proforma->fecha_creacion->format('d/m/Y') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Fecha Fin:</strong></div>
                    <div class="col-md-8">
                        <i class="bi bi-calendar-x"></i> {{ $proforma->fecha_fin->format('d/m/Y') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Transacción:</strong></div>
                    <div class="col-md-8">
                        @if($proforma->transaccion)
                            <span class="badge bg-warning text-dark">{{ $proforma->transaccion->name }}</span>
                        @else
                            <span class="badge bg-secondary">Sin transacción</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Temperatura:</strong></div>
                    <div class="col-md-8">
                        @if($proforma->temperatura)
                            <span class="badge bg-info">{{ $proforma->temperatura->name }}</span>
                        @else
                            <span class="badge bg-secondary">Sin temperatura</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Estado:</strong></div>
                    <div class="col-md-8">
                        @if($proforma->estado)
                            <span class="badge bg-success">{{ $proforma->estado->name }}</span>
                        @else
                            <span class="badge bg-secondary">Sin estado</span>
                        @endif
                    </div>
                </div>

                <!-- Moneda destacada -->
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Moneda:</strong></div>
                    <div class="col-md-8">
                        @if($esDolar)
                            <span class="badge bg-success fs-6 px-3">
                                <i class="bi bi-currency-dollar"></i> Dólares (USD)
                            </span>
                        @else
                            <span class="badge bg-warning text-dark fs-6 px-3">
                                <i class="bi bi-cash-coin"></i> Soles (PEN)
                            </span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4"><strong>Creado por:</strong></div>
                    <div class="col-md-8">{{ $proforma->user->name }}</div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Resumen Financiero -->
        <div class="card shadow-sm mb-4">
            <div class="card-header {{ $esDolar ? 'bg-success' : 'bg-success' }} text-white">
                <h5 class="mb-0">
                    <i class="bi bi-receipt"></i> Resumen Financiero
                    <span class="badge {{ $esDolar ? 'bg-light text-success' : 'bg-light text-success' }} ms-2">
                        {{ $esDolar ? '$ USD' : 'S/ PEN' }}
                    </span>
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Subtotal</label>
                    <input type="text" class="form-control form-control-lg"
                           value="{{ $sym }} {{ number_format($proforma->sub_total, 2) }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">IGV (18%)</label>
                    <input type="text" class="form-control form-control-lg"
                           value="{{ $sym }} {{ number_format($proforma->monto_igv, 2) }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">TOTAL</label>
                    <input type="text" class="form-control form-control-lg fw-bold fs-3 text-success"
                           value="{{ $sym }} {{ number_format($proforma->total, 2) }}" readonly>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-gear"></i> Acciones</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('proformas.pdf', $proforma->id) }}"
                       class="btn btn-primary btn-sm" target="_blank">
                        <i class="bi bi-file-pdf"></i> Generar PDF
                    </a>
                    <a href="{{ route('proformas.pdf.preview', $proforma->id) }}"
                       class="btn btn-outline-primary btn-sm" target="_blank">
                        <i class="bi bi-eye"></i> Vista Previa PDF
                    </a>
                    <a href="{{ route('proformas.edit', $proforma->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Editar Proforma
                    </a>
                    <a href="{{ route('proformas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Listado
                    </a>
                    <form action="{{ route('proformas.destroy', $proforma->id) }}"
                          method="POST"
                          onsubmit="return confirm('¿Está seguro de eliminar esta proforma?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash"></i> Eliminar Proforma
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Productos -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0">
            <i class="bi bi-cart-check"></i> Productos en la Proforma
            <span class="badge {{ $esDolar ? 'bg-light text-success' : 'bg-light text-success' }} ms-2">
                {{ $esDolar ? '$ USD' : 'S/ PEN' }}
            </span>
        </h5>
    </div>
    <div class="card-body">
        @if($proforma->productos->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width:10%;">Código</th>
                            <th style="width:35%;">Descripción</th>
                            <th style="width:10%;">Cantidad</th>
                            <th style="width:12%;">Precio Unit.</th>
                            <th style="width:13%;">Descuento</th>
                            <th style="width:12%;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proforma->productos as $producto)
                        <tr>
                            <td><span class="badge bg-info">{{ $producto->codigo_p }}</span></td>
                            <td>
                                <strong>{{ $producto->nombre }}</strong><br>
                                <small class="text-muted">{{ $producto->marca }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $producto->pivot->cantidad }}</span>
                            </td>
                            <td>
                                <strong>
                                    {{ $sym }} {{ number_format($producto->pivot->precio_unitario, 2) }}
                                </strong>
                            </td>
                            <td>
                                @if($producto->pivot->descuento_cliente > 0)
                                    <span class="badge bg-warning text-dark">
                                        {{ number_format($producto->pivot->descuento_cliente, 2) }}%
                                    </span>
                                @else
                                    <span class="badge bg-secondary">0%</span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-success">
                                    {{ $sym }} {{ number_format(
                                        $producto->pivot->cantidad
                                        * $producto->pivot->precio_unitario
                                        * (1 - $producto->pivot->descuento_cliente / 100),
                                        2
                                    ) }}
                                </strong>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-primary">
                            <th colspan="5" class="text-end fw-bold">TOTAL GENERAL:</th>
                            <th class="fw-bold fs-5">
                                {{ $sym }} {{ number_format($proforma->total, 2) }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="bi bi-inbox" style="font-size:3rem;color:#ccc;"></i>
                <p class="mt-2 text-muted">Esta proforma no tiene productos asignados</p>
            </div>
        @endif
    </div>
</div>

@endsection
