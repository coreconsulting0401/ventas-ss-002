@extends('layouts.app')

@section('title', 'Detalle de Cliente')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-people"></i> Detalle de Cliente
    </h2>
    <div class="btn-group">
        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-building"></i> Información del Cliente
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>RUC:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-info fs-5">
                            <i class="bi bi-credit-card"></i> {{ $cliente->ruc }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Razón Social:</strong>
                    </div>
                    <div class="col-md-8">
                        <h5 class="mb-0">{{ $cliente->razon }}</h5>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Dirección Principal:</strong>
                    </div>
                    <div class="col-md-8">
                        <i class="bi bi-geo-alt"></i> {{ $cliente->direccion }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Teléfono 1:</strong>
                    </div>
                    <div class="col-md-8">
                        <i class="bi bi-telephone"></i> {{ $cliente->telefono1 }}
                    </div>
                </div>

                @if($cliente->telefono2)
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Teléfono 2:</strong>
                    </div>
                    <div class="col-md-8">
                        <i class="bi bi-phone"></i> {{ $cliente->telefono2 }}
                    </div>
                </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Categoría:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($cliente->categoria)
                            <span class="badge bg-primary">
                                <i class="bi bi-tag"></i> {{ $cliente->categoria->name }}
                            </span>
                        @else
                            <span class="badge bg-secondary">Sin categoría</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Crédito:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($cliente->credito)
                            @if($cliente->credito->aprobacion)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Aprobado
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle"></i> Desaprobado
                                </span>
                            @endif
                        @else
                            <span class="badge bg-secondary">Sin crédito</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Fecha de Creación:</strong>
                    </div>
                    <div class="col-md-8">
                        <i class="bi bi-calendar-check"></i>
                        {{ $cliente->created_at->format('d/m/Y H:i:s') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Última Actualización:</strong>
                    </div>
                    <div class="col-md-8">
                        <i class="bi bi-calendar-x"></i>
                        {{ $cliente->updated_at->format('d/m/Y H:i:s') }}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <form action="{{ route('clientes.destroy', $cliente) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('¿Está seguro de eliminar este cliente?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Eliminar Cliente
                        </button>
                    </form>
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>
        </div>

        <!-- Sección de Contactos -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-lines-fill"></i> Contactos Asociados ({{ $cliente->contactos->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($cliente->contactos->count() > 0)
                    <div class="row">
                        @foreach($cliente->contactos as $contacto)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-person-badge"></i>
                                        {{ $contacto->nombre }} {{ $contacto->apellido_paterno }}
                                    </h6>
                                    <p class="card-text small">
                                        <strong>DNI:</strong> {{ $contacto->dni }}<br>
                                        <strong>Cargo:</strong> {{ $contacto->cargo }}<br>
                                        <strong>Teléfono:</strong> {{ $contacto->telefono }}<br>
                                        <strong>Email:</strong> {{ $contacto->email }}
                                    </p>
                                    <a href="{{ route('contactos.show', $contacto) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Ver Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="mt-2 text-muted">Este cliente no tiene contactos asociados</p>
                        <a href="{{ route('contactos.create') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-circle"></i> Crear Contacto
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sección de Direcciones Adicionales -->
        <div class="card mt-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="bi bi-geo-alt"></i> Agencias / Direcciones Adicionales ({{ $cliente->direcciones->count() }})
                </h5>
            </div>
            <div class="card-body p-0">
                @if($cliente->direcciones->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($cliente->direcciones as $direccion)
                        <div class="list-group-item">
                            <div class="ms-1 me-auto">

                                {{-- Número de agencia --}}
                                <div class="fw-bold mb-1">
                                    <span class="badge bg-info text-white me-1">Agencia {{ $loop->iteration }}</span>
                                </div>

                                {{-- Dirección textual --}}
                                <div class="mb-2">
                                    <i class="bi bi-geo-alt-fill text-info"></i>
                                    <strong>Dirección:</strong> {{ $direccion->direccion }}
                                </div>

                                {{-- Ubigeo --}}
                                @if($direccion->distrito)
                                    <div class="d-flex flex-wrap gap-2 align-items-center">
                                        <span class="badge rounded-pill bg-primary">
                                            <i class="bi bi-map"></i>
                                            {{ $direccion->distrito->provincia->departamento->nombre ?? '—' }}
                                        </span>
                                        <i class="bi bi-chevron-right text-muted small"></i>
                                        <span class="badge rounded-pill bg-secondary">
                                            <i class="bi bi-building"></i>
                                            {{ $direccion->distrito->provincia->nombre ?? '—' }}
                                        </span>
                                        <i class="bi bi-chevron-right text-muted small"></i>
                                        <span class="badge rounded-pill bg-success">
                                            <i class="bi bi-pin-map"></i>
                                            {{ $direccion->distrito->nombre }}
                                        </span>
                                    </div>
                                @else
                                    <span class="badge bg-light text-muted border">
                                        <i class="bi bi-geo-alt"></i> Sin ubigeo registrado
                                    </span>
                                @endif

                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="mt-2 text-muted">Este cliente no tiene agencias adicionales registradas</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sección de Proformas -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text"></i> Proformas ({{ $cliente->proformas->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($cliente->proformas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cliente->proformas->take(5) as $proforma)
                                <tr>
                                    <td>{{ $proforma->id }}</td>
                                    <td>{{ $proforma->created_at->format('d/m/Y') }}</td>
                                    <td>S/. {{ number_format($proforma->total, 2) }}</td>
                                    <td>
                                        <a href="{{ route('proformas.show', $proforma) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($cliente->proformas->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('proformas.index') }}?cliente_id={{ $cliente->id }}" class="btn btn-sm btn-primary">
                                Ver todas las proformas ({{ $cliente->proformas->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="mt-2 text-muted">Este cliente no tiene proformas registradas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle"></i> Información Adicional
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="card-img">
                        <i class="bi bi-people" style="font-size: 4rem;"></i>
                    </div>
                </div>

                <div class="alert alert-primary">
                    <i class="bi bi-lightbulb"></i>
                    <strong>Tip:</strong> Los clientes pueden tener múltiples contactos y direcciones para mejor organización.
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Importante:</strong> Al eliminar un cliente, se eliminarán todas sus direcciones adicionales y proformas asociadas.
                </div>

                <div class="mt-3">
                    <h6 class="mb-2">Resumen del Cliente:</h6>
                    <ul class="list-unstyled small">
                        <li><i class="bi bi-check-circle text-success"></i> RUC: {{ $cliente->ruc }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Razón Social: {{ Str::limit($cliente->razon, 30) }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Categoría: {{ $cliente->categoria ? $cliente->categoria->name : 'N/A' }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Contactos: {{ $cliente->contactos->count() }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Agencias: {{ $cliente->direcciones->count() + 1 }} (incluye principal)</li>
                        <li><i class="bi bi-check-circle text-success"></i> Proformas: {{ $cliente->proformas->count() }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
