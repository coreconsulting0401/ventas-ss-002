@extends('layouts.app')

@section('title', 'Detalles del Usuario')

@section('page-title')
<div class="section-header">
    <h2><i class="bi bi-person-circle"></i> Detalles del Usuario</h2>
    <div class="d-flex gap-2">
        @can('edit users')
        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        @endcan
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>
@endsection

@section('content')

<div class="row">
    {{-- Columna Izquierda --}}
    <div class="col-lg-4">
        {{-- Tarjeta de Perfil --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center py-4">
                <div class="avatar-large mx-auto mb-3">
                    {{ substr($user->name, 0, 2) }}
                </div>
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-3">{{ $user->email }}</p>

                <div class="d-flex justify-content-center gap-2 mb-3">
                    @foreach($user->roles as $role)
                        <span class="badge
                            @if($role->name == 'Administrador') bg-danger
                            @elseif($role->name == 'Vendedor') bg-primary
                            @elseif($role->name == 'Almacén') bg-success
                            @else bg-secondary
                            @endif px-3 py-2">
                            <i class="bi bi-shield-fill-check"></i>
                            {{ $role->name }}
                        </span>
                    @endforeach
                </div>

                <hr>

                <div class="row text-center">
                    <div class="col-6">
                        <h5 class="mb-0 text-primary">{{ $user->proformas->count() }}</h5>
                        <small class="text-muted">Proformas</small>
                    </div>
                    <div class="col-6">
                        <h5 class="mb-0 text-success">
                            {{ $user->roles->sum(fn($r) => $r->permissions->count()) }}
                        </h5>
                        <small class="text-muted">Permisos</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Información Personal --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-person-vcard"></i> Información Personal
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 40%;">
                            <i class="bi bi-credit-card"></i> DNI
                        </td>
                        <td><strong>{{ $user->dni }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i class="bi bi-upc-scan"></i> Código
                        </td>
                        <td><span class="badge bg-dark">{{ $user->codigo }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i class="bi bi-envelope"></i> Email
                        </td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i class="bi bi-calendar-plus"></i> Creado
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i class="bi bi-calendar-check"></i> Actualizado
                        </td>
                        <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Columna Derecha --}}
    <div class="col-lg-8">
        {{-- Roles y Permisos --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-shield-fill-check"></i> Roles y Permisos
                </h5>
                <span class="badge bg-dark">{{ $user->roles->count() }} roles</span>
            </div>
            <div class="card-body">
                @forelse($user->roles as $role)
                    <div class="card mb-3 border">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="bi bi-shield-fill text-primary"></i>
                                    {{ $role->name }}
                                </h6>
                                <span class="badge bg-primary">
                                    {{ $role->permissions->count() }} permisos
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                @php
                                    $groupedPerms = $role->permissions->groupBy(function($perm) {
                                        return explode(' ', $perm->name)[1] ?? 'otros';
                                    });
                                @endphp

                                @foreach($groupedPerms as $module => $perms)
                                    <div class="col-md-6">
                                        <div class="bg-light p-2 rounded">
                                            <strong class="text-primary small">
                                                <i class="bi bi-folder"></i> {{ ucfirst($module) }}
                                            </strong>
                                            <ul class="list-unstyled mb-0 mt-1 small ps-3">
                                                @foreach($perms as $perm)
                                                    <li>
                                                        <i class="bi bi-check-circle text-success"></i>
                                                        {{ explode(' ', $perm->name)[0] }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        Este usuario no tiene roles asignados
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Proformas Creadas --}}
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text"></i> Proformas Creadas
                </h5>
                <span class="badge bg-light text-dark">{{ $user->proformas->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($user->proformas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>N° Cotización</th>
                                    <th>Cliente</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->proformas->take(10) as $proforma)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">
                                            NCT-{{ str_pad($proforma->id, 11, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($proforma->cliente->razon ?? '—', 30) }}</td>
                                    <td>
                                        <strong>S/. {{ number_format($proforma->total, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $proforma->estado->name ?? 'Sin estado' }}
                                        </span>
                                    </td>
                                    <td>{{ $proforma->fecha_creacion->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('proformas.show', $proforma) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($user->proformas->count() > 10)
                        <div class="card-footer text-center">
                            <a href="{{ route('proformas.index', ['user_id' => $user->id]) }}"
                               class="btn btn-sm btn-outline-primary">
                                Ver todas las proformas ({{ $user->proformas->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                        <p class="mt-2">Este usuario aún no ha creado proformas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.avatar-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6c5dd3, #4f8ef7);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 2.5rem;
    text-transform: uppercase;
    box-shadow: 0 4px 12px rgba(108, 93, 211, 0.3);
}
</style>
@endpush
