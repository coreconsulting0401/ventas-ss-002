{{--
    VISTA: resources/views/profile/edit.blade.php
    Extiende el layout principal del sistema (layouts.app con Bootstrap 5 + Bootstrap Icons).
    Contiene tres secciones colapsables:
      1. Información personal (name, email, dni, telefono_user)
      2. Cambiar contraseña
      3. Eliminar cuenta
--}}
@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('page-title')
<div class="section-header">
    <h2><i class="bi bi-person-circle"></i> Mi Perfil</h2>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Volver al Dashboard
    </a>
</div>
@endsection

@push('styles')
<style>
.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6c5dd3, #4f8ef7);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 2rem;
    text-transform: uppercase;
    box-shadow: 0 4px 14px rgba(108, 93, 211, .35);
}
.profile-card-header {
    background: linear-gradient(135deg, #6c5dd3 0%, #4f8ef7 100%);
    color: white;
    border-radius: 12px 12px 0 0 !important;
    padding: 1.1rem 1.5rem;
}
.profile-card-header h5 { margin: 0; font-size: .92rem; font-weight: 700; letter-spacing: .03em; }
.section-divider {
    border: none;
    border-top: 1px solid #e9ecef;
    margin: 1.5rem 0;
}
.form-label { font-weight: 600; font-size: .83rem; color: #374151; margin-bottom: 4px; }
.form-control, .form-select { font-size: .88rem; border-radius: 8px; }
.form-control:focus { border-color: #6c5dd3; box-shadow: 0 0 0 .2rem rgba(108,93,211,.18); }
.input-icon-wrap { position: relative; }
.input-icon-wrap .bi {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: .95rem;
    pointer-events: none;
}
.input-icon-wrap .form-control { padding-left: 2.1rem; }
.btn-save {
    background: linear-gradient(135deg, #6c5dd3, #4f8ef7);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 8px 22px;
    font-weight: 600;
    font-size: .85rem;
    transition: opacity .2s;
}
.btn-save:hover { opacity: .88; color: white; }
.alert-success-inline {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #d1fae5;
    color: #065f46;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: .8rem;
    font-weight: 600;
    animation: fadeOut 3s ease forwards;
    animation-delay: .5s;
}
@keyframes fadeOut { 0%{opacity:1} 80%{opacity:1} 100%{opacity:0;visibility:hidden} }
.role-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 12px;
    border-radius: 20px;
    font-size: .73rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
}
</style>
@endpush

@section('content')
@php
    $user = Auth::user();
    $rol  = $user->getRoleNames()->first() ?? 'Sin rol';
@endphp

<div class="row g-4">

    {{-- ── Columna izquierda: Tarjeta de identidad ── --}}
    <div class="col-lg-3">
        <div class="card text-center p-4 h-100">
            <div class="profile-avatar mx-auto mb-3">{{ substr($user->name, 0, 2) }}</div>
            <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
            <p class="text-muted small mb-3">{{ $user->email }}</p>

            @php
                $rolColor = match(true) {
                    str_contains($rol,'Administrador') => 'bg-danger text-white',
                    str_contains($rol,'Gerente')       => 'bg-warning text-dark',
                    str_contains($rol,'Vendedor')      => 'bg-primary text-white',
                    str_contains($rol,'Almacén')       => 'bg-success text-white',
                    default                            => 'bg-secondary text-white',
                };
            @endphp
            <span class="role-chip {{ $rolColor }} mx-auto">
                <i class="bi bi-shield-fill"></i> {{ $rol }}
            </span>

            <hr class="section-divider">

            <ul class="list-unstyled text-start small text-muted">
                <li class="mb-2">
                    <i class="bi bi-credit-card me-2 text-primary"></i>
                    <strong>DNI:</strong> {{ $user->dni }}
                </li>
                <li class="mb-2">
                    <i class="bi bi-upc-scan me-2 text-primary"></i>
                    <strong>Código:</strong> {{ $user->codigo ?? '—' }}
                </li>
                <li class="mb-2">
                    <i class="bi bi-telephone me-2 text-primary"></i>
                    <strong>Teléfono:</strong> {{ $user->telefono_user ?? '—' }}
                </li>
                <li class="mb-2">
                    <i class="bi bi-calendar me-2 text-primary"></i>
                    <strong>Miembro desde:</strong><br>
                    {{ $user->created_at->format('d/m/Y') }}
                </li>
                <li>
                    <i class="bi bi-file-earmark-text me-2 text-primary"></i>
                    <strong>Proformas:</strong> {{ $user->proformas()->count() }}
                </li>
            </ul>
        </div>
    </div>

    {{-- ── Columna derecha: formularios ── --}}
    <div class="col-lg-9">

        {{-- ══ 1. INFORMACIÓN PERSONAL ══ --}}
        <div class="card mb-4">
            <div class="profile-card-header d-flex align-items-center gap-2">
                <i class="bi bi-person-vcard-fill fs-5"></i>
                <h5>Información Personal</h5>
            </div>
            <div class="card-body">
                @if(session('status') === 'profile-updated')
                    <div class="alert-success-inline mb-3">
                        <i class="bi bi-check-circle-fill"></i> Perfil actualizado correctamente
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="row g-3">
                        {{-- Nombre --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                <i class="bi bi-person text-muted me-1"></i>Nombre completo <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="name" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}"
                                   required autofocus>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope text-muted me-1"></i>Correo electrónico <span class="text-danger">*</span>
                            </label>
                            <input type="email" id="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- DNI --}}
                        <div class="col-md-6">
                            <label for="dni" class="form-label">
                                <i class="bi bi-credit-card text-muted me-1"></i>DNI <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="dni" name="dni"
                                   class="form-control @error('dni') is-invalid @enderror"
                                   value="{{ old('dni', $user->dni) }}"
                                   maxlength="8" inputmode="numeric"
                                   required>
                            <div class="form-text">Exactamente 8 dígitos numéricos.</div>
                            @error('dni')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Teléfono --}}
                        <div class="col-md-6">
                            <label for="telefono_user" class="form-label">
                                <i class="bi bi-telephone text-muted me-1"></i>Teléfono
                            </label>
                            <input type="text" id="telefono_user" name="telefono_user"
                                   class="form-control @error('telefono_user') is-invalid @enderror"
                                   value="{{ old('telefono_user', $user->telefono_user) }}"
                                   maxlength="14" inputmode="numeric"
                                   placeholder="Ej: 51987654321">
                            <div class="form-text">Solo números, máximo 14 dígitos.</div>
                            @error('telefono_user')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 mt-4">
                        <button type="submit" class="btn btn-save">
                            <i class="bi bi-save me-1"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══ 2. CAMBIAR CONTRASEÑA ══ --}}
        <div class="card mb-4">
            <div class="card-header" style="background:#f8fafc;border-bottom:1px solid #e9ecef;">
                <h5 class="mb-0" style="font-size:.9rem;font-weight:700;color:#374151;">
                    <i class="bi bi-key-fill text-warning me-2"></i>Cambiar Contraseña
                </h5>
            </div>
            <div class="card-body">
                @if(session('status') === 'password-updated')
                    <div class="alert-success-inline mb-3">
                        <i class="bi bi-check-circle-fill"></i> Contraseña actualizada correctamente
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="current_password" class="form-label">Contraseña actual <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="current_password" name="current_password"
                                       class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                       autocomplete="current-password">
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePwd('current_password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('current_password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="new_password" class="form-label">Nueva contraseña <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="new_password" name="password"
                                       class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePwd('new_password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">Mínimo 8 caracteres.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="password_confirmation" class="form-label">Confirmar contraseña <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control"
                                       autocomplete="new-password">
                                <button class="btn btn-outline-secondary" type="button"
                                        onclick="togglePwd('password_confirmation', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-warning fw-semibold" style="border-radius:8px;font-size:.85rem;">
                            <i class="bi bi-lock-fill me-1"></i> Actualizar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══ 3. ELIMINAR CUENTA ══ --}}
        <div class="card border-danger" style="border-width:1px;">
            <div class="card-header" style="background:#fff1f2;border-bottom:1px solid #fca5a5;">
                <h5 class="mb-0 text-danger" style="font-size:.9rem;font-weight:700;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Zona de Peligro — Eliminar Cuenta
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Una vez que elimines tu cuenta, todos tus datos serán eliminados permanentemente.
                    Esta acción <strong>no puede deshacerse</strong>.
                </p>
                <button type="button" class="btn btn-danger btn-sm fw-semibold"
                        style="border-radius:8px;"
                        data-bs-toggle="modal" data-bs-target="#modalEliminarCuenta">
                    <i class="bi bi-trash3-fill me-1"></i> Eliminar mi cuenta
                </button>
            </div>
        </div>

    </div>{{-- /col-lg-9 --}}
</div>{{-- /row --}}

{{-- ── Modal confirmar eliminación ── --}}
<div class="modal fade" id="modalEliminarCuenta" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:12px;overflow:hidden;">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalEliminarLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Por seguridad, ingresa tu contraseña actual para confirmar que deseas eliminar permanentemente tu cuenta.
                    </p>
                    <label for="delete_password" class="form-label fw-semibold">Contraseña actual</label>
                    <div class="input-group">
                        <input type="password" id="delete_password" name="password"
                               class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                               placeholder="••••••••">
                        <button class="btn btn-outline-secondary" type="button"
                                onclick="togglePwd('delete_password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger btn-sm fw-semibold">
                        <i class="bi bi-trash3-fill me-1"></i> Sí, eliminar mi cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Abrir el modal si hay errores de eliminación --}}
@if($errors->userDeletion->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('modalEliminarCuenta')).show();
    });
</script>
@endif
@endsection

@push('scripts')
<script>
// Solo números para DNI y teléfono
['dni','telefono_user'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});

// Toggle visibilidad de contraseña
function togglePwd(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
@endpush
