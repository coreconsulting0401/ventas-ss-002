@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('page-title')
<div class="section-header">
    <h2><i class="bi bi-pencil-square"></i> Editar Usuario</h2>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')

<form action="{{ route('users.update', $user) }}" method="POST" id="formUser">
    @csrf
    @method('PUT')

    <div class="row">
        {{-- Columna Izquierda: Datos del Usuario --}}
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-person-vcard"></i> Información del Usuario
                    </h5>
                </div>
                <div class="card-body">

                    {{-- Nombre completo --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-person"></i> Nombre Completo <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $user->name) }}"
                               required
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        {{-- DNI --}}
                        <div class="col-md-6 mb-3">
                            <label for="dni" class="form-label">
                                <i class="bi bi-credit-card"></i> DNI <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('dni') is-invalid @enderror"
                                   id="dni"
                                   name="dni"
                                   value="{{ old('dni', $user->dni) }}"
                                   maxlength="8"
                                   pattern="[0-9]{8}"
                                   required>
                            <small class="text-muted">8 dígitos</small>
                            @error('dni')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Código --}}
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label">
                                <i class="bi bi-upc-scan"></i> Código
                            </label>
                            <input type="text"
                                   class="form-control @error('codigo') is-invalid @enderror"
                                   id="codigo"
                                   name="codigo"
                                   value="{{ old('codigo', $user->codigo) }}"
                                   placeholder="Ej: USR-001">
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i> Email <span class="text-danger">*</span>
                        </label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Cambiar Contraseña --}}
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Cambiar contraseña:</strong> Deja estos campos vacíos si no deseas cambiar la contraseña actual
                    </div>

                    <div class="row">
                        {{-- Nueva Password --}}
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-key"></i> Nueva Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password">
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password')">
                                    <i class="bi bi-eye" id="password-icon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Mínimo 8 caracteres (dejar vacío para no cambiar)</small>
                        </div>

                        {{-- Confirmar Nueva Password --}}
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-key"></i> Confirmar Nueva Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation">
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password_confirmation')">
                                    <i class="bi bi-eye" id="password_confirmation-icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Columna Derecha: Roles --}}
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-shield-fill-check"></i> Roles y Permisos
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        <i class="bi bi-info-circle"></i>
                        Seleccione al menos un rol para el usuario
                    </p>

                    @error('roles')
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> {{ $message }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @enderror

                    <div class="list-group">
                        @foreach($roles as $role)
                            <label class="list-group-item">
                                <div class="d-flex align-items-start">
                                    <input class="form-check-input me-2 mt-1 flex-shrink-0"
                                           type="checkbox"
                                           name="roles[]"
                                           value="{{ $role->name }}"
                                           id="role-{{ $role->id }}"
                                           {{ in_array($role->name, old('roles', $user->roles->pluck('name')->toArray())) ? 'checked' : '' }}>
                                    <div class="flex-grow-1">
                                        <strong class="d-block">{{ $role->name }}</strong>
                                        <small class="text-muted">
                                            {{ $role->permissions->count() }} permisos
                                        </small>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Info del usuario --}}
            <div class="card shadow-sm mb-4 bg-light">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="bi bi-info-circle"></i> Información Adicional
                    </h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">
                            <strong>Creado:</strong><br>
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </li>
                        <li class="mb-2">
                            <strong>Última actualización:</strong><br>
                            {{ $user->updated_at->format('d/m/Y H:i') }}
                        </li>
                        <li>
                            <strong>Proformas creadas:</strong>
                            <span class="badge bg-info">{{ $user->proformas->count() }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Botones de acción --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <button type="submit" class="btn btn-warning w-100 btn-lg mb-2">
                        <i class="bi bi-save"></i> Actualizar Usuario
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
// Toggle mostrar/ocultar contraseña
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '-icon');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

// Solo números en DNI
document.getElementById('dni').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

// Validación antes de enviar
document.getElementById('formUser').addEventListener('submit', function(e) {
    const rolesChecked = document.querySelectorAll('input[name="roles[]"]:checked');

    if (rolesChecked.length === 0) {
        e.preventDefault();
        alert('Debe seleccionar al menos un rol para el usuario');
        return false;
    }

    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;

    // Solo validar si se está intentando cambiar la contraseña
    if (password || confirmation) {
        if (password !== confirmation) {
            e.preventDefault();
            alert('Las contraseñas no coinciden');
            return false;
        }
    }
});
</script>
@endpush
