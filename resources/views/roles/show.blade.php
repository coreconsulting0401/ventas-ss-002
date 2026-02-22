@extends('layouts.app')

@section('title', 'Detalles del Rol')

@section('page-title')
<div class="section-header">
    <h2><i class="bi bi-shield-check"></i> Detalles del Rol</h2>
    <div class="d-flex gap-2">
        @can('edit roles')
        <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        @endcan
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>
@endsection

@section('content')

<div class="row">
    {{-- Columna Izquierda --}}
    <div class="col-lg-4">
        {{-- Tarjeta Principal del Rol --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center py-4">
                <div class="role-icon mx-auto mb-3">
                    <i class="bi bi-shield-fill"></i>
                </div>
                <h3 class="mb-2">{{ $role->name }}</h3>

                <div class="row text-center mt-4">
                    <div class="col-6">
                        <h4 class="mb-0 text-primary">{{ $role->users->count() }}</h4>
                        <small class="text-muted">Usuarios</small>
                    </div>
                    <div class="col-6">
                        <h4 class="mb-0 text-success">{{ $role->permissions->count() }}</h4>
                        <small class="text-muted">Permisos</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Información del Rol --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle"></i> Información
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted">
                            <i class="bi bi-calendar-plus"></i> Creado
                        </td>
                        <td>{{ $role->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i class="bi bi-calendar-check"></i> Actualizado
                        </td>
                        <td>{{ $role->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i class="bi bi-people"></i> Usuarios
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $role->users->count() }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <i class="bi bi-key"></i> Permisos
                        </td>
                        <td>
                            <span class="badge bg-success">{{ $role->permissions->count() }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Protección del Sistema --}}
        @php
            $rolesProtegidos = ['Administrador', 'Vendedor', 'Almacén', 'Visualizador'];
        @endphp
        @if(in_array($role->name, $rolesProtegidos))
        <div class="alert alert-warning">
            <i class="bi bi-shield-lock-fill"></i>
            <strong>Rol del Sistema</strong><br>
            <small>Este rol está protegido y no puede ser eliminado.</small>
        </div>
        @endif
    </div>

    {{-- Columna Derecha --}}
    <div class="col-lg-8">
        {{-- Permisos Agrupados --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-key-fill"></i> Permisos Asignados
                </h5>
                <span class="badge bg-light text-dark">
                    {{ $role->permissions->count() }} permisos
                </span>
            </div>
            <div class="card-body">
                @if($permissionsByModule->count() > 0)
                    <div class="row g-3">
                        @foreach($permissionsByModule as $module => $perms)
                            <div class="col-md-6">
                                <div class="card border h-100">
                                    <div class="card-header bg-light">
                                        <strong class="text-primary">
                                            <i class="bi bi-folder"></i> {{ ucfirst($module) }}
                                        </strong>
                                        <span class="badge bg-primary float-end">
                                            {{ $perms->count() }}
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled mb-0">
                                            @foreach($perms as $perm)
                                                <li class="mb-2">
                                                    @php
                                                        $action = explode(' ', $perm->name)[0];
                                                        $icons = [
                                                            'view' => ['bi-eye', 'text-info'],
                                                            'create' => ['bi-plus-circle', 'text-success'],
                                                            'edit' => ['bi-pencil', 'text-warning'],
                                                            'delete' => ['bi-trash', 'text-danger']
                                                        ];
                                                        $icon = $icons[$action] ?? ['bi-key', 'text-secondary'];
                                                    @endphp
                                                    <i class="bi {{ $icon[0] }} {{ $icon[1] }}"></i>
                                                    {{ ucfirst($action) }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        Este rol no tiene permisos asignados
                    </div>
                @endif
            </div>
        </div>

        {{-- Usuarios con Este Rol --}}
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-people-fill"></i> Usuarios con Este Rol
                </h5>
                <span class="badge bg-light text-dark">
                    {{ $role->users->count() }} usuarios
                </span>
            </div>
            <div class="card-body p-0">
                @if($role->users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>DNI</th>
                                    <th>Código</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($role->users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-small me-2">
                                                {{ substr($user->name, 0, 2) }}
                                            </div>
                                            <strong>{{ $user->name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->dni }}</td>
                                    <td>
                                        <span class="badge bg-dark">{{ $user->codigo }}</span>
                                    </td>
                                    <td class="text-center">
                                        @can('view users')
                                        <a href="{{ route('users.show', $user) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Ver
                                        </a>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-people-fill" style="font-size: 3rem;"></i>
                        <p class="mt-2">No hay usuarios asignados a este rol</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.role-icon {
    width: 100px;
    height: 100px;
    border-radius: 20px;
    background: linear-gradient(135deg, #6c5dd3, #4f8ef7);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    box-shadow: 0 4px 12px rgba(108, 93, 211, 0.3);
}

.avatar-small {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6c5dd3, #4f8ef7);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
}
</style>
@endpush
