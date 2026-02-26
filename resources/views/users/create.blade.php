@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('page-title')
<div class="section-header">
    <h2><i class="bi bi-person-plus-fill"></i> Crear Usuario</h2>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')

<form action="{{ route('users.store') }}" method="POST" id="formUser">
    @csrf

    <div class="row">
        {{-- Columna Izquierda: Datos del Usuario --}}
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-vcard"></i> Información del Usuario</h5>
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
                               value="{{ old('name') }}"
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
                                   value="{{ old('dni') }}"
                                   maxlength="8"
                                   pattern="[0-9]{8}"
                                   required>
                            <small class="text-muted">8 dígitos</small>
                            @error('dni')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Código (auto-generado) --}}
                        <div class="col-md-6 mb-3">
                            <label for="codigo" class="form-label">
                                <i class="bi bi-upc-scan"></i> Código <span class="text-muted">(Auto-generado)</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('codigo') is-invalid @enderror"
                                   id="codigo"
                                   name="codigo"
                                   value="{{ old('codigo') }}"
                                   readonly
                                   style="background-color: #f8f9fa;">
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i> Se genera al seleccionar un rol
                            </small>
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
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Teléfono --}}
                    <div class="mb-3">
                        <label for="telefono_user" class="form-label">
                            <i class="bi bi-telephone"></i> Teléfono
                        </label>
                        <input type="text"
                               class="form-control @error('telefono_user') is-invalid @enderror"
                               id="telefono_user"
                               name="telefono_user"
                               value="{{ old('telefono_user') }}"
                               maxlength="14"
                               inputmode="numeric"
                               placeholder="Ej: 51987654321">
                        <small class="text-muted">Solo números, máximo 14 dígitos</small>
                        @error('telefono_user')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-key"></i> Contraseña <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       required>
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePassword('password')">
                                    <i class="bi bi-eye" id="password-icon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Mínimo 8 caracteres</small>
                        </div>

                        {{-- Confirmar Password --}}
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-key"></i> Confirmar Contraseña <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       required>
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
                        Seleccione al menos un rol (el código se generará automáticamente)
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
                                    <input class="form-check-input me-2 mt-1 flex-shrink-0 role-checkbox"
                                           type="checkbox"
                                           name="roles[]"
                                           value="{{ $role->name }}"
                                           id="role-{{ $role->id }}"
                                           data-role-name="{{ $role->name }}"
                                           onchange="generarCodigo()"
                                           {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}>
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

            {{-- Botones de acción --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 btn-lg mb-2">
                        <i class="bi bi-save"></i> Guardar Usuario
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Datos para JavaScript --}}
<div id="js-data"
     data-next-user-id="{{ \App\Models\User::max('id') + 1 }}"
     style="display:none;">
</div>

@endsection

@push('scripts')
<script>
const nextUserId = document.getElementById('js-data').dataset.nextUserId;

/**
 * Generar código: [3 letras del rol]-[ID]
 * Ejemplo: VEN-002, ADM-003
 */
function generarCodigo() {
    const rolesChecked = document.querySelectorAll('.role-checkbox:checked');
    const codigoInput = document.getElementById('codigo');

    if (rolesChecked.length === 0) {
        codigoInput.value = '';
        return;
    }

    const primerRol = rolesChecked[0].dataset.roleName;
    const prefijo = primerRol.substring(0, 3).toUpperCase();
    const idFormateado = String(nextUserId).padStart(3, '0');
    const codigo = `${prefijo}-${idFormateado}`;

    codigoInput.value = codigo;
    codigoInput.classList.add('border-success');
    setTimeout(() => codigoInput.classList.remove('border-success'), 1000);
}

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

document.getElementById('dni').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

document.getElementById('telefono_user').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

document.getElementById('formUser').addEventListener('submit', function(e) {
    const rolesChecked = document.querySelectorAll('input[name="roles[]"]:checked');

    if (rolesChecked.length === 0) {
        e.preventDefault();
        alert('Debe seleccionar al menos un rol para el usuario');
        return false;
    }

    const password = document.getElementById('password').value;
    const confirmation = document.getElementById('password_confirmation').value;

    if (password !== confirmation) {
        e.preventDefault();
        alert('Las contraseñas no coinciden');
        return false;
    }

    const codigo = document.getElementById('codigo').value;
    if (!codigo) {
        e.preventDefault();
        alert('No se ha generado el código. Seleccione un rol.');
        return false;
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const rolesChecked = document.querySelectorAll('.role-checkbox:checked');
    if (rolesChecked.length > 0) generarCodigo();
});
</script>
@endpush

@push('styles')
<style>
#codigo { transition: border-color 0.3s ease; }
#codigo.border-success { border-width: 2px !important; }
#codigo:read-only { cursor: not-allowed; }
</style>
@endpush
