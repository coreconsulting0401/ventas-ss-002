@extends('layouts.app')

@section('title', 'Crear Contacto')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-person-lines-fill"></i> Nuevo Contacto
    </h2>
    <a href="{{ route('contactos.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Formulario de Registro</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('contactos.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="dni" class="form-label">
                            <i class="bi bi-credit-card"></i> DNI <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('dni') is-invalid @enderror"
                               id="dni"
                               name="dni"
                               value="{{ old('dni') }}"
                               placeholder="8 dígitos"
                               maxlength="8"
                               required
                               autofocus>
                        @error('dni')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Solo números, 8 dígitos</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">
                            <i class="bi bi-person"></i> Nombre <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('nombre') is-invalid @enderror"
                               id="nombre"
                               name="nombre"
                               value="{{ old('nombre') }}"
                               placeholder="Nombre"
                               maxlength="100"
                               required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="apellido_paterno" class="form-label">
                            <i class="bi bi-person"></i> Apellido Paterno <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('apellido_paterno') is-invalid @enderror"
                               id="apellido_paterno"
                               name="apellido_paterno"
                               value="{{ old('apellido_paterno') }}"
                               placeholder="Apellido Paterno"
                               maxlength="100"
                               required>
                        @error('apellido_paterno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="apellido_materno" class="form-label">
                            <i class="bi bi-person"></i> Apellido Materno <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('apellido_materno') is-invalid @enderror"
                               id="apellido_materno"
                               name="apellido_materno"
                               value="{{ old('apellido_materno') }}"
                               placeholder="Apellido Materno"
                               maxlength="100"
                               required>
                        @error('apellido_materno')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="telefono" class="form-label">
                            <i class="bi bi-telephone"></i> Teléfono <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('telefono') is-invalid @enderror"
                               id="telefono"
                               name="telefono"
                               value="{{ old('telefono') }}"
                               placeholder="Teléfono"
                               maxlength="15"
                               required>
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Solo números</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i> Email <span class="text-danger">*</span>
                        </label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="ejemplo@email.com"
                               maxlength="255"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="cargo" class="form-label">
                            <i class="bi bi-briefcase"></i> Cargo <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('cargo') is-invalid @enderror"
                               id="cargo"
                               name="cargo"
                               value="{{ old('cargo') }}"
                               placeholder="Cargo del contacto"
                               maxlength="50"
                               required>
                        @error('cargo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('contactos.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Guardar Contacto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
