@extends('layouts.app')

@section('title', 'Editar Rol')

@section('page-title')
<div class="section-header">
    <h2><i class="bi bi-pencil-square"></i> Editar Rol</h2>
    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')

<form action="{{ route('roles.update', $role) }}" method="POST" id="formRole">
    @csrf
    @method('PUT')

    <div class="row">
        {{-- Columna Izquierda --}}
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-fill-check"></i> Información del Rol
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-tag"></i> Nombre del Rol <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $role->name) }}"
                               placeholder="Ej: Supervisor"
                               required
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Nombre único que identifica este rol
                        </small>
                    </div>

                    @error('permissions')
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            {{-- Información adicional --}}
            <div class="card shadow-sm mb-4 bg-light">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="bi bi-info-circle"></i> Información
                    </h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">
                            <strong>Creado:</strong><br>
                            {{ $role->created_at->format('d/m/Y H:i') }}
                        </li>
                        <li class="mb-2">
                            <strong>Última actualización:</strong><br>
                            {{ $role->updated_at->format('d/m/Y H:i') }}
                        </li>
                        <li>
                            <strong>Usuarios con este rol:</strong>
                            <span class="badge bg-info">{{ $role->users->count() }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Botones --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <button type="submit" class="btn btn-warning w-100 btn-lg mb-2">
                        <i class="bi bi-save"></i> Actualizar Rol
                    </button>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </div>
        </div>

        {{-- Columna Derecha: Permisos --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-key-fill"></i> Permisos del Rol
                    </h5>
                    <button type="button" class="btn btn-sm btn-dark" onclick="toggleAllPermissions()">
                        <i class="bi bi-check-all"></i> Seleccionar Todo
                    </button>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        <i class="bi bi-info-circle"></i>
                        Modifica los permisos de este rol. Los cambios afectarán a todos los usuarios asignados.
                    </p>

                    <div class="row g-3">
                        @foreach($permissions as $module => $modulePermissions)
                            <div class="col-md-6">
                                <div class="card border h-100">
                                    <div class="card-header bg-light">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong class="text-primary">
                                                <i class="bi bi-folder"></i> {{ ucfirst($module) }}
                                            </strong>
                                            <div class="form-check">
                                                <input class="form-check-input module-checkbox"
                                                       type="checkbox"
                                                       id="module-{{ $module }}"
                                                       onchange="toggleModule('{{ $module }}')">
                                                <label class="form-check-label small" for="module-{{ $module }}">
                                                    Todo
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="list-group list-group-flush">
                                            @foreach($modulePermissions as $permission)
                                                <label class="list-group-item border-0 py-2">
                                                    <input class="form-check-input me-2 permission-checkbox permission-{{ $module }}"
                                                           type="checkbox"
                                                           name="permissions[]"
                                                           value="{{ $permission->name }}"
                                                           id="perm-{{ $permission->id }}"
                                                           {{ in_array($permission->name, old('permissions', $role->permissions->pluck('name')->toArray())) ? 'checked' : '' }}
                                                           onchange="updateModuleCheckbox('{{ $module }}')">
                                                    <span class="small">
                                                        @php
                                                            $action = explode(' ', $permission->name)[0];
                                                            $icons = [
                                                                'view' => 'bi-eye',
                                                                'create' => 'bi-plus-circle',
                                                                'edit' => 'bi-pencil',
                                                                'delete' => 'bi-trash'
                                                            ];
                                                        @endphp
                                                        <i class="bi {{ $icons[$action] ?? 'bi-key' }}"></i>
                                                        {{ ucfirst($action) }}
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Contador de permisos seleccionados --}}
                    <div class="mt-3 p-3 bg-light rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">
                                <i class="bi bi-info-circle"></i> Permisos seleccionados:
                            </span>
                            <span class="badge bg-primary" id="permCount">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
// Actualizar contador de permisos
function updatePermissionCount() {
    const count = document.querySelectorAll('input[name="permissions[]"]:checked').length;
    document.getElementById('permCount').textContent = count;
}

// Toggle todos los permisos
function toggleAllPermissions() {
    const allChecked = document.querySelectorAll('input[name="permissions[]"]:checked').length ===
                       document.querySelectorAll('input[name="permissions[]"]').length;

    document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
        checkbox.checked = !allChecked;
    });

    document.querySelectorAll('.module-checkbox').forEach(checkbox => {
        checkbox.checked = !allChecked;
    });

    updatePermissionCount();
}

// Toggle permisos de un módulo completo
function toggleModule(module) {
    const moduleCheckbox = document.getElementById(`module-${module}`);
    const isChecked = moduleCheckbox.checked;

    document.querySelectorAll(`.permission-${module}`).forEach(checkbox => {
        checkbox.checked = isChecked;
    });

    updatePermissionCount();
}

// Actualizar checkbox del módulo cuando cambian los permisos individuales
function updateModuleCheckbox(module) {
    const modulePermissions = document.querySelectorAll(`.permission-${module}`);
    const checkedPermissions = document.querySelectorAll(`.permission-${module}:checked`);
    const moduleCheckbox = document.getElementById(`module-${module}`);

    moduleCheckbox.checked = modulePermissions.length === checkedPermissions.length;
    moduleCheckbox.indeterminate = checkedPermissions.length > 0 &&
                                   checkedPermissions.length < modulePermissions.length;

    updatePermissionCount();
}

// Event listener para todos los checkboxes de permisos
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updatePermissionCount);
    });

    // Actualizar contador inicial
    updatePermissionCount();

    // Actualizar estado inicial de checkboxes de módulo
    @foreach($permissions as $module => $modulePermissions)
        updateModuleCheckbox('{{ $module }}');
    @endforeach
});

// Validación antes de enviar
document.getElementById('formRole').addEventListener('submit', function(e) {
    const permissionsChecked = document.querySelectorAll('input[name="permissions[]"]:checked');

    if (permissionsChecked.length === 0) {
        e.preventDefault();
        alert('Debe seleccionar al menos un permiso para el rol');
        return false;
    }

    const name = document.getElementById('name').value.trim();
    if (!name) {
        e.preventDefault();
        alert('Debe ingresar un nombre para el rol');
        return false;
    }
});
</script>
@endpush
