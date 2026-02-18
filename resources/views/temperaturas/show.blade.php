@extends('layouts.app')

@section('title', 'Detalle de Temperatura')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-thermometer-sun"></i> Detalle de Temperatura
    </h2>
    <div class="btn-group">
        <a href="{{ route('temperaturas.edit', $temperatura) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('temperaturas.index') }}" class="btn btn-secondary">
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
                    <i class="bi bi-thermometer-half"></i> Información de la Temperatura
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>ID:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-secondary">{{ $temperatura->id }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Nombre:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-info fs-5">
                            <i class="bi bi-thermometer"></i> {{ $temperatura->name }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Número de Proformas:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($temperatura->proformas_count > 0)
                            <span class="badge bg-success">
                                <i class="bi bi-file-earmark-text"></i> {{ $temperatura->proformas_count }} proforma(s)
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="bi bi-inbox"></i> Sin proformas asociadas
                            </span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Fecha de Creación:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($temperatura->created_at)
                            <i class="bi bi-calendar-check"></i>
                            {{ $temperatura->created_at->format('d/m/Y H:i:s') }}
                        @else
                            <i class="bi bi-calendar-x"></i>
                            <span class="text-muted">No disponible</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Última Actualización:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($temperatura->updated_at)
                            <i class="bi bi-calendar-x"></i>
                            {{ $temperatura->updated_at->format('d/m/Y H:i:s') }}
                        @else
                            <i class="bi bi-calendar-x"></i>
                            <span class="text-muted">No disponible</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <form action="{{ route('temperaturas.destroy', $temperatura) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('¿Está seguro de eliminar esta temperatura?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Eliminar Temperatura
                        </button>
                    </form>
                    <a href="{{ route('temperaturas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>
        </div>

        <!-- Sección de Proformas Relacionadas -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text"></i> Proformas Asociadas ({{ $temperatura->proformas->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($temperatura->proformas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($temperatura->proformas->take(5) as $proforma)
                                <tr>
                                    <td>
                                        <span class="badge bg-info">{{ $proforma->id }}</span>
                                    </td>
                                    <td>
                                        @if($proforma->cliente)
                                            {{ Str::limit($proforma->cliente->razon, 30) }}
                                        @else
                                            <span class="text-muted">Sin cliente</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>S/. {{ number_format($proforma->total, 2) }}</strong>
                                    </td>
                                    <td>
                                        {{ $proforma->created_at->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('proformas.show', $proforma) }}"
                                           class="btn btn-sm btn-info"
                                           title="Ver proforma">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($temperatura->proformas->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('proformas.index') }}?temperatura_id={{ $temperatura->id }}" class="btn btn-sm btn-primary">
                                Ver todas las proformas ({{ $temperatura->proformas->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="mt-2 text-muted">Esta temperatura no tiene proformas asociadas</p>
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
                        <i class="bi bi-thermometer-sun" style="font-size: 4rem;"></i>
                    </div>
                </div>

                <div class="alert alert-primary">
                    <i class="bi bi-lightbulb"></i>
                    <strong>Tip:</strong> Las temperaturas se utilizan para clasificar las proformas segun la probalidad de exito de concretar la pósterior venta.
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Importante:</strong> Al eliminar una temperatura, todas las proformas asociadas perderán su clasificación de temperatura.
                </div>

                <div class="mt-3">
                    <h6 class="mb-2">Datos de la Temperatura:</h6>
                    <ul class="list-unstyled small">
                        <li><i class="bi bi-check-circle text-success"></i> ID: {{ $temperatura->id }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Nombre: {{ $temperatura->name }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Proformas: {{ $temperatura->proformas_count }}</li>
                        @if($temperatura->created_at)
                            <li><i class="bi bi-check-circle text-success"></i> Creado: {{ $temperatura->created_at->format('d/m/Y H:i') }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
