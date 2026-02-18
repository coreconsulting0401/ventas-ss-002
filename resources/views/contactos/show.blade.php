@extends('layouts.app')

@section('title', 'Detalle de Contacto')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-person-lines-fill"></i> Detalle de Contacto
    </h2>
    <div class="btn-group">
        <a href="{{ route('contactos.edit', $contacto) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('contactos.index') }}" class="btn btn-secondary">
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
                    <i class="bi bi-person-badge"></i> Información del Contacto
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>DNI:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-info fs-5">
                            <i class="bi bi-credit-card"></i> {{ $contacto->dni }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Nombre Completo:</strong>
                    </div>
                    <div class="col-md-8">
                        <h5 class="mb-0">
                            {{ $contacto->nombre }} {{ $contacto->apellido_paterno }} {{ $contacto->apellido_materno }}
                        </h5>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Teléfono:</strong>
                    </div>
                    <div class="col-md-8">
                        <i class="bi bi-telephone"></i> {{ $contacto->telefono }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Email:</strong>
                    </div>
                    <div class="col-md-8">
                        <i class="bi bi-envelope"></i> {{ $contacto->email }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Cargo:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-warning text-dark">
                            <i class="bi bi-briefcase"></i> {{ $contacto->cargo }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Fecha de Creación:</strong>
                    </div>
                    <div class="col-md-8">
                        <i class="bi bi-calendar-check"></i>
                        {{ $contacto->created_at->format('d/m/Y H:i:s') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Última Actualización:</strong>
                    </div>
                    <div class="col-md-8">
                        <i class="bi bi-calendar-x"></i>
                        {{ $contacto->updated_at->format('d/m/Y H:i:s') }}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <form action="{{ route('contactos.destroy', $contacto) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('¿Está seguro de eliminar este contacto?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Eliminar Contacto
                        </button>
                    </form>
                    <a href="{{ route('contactos.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>
        </div>

        <!-- Sección de Clientes Relacionados -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-people"></i> Clientes Asociados ({{ $contacto->clientes->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($contacto->clientes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>RUC</th>
                                    <th>Razón Social</th>
                                    <th>Teléfono</th>
                                    <th>Categoría</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contacto->clientes as $cliente)
                                <tr>
                                    <td>
                                        <span class="badge bg-info">{{ $cliente->ruc }}</span>
                                    </td>
                                    <td>{{ $cliente->razon }}</td>
                                    <td>
                                        <i class="bi bi-telephone"></i> {{ $cliente->telefono1 }}
                                    </td>
                                    <td>
                                        @if($cliente->categoria)
                                            <span class="badge bg-primary">{{ $cliente->categoria->name }}</span>
                                        @else
                                            <span class="badge bg-secondary">Sin categoría</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('clientes.show', $cliente) }}"
                                           class="btn btn-sm btn-info"
                                           title="Ver cliente">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="mt-2 text-muted">Este contacto no está asociado a ningún cliente</p>
                        <a href="{{ route('clientes.create') }}" class="btn btn-primary mt-2">
                            <i class="bi bi-plus-circle"></i> Crear Cliente y Asignar
                        </a>
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
                        <i class="bi bi-person-lines-fill" style="font-size: 4rem;"></i>
                    </div>
                </div>

                <div class="alert alert-primary">
                    <i class="bi bi-lightbulb"></i>
                    <strong>Tip:</strong> Los contactos pueden estar asociados a múltiples clientes y viceversa.
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Importante:</strong> Al eliminar un contacto, se eliminará su relación con todos los clientes asociados.
                </div>

                <div class="mt-3">
                    <h6 class="mb-2">Datos del Contacto:</h6>
                    <ul class="list-unstyled small">
                        <li><i class="bi bi-check-circle text-success"></i> DNI: {{ $contacto->dni }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Nombre: {{ $contacto->nombre }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Apellidos: {{ $contacto->apellido_paterno }} {{ $contacto->apellido_materno }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Teléfono: {{ $contacto->telefono }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Email: {{ $contacto->email }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Cargo: {{ $contacto->cargo }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
