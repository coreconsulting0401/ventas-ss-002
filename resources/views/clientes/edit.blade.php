@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-people"></i> Editar Cliente: {{ $cliente->razon }}
    </h2>
    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<form action="{{ route('clientes.update', $cliente) }}" method="POST" id="formCliente">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <!-- Información del Cliente -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-building"></i> Información del Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="ruc" class="form-label">
                                <i class="bi bi-credit-card"></i> RUC <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="ruc" name="ruc" value="{{ old('ruc', $cliente->ruc) }}" readonly>
                            <small class="text-muted">No se puede modificar el RUC</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="razon" class="form-label">
                                <i class="bi bi-building"></i> Razón Social <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('razon') is-invalid @enderror" id="razon" name="razon" value="{{ old('razon', $cliente->razon) }}" required>
                            @error('razon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="direccion" class="form-label">
                                <i class="bi bi-geo-alt"></i> Dirección Principal <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion', $cliente->direccion) }}" required>
                            @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono1" class="form-label">
                                <i class="bi bi-telephone"></i> Teléfono 1 <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('telefono1') is-invalid @enderror" id="telefono1" name="telefono1" value="{{ old('telefono1', $cliente->telefono1) }}" required>
                            @error('telefono1')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label for="telefono2" class="form-label">
                                <i class="bi bi-phone"></i> Teléfono 2
                            </label>
                            <input type="text" class="form-control" id="telefono2" name="telefono2" value="{{ old('telefono2', $cliente->telefono2) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="categoria_id" class="form-label">
                                <i class="bi bi-tag"></i> Categoría
                            </label>
                            <select class="form-select" id="categoria_id" name="categoria_id">
                                <option value="">-- Seleccionar --</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id', $cliente->categoria_id) == $categoria->id ? 'selected' : '' }}>{{ $categoria->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="credito_id" class="form-label">
                                <i class="bi bi-credit-card-2-front"></i> Crédito
                            </label>
                            <select class="form-select" id="credito_id" name="credito_id">
                                <option value="">Sin crédito</option>
                                @foreach($creditos as $credito)
                                    <option value="{{ $credito->id }}" {{ old('credito_id', $cliente->credito_id) == $credito->id ? 'selected' : '' }}>{{ $credito->aprobacion ? 'Aprobado' : 'Desaprobado' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Direcciones Adicionales -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Direcciones Adicionales (Agencias)</h5>
                    <button type="button" class="btn btn-sm btn-light" onclick="agregarDireccion()">
                        <i class="bi bi-plus-circle"></i> Agregar Agencia
                    </button>
                </div>
                <div class="card-body">
                    <div id="direccionesContainer">
                        @forelse($cliente->direcciones as $index => $dir)
                            <div class="card mb-2 border-info" id="direccion-{{ $index }}">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong class="small text-info"><i class="bi bi-building"></i> Agencia {{ $index + 1 }}</strong>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removerDireccion({{ $index }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small mb-1">Dirección <span class="text-danger">*</span></label>
                                        <input type="text"
                                               name="direcciones[]"
                                               class="form-control form-control-sm"
                                               value="{{ $dir->direccion }}"
                                               placeholder="Ej: Av. Principal 123"
                                               maxlength="250">
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label small mb-1">Departamento</label>
                                            <select class="form-select form-select-sm"
                                                    id="departamento-{{ $index }}"
                                                    onchange="cargarProvincias(this.value, {{ $index }})"
                                                    data-selected-departamento="{{ $dir->distrito?->provincia?->departamento?->id }}">
                                                <option value="">-- Departamento --</option>
                                                @foreach($departamentos as $dep)
                                                    <option value="{{ $dep->id }}"
                                                        {{ $dir->distrito?->provincia?->departamento?->id == $dep->id ? 'selected' : '' }}>
                                                        {{ $dep->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small mb-1">Provincia</label>
                                            <select class="form-select form-select-sm"
                                                    id="provincia-{{ $index }}"
                                                    onchange="cargarDistritos(this.value, {{ $index }})"
                                                    data-selected-provincia="{{ $dir->distrito?->provincia?->id }}"
                                                    {{ $dir->distrito ? '' : 'disabled' }}>
                                                <option value="">-- Provincia --</option>
                                                @if($dir->distrito?->provincia)
                                                    <option value="{{ $dir->distrito->provincia->id }}" selected>
                                                        {{ $dir->distrito->provincia->nombre }}
                                                    </option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label small mb-1">Distrito</label>
                                            <select class="form-select form-select-sm"
                                                    id="distrito-{{ $index }}"
                                                    name="distritos[]"
                                                    data-selected-distrito="{{ $dir->distrito_id }}"
                                                    {{ $dir->distrito ? '' : 'disabled' }}>
                                                <option value="">-- Distrito --</option>
                                                @if($dir->distrito)
                                                    <option value="{{ $dir->distrito->id }}" selected>
                                                        {{ $dir->distrito->nombre }}
                                                    </option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted small text-center py-3" id="sinDirecciones">No hay direcciones adicionales</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Contactos -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill"></i> Contactos</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong><i class="bi bi-info-circle"></i> Paso 1:</strong> Busque o cree contactos
                    </div>

                    <div class="mb-3">
                        <label for="dni_contacto" class="form-label">
                            <i class="bi bi-search"></i> Buscar Contacto por DNI
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="dni_contacto" placeholder="Ingrese DNI (8 dígitos)" maxlength="8">
                            <button class="btn btn-outline-secondary" type="button" onclick="buscarContactoPorDni()">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        <div id="contactoResultado" class="mt-2"></div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <button class="btn btn-sm btn-success w-100" type="button" data-bs-toggle="collapse" data-bs-target="#nuevoContactoForm">
                            <i class="bi bi-person-plus"></i> Crear Contacto Nuevo
                        </button>
                    </div>

                    <div class="collapse" id="nuevoContactoForm">
                        <div class="card card-body bg-light">
                            <h6 class="mb-3"><i class="bi bi-person-plus-fill"></i> Nuevo Contacto</h6>

                            <!-- DNI con búsqueda automática -->
                            <div class="mb-2">
                                <label for="new_dni" class="form-label small">DNI <span class="text-danger">*</span></label>
                                <div class="input-group input-group-sm">
                                    <input type="text"
                                           class="form-control"
                                           id="new_dni"
                                           maxlength="8"
                                           placeholder="DNI">
                                    <button class="btn btn-outline-secondary" type="button" onclick="buscarDNI()">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                                <div id="dniAlert" class="mt-1"></div>
                            </div>

                            <div class="mb-2"><label class="form-label small">Nombre <span class="text-danger">*</span></label><input type="text" class="form-control form-control-sm" id="new_nombre"></div>
                            <div class="mb-2"><label class="form-label small">Apellido Paterno <span class="text-danger">*</span></label><input type="text" class="form-control form-control-sm" id="new_apellido_paterno"></div>
                            <div class="mb-2"><label class="form-label small">Apellido Materno <span class="text-danger">*</span></label><input type="text" class="form-control form-control-sm" id="new_apellido_materno"></div>
                            <div class="mb-2"><label class="form-label small">Teléfono <span class="text-danger">*</span></label><input type="text" class="form-control form-control-sm" id="new_telefono"></div>
                            <div class="mb-2"><label class="form-label small">Email <span class="text-danger">*</span></label><input type="email" class="form-control form-control-sm" id="new_email"></div>
                            <div class="mb-3"><label class="form-label small">Cargo <span class="text-danger">*</span></label><input type="text" class="form-control form-control-sm" id="new_cargo"></div>
                            <button type="button" class="btn btn-sm btn-primary w-100" onclick="crearContactoSolamente()">
                                <i class="bi bi-save"></i> Guardar Contacto
                            </button>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="alert alert-success">
                            <strong><i class="bi bi-info-circle"></i> Paso 2:</strong> Contactos que se asignarán
                        </div>
                        <label class="form-label"><i class="bi bi-people"></i> Contactos para Asignar</label>
                        <div id="contactosSeleccionados">
                            @if($cliente->contactos->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($cliente->contactos as $contacto)
                                        <div class="list-group-item d-flex justify-content-between align-items-center p-2 bg-light" data-contacto-id="{{ $contacto->id }}">
                                            <small><i class="bi bi-person-check-fill text-success"></i> {{ $contacto->nombre }} {{ $contacto->apellido_paterno }}</small>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removerContacto({{ $contacto->id }})"><i class="bi bi-x"></i></button>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-secondary small"><i class="bi bi-info-circle"></i> No hay contactos asignados</div>
                            @endif
                        </div>
                        <input type="hidden" name="contactos" id="contactosHidden" value="{{ $cliente->contactos->pluck('id')->join(',') }}">
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 btn-lg">
                        <i class="bi bi-save"></i> Actualizar Cliente
                    </button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary w-100 mt-2">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
let contactosSeleccionados = [
    @foreach($cliente->contactos as $contacto)
        {id: {{ $contacto->id }}, nombre: '{{ addslashes($contacto->nombre . ' ' . $contacto->apellido_paterno) }}'},
    @endforeach
];
let direccionesCount = {{ $cliente->direcciones->count() }};
// Las consultas a RENIEC/SUNAT se realizan a través del proxy seguro en el backend.

// Buscar contacto por DNI
async function buscarContactoPorDni(){
    const dni = document.getElementById('dni_contacto').value.trim();
    const resultDiv = document.getElementById('contactoResultado');

    if(dni.length !== 8 || isNaN(dni)){
        resultDiv.innerHTML = '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> El DNI debe tener 8 dígitos</div>';
        return;
    }

    try{
        const response = await fetch(`/contactos/buscar-dni/${dni}`);
        const data = await response.json();

        if(data.success && data.contacto){
            const contacto = data.contacto;
            const nombreCompleto = `${contacto.nombre} ${contacto.apellido_paterno} ${contacto.apellido_materno}`;

            resultDiv.innerHTML = `
                <div class="card border-success">
                    <div class="card-body">
                        <h6 class="card-title text-success"><i class="bi bi-person-check-fill"></i> Encontrado</h6>
                        <p class="card-text small mb-1">
                            <strong>Nombre:</strong> ${nombreCompleto}<br>
                            <strong>DNI:</strong> ${contacto.dni}<br>
                            <strong>Cargo:</strong> ${contacto.cargo}
                        </p>
                        <button class="btn btn-sm btn-success w-100 mt-2" onclick="agregarContactoParaAsignar(${contacto.id},'${nombreCompleto.replace(/'/g,"\\'")}')">
                            <i class="bi bi-plus-circle"></i> Seleccionar
                        </button>
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = '<div class="alert alert-info">Contacto no encontrado. Créelo con el botón "Crear Contacto Nuevo"</div>';
            document.getElementById('new_dni').value = dni;
            const collapseElement = document.getElementById('nuevoContactoForm');
            new bootstrap.Collapse(collapseElement, {show: true});
        }
    } catch(error){
        console.error('Error:', error);
        resultDiv.innerHTML = '<div class="alert alert-danger">Error al buscar contacto</div>';
    }
}

// Buscar DNI en API RENIEC para autocompletar
async function buscarDNI() {
    const dni = document.getElementById('new_dni').value.trim();
    const alertDiv = document.getElementById('dniAlert');

    if (dni.length !== 8 || isNaN(dni)) {
        alertDiv.innerHTML = `
            <small class="text-danger">
                <i class="bi bi-exclamation-triangle"></i> El DNI debe tener 8 dígitos
            </small>
        `;
        return;
    }

    alertDiv.innerHTML = `
        <small class="text-info">
            <i class="bi bi-hourglass-split"></i> Consultando RENIEC...
        </small>
    `;

    try {
        const response = await fetch(`/api-externa/consultar-dni/${dni}`);
        const data = await response.json();

        if (data.success && data.data) {
            // Autocompletar campos
            document.getElementById('new_nombre').value = data.data.nombres || '';
            document.getElementById('new_apellido_paterno').value = data.data.apellido_paterno || '';
            document.getElementById('new_apellido_materno').value = data.data.apellido_materno || '';

            alertDiv.innerHTML = `
                <small class="text-success">
                    <i class="bi bi-check-circle"></i> Datos encontrados en RENIEC
                </small>
            `;
        } else {
            alertDiv.innerHTML = `
                <small class="text-warning">
                    <i class="bi bi-info-circle"></i> DNI no encontrado. Complete manualmente
                </small>
            `;
        }
    } catch (error) {
        console.error('Error:', error);
        alertDiv.innerHTML = `
            <small class="text-danger">
                <i class="bi bi-x-circle"></i> Error al consultar RENIEC
            </small>
        `;
    }
}

// Crear nuevo contacto
async function crearContactoSolamente(){
    const dni = document.getElementById('new_dni').value.trim();
    const nombre = document.getElementById('new_nombre').value.trim();
    const apellidoPaterno = document.getElementById('new_apellido_paterno').value.trim();
    const apellidoMaterno = document.getElementById('new_apellido_materno').value.trim();
    const telefono = document.getElementById('new_telefono').value.trim();
    const email = document.getElementById('new_email').value.trim();
    const cargo = document.getElementById('new_cargo').value.trim();

    if(!dni || !nombre || !apellidoPaterno || !apellidoMaterno || !telefono || !email || !cargo){
        alert('Complete todos los campos');
        return;
    }

    if(dni.length !== 8){
        alert('El DNI debe tener 8 dígitos');
        return;
    }

    try{
        const response = await fetch('/contactos', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                dni: dni,
                nombre: nombre,
                apellido_paterno: apellidoPaterno,
                apellido_materno: apellidoMaterno,
                telefono: telefono,
                email: email,
                cargo: cargo
            })
        });

        const data = await response.json();

        if(data.id){
            const nombreCompleto = `${nombre} ${apellidoPaterno} ${apellidoMaterno}`;
            agregarContactoParaAsignar(data.id, nombreCompleto);

            // Limpiar formulario
            document.getElementById('new_dni').value = '';
            document.getElementById('new_nombre').value = '';
            document.getElementById('new_apellido_paterno').value = '';
            document.getElementById('new_apellido_materno').value = '';
            document.getElementById('new_telefono').value = '';
            document.getElementById('new_email').value = '';
            document.getElementById('new_cargo').value = '';
            document.getElementById('dniAlert').innerHTML = '';

            // Cerrar collapse
            const collapseElement = document.getElementById('nuevoContactoForm');
            const collapse = bootstrap.Collapse.getInstance(collapseElement) || new bootstrap.Collapse(collapseElement);
            collapse.hide();

            document.getElementById('contactoResultado').innerHTML = '<div class="alert alert-success">Contacto creado y agregado</div>';
            document.getElementById('dni_contacto').value = '';
        } else {
            alert('Error al crear contacto');
        }
    } catch(error){
        console.error('Error:', error);
        alert('Error al crear contacto. Verifique que el DNI no esté duplicado.');
    }
}

// Agregar contacto para asignar
function agregarContactoParaAsignar(contactoId, nombreCompleto){
    if(contactosSeleccionados.find(c => c.id === contactoId)){
        alert('Este contacto ya está en la lista');
        return;
    }

    contactosSeleccionados.push({id: contactoId, nombre: nombreCompleto});
    actualizarListaContactos();

    document.getElementById('contactoResultado').innerHTML = '<div class="alert alert-success">Contacto agregado. Será asignado al actualizar</div>';
    document.getElementById('dni_contacto').value = '';
}

// Actualizar lista de contactos
function actualizarListaContactos(){
    const container = document.getElementById('contactosSeleccionados');
    const hiddenInput = document.getElementById('contactosHidden');

    if(contactosSeleccionados.length > 0){
        const ids = contactosSeleccionados.map(c => c.id);
        hiddenInput.value = ids.join(',');

        let html = '<div class="list-group list-group-flush">';
        contactosSeleccionados.forEach((contacto, index) => {
            html += `
                <div class="list-group-item d-flex justify-content-between align-items-center p-2 bg-light">
                    <small><i class="bi bi-person-check-fill text-success"></i> <strong>${index + 1}.</strong> ${contacto.nombre}</small>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removerContacto(${contacto.id})">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            `;
        });
        html += '</div>';

        container.innerHTML = html;
    } else {
        hiddenInput.value = '';
        container.innerHTML = '<div class="alert alert-secondary small">No hay contactos seleccionados</div>';
    }
}

// Remover contacto
function removerContacto(contactoId){
    contactosSeleccionados = contactosSeleccionados.filter(c => c.id !== contactoId);
    actualizarListaContactos();
}

// Departamentos disponibles (inyectados desde el controlador)
const DEPARTAMENTOS = @json($departamentos);

function departamentosOptions() {
    return DEPARTAMENTOS.map(d => `<option value="${d.id}">${d.nombre}</option>`).join('');
}

// Agregar dirección nueva con ubigeo encadenado
function agregarDireccion(){
    direccionesCount++;
    const n = direccionesCount;
    const container = document.getElementById('direccionesContainer');

    // Quitar mensaje de "sin direcciones" si existe
    const sinDir = document.getElementById('sinDirecciones');
    if (sinDir) sinDir.remove();

    const html = `
        <div class="card mb-2 border-info" id="direccion-${n}">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="small text-info"><i class="bi bi-building"></i> Agencia ${n + 1}</strong>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removerDireccion(${n})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <div class="mb-2">
                    <label class="form-label small mb-1">Dirección <span class="text-danger">*</span></label>
                    <input type="text" name="direcciones[]" class="form-control form-control-sm"
                           placeholder="Ej: Av. Principal 123" maxlength="250">
                </div>
                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label small mb-1">Departamento</label>
                        <select class="form-select form-select-sm" id="departamento-${n}"
                                onchange="cargarProvincias(this.value, ${n})">
                            <option value="">-- Departamento --</option>
                            ${departamentosOptions()}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small mb-1">Provincia</label>
                        <select class="form-select form-select-sm" id="provincia-${n}"
                                disabled onchange="cargarDistritos(this.value, ${n})">
                            <option value="">-- Provincia --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small mb-1">Distrito</label>
                        <select class="form-select form-select-sm" id="distrito-${n}"
                                name="distritos[]" disabled>
                            <option value="">-- Distrito --</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
}

// Carga provincias vía AJAX al cambiar departamento
async function cargarProvincias(departamentoId, n) {
    const selectProv = document.getElementById(`provincia-${n}`);
    const selectDist = document.getElementById(`distrito-${n}`);

    selectProv.innerHTML = '<option value="">Cargando...</option>';
    selectProv.disabled = true;
    selectDist.innerHTML = '<option value="">-- Distrito --</option>';
    selectDist.disabled = true;

    if (!departamentoId) {
        selectProv.innerHTML = '<option value="">-- Provincia --</option>';
        return;
    }

    try {
        const res = await fetch(`/ubigeo/provincias/${departamentoId}`);
        const data = await res.json();
        selectProv.innerHTML = '<option value="">-- Provincia --</option>' +
            data.map(p => `<option value="${p.id}">${p.nombre}</option>`).join('');
        selectProv.disabled = false;
    } catch {
        selectProv.innerHTML = '<option value="">Error al cargar</option>';
    }
}

// Carga distritos vía AJAX al cambiar provincia
async function cargarDistritos(provinciaId, n, selectedDistrito = null) {
    const selectDist = document.getElementById(`distrito-${n}`);

    selectDist.innerHTML = '<option value="">Cargando...</option>';
    selectDist.disabled = true;

    if (!provinciaId) {
        selectDist.innerHTML = '<option value="">-- Distrito --</option>';
        return;
    }

    try {
        const res = await fetch(`/ubigeo/distritos/${provinciaId}`);
        const data = await res.json();
        selectDist.innerHTML = '<option value="">-- Distrito --</option>' +
            data.map(d =>
                `<option value="${d.id}" ${selectedDistrito && d.id == selectedDistrito ? 'selected' : ''}>${d.nombre}</option>`
            ).join('');
        selectDist.disabled = false;
    } catch {
        selectDist.innerHTML = '<option value="">Error al cargar</option>';
    }
}

// Remover dirección
function removerDireccion(id){
    const direccion = document.getElementById(`direccion-${id}`);
    if(direccion) direccion.remove();
}

// Solo números en campos numéricos
document.querySelectorAll('input[maxlength="8"], input[maxlength="15"]').forEach(input => {
    input.addEventListener('input', function(){
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});

// Inicializar: recarga provincias y distritos de las direcciones existentes con ubigeo
document.addEventListener('DOMContentLoaded', async function () {
    contactosSeleccionados = contactosSeleccionados.filter(c => c.id !== undefined);

    // Para cada dirección existente que tenga departamento pre-seleccionado,
    // recargar sus provincias y distritos para que los selects queden funcionales
    const depSelects = document.querySelectorAll('[id^="departamento-"]');

    for (const depSelect of depSelects) {
        const n = depSelect.id.split('-')[1];
        const depId = depSelect.value;
        const provSelect = document.getElementById(`provincia-${n}`);
        const distSelect = document.getElementById(`distrito-${n}`);

        if (!depId) continue;

        const selectedProv = provSelect?.dataset?.selectedProvincia;
        const selectedDist = distSelect?.dataset?.selectedDistrito;

        // Recargar provincias
        try {
            const res = await fetch(`/ubigeo/provincias/${depId}`);
            const data = await res.json();
            provSelect.innerHTML = '<option value="">-- Provincia --</option>' +
                data.map(p =>
                    `<option value="${p.id}" ${p.id == selectedProv ? 'selected' : ''}>${p.nombre}</option>`
                ).join('');
            provSelect.disabled = false;
        } catch { continue; }

        if (!selectedProv) continue;

        // Recargar distritos
        try {
            const res2 = await fetch(`/ubigeo/distritos/${selectedProv}`);
            const data2 = await res2.json();
            distSelect.innerHTML = '<option value="">-- Distrito --</option>' +
                data2.map(d =>
                    `<option value="${d.id}" ${d.id == selectedDist ? 'selected' : ''}>${d.nombre}</option>`
                ).join('');
            distSelect.disabled = false;
        } catch { continue; }
    }
});
</script>
@endpush
@endsection
