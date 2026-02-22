@extends('layouts.app')

@section('title', 'Roles')

@section('page-title')
<div class="section-header">
    <h2><i class="bi bi-shield-fill-check"></i> Gestión de Roles</h2>
    @can('create roles')
    <a href="{{ route('roles.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Rol
    </a>
    @endcan
</div>
@endsection

@section('content')

{{-- Alertas --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    <i class="bi bi-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Grid de Roles --}}
<div class="row g-3">
    @forelse($roles as $role)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title mb-1">
                            <i class="bi bi-shield-fill text-primary"></i>
                            {{ $role->name }}
                        </h5>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-people"></i> {{ $role->users_count }} usuarios
                        </p>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @can('view roles')
                            <li>
                                <a class="dropdown-item" href="{{ route('roles.show', $role) }}">
                                    <i class="bi bi-eye"></i> Ver detalles
                                </a>
                            </li>
                            @endcan
                            @can('edit roles')
                            <li>
                                <a class="dropdown-item" href="{{ route('roles.edit', $role) }}">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                            </li>
                            @endcan
                            @can('delete roles')
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#"
                                   onclick="confirmarEliminar({{ $role->id }}, '{{ $role->name }}')">
                                    <i class="bi bi-trash"></i> Eliminar
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="badge bg-primary-subtle text-primary me-1 mb-1">
                        <i class="bi bi-key"></i> {{ $role->permissions->count() }} permisos
                    </div>
                </div>

                <div class="small text-muted">
                    <strong>Permisos destacados:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($role->permissions->take(3) as $permission)
                            <li>{{ $permission->name }}</li>
                        @endforeach
                        @if($role->permissions->count() > 3)
                            <li class="text-primary">+ {{ $role->permissions->count() - 3 }} más</li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="card-footer bg-light">
                <div class="d-flex gap-2">
                    @can('view roles')
                    <a href="{{ route('roles.show', $role) }}"
                       class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-eye"></i> Ver
                    </a>
                    @endcan
                    @can('edit roles')
                    <a href="{{ route('roles.edit', $role) }}"
                       class="btn btn-sm btn-outline-warning flex-fill">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="bi bi-shield-x" style="font-size:4rem;color:#ccc;"></i>
            <p class="text-muted mt-3">No hay roles creados</p>
        </div>
    </div>
    @endforelse
</div>

{{-- Paginación --}}
<div class="mt-4">
    {{ $roles->links() }}
</div>

{{-- Form de eliminación --}}
<form id="formEliminar" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
function confirmarEliminar(roleId, roleName) {
    if (confirm(`¿Eliminar el rol "${roleName}"?\n\nEsta acción no se puede deshacer.`)) {
        const form = document.getElementById('formEliminar');
        form.action = `/roles/${roleId}`;
        form.submit();
    }
}
</script>
@endpush
