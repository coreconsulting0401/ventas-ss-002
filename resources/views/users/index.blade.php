@extends('layouts.app')

@section('title', 'Usuarios')

@section('page-title')
<div class="section-header">
    <h2><i class="bi bi-people-fill"></i> Gestión de Usuarios</h2>
    @can('create users')
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Usuario
    </a>
    @endcan
</div>
@endsection

@section('content')

{{-- Alertas --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Filtros --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('users.index') }}" method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold">
                        <i class="bi bi-search"></i> Buscar
                    </label>
                    <input type="text" name="search" class="form-control"
                           value="{{ request('search') }}"
                           placeholder="Nombre, email, DNI, código...">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">
                        <i class="bi bi-shield-fill-check"></i> Rol
                    </label>
                    <select name="role" class="form-select">
                        <option value="">Todos los roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}"
                                {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Tabla de usuarios --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Listado de Usuarios</h5>
        <span class="badge bg-secondary">{{ $users->total() }} usuarios</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>DNI</th>
                        <th>Teléfono</th>
                        <th>Roles</th>
                        <th>Proformas</th>
                        <th class="text-center" style="width:180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <span class="badge bg-dark">{{ $user->codigo }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-2">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <strong>{{ $user->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->dni }}</td>
                        <td>{{ $user->telefono_user ?? '—' }}</td>
                        <td>
                            @forelse($user->roles as $role)
                                <span class="badge
                                    @if($role->name == 'Administrador') bg-danger
                                    @elseif($role->name == 'Vendedor') bg-primary
                                    @elseif($role->name == 'Almacén') bg-success
                                    @else bg-secondary
                                    @endif">
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span class="badge bg-warning">Sin rol</span>
                            @endforelse
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ $user->proformas_count ?? 0 }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                @can('view users')
                                <a href="{{ route('users.show', $user) }}"
                                   class="btn btn-info" title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @endcan

                                @can('edit users')
                                <a href="{{ route('users.edit', $user) }}"
                                   class="btn btn-warning" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan

                                @can('delete users')
                                    @if($user->id !== auth()->id())
                                    <button type="button" class="btn btn-danger" title="Eliminar"
                                            onclick="confirmarEliminar({{ $user->id }}, '{{ $user->name }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-people" style="font-size:3rem;color:#ccc;"></i>
                            <p class="mt-2 text-muted">No se encontraron usuarios</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <p class="mb-0 text-muted small">
                Mostrando {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }}
                de <strong>{{ $users->total() }}</strong> usuarios
            </p>
            {{ $users->links() }}
        </div>
    </div>
</div>

{{-- Form de eliminación oculto --}}
<form id="formEliminar" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('styles')
<style>
.avatar-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6c5dd3, #4f8ef7);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
}
</style>
@endpush

@push('scripts')
<script>
function confirmarEliminar(userId, userName) {
    if (confirm(`¿Está seguro de eliminar al usuario "${userName}"?\n\nEsta acción no se puede deshacer.`)) {
        const form = document.getElementById('formEliminar');
        form.action = `/users/${userId}`;
        form.submit();
    }
}
</script>
@endpush
