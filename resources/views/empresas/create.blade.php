@extends('layouts.app')
@section('title', 'Crear Empresa')

@section('page-title')
    <i class="bi bi-building-add"></i> Crear Empresa
    <a href="{{ route('empresas.index') }}" class="btn btn-sm btn-secondary float-end">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
@endsection

@section('content')

{{--
    NOTA: Este formulario registra los datos básicos de la empresa en un ÚNICO registro.
    Las imágenes (logo, publicidad, condiciones, cuentas bancarias) se cargan DESPUÉS desde la vista de edición.
--}}

<form action="{{ route('empresas.store') }}" method="POST" id="formEmpresa">
    @csrf

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle-fill me-1"></i>
            <strong>Por favor corrija los siguientes errores:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ===================================================================
         INFORMACIÓN BÁSICA
    =================================================================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-building"></i> Información Básica</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="razon_social" class="form-label fw-semibold">
                        Razón Social <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           class="form-control @error('razon_social') is-invalid @enderror"
                           id="razon_social"
                           name="razon_social"
                           value="{{ old('razon_social') }}"
                           placeholder="Ej: Mi Empresa S.A.C."
                           required>
                    @error('razon_social')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="ruc" class="form-label fw-semibold">
                        <i class="bi bi-card-text"></i> RUC
                    </label>
                    <input type="text"
                           class="form-control @error('ruc') is-invalid @enderror"
                           id="ruc"
                           name="ruc"
                           value="{{ old('ruc') }}"
                           placeholder="Ej: 20512345678"
                           maxlength="11"
                           inputmode="numeric"
                           pattern="\d{11}"
                           title="El RUC debe tener exactamente 11 dígitos">
                    @error('ruc')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">11 dígitos numéricos</small>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="pagina_web" class="form-label fw-semibold">
                        <i class="bi bi-globe"></i> Página Web
                    </label>
                    <input type="url"
                           class="form-control @error('pagina_web') is-invalid @enderror"
                           id="pagina_web"
                           name="pagina_web"
                           value="{{ old('pagina_web') }}"
                           placeholder="https://www.miempresa.com">
                    @error('pagina_web')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label for="direccion" class="form-label fw-semibold">
                        <i class="bi bi-geo-alt"></i> Dirección
                    </label>
                    <textarea class="form-control @error('direccion') is-invalid @enderror"
                              id="direccion"
                              name="direccion"
                              rows="2"
                              placeholder="Av. Ejemplo 123, Ciudad">{{ old('direccion') }}</textarea>
                    @error('direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ===================================================================
         CORREOS ELECTRÓNICOS
    =================================================================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-envelope-fill"></i> Correos Electrónicos</h5>
            <button type="button" class="btn btn-sm btn-light" id="btnAgregarEmail">
                <i class="bi bi-plus-circle"></i> Agregar
            </button>
        </div>
        <div class="card-body">
            <div id="emailsContainer">
                <div class="email-item row g-2 mb-2 align-items-center" data-index="0">
                    <div class="col-md-4">
                        <input type="text" name="emails[0][area]"
                               class="form-control" placeholder="Área (Ej: Ventas)">
                    </div>
                    <div class="col-md-5">
                        <input type="email" name="emails[0][email]"
                               class="form-control" placeholder="correo@empresa.com">
                    </div>
                    <div class="col-md-2">
                        <div class="form-check">
                            <input type="checkbox" name="emails[0][activo]"
                                   class="form-check-input" value="1" checked id="emailActivo0">
                            <label class="form-check-label" for="emailActivo0">Activo</label>
                        </div>
                    </div>
                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-email" title="Eliminar fila">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </div>
            </div>
            <small class="text-muted">
                <i class="bi bi-info-circle"></i>
                Puede dejar estos campos vacíos y agregarlos desde la pantalla de edición.
            </small>
        </div>
    </div>

    {{-- ===================================================================
         TELÉFONOS
    =================================================================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-telephone-fill"></i> Teléfonos</h5>
            <button type="button" class="btn btn-sm btn-light" id="btnAgregarTelefono">
                <i class="bi bi-plus-circle"></i> Agregar
            </button>
        </div>
        <div class="card-body">
            <div id="telefonosContainer">
                <div class="telefono-item row g-2 mb-2 align-items-center" data-index="0">
                    <div class="col-md-3">
                        <input type="text" name="telefonos[0][area]"
                               class="form-control" placeholder="Área (Ej: Ventas)">
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="telefonos[0][telefono]"
                               class="form-control" placeholder="Número">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="telefonos[0][descripcion]"
                               class="form-control" placeholder="Descripción (Opcional)">
                    </div>
                    <div class="col-md-1">
                        <div class="form-check">
                            <input type="checkbox" name="telefonos[0][activo]"
                                   class="form-check-input" value="1" checked id="telActivo0">
                            <label class="form-check-label" for="telActivo0">Activo</label>
                        </div>
                    </div>
                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-telefono" title="Eliminar fila">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </div>
                </div>
            </div>
            <small class="text-muted">
                <i class="bi bi-info-circle"></i>
                Puede dejar estos campos vacíos y agregarlos desde la pantalla de edición.
            </small>
        </div>
    </div>

    {{-- ===================================================================
         NOTA SOBRE IMÁGENES
    =================================================================== --}}
    <div class="alert alert-info mb-4">
        <i class="bi bi-images me-1"></i>
        <strong>Imágenes:</strong> El logo, imagen de publicidad, imagen de condiciones e imagen de cuentas bancarias
        se cargan <strong>después de crear el registro</strong>, desde la pantalla de edición.
    </div>

    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="{{ route('empresas.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Crear Empresa
        </button>
    </div>
</form>

@push('scripts')
<script>
(function () {
    let emailIdx = 1;
    let telIdx   = 1;

    document.getElementById('btnAgregarEmail').addEventListener('click', function () {
        const container = document.getElementById('emailsContainer');
        const idx = emailIdx++;
        const div = document.createElement('div');
        div.className = 'email-item row g-2 mb-2 align-items-center';
        div.dataset.index = idx;
        div.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="emails[${idx}][area]" class="form-control" placeholder="Área">
            </div>
            <div class="col-md-5">
                <input type="email" name="emails[${idx}][email]" class="form-control" placeholder="correo@empresa.com">
            </div>
            <div class="col-md-2">
                <div class="form-check">
                    <input type="checkbox" name="emails[${idx}][activo]" class="form-check-input" value="1" checked id="emailActivo${idx}">
                    <label class="form-check-label" for="emailActivo${idx}">Activo</label>
                </div>
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-email" title="Eliminar fila">
                    <i class="bi bi-trash3"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
    });

    document.getElementById('btnAgregarTelefono').addEventListener('click', function () {
        const container = document.getElementById('telefonosContainer');
        const idx = telIdx++;
        const div = document.createElement('div');
        div.className = 'telefono-item row g-2 mb-2 align-items-center';
        div.dataset.index = idx;
        div.innerHTML = `
            <div class="col-md-3">
                <input type="text" name="telefonos[${idx}][area]" class="form-control" placeholder="Área">
            </div>
            <div class="col-md-3">
                <input type="text" name="telefonos[${idx}][telefono]" class="form-control" placeholder="Número">
            </div>
            <div class="col-md-4">
                <input type="text" name="telefonos[${idx}][descripcion]" class="form-control" placeholder="Descripción">
            </div>
            <div class="col-md-1">
                <div class="form-check">
                    <input type="checkbox" name="telefonos[${idx}][activo]" class="form-check-input" value="1" checked id="telActivo${idx}">
                    <label class="form-check-label" for="telActivo${idx}">Activo</label>
                </div>
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-telefono" title="Eliminar fila">
                    <i class="bi bi-trash3"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
    });

    document.getElementById('emailsContainer').addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-eliminar-email');
        if (btn) btn.closest('.email-item').remove();
    });

    document.getElementById('telefonosContainer').addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-eliminar-telefono');
        if (btn) btn.closest('.telefono-item').remove();
    });

    // Solo permitir dígitos en RUC
    const rucInput = document.getElementById('ruc');
    if (rucInput) {
        rucInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 11);
        });
    }
})();
</script>
@endpush
@endsection
