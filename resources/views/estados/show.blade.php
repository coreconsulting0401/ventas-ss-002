@extends('layouts.app')

@section('title', 'Detalle de Estado')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-flag"></i> Detalle de Estado
    </h2>
    <div class="btn-group">
        @haspermission('edit estados')
        <a href="{{ route('estados.edit', $estado) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        @endhaspermission
        <a href="{{ route('estados.index') }}" class="btn btn-secondary">
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
                    <i class="bi bi-flag-fill"></i> Información del Estado
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>ID:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-secondary">{{ $estado->id }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Nombre:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-primary fs-5">
                            <i class="bi bi-flag"></i> {{ $estado->name }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Número de Proformas:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($estado->proformas_count > 0)
                            <span class="badge bg-success">
                                <i class="bi bi-file-earmark-text"></i> {{ $estado->proformas_count }} proforma(s)
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
                        @if($estado->created_at)
                            <i class="bi bi-calendar-check"></i>
                            {{ $estado->created_at->format('d/m/Y H:i:s') }}
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
                        @if($estado->updated_at)
                            <i class="bi bi-calendar-x"></i>
                            {{ $estado->updated_at->format('d/m/Y H:i:s') }}
                        @else
                            <i class="bi bi-calendar-x"></i>
                            <span class="text-muted">No disponible</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">

                    @haspermission('delete estados')
                    <form action="{{ route('estados.destroy', $estado) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('¿Está seguro de eliminar este estado?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Eliminar Estado
                        </button>
                    </form>
                    @endhaspermission
                    <a href="{{ route('estados.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>
        </div>

        <!-- Sección de Proformas Relacionadas -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text"></i> Proformas Asociadas ({{ $estado->proformas->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($estado->proformas->count() > 0)
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
                                @foreach($estado->proformas->take(5) as $proforma)
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
                    @if($estado->proformas->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('proformas.index') }}?estado_id={{ $estado->id }}" class="btn btn-sm btn-primary">
                                Ver todas las proformas ({{ $estado->proformas->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="mt-2 text-muted">Este estado no tiene proformas asociadas</p>
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
                        <i class="bi bi-flag" style="font-size: 4rem;"></i>
                    </div>
                </div>

                <div class="alert alert-primary">
                    <i class="bi bi-lightbulb"></i>
                    <strong>Tip:</strong> Los estados ayudan a clasificar y organizar las proformas según su situación actual.
                </div>

                @haspermission('delete estados')
                <div class="alert alert-info">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Importante:</strong> Al eliminar un estado, todas las proformas asociadas perderán su estado actual.
                </div>
                @endhaspermission
                <div class="mt-3">
                    <h6 class="mb-2">Datos del Estado:</h6>
                    <ul class="list-unstyled small">
                        <li><i class="bi bi-check-circle text-success"></i> ID: {{ $estado->id }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Nombre: {{ $estado->name }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Proformas: {{ $estado->proformas_count }}</li>
                        @if($estado->created_at)
                            <li><i class="bi bi-check-circle text-success"></i> Creado: {{ $estado->created_at->format('d/m/Y H:i') }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
