@extends('layouts.app')

@section('title', 'Crear Cliente')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-people"></i> Crear Cliente
    </h2>
    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<form action="{{ route('clientes.store') }}" method="POST" id="formCliente">
    @csrf
    <div class="row">
        <!-- Sección Principal del Cliente -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-building"></i> Información del Cliente</h5>
                </div>
                <div class="card-body">
                    <!-- RUC con búsqueda -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="ruc" class="form-label">
                                <i class="bi bi-credit-card"></i> RUC <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control @error('ruc') is-invalid @enderror"
                                       id="ruc"
                                       name="ruc"
                                       value="{{ old('ruc') }}"
                                       maxlength="11"
                                       pattern="[0-9]{11}"
                                       placeholder="Ingrese RUC de 11 dígitos"
                                       required
                                       autofocus>
                                <button class="btn btn-outline-secondary" type="button" onclick="buscarRUC()">
                                    <i class="bi bi-search"></i> Buscar
                                </button>
                                @error('ruc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Solo números, 11 dígitos</small>
                            <div id="rucAlert" class="mt-2"></div>
                        </div>
                    </div>

                    <!-- Razón Social -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="razon" class="form-label">
                                <i class="bi bi-building"></i> Razón Social <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('razon') is-invalid @enderror"
                                   id="razon"
                                   name="razon"
                                   value="{{ old('razon') }}"
                                   maxlength="250"
                                   placeholder="Razón social del cliente"
                                   required>
                            @error('razon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Dirección Principal -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="direccion" class="form-label">
                                <i class="bi bi-geo-alt"></i> Dirección Principal <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('direccion') is-invalid @enderror"
                                   id="direccion"
                                   name="direccion"
                                   value="{{ old('direccion') }}"
                                   maxlength="200"
                                   placeholder="Dirección principal del cliente"
                                   required>
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Teléfonos -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono1" class="form-label">
                                <i class="bi bi-telephone"></i> Teléfono 1 <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('telefono1') is-invalid @enderror"
                                   id="telefono1"
                                   name="telefono1"
                                   value="{{ old('telefono1') }}"
                                   maxlength="15"
                                   placeholder="Teléfono principal"
                                   required>
                            @error('telefono1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="telefono2" class="form-label">
                                <i class="bi bi-phone"></i> Teléfono 2
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="telefono2"
                                   name="telefono2"
                                   value="{{ old('telefono2') }}"
                                   maxlength="15"
                                   placeholder="Teléfono secundario">
                        </div>
                    </div>

                    <!-- Categoría y Crédito -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="categoria_id" class="form-label">
                                <i class="bi bi-tag"></i> Categoría
                            </label>
                            <select class="form-select @error('categoria_id') is-invalid @enderror"
                                    id="categoria_id"
                                    name="categoria_id">
                                <option value="">-- Seleccionar --</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categoria_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="credito_id" class="form-label">
                                <i class="bi bi-credit-card-2-front"></i> Crédito
                            </label>
                            <select class="form-select" id="credito_id" name="credito_id">
                                <option value="">Sin crédito</option>
                                @foreach($creditos as $credito)
                                    <option value="{{ $credito->id }}" {{ old('credito_id') == $credito->id ? 'selected' : '' }}>
                                        {{ $credito->aprobacion ? 'Aprobado' : 'Desaprobado' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Direcciones Adicionales (Agencias) -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-geo-alt"></i> Direcciones Adicionales (Agencias)</h5>
                    <button type="button" class="btn btn-sm btn-light" onclick="agregarDireccion()">
                        <i class="bi bi-plus-circle"></i> Agregar Agencia
                    </button>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        <i class="bi bi-info-circle"></i> Agregue las direcciones de las agencias o puntos físicos adicionales del cliente
                    </p>
                    <div id="direccionesContainer">
                        <!-- Las direcciones adicionales se agregarán aquí dinámicamente -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Contactos -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill"></i> Contactos</h5>
                </div>
                <div class="card-body">
                    <!-- PASO 1: Buscar Contacto Existente -->
                    <div class="alert alert-info">
                        <strong><i class="bi bi-info-circle"></i> Paso 1:</strong> Busque un contacto existente o cree uno nuevo
                    </div>

                    <div class="mb-3">
                        <label for="dni_contacto" class="form-label">
                            <i class="bi bi-search"></i> Buscar Contacto por DNI
                        </label>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control"
                                   id="dni_contacto"
                                   placeholder="Ingrese DNI (8 dígitos)"
                                   maxlength="8">
                            <button class="btn btn-outline-secondary" type="button" onclick="buscarContactoPorDni()">
                                <i class="bi bi-search"></i> Buscar
                            </button>
                        </div>
                        <div id="contactoResultado" class="mt-2"></div>
                    </div>

                    <hr>

                    <!-- PASO 2: Crear Nuevo Contacto (si no existe) -->
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

                            <div class="mb-2">
                                <label for="new_nombre" class="form-label small">Nombre <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control form-control-sm"
                                       id="new_nombre"
                                       maxlength="100"
                                       placeholder="Nombre">
                            </div>

                            <div class="mb-2">
                                <label for="new_apellido_paterno" class="form-label small">Apellido Paterno <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control form-control-sm"
                                       id="new_apellido_paterno"
                                       maxlength="100"
                                       placeholder="Apellido Paterno">
                            </div>

                            <div class="mb-2">
                                <label for="new_apellido_materno" class="form-label small">Apellido Materno <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control form-control-sm"
                                       id="new_apellido_materno"
                                       maxlength="100"
                                       placeholder="Apellido Materno">
                            </div>

                            <div class="mb-2">
                                <label for="new_telefono" class="form-label small">Teléfono <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control form-control-sm"
                                       id="new_telefono"
                                       maxlength="15"
                                       placeholder="Teléfono">
                            </div>

                            <div class="mb-2">
                                <label for="new_email" class="form-label small">Email <span class="text-danger">*</span></label>
                                <input type="email"
                                       class="form-control form-control-sm"
                                       id="new_email"
                                       placeholder="correo@ejemplo.com">
                            </div>

                            <div class="mb-3">
                                <label for="new_cargo" class="form-label small">Cargo <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control form-control-sm"
                                       id="new_cargo"
                                       maxlength="50"
                                       placeholder="Cargo">
                            </div>

                            <button type="button" class="btn btn-sm btn-primary w-100" onclick="crearContactoSolamente()">
                                <i class="bi bi-save"></i> Guardar Contacto
                            </button>
                        </div>
                    </div>

                    <hr>

                    <!-- PASO 3: Contactos Seleccionados para Asignar -->
                    <div class="mb-3">
                        <div class="alert alert-success">
                            <strong><i class="bi bi-info-circle"></i> Paso 2:</strong> Estos contactos se asignarán al guardar el cliente
                        </div>
                        <label class="form-label">
                            <i class="bi bi-people"></i> Contactos para Asignar
                        </label>
                        <div id="contactosSeleccionados">
                            <div class="alert alert-secondary small">
                                <i class="bi bi-info-circle"></i> No hay contactos seleccionados
                            </div>
                        </div>
                        <input type="hidden" name="contactos" id="contactosHidden">
                    </div>
                </div>
            </div>

            <!-- Botón de Guardar -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 btn-lg">
                        <i class="bi bi-save"></i> Guardar Cliente y Asignar Contactos
                    </button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary w-100 mt-2">
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
// Variables globales
let contactosSeleccionados = [];
let direccionesCount = 0;
// Las consultas a RENIEC/SUNAT se realizan a través del proxy seguro en el backend.

// Buscar RUC en BD y API
async function buscarRUC() {
    const ruc = document.getElementById('ruc').value.trim();
    const alertDiv = document.getElementById('rucAlert');

    if (ruc.length !== 11 || isNaN(ruc)) {
        alertDiv.innerHTML = `
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> El RUC debe tener 11 dígitos numéricos
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        return;
    }

    alertDiv.innerHTML = `
        <div class="alert alert-info">
            <i class="bi bi-hourglass-split"></i> Buscando RUC...
        </div>
    `;

    try {
        // Primero buscar en la base de datos
        const responseDB = await fetch(`/clientes/verificar-ruc/${ruc}`);
        const dataDB = await responseDB.json();

        if (dataDB.existe) {
            alertDiv.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> Este RUC ya está registrado en el sistema
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            return;
        }

        // Si no existe en BD, consultar SUNAT a través del proxy seguro del backend
        const responseAPI = await fetch(`/api-externa/consultar-ruc/${ruc}`);
        const dataAPI = await responseAPI.json();

        if (dataAPI.success && dataAPI.data) {
            // Autocompletar campos
            document.getElementById('razon').value = dataAPI.data.razon_social || '';
            document.getElementById('direccion').value = dataAPI.data.direccion || '';

            alertDiv.innerHTML = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> Datos encontrados en SUNAT
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        } else {
            alertDiv.innerHTML = `
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle"></i> RUC no encontrado en SUNAT. Complete los datos manualmente
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error:', error);
        alertDiv.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-x-circle"></i> Error al consultar. Complete los datos manualmente
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    }
}

// Buscar DNI en API RENIEC
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

// Buscar contacto por DNI en la BD
async function buscarContactoPorDni() {
    const dni = document.getElementById('dni_contacto').value.trim();
    const resultDiv = document.getElementById('contactoResultado');

    if (dni.length !== 8 || isNaN(dni)) {
        resultDiv.innerHTML = `
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> El DNI debe tener 8 dígitos
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        return;
    }

    try {
        const response = await fetch(`/contactos/buscar-dni/${dni}`);
        const data = await response.json();

        if (data.success && data.contacto) {
            const contacto = data.contacto;
            const nombreCompleto = `${contacto.nombre} ${contacto.apellido_paterno} ${contacto.apellido_materno}`;

            resultDiv.innerHTML = `
                <div class="card border-success">
                    <div class="card-body">
                        <h6 class="card-title text-success">
                            <i class="bi bi-person-check-fill"></i> Contacto Encontrado
                        </h6>
                        <p class="card-text small mb-1">
                            <strong>Nombre:</strong> ${nombreCompleto}<br>
                            <strong>DNI:</strong> ${contacto.dni}<br>
                            <strong>Cargo:</strong> ${contacto.cargo}<br>
                            <strong>Teléfono:</strong> ${contacto.telefono}<br>
                            <strong>Email:</strong> ${contacto.email}
                        </p>
                        <button class="btn btn-sm btn-success w-100 mt-2" onclick="agregarContactoParaAsignar(${contacto.id}, '${nombreCompleto.replace(/'/g, "\\'")}')">
                            <i class="bi bi-plus-circle"></i> Seleccionar para Asignar
                        </button>
                    </div>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="bi bi-info-circle"></i> <strong>Contacto no encontrado</strong><br>
                    Use el botón "Crear Contacto Nuevo" para registrarlo
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            // Copiar DNI al formulario de nuevo contacto
            document.getElementById('new_dni').value = dni;

            // Abrir automáticamente el formulario de nuevo contacto
            const collapseElement = document.getElementById('nuevoContactoForm');
            const collapse = new bootstrap.Collapse(collapseElement, {
                show: true
            });
        }
    } catch (error) {
        console.error('Error:', error);
        resultDiv.innerHTML = `
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-x-circle"></i> Error al buscar contacto
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
    }
}

// Crear contacto solamente (sin asignar todavía)
async function crearContactoSolamente() {
    const dni = document.getElementById('new_dni').value.trim();
    const nombre = document.getElementById('new_nombre').value.trim();
    const apellidoPaterno = document.getElementById('new_apellido_paterno').value.trim();
    const apellidoMaterno = document.getElementById('new_apellido_materno').value.trim();
    const telefono = document.getElementById('new_telefono').value.trim();
    const email = document.getElementById('new_email').value.trim();
    const cargo = document.getElementById('new_cargo').value.trim();

    if (!dni || !nombre || !apellidoPaterno || !apellidoMaterno || !telefono || !email || !cargo) {
        alert('Por favor complete todos los campos del contacto');
        return;
    }

    if (dni.length !== 8) {
        alert('El DNI debe tener 8 dígitos');
        return;
    }

    try {
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

        if (data.id) {
            const nombreCompleto = `${nombre} ${apellidoPaterno} ${apellidoMaterno}`;

            // Agregar automáticamente a la lista de contactos para asignar
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

            // Mostrar mensaje de éxito
            const resultDiv = document.getElementById('contactoResultado');
            resultDiv.innerHTML = `
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> Contacto creado y agregado a la lista de asignación
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            // Limpiar búsqueda
            document.getElementById('dni_contacto').value = '';
        } else {
            alert('Error al crear el contacto');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al crear el contacto. Verifique que el DNI no esté duplicado.');
    }
}

// Agregar contacto a la lista para asignar (no guarda todavía)
function agregarContactoParaAsignar(contactoId, nombreCompleto) {
    // Verificar si ya está en la lista
    if (contactosSeleccionados.find(c => c.id === contactoId)) {
        alert('Este contacto ya está en la lista para asignar');
        return;
    }

    contactosSeleccionados.push({
        id: contactoId,
        nombre: nombreCompleto
    });

    actualizarListaContactos();

    // Limpiar resultado de búsqueda
    document.getElementById('contactoResultado').innerHTML = `
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> Contacto agregado a la lista. Será asignado al guardar el cliente
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    document.getElementById('dni_contacto').value = '';
}

// Actualizar lista de contactos seleccionados
function actualizarListaContactos() {
    const container = document.getElementById('contactosSeleccionados');
    const hiddenInput = document.getElementById('contactosHidden');

    if (contactosSeleccionados.length > 0) {
        const ids = contactosSeleccionados.map(c => c.id);
        hiddenInput.value = ids.join(',');

        let html = '<div class="list-group list-group-flush">';
        contactosSeleccionados.forEach((contacto, index) => {
            html += `
                <div class="list-group-item d-flex justify-content-between align-items-center p-2 bg-light">
                    <small>
                        <i class="bi bi-person-check-fill text-success"></i>
                        <strong>${index + 1}.</strong> ${contacto.nombre}
                    </small>
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
        container.innerHTML = `
            <div class="alert alert-secondary small">
                <i class="bi bi-info-circle"></i> No hay contactos seleccionados
            </div>
        `;
    }
}

// Remover contacto de la lista
function removerContacto(contactoId) {
    contactosSeleccionados = contactosSeleccionados.filter(c => c.id !== contactoId);
    actualizarListaContactos();
}

// Agregar dirección adicional
function agregarDireccion() {
    direccionesCount++;
    const container = document.getElementById('direccionesContainer');

    const direccionHtml = `
        <div class="card mb-2" id="direccion-${direccionesCount}">
            <div class="card-body p-2">
                <div class="row g-2">
                    <div class="col-md-10">
                        <label class="form-label small mb-1">
                            <i class="bi bi-building"></i> Agencia ${direccionesCount}
                        </label>
                        <input type="text"
                               name="direcciones[]"
                               class="form-control form-control-sm"
                               placeholder="Ej: Av. Principal 123, Lima"
                               maxlength="250">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small mb-1 invisible">-</label>
                        <button type="button" class="btn btn-sm btn-danger w-100" onclick="removerDireccion(${direccionesCount})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', direccionHtml);
}

// Remover dirección
function removerDireccion(id) {
    const direccion = document.getElementById(`direccion-${id}`);
    if (direccion) {
        direccion.remove();
    }
}

// Validación del formulario
document.getElementById('formCliente').addEventListener('submit', function(e) {
    const ruc = document.getElementById('ruc').value;
    const razon = document.getElementById('razon').value;
    const direccion = document.getElementById('direccion').value;
    const telefono1 = document.getElementById('telefono1').value;

    if (ruc.length !== 11 || isNaN(ruc)) {
        e.preventDefault();
        alert('El RUC debe tener 11 dígitos numéricos');
        return;
    }

    if (!razon || !direccion || !telefono1) {
        e.preventDefault();
        alert('Por favor complete todos los campos obligatorios');
        return;
    }

    // Mostrar mensaje de confirmación
    if (contactosSeleccionados.length > 0) {
        const confirmar = confirm(`Se creará el cliente y se asignarán ${contactosSeleccionados.length} contacto(s). ¿Desea continuar?`);
        if (!confirmar) {
            e.preventDefault();
        }
    }
});

// Solo números en campos numéricos
document.querySelectorAll('input[maxlength="11"], input[maxlength="8"], input[maxlength="15"]').forEach(input => {
    input.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>
@endpush
