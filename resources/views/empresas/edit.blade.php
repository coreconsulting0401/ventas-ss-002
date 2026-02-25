@extends('layouts.app')
@section('title', 'Editar Empresa')

@section('page-title')
    <i class="bi bi-pencil-square"></i> Editar Empresa
    <a href="{{ route('empresas.index') }}" class="btn btn-sm btn-secondary float-end">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
@endsection

@section('content')

<form action="{{ route('empresas.update', $empresa) }}" method="POST"
      enctype="multipart/form-data" id="formEmpresa">
    @csrf
    @method('PUT')

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
                           value="{{ old('razon_social', $empresa->razon_social) }}"
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
                           value="{{ old('ruc', $empresa->ruc) }}"
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
                           value="{{ old('pagina_web', $empresa->pagina_web) }}"
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
                              placeholder="Av. Ejemplo 123, Ciudad">{{ old('direccion', $empresa->direccion) }}</textarea>
                    @error('direccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ===================================================================
         IMÁGENES
    =================================================================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="bi bi-images"></i> Imágenes
                <small class="fw-normal">(PNG, JPG, JPEG – Máx. 2 MB c/u)</small>
            </h5>
        </div>
        <div class="card-body">
            <div class="row">

                {{-- ── Logo ─────────────────────────────────────────────── --}}
                <div class="col-md-3 mb-4">
                    <label class="form-label fw-semibold"><i class="bi bi-badge-tm"></i> Logo</label>
                    <div class="mb-2 text-center">
                        @if($empresa->uri_img_logo)
                            <img src="{{ asset('storage/' . $empresa->uri_img_logo) }}"
                                 alt="Logo actual" class="img-thumbnail"
                                 style="max-height: 100px; object-fit: contain;">
                            <p class="text-muted small mt-1 mb-0">Imagen actual</p>
                        @else
                            <div class="bg-light border rounded p-3">
                                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0 small">Sin imagen</p>
                            </div>
                        @endif
                    </div>
                    <input type="file"
                           class="form-control @error('uri_img_logo') is-invalid @enderror"
                           id="uri_img_logo" name="uri_img_logo"
                           accept=".png,.jpg,.jpeg"
                           onchange="previsualizarImagen(this, 'prev_logo_new')">
                    @error('uri_img_logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div id="prev_logo_new" class="mt-2 text-center d-none">
                        <img src="" alt="Vista previa" class="img-thumbnail" style="max-height: 70px; object-fit: contain;">
                        <p class="text-success small mb-0">Nueva (pendiente guardar)</p>
                    </div>
                    @if($empresa->uri_img_logo)
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input"
                                   id="eliminar_logo" name="eliminar_logo" value="1">
                            <label class="form-check-label text-danger small" for="eliminar_logo">
                                <i class="bi bi-trash3"></i> Eliminar imagen actual
                            </label>
                        </div>
                    @endif
                    <small class="text-muted d-block mt-1">Vacío = mantener imagen actual.</small>
                </div>

                {{-- ── Publicidad ───────────────────────────────────────── --}}
                <div class="col-md-3 mb-4">
                    <label class="form-label fw-semibold"><i class="bi bi-megaphone"></i> Publicidad</label>
                    <div class="mb-2 text-center">
                        @if($empresa->uri_img_publicidad)
                            <img src="{{ asset('storage/' . $empresa->uri_img_publicidad) }}"
                                 alt="Publicidad actual" class="img-thumbnail"
                                 style="max-height: 100px; object-fit: contain;">
                            <p class="text-muted small mt-1 mb-0">Imagen actual</p>
                        @else
                            <div class="bg-light border rounded p-3">
                                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0 small">Sin imagen</p>
                            </div>
                        @endif
                    </div>
                    <input type="file"
                           class="form-control @error('uri_img_publicidad') is-invalid @enderror"
                           id="uri_img_publicidad" name="uri_img_publicidad"
                           accept=".png,.jpg,.jpeg"
                           onchange="previsualizarImagen(this, 'prev_pub_new')">
                    @error('uri_img_publicidad') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div id="prev_pub_new" class="mt-2 text-center d-none">
                        <img src="" alt="Vista previa" class="img-thumbnail" style="max-height: 70px; object-fit: contain;">
                        <p class="text-success small mb-0">Nueva (pendiente guardar)</p>
                    </div>
                    @if($empresa->uri_img_publicidad)
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input"
                                   id="eliminar_publicidad" name="eliminar_publicidad" value="1">
                            <label class="form-check-label text-danger small" for="eliminar_publicidad">
                                <i class="bi bi-trash3"></i> Eliminar imagen actual
                            </label>
                        </div>
                    @endif
                    <small class="text-muted d-block mt-1">Vacío = mantener imagen actual.</small>
                </div>

                {{-- ── Condiciones ──────────────────────────────────────── --}}
                <div class="col-md-3 mb-4">
                    <label class="form-label fw-semibold"><i class="bi bi-file-earmark-check"></i> Condiciones</label>
                    <div class="mb-2 text-center">
                        @if($empresa->uri_img_condiciones)
                            <img src="{{ asset('storage/' . $empresa->uri_img_condiciones) }}"
                                 alt="Condiciones actual" class="img-thumbnail"
                                 style="max-height: 100px; object-fit: contain;">
                            <p class="text-muted small mt-1 mb-0">Imagen actual</p>
                        @else
                            <div class="bg-light border rounded p-3">
                                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0 small">Sin imagen</p>
                            </div>
                        @endif
                    </div>
                    <input type="file"
                           class="form-control @error('uri_img_condiciones') is-invalid @enderror"
                           id="uri_img_condiciones" name="uri_img_condiciones"
                           accept=".png,.jpg,.jpeg"
                           onchange="previsualizarImagen(this, 'prev_cond_new')">
                    @error('uri_img_condiciones') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div id="prev_cond_new" class="mt-2 text-center d-none">
                        <img src="" alt="Vista previa" class="img-thumbnail" style="max-height: 70px; object-fit: contain;">
                        <p class="text-success small mb-0">Nueva (pendiente guardar)</p>
                    </div>
                    @if($empresa->uri_img_condiciones)
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input"
                                   id="eliminar_condiciones" name="eliminar_condiciones" value="1">
                            <label class="form-check-label text-danger small" for="eliminar_condiciones">
                                <i class="bi bi-trash3"></i> Eliminar imagen actual
                            </label>
                        </div>
                    @endif
                    <small class="text-muted d-block mt-1">Vacío = mantener imagen actual.</small>
                </div>

                {{-- ── Cuentas Bancarias ────────────────────────────────── --}}
                <div class="col-md-3 mb-4">
                    <label class="form-label fw-semibold"><i class="bi bi-bank"></i> Cuentas Bancarias</label>
                    <div class="mb-2 text-center">
                        @if($empresa->uri_cuentas_bancarias)
                            <img src="{{ asset('storage/' . $empresa->uri_cuentas_bancarias) }}"
                                 alt="Cuentas bancarias actual" class="img-thumbnail"
                                 style="max-height: 100px; object-fit: contain;">
                            <p class="text-muted small mt-1 mb-0">Imagen actual</p>
                        @else
                            <div class="bg-light border rounded p-3">
                                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0 small">Sin imagen</p>
                            </div>
                        @endif
                    </div>
                    <input type="file"
                           class="form-control @error('uri_cuentas_bancarias') is-invalid @enderror"
                           id="uri_cuentas_bancarias" name="uri_cuentas_bancarias"
                           accept=".png,.jpg,.jpeg"
                           onchange="previsualizarImagen(this, 'prev_cuentas_new')">
                    @error('uri_cuentas_bancarias') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div id="prev_cuentas_new" class="mt-2 text-center d-none">
                        <img src="" alt="Vista previa" class="img-thumbnail" style="max-height: 70px; object-fit: contain;">
                        <p class="text-success small mb-0">Nueva (pendiente guardar)</p>
                    </div>
                    @if($empresa->uri_cuentas_bancarias)
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input"
                                   id="eliminar_cuentas" name="eliminar_cuentas" value="1">
                            <label class="form-check-label text-danger small" for="eliminar_cuentas">
                                <i class="bi bi-trash3"></i> Eliminar imagen actual
                            </label>
                        </div>
                    @endif
                    <small class="text-muted d-block mt-1">Vacío = mantener imagen actual.</small>
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
                @forelse($empresa->emails as $index => $email)
                    <div class="email-item row g-2 mb-2 align-items-center" data-index="{{ $index }}">
                        <input type="hidden" name="emails[{{ $index }}][id]" value="{{ $email->id }}">
                        <div class="col-md-4">
                            <input type="text" name="emails[{{ $index }}][area]"
                                   class="form-control" value="{{ old('emails.'.$index.'.area', $email->area) }}"
                                   placeholder="Área">
                        </div>
                        <div class="col-md-5">
                            <input type="email" name="emails[{{ $index }}][email]"
                                   class="form-control" value="{{ old('emails.'.$index.'.email', $email->email) }}"
                                   placeholder="correo@empresa.com">
                        </div>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input type="checkbox" name="emails[{{ $index }}][activo]"
                                       class="form-check-input" value="1"
                                       id="emailActivo{{ $index }}"
                                       {{ old('emails.'.$index.'.activo', $email->activo) ? 'checked' : '' }}>
                                <label class="form-check-label" for="emailActivo{{ $index }}">Activo</label>
                            </div>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-email" title="Eliminar fila">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small mb-2">
                        <i class="bi bi-info-circle"></i>
                        No hay correos registrados. Use el botón "Agregar" para añadir.
                    </p>
                @endforelse
            </div>
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
                @forelse($empresa->telefonos as $index => $tel)
                    <div class="telefono-item row g-2 mb-2 align-items-center" data-index="{{ $index }}">
                        <input type="hidden" name="telefonos[{{ $index }}][id]" value="{{ $tel->id }}">
                        <div class="col-md-3">
                            <input type="text" name="telefonos[{{ $index }}][area]"
                                   class="form-control" value="{{ old('telefonos.'.$index.'.area', $tel->area) }}"
                                   placeholder="Área">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="telefonos[{{ $index }}][telefono]"
                                   class="form-control" value="{{ old('telefonos.'.$index.'.telefono', $tel->telefono) }}"
                                   placeholder="Número">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="telefonos[{{ $index }}][descripcion]"
                                   class="form-control" value="{{ old('telefonos.'.$index.'.descripcion', $tel->descripcion) }}"
                                   placeholder="Descripción (Opcional)">
                        </div>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input type="checkbox" name="telefonos[{{ $index }}][activo]"
                                       class="form-check-input" value="1"
                                       id="telActivo{{ $index }}"
                                       {{ old('telefonos.'.$index.'.activo', $tel->activo) ? 'checked' : '' }}>
                                <label class="form-check-label" for="telActivo{{ $index }}">Activo</label>
                            </div>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-telefono" title="Eliminar fila">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small mb-2">
                        <i class="bi bi-info-circle"></i>
                        No hay teléfonos registrados. Use el botón "Agregar" para añadir.
                    </p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="{{ route('empresas.index') }}" class="btn btn-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
        <button type="submit" class="btn btn-success">
            <i class="bi bi-check-circle"></i> Guardar Cambios
        </button>
    </div>
</form>

@push('scripts')
<script>
(function () {
    let emailIdx = {{ $empresa->emails->count() }};
    let telIdx   = {{ $empresa->telefonos->count() }};

    // ── Previsualizar imagen ───────────────────────────────────────────────
    window.previsualizarImagen = function (input, previewContainerId) {
        const container = document.getElementById(previewContainerId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                container.querySelector('img').src = e.target.result;
                container.classList.remove('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            container.classList.add('d-none');
        }
    };

    // ── Solo dígitos en RUC ────────────────────────────────────────────────
    const rucInput = document.getElementById('ruc');
    if (rucInput) {
        rucInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 11);
        });
    }

    // ── Agregar email ──────────────────────────────────────────────────────
    document.getElementById('btnAgregarEmail').addEventListener('click', function () {
        const container = document.getElementById('emailsContainer');
        const msg = container.querySelector('p.text-muted');
        if (msg) msg.remove();
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

    // ── Agregar teléfono ───────────────────────────────────────────────────
    document.getElementById('btnAgregarTelefono').addEventListener('click', function () {
        const container = document.getElementById('telefonosContainer');
        const msg = container.querySelector('p.text-muted');
        if (msg) msg.remove();
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
            <div class="col-md-3">
                <input type="text" name="telefonos[${idx}][descripcion]" class="form-control" placeholder="Descripción">
            </div>
            <div class="col-md-2">
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

    // ── Eliminar filas ─────────────────────────────────────────────────────
    document.getElementById('emailsContainer').addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-eliminar-email');
        if (btn) btn.closest('.email-item').remove();
    });

    document.getElementById('telefonosContainer').addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-eliminar-telefono');
        if (btn) btn.closest('.telefono-item').remove();
    });
})();
</script>
@endpush
@endsection
