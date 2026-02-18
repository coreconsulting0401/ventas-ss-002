@extends('layouts.app')

@section('title', 'Editar Proforma')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-file-earmark-text"></i> Editar Proforma
    </h2>
    <a href="{{ route('proformas.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<form action="{{ route('proformas.update', $proforma->id) }}" method="POST" id="proformaForm">
    @csrf
    @method('PUT')

    <!-- Header con Número de Cotización -->
    <div class="row mb-3">
        <div class="col-md-6 text-end">
            <div class="d-inline-block bg-light p-3 rounded">
                <h5 class="mb-0">N° COTIZACIÓN</h5>
                <h3 class="text-primary mb-0">NCT-{{ str_pad($proforma->id, 11, '0', STR_PAD_LEFT) }}</h3>
            </div>
        </div>
    </div>

    <!-- Información del Cliente y Configuración -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill"></i> Información del Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="cliente_id" class="form-label">
                                <i class="bi bi-people"></i> Cliente <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('cliente_id') is-invalid @enderror"
                                    id="cliente_id"
                                    name="cliente_id"
                                    required>
                                <option value="">Seleccionar cliente...</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('cliente_id', $proforma->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->razon }} - {{ $cliente->ruc }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="nota" class="form-label">
                                <i class="bi bi-chat-left-text"></i> Nota
                            </label>
                            <input type="text"
                                   class="form-control @error('nota') is-invalid @enderror"
                                   id="nota"
                                   name="nota"
                                   value="{{ old('nota', $proforma->nota) }}"
                                   placeholder="Nota adicional">
                            @error('nota')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2 mb-3">
                            <label for="orden" class="form-label">
                                <i class="bi bi-hash"></i> N° Orden
                            </label>
                            <input type="text"
                                   class="form-control @error('orden') is-invalid @enderror"
                                   id="orden"
                                   name="orden"
                                   value="{{ old('orden', $proforma->orden) }}"
                                   placeholder="Número de orden">
                            @error('orden')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2 mb-3">
                            <label for="fecha_creacion" class="form-label">
                                <i class="bi bi-calendar-check"></i> Fecha Creación <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control @error('fecha_creacion') is-invalid @enderror"
                                   id="fecha_creacion"
                                   name="fecha_creacion"
                                   value="{{ old('fecha_creacion', $proforma->fecha_creacion->format('Y-m-d')) }}"
                                   required>
                            @error('fecha_creacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2 mb-3">
                            <label for="fecha_fin" class="form-label">
                                <i class="bi bi-calendar-x"></i> Fecha Fin <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                   class="form-control @error('fecha_fin') is-invalid @enderror"
                                   id="fecha_fin"
                                   name="fecha_fin"
                                   value="{{ old('fecha_fin', $proforma->fecha_fin->format('Y-m-d')) }}"
                                   required>
                            @error('fecha_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Tabla de Productos - OCUPA TODO EL ANCHO -->
        <div class="col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-cart-plus"></i> Productos</h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalBuscarProducto">
                            <i class="bi bi-search"></i> Buscar Producto
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablaProductos">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 10%;">Código</th>
                                    <th style="width: 35%;">Descripción</th>
                                    <th style="width: 10%;">Cantidad</th>
                                    <th style="width: 12%;">Precio Unit.</th>
                                    <th style="width: 13%;">Desc. Cliente (%)</th>
                                    <th style="width: 12%;">Subtotal</th>
                                    <th style="width: 8%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="productosContainer">
                                @php
                                    $productoCount = 0;
                                @endphp
                                @forelse($proforma->productos as $producto)
                                <tr id="producto-{{ $productoCount }}">
                                    <td>
                                        <span class="badge bg-info">{{ $producto->codigo_p }}</span>
                                        <input type="hidden" name="productos[{{ $productoCount }}][id]" value="{{ $producto->id }}">
                                    </td>
                                    <td>
                                        <strong>{{ $producto->nombre }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $producto->marca }}</small>
                                    </td>
                                    <td>
                                        <input type="number" name="productos[{{ $productoCount }}][cantidad]" class="form-control form-control-sm cantidad-input" value="{{ $producto->pivot->cantidad ?? 1 }}" min="1" required onchange="calcularSubtotal({{ $productoCount }})">
                                    </td>
                                    <td>
                                        <input type="number" name="productos[{{ $productoCount }}][precio_unitario]" class="form-control form-control-sm precio-input" step="0.01" min="0" value="{{ $producto->pivot->precio_unitario ?? $producto->precio_lista }}" required onchange="calcularSubtotal({{ $productoCount }})">
                                    </td>
                                    <td>
                                        @php
                                            $maxDescuento = $producto->descuento ? $producto->descuento->porcentaje : 0;
                                            $descuentoCliente = $producto->pivot->descuento_cliente ?? 0;
                                        @endphp
                                        <input type="number" name="productos[{{ $productoCount }}][descuento_cliente]" class="form-control form-control-sm descuento-input" step="0.01" min="0" max="{{ $maxDescuento }}" value="{{ $descuentoCliente }}" data-max-descuento="{{ $maxDescuento }}" onchange="validarDescuento({{ $productoCount }})" placeholder="Máx: {{ $maxDescuento }}%">
                                    </td>
                                    <td>
                                        <span class="subtotal" id="subtotal-{{ $productoCount }}">
                                            S/. {{ number_format((($producto->pivot->cantidad ?? 1) * ($producto->pivot->precio_unitario ?? $producto->precio_lista) * (1 - ($descuentoCliente / 100))), 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="eliminarProducto({{ $productoCount }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @php
                                    $productoCount++;
                                @endphp
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="alert alert-info mb-0">
                                            <i class="bi bi-info-circle"></i>
                                            Esta proforma no tiene productos asignados. Haz clic en "Buscar Producto" para agregar productos.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <th colspan="5" class="text-end fw-bold">TOTAL:</th>
                                    <th id="totalGeneral" class="fw-bold fs-5">S/. {{ number_format($proforma->total, 2) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!-- Configuración de Proforma y Resumen -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Configuración de Proforma -->
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-gear"></i> Configuración
                                </label>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="transaccion_id" class="form-label">
                                            <i class="bi bi-coin"></i> Transacción
                                        </label>
                                        <select class="form-select" id="transaccion_id" name="transaccion_id">
                                            <option value="">Seleccionar...</option>
                                            @foreach($transacciones as $transaccion)
                                                <option value="{{ $transaccion->id }}" {{ old('transaccion_id', $proforma->transaccion_id) == $transaccion->id ? 'selected' : '' }}>
                                                    {{ $transaccion->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="temperatura_id" class="form-label">
                                            <i class="bi bi-thermometer-sun"></i> Temperatura
                                        </label>
                                        <select class="form-select" id="temperatura_id" name="temperatura_id">
                                            <option value="">Seleccionar...</option>
                                            @foreach($temperaturas as $temperatura)
                                                <option value="{{ $temperatura->id }}" {{ old('temperatura_id', $proforma->temperatura_id) == $temperatura->id ? 'selected' : '' }}>
                                                    {{ $temperatura->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="estado_id" class="form-label">
                                            <i class="bi bi-flag"></i> Estado
                                        </label>
                                        <select class="form-select" id="estado_id" name="estado_id">
                                            <option value="">Seleccionar...</option>
                                            @foreach($estados as $estado)
                                                <option value="{{ $estado->id }}" {{ old('estado_id', $proforma->estado_id) == $estado->id ? 'selected' : '' }}>
                                                    {{ $estado->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-currency-dollar"></i> Moneda
                                    </label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="moneda" id="monedaDolares" value="Dolares" {{ old('moneda', $proforma->moneda) == 'Dolares' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="monedaDolares">
                                                Dólares ($)
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="moneda" id="monedaSoles" value="Soles" {{ old('moneda', $proforma->moneda) == 'Soles' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="monedaSoles">
                                                Soles (S/.)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Resumen de Precios -->
                            <div>
                                <label class="form-label">
                                    <i class="bi bi-receipt"></i> Resumen
                                </label>
                                <div class="card bg-light p-3">
                                    <div class="mb-3">
                                        <label class="form-label mb-1">Subtotal</label>
                                        <input type="text" class="form-control form-control-lg" id="subtotal" value="S/. {{ number_format($proforma->sub_total, 2) }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label mb-1">IGV (18%)</label>
                                        <input type="text" class="form-control form-control-lg" id="igv" value="S/. {{ number_format($proforma->monto_igv, 2) }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-bold">TOTAL</label>
                                        <input type="text" class="form-control form-control-lg fw-bold fs-3 text-success" id="totalResumen" value="S/. {{ number_format($proforma->total, 2) }}" readonly>
                                    </div>
                                    <input type="hidden" name="sub_total" id="sub_total_hidden" value="{{ $proforma->sub_total }}">
                                    <input type="hidden" name="monto_igv" id="monto_igv_hidden" value="{{ $proforma->monto_igv }}">
                                    <input type="hidden" name="total" id="total_hidden" value="{{ $proforma->total }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('proformas.index') }}" class="btn btn-secondary me-md-2">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check-circle"></i> Actualizar Proforma
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal para Buscar Producto -->
<div class="modal fade" id="modalBuscarProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buscar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control mb-3" id="buscarProducto" placeholder="Buscar por código o descripción...">
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="table table-hover">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Marca</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="listaProductos">
                            @foreach($productos as $producto)
                            <tr>
                                <td>{{ $producto->codigo_p }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->marca }}</td>
                                <td>S/. {{ number_format($producto->precio_lista, 2) }}</td>
                                <td>
                                    <span class="badge {{ $producto->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $producto->stock }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-primary btn-seleccionar-producto"
                                            data-id="{{ $producto->id }}"
                                            data-codigo="{{ $producto->codigo_p }}"
                                            data-nombre="{{ $producto->nombre }}"
                                            data-precio="{{ $producto->precio_lista }}"
                                            data-stock="{{ $producto->stock }}"
                                            data-descuento="{{ $producto->descuento ? $producto->descuento->porcentaje : 0 }}">
                                        <i class="bi bi-plus"></i> Seleccionar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let productoCount = {{ $proforma->productos->count() }};
let productosSeleccionados = @json($proforma->productos->pluck('id')->toArray());

// Seleccionar producto desde modal
document.querySelectorAll('.btn-seleccionar-producto').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const codigo = this.dataset.codigo;
        const nombre = this.dataset.nombre;
        const precio = parseFloat(this.dataset.precio);
        const stock = parseInt(this.dataset.stock);
        const descuentoProducto = parseFloat(this.dataset.descuento);

        // Verificar si ya está seleccionado
        if (productosSeleccionados.includes(id)) {
            alert('Este producto ya está en la lista');
            return;
        }

        const container = document.getElementById('productosContainer');
        const productoHtml = `
            <tr id="producto-${productoCount}">
                <td>
                    <span class="badge bg-info">${codigo}</span>
                    <input type="hidden" name="productos[${productoCount}][id]" value="${id}">
                </td>
                <td>
                    <strong>${nombre}</strong>
                </td>
                <td>
                    <input type="number" name="productos[${productoCount}][cantidad]" class="form-control form-control-sm cantidad-input" value="1" min="1" max="${stock}" required onchange="calcularSubtotal(${productoCount})">
                </td>
                <td>
                    <input type="number" name="productos[${productoCount}][precio_unitario]" class="form-control form-control-sm precio-input" step="0.01" min="0" value="${precio.toFixed(2)}" required onchange="calcularSubtotal(${productoCount})">
                </td>
                <td>
                    <input type="number" name="productos[${productoCount}][descuento_cliente]" class="form-control form-control-sm descuento-input" step="0.01" min="0" max="${descuentoProducto}" value="0.00" data-max-descuento="${descuentoProducto}" onchange="validarDescuento(${productoCount})" placeholder="Máx: ${descuentoProducto}%">
                </td>
                <td>
                    <span class="subtotal" id="subtotal-${productoCount}">S/. ${(precio).toFixed(2)}</span>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="eliminarProducto(${productoCount})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        container.insertAdjacentHTML('beforeend', productoHtml);
        productosSeleccionados.push(id);
        productoCount++;

        // Cerrar modal usando Bootstrap nativo
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalBuscarProducto'));
        modal.hide();

        calcularTotales();
    });
});

// Buscar producto en modal
document.getElementById('buscarProducto').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#listaProductos tr');

    rows.forEach(row => {
        const cells = row.getElementsByTagName('td');
        let found = false;

        for (let i = 0; i < cells.length - 1; i++) {
            if (cells[i].textContent.toLowerCase().includes(searchTerm)) {
                found = true;
                break;
            }
        }

        row.style.display = found ? '' : 'none';
    });
});

// Calcular subtotal de un producto
function calcularSubtotal(index) {
    const cantidad = parseFloat(document.querySelector(`input[name="productos[${index}][cantidad]"]`).value) || 0;
    const precio = parseFloat(document.querySelector(`input[name="productos[${index}][precio_unitario]"]`).value) || 0;
    const descuento = parseFloat(document.querySelector(`input[name="productos[${index}][descuento_cliente]"]`).value) || 0;

    const subtotal = cantidad * precio * (1 - descuento / 100);
    document.getElementById(`subtotal-${index}`).textContent = `S/. ${subtotal.toFixed(2)}`;

    calcularTotales();
}

// Validar descuento no exceda el máximo
function validarDescuento(index) {
    const input = document.querySelector(`input[name="productos[${index}][descuento_cliente]"]`);
    const maxDescuento = parseFloat(input.dataset.maxDescuento) || 100;
    const valor = parseFloat(input.value) || 0;

    if (valor > maxDescuento) {
        input.value = maxDescuento;
        alert(`El descuento máximo permitido para este producto es ${maxDescuento}%`);
    }

    calcularSubtotal(index);
}

// Eliminar producto
function eliminarProducto(index) {
    const row = document.getElementById(`producto-${index}`);
    if (row) {
        const idInput = row.querySelector('input[name="productos[' + index + '][id]"]');
        if (idInput && idInput.value) {
            const id = idInput.value;
            const idx = productosSeleccionados.indexOf(id);
            if (idx > -1) {
                productosSeleccionados.splice(idx, 1);
            }
        }
        row.remove();
        calcularTotales();
    }
}

// Calcular totales
function calcularTotales() {
    let subtotal = 0;

    document.querySelectorAll('.subtotal').forEach(element => {
        const valor = parseFloat(element.textContent.replace('S/. ', ''));
        if (!isNaN(valor)) {
            subtotal += valor;
        }
    });

    const igv = subtotal * 0.18;
    const total = subtotal + igv;

    document.getElementById('subtotal').value = `S/. ${subtotal.toFixed(2)}`;
    document.getElementById('igv').value = `S/. ${igv.toFixed(2)}`;
    document.getElementById('totalResumen').value = `S/. ${total.toFixed(2)}`;
    document.getElementById('totalGeneral').textContent = `S/. ${total.toFixed(2)}`;

    // Actualizar campos hidden
    document.getElementById('sub_total_hidden').value = subtotal.toFixed(2);
    document.getElementById('monto_igv_hidden').value = igv.toFixed(2);
    document.getElementById('total_hidden').value = total.toFixed(2);
}

// Inicializar cálculos
document.addEventListener('DOMContentLoaded', function() {
    calcularTotales();
});
</script>
@endpush
@endsection
