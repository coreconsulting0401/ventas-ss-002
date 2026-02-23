@extends('layouts.app')
@section('title', 'Crear Proforma')
@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-file-earmark-text"></i> Nueva Proforma
    </h2>
    <a href="{{ route('proformas.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single { height: 38px; border: 1px solid #ced4da; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 36px; }
    .select2-results__option { padding: 6px 12px; }
</style>
@endpush

@section('content')
<form action="{{ route('proformas.store') }}" method="POST" id="formProforma">
    @csrf

    <!-- Header con Número de Cotización -->
    <div class="row mb-3">
        <div class="col-md-6 text-end">
            <div class="d-inline-block bg-light p-3 rounded">
                <h5 class="mb-0">N° COTIZACIÓN</h5>
                <h3 class="text-primary mb-0">NCT-{{ str_pad(App\Models\Proforma::count() + 1, 11, '0', STR_PAD_LEFT) }}</h3>
            </div>
        </div>
    </div>

    <!-- Información del Cliente -->
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
                            <select class="form-select select2-clientes @error('cliente_id') is-invalid @enderror"
                                    id="cliente_id"
                                    name="cliente_id"
                                    required>
                                <option value="">Seleccionar cliente...</option>
                                @if(old('cliente_id'))
                                    <option value="{{ old('cliente_id') }}" selected></option>
                                @endif
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
                                   value="{{ old('nota') }}"
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
                                   value="{{ old('orden') }}"
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
                                   value="{{ old('fecha_creacion', date('Y-m-d')) }}"
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
                                   value="{{ old('fecha_fin', date('Y-m-d', strtotime('+30 days'))) }}"
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

    <!-- Lista de Productos -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-cart-plus"></i> Productos</h5>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalBuscarProducto">
                        <i class="bi bi-search"></i> Buscar Producto
                    </button>
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
                                <!-- Los productos se agregarán aquí dinámicamente -->
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <th colspan="5" class="text-end fw-bold">TOTAL:</th>
                                    <th id="totalGeneral" class="fw-bold fs-5">S/. 0.00</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuración y Resumen -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-gear"></i> Configuración</label>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="transaccion_id" class="form-label"><i class="bi bi-coin"></i> Transacción</label>
                                        <select class="form-select" id="transaccion_id" name="transaccion_id">
                                            <option value="">Seleccionar...</option>
                                            @foreach($transacciones as $transaccion)
                                                <option value="{{ $transaccion->id }}" {{ old('transaccion_id') == $transaccion->id ? 'selected' : '' }}>{{ $transaccion->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="temperatura_id" class="form-label"><i class="bi bi-thermometer-sun"></i> Temperatura</label>
                                        <select class="form-select" id="temperatura_id" name="temperatura_id">
                                            <option value="">Seleccionar...</option>
                                            @foreach($temperaturas as $temperatura)
                                                <option value="{{ $temperatura->id }}" {{ old('temperatura_id') == $temperatura->id ? 'selected' : '' }}>{{ $temperatura->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="estado_id" class="form-label"><i class="bi bi-flag"></i> Estado</label>
                                        <select class="form-select" id="estado_id" name="estado_id">
                                            <option value="">Seleccionar...</option>
                                            @foreach($estados as $estado)
                                                <option value="{{ $estado->id }}" {{ old('estado_id') == $estado->id ? 'selected' : '' }}>{{ $estado->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><i class="bi bi-currency-dollar"></i> Moneda</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="moneda" id="monedaDolares" value="Dolares" checked>
                                            <label class="form-check-label" for="monedaDolares">Dólares ($)</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="moneda" id="monedaSoles" value="Soles">
                                            <label class="form-check-label" for="monedaSoles">Soles (S/.)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <label class="form-label"><i class="bi bi-receipt"></i> Resumen</label>
                                <div class="card bg-light p-3">
                                    <div class="mb-3">
                                        <label class="form-label mb-1">Subtotal</label>
                                        <input type="text" class="form-control form-control-lg" id="subtotal" value="S/. 0.00" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label mb-1">IGV (18%)</label>
                                        <input type="text" class="form-control form-control-lg" id="igv" value="S/. 0.00" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label mb-1 fw-bold">TOTAL</label>
                                        <input type="text" class="form-control form-control-lg fw-bold fs-3 text-success" id="totalResumen" value="S/. 0.00" readonly>
                                    </div>
                                    <input type="hidden" name="sub_total" id="sub_total_hidden">
                                    <input type="hidden" name="monto_igv" id="monto_igv_hidden">
                                    <input type="hidden" name="total" id="total_hidden">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('proformas.index') }}" class="btn btn-secondary me-md-2">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Generar Proforma
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal Buscar Producto -->
<div class="modal fade" id="modalBuscarProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Buscar Producto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="buscarProducto" class="form-control mb-3" placeholder="Buscar por código o nombre...">
                <div class="table-responsive">
                    <table class="table table-hover" id="listaProductos">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Marca</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productos as $producto)
                            <tr>
                                <td>{{ $producto->codigo_p }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->marca }}</td>
                                <td>S/. {{ number_format($producto->precio_lista, 2) }}</td>
                                <td>{{ $producto->stock }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-success btn-seleccionar-producto"
                                        data-id="{{ $producto->id }}"
                                        data-codigo="{{ $producto->codigo_p }}"
                                        data-nombre="{{ $producto->nombre }}"
                                        data-precio="{{ $producto->precio_lista }}"
                                        data-stock="{{ $producto->stock }}"
                                        data-descuento="{{ $producto->descuento ? $producto->descuento->porcentaje : 0 }}">
                                        Seleccionar
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // ==========================================
    // PREVENIR ENVÍO DEL FORMULARIO CON ENTER EN INPUTS DE PRODUCTOS
    // ==========================================
    $(document).on('keydown', '.cantidad-input, .precio-input, .descuento-input', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            e.stopPropagation();

            // Solo calcular, no enviar formulario
            const index = $(this).closest('tr').attr('id').replace('producto-', '');
            if ($(this).hasClass('cantidad-input')) {
                validarStock(this, index);
            }
            calcularSubtotal(index);

            // Mover foco al siguiente input si existe
            const currentInput = $(this);
            const nextInput = currentInput.closest('td').next('td').find('input');
            if (nextInput.length) {
                nextInput.focus();
            }
            return false;
        }
    });

    // ==========================================
    // SELECT2 PARA CLIENTES
    // ==========================================
    $('.select2-clientes').select2({
        placeholder: 'Buscar por RUC o Razón Social...',
        allowClear: true,
        ajax: {
            url: '{{ route("api.clientes.buscar") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data) {
                return {
                    results: data.results
                };
            },
            cache: true
        },
        minimumInputLength: 2,
        templateResult: formatCliente,
        templateSelection: formatCliente
    });

    function formatCliente(cliente) {
        if (!cliente.id) {
            return cliente.text;
        }
        if(cliente.razon) {
            return $('<span>' + cliente.razon + '<br><small class="text-muted">RUC: ' + cliente.ruc + '</small></span>');
        }
        return $('<span>' + cliente.text + '</span>');
    }

    // Cargar cliente si hay valor antiguo (por error de validación)
    @if(old('cliente_id'))
    $.ajax({
        url: '{{ route("api.clientes.obtener", ["id" => "__ID__"]) }}'.replace('__ID__', '{{ old("cliente_id") }}'),
        type: 'GET',
        success: function(data) {
            var option = new Option(data.text, data.id, true, true);
            $('.select2-clientes').append(option).trigger('change');
        }
    });
    @endif

    // ==========================================
    // GESTIÓN DE PRODUCTOS
    // ==========================================
    let productoCount = 0;
    let productosSeleccionados = [];

    // Seleccionar producto desde modal
    document.querySelectorAll('.btn-seleccionar-producto').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const codigo = this.dataset.codigo;
            const nombre = this.dataset.nombre;
            const precio = parseFloat(this.dataset.precio);
            const stock = parseInt(this.dataset.stock);
            const descuentoProducto = parseFloat(this.dataset.descuento);

            if (productosSeleccionados.includes(id)) {
                alert('Este producto ya está en la lista');
                return;
            }

            productoCount++;
            productosSeleccionados.push(id);

            const container = document.getElementById('productosContainer');
            const productoHtml = `
                <tr id="producto-${productoCount}">
                    <td>
                        <span class="badge bg-info">${codigo}</span>
                        <input type="hidden" name="productos[${productoCount}][id]" value="${id}">
                    </td>
                    <td><strong>${nombre}</strong></td>
                    <td>
                        <input type="number" name="productos[${productoCount}][cantidad]"
                               class="form-control form-control-sm cantidad-input"
                               value="1" min="1" max="${stock}" required
                               onchange="calcularSubtotal(${productoCount})"
                               data-stock="${stock}">
                    </td>
                    <td>
                        <input type="number" name="productos[${productoCount}][precio_unitario]"
                               class="form-control form-control-sm precio-input"
                               step="0.01" min="0" value="${precio.toFixed(2)}" required
                               onchange="calcularSubtotal(${productoCount})">
                    </td>
                    <td>
                        <input type="number" name="productos[${productoCount}][descuento_cliente]"
                               class="form-control form-control-sm descuento-input"
                               step="0.01" min="0" max="${descuentoProducto}" value="0.00"
                               data-max-descuento="${descuentoProducto}"
                               onchange="validarDescuento(${productoCount})"
                               placeholder="Máx: ${descuentoProducto}%">
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

            const modal = bootstrap.Modal.getInstance(document.getElementById('modalBuscarProducto'));
            modal.hide();

            calcularTotales();
        });
    });

    // ==========================================
    // VALIDAR STOCK
    // ==========================================
    function validarStock(input, index) {
        const cantidad = parseInt(input.value) || 0;
        const stock = parseInt(input.dataset.stock) || 0;

        if (cantidad > stock) {
            input.value = stock;
            alert(`La cantidad no puede exceder el stock disponible (${stock} unidades)`);
            input.focus();
            input.select();
            return false;
        }

        if (cantidad < 1) {
            input.value = 1;
            alert('La cantidad mínima es 1');
            return false;
        }

        return true;
    }

    // ==========================================
    // BUSCADOR EN MODAL
    // ==========================================
    document.getElementById('buscarProducto').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#listaProductos tbody tr');

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

    // ==========================================
    // CÁLCULOS
    // ==========================================
    window.calcularSubtotal = function(index) {
        const cantidadInput = document.querySelector(`input[name="productos[${index}][cantidad]"]`);
        const cantidad = parseFloat(cantidadInput?.value) || 0;
        const precio = parseFloat(document.querySelector(`input[name="productos[${index}][precio_unitario]"]`)?.value) || 0;
        const descuento = parseFloat(document.querySelector(`input[name="productos[${index}][descuento_cliente]"]`)?.value) || 0;

        // Validar stock antes de calcular
        if (cantidadInput && cantidadInput.dataset.stock) {
            const stock = parseInt(cantidadInput.dataset.stock) || 0;
            if (cantidad > stock) {
                cantidadInput.value = stock;
                alert(`La cantidad no puede exceder el stock disponible (${stock} unidades)`);
                return;
            }
        }

        const subtotal = cantidad * precio * (1 - descuento / 100);
        const subtotalElement = document.getElementById(`subtotal-${index}`);
        if (subtotalElement) {
            subtotalElement.textContent = `S/. ${subtotal.toFixed(2)}`;
        }

        calcularTotales();
    };

    window.validarDescuento = function(index) {
        const input = document.querySelector(`input[name="productos[${index}][descuento_cliente]"]`);
        const maxDescuento = parseFloat(input?.dataset.maxDescuento) || 100;
        const valor = parseFloat(input?.value) || 0;

        if (valor > maxDescuento) {
            input.value = maxDescuento;
            alert(`El descuento máximo permitido para este producto es ${maxDescuento}%`);
        }

        calcularSubtotal(index);
    };

    window.eliminarProducto = function(index) {
        const row = document.getElementById(`producto-${index}`);
        if (row) {
            const idInput = row.querySelector(`input[name="productos[${index}][id]"]`);
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
    };

    window.calcularTotales = function() {
        let subtotal = 0;

        document.querySelectorAll('.subtotal').forEach(element => {
            const valor = parseFloat(element.textContent.replace('S/. ', '').replace(',', ''));
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

        document.getElementById('sub_total_hidden').value = subtotal.toFixed(2);
        document.getElementById('monto_igv_hidden').value = igv.toFixed(2);
        document.getElementById('total_hidden').value = total.toFixed(2);
    };

    // ==========================================
    // VALIDACIÓN ANTES DE ENVIAR
    // ==========================================
    document.getElementById('formProforma').addEventListener('submit', function(e) {
        if (productosSeleccionados.length === 0) {
            e.preventDefault();
            alert('Debe agregar al menos un producto a la proforma');
            return false;
        }

        const clienteId = document.getElementById('cliente_id').value;
        if (!clienteId) {
            e.preventDefault();
            alert('Debe seleccionar un cliente');
            return false;
        }

        // Validar stock de todos los productos antes de enviar
        let stockValido = true;
        document.querySelectorAll('.cantidad-input').forEach(input => {
            const cantidad = parseInt(input.value) || 0;
            const stock = parseInt(input.dataset.stock) || 0;
            if (cantidad > stock) {
                stockValido = false;
            }
        });

        if (!stockValido) {
            e.preventDefault();
            alert('Hay productos con cantidad mayor al stock disponible. Corrija antes de enviar.');
            return false;
        }
    });
});
</script>
@endpush
@endsection
