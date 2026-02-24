@extends('layouts.app')
@section('title', 'Editar Proforma')
@section('page-title')
<div class="section-header">
    <h2><i class="bi bi-file-earmark-text"></i> Editar Proforma</h2>
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
    #wrapDireccion { transition: opacity .25s; }
    #wrapDireccion.loading { opacity: .5; pointer-events: none; }
</style>
@endpush

@section('content')
<form action="{{ route('proformas.update', $proforma->id) }}" method="POST" id="formProforma">
    @csrf
    @method('PUT')

    <!-- Header -->
    <div class="row mb-3">
        <div class="col-md-6 text-end">
            <div class="d-inline-block bg-light p-3 rounded">
                <h5 class="mb-0">N° COTIZACIÓN</h5>
                <h3 class="text-primary mb-0">NCT-{{ str_pad($proforma->id, 11, '0', STR_PAD_LEFT) }}</h3>
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
                        {{-- Fila 1: Cliente y Dirección --}}
                        <div class="col-md-6 mb-3">
                            <label for="cliente_id" class="form-label">
                                <i class="bi bi-people"></i> Cliente <span class="text-danger">*</span>
                            </label>
                            <select class="form-select select2-clientes @error('cliente_id') is-invalid @enderror"
                                    id="cliente_id" name="cliente_id" required>
                                <option value="">Seleccionar cliente...</option>
                                @if($proforma->cliente)
                                    <option value="{{ $proforma->cliente_id }}" selected>
                                        {{ $proforma->cliente->razon }} - {{ $proforma->cliente->ruc }}
                                    </option>
                                @endif
                            </select>
                            @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3" id="wrapDireccion">
                            <label for="direccion_id" class="form-label">
                                <i class="bi bi-geo-alt"></i> Dirección de entrega
                            </label>
                            <select class="form-select @error('direccion_id') is-invalid @enderror"
                                    id="direccion_id" name="direccion_id">
                                <option value="">— Cargando... —</option>
                            </select>
                            <small class="text-muted" id="hintDireccion"></small>
                            @error('direccion_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Fila 2: Nota, Orden y Fechas --}}
                        <div class="col-md-3 mb-3">
                            <label for="nota" class="form-label">
                                <i class="bi bi-chat-left-text"></i> Nota
                            </label>
                            <input type="text" class="form-control @error('nota') is-invalid @enderror"
                                id="nota" name="nota" value="{{ old('nota', $proforma->nota) }}"
                                placeholder="Nota adicional">
                            @error('nota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="orden" class="form-label">
                                <i class="bi bi-hash"></i> N° Orden
                            </label>
                            <input type="text" class="form-control @error('orden') is-invalid @enderror"
                                id="orden" name="orden" value="{{ old('orden', $proforma->orden) }}"
                                placeholder="Número de orden">
                            @error('orden')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="fecha_creacion" class="form-label">
                                <i class="bi bi-calendar-check"></i> F. Creación <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('fecha_creacion') is-invalid @enderror"
                                id="fecha_creacion" name="fecha_creacion"
                                value="{{ old('fecha_creacion', $proforma->fecha_creacion ? $proforma->fecha_creacion->format('Y-m-d') : '') }}"
                                required>
                            @error('fecha_creacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="fecha_fin" class="form-label">
                                <i class="bi bi-calendar-x"></i> Fecha Fin <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror"
                                id="fecha_fin" name="fecha_fin"
                                value="{{ old('fecha_fin', $proforma->fecha_fin ? $proforma->fecha_fin->format('Y-m-d') : '') }}"
                                required>
                            @error('fecha_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                                    <th style="width:10%;">Código</th>
                                    <th style="width:35%;">Descripción</th>
                                    <th style="width:10%;">Cantidad</th>
                                    <th style="width:12%;">Precio Unit.</th>
                                    <th style="width:13%;">Desc. Cliente (%)</th>
                                    <th style="width:12%;">Subtotal</th>
                                    <th style="width:8%;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="productosContainer">
                                @php $productoCount = 0; @endphp
                                @forelse($proforma->productos as $producto)
                                <tr id="producto-{{ $productoCount }}">
                                    <td>
                                        <span class="badge bg-info">{{ $producto->codigo_p }}</span>
                                        <input type="hidden" name="productos[{{ $productoCount }}][id]" value="{{ $producto->id }}">
                                    </td>
                                    <td>
                                        <strong>{{ $producto->nombre }}</strong>
                                        <br><small class="text-muted">{{ $producto->marca }}</small>
                                    </td>
                                    <td>
                                        <input type="number"
                                               name="productos[{{ $productoCount }}][cantidad]"
                                               class="form-control form-control-sm cantidad-input"
                                               value="{{ $producto->pivot->cantidad ?? 1 }}"
                                               min="1" max="{{ $producto->stock }}"
                                               data-stock="{{ $producto->stock }}" required
                                               onchange="calcularSubtotal({{ $productoCount }})">
                                    </td>
                                    <td>
                                        <input type="number"
                                               name="productos[{{ $productoCount }}][precio_unitario]"
                                               class="form-control form-control-sm precio-input"
                                               step="0.01" min="0"
                                               value="{{ $producto->pivot->precio_unitario ?? $producto->precio_lista }}"
                                               required
                                               onchange="calcularSubtotal({{ $productoCount }})">
                                    </td>
                                    <td>
                                        <input type="number"
                                               name="productos[{{ $productoCount }}][descuento_cliente]"
                                               class="form-control form-control-sm descuento-input"
                                               step="0.01" min="0"
                                               max="{{ $producto->descuento ? $producto->descuento->porcentaje : 100 }}"
                                               value="{{ $producto->pivot->descuento_cliente ?? 0 }}"
                                               data-max-descuento="{{ $producto->descuento ? $producto->descuento->porcentaje : 100 }}"
                                               onchange="validarDescuento({{ $productoCount }})">
                                    </td>
                                    <td>
                                        @php
                                            $sub = ($producto->pivot->cantidad ?? 1)
                                                 * ($producto->pivot->precio_unitario ?? $producto->precio_lista)
                                                 * (1 - ($producto->pivot->descuento_cliente ?? 0) / 100);
                                        @endphp
                                        <span class="subtotal" id="subtotal-{{ $productoCount }}">
                                            S/. {{ number_format($sub, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="eliminarProducto({{ $productoCount }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @php $productoCount++; @endphp
                                @empty
                                @endforelse
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
                            <label class="form-label"><i class="bi bi-gear"></i> Configuración</label>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="transaccion_id" class="form-label"><i class="bi bi-coin"></i> Transacción</label>
                                    <select class="form-select" id="transaccion_id" name="transaccion_id">
                                        <option value="">Seleccionar...</option>
                                        @foreach($transacciones as $t)
                                            <option value="{{ $t->id }}" {{ old('transaccion_id', $proforma->transaccion_id) == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="temperatura_id" class="form-label"><i class="bi bi-thermometer-sun"></i> Temperatura</label>
                                    <select class="form-select" id="temperatura_id" name="temperatura_id">
                                        <option value="">Seleccionar...</option>
                                        @foreach($temperaturas as $t)
                                            <option value="{{ $t->id }}" {{ old('temperatura_id', $proforma->temperatura_id) == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="estado_id" class="form-label"><i class="bi bi-flag"></i> Estado</label>
                                    <select class="form-select" id="estado_id" name="estado_id">
                                        <option value="">Seleccionar...</option>
                                        @foreach($estados as $e)
                                            <option value="{{ $e->id }}" {{ old('estado_id', $proforma->estado_id) == $e->id ? 'selected' : '' }}>{{ $e->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-currency-dollar"></i> Moneda</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="moneda" id="monedaDolares" value="Dolares"
                                            {{ old('moneda', $proforma->moneda) == 'Dolares' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="monedaDolares">Dólares ($)</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="moneda" id="monedaSoles" value="Soles"
                                            {{ old('moneda', $proforma->moneda) == 'Soles' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="monedaSoles">Soles (S/.)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-receipt"></i> Resumen</label>
                            <div class="card bg-light p-3">
                                <div class="mb-3">
                                    <label class="form-label mb-1">Subtotal</label>
                                    <input type="text" class="form-control form-control-lg" id="subtotal" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label mb-1">IGV (18%)</label>
                                    <input type="text" class="form-control form-control-lg" id="igv" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label mb-1 fw-bold">TOTAL</label>
                                    <input type="text" class="form-control form-control-lg fw-bold fs-3 text-success" id="totalResumen" readonly>
                                </div>
                                <input type="hidden" name="sub_total"  id="sub_total_hidden">
                                <input type="hidden" name="monto_igv" id="monto_igv_hidden">
                                <input type="hidden" name="total"      id="total_hidden">
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('proformas.index') }}" class="btn btn-secondary me-md-2">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-circle"></i> Actualizar Proforma
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
                            <tr><th>Código</th><th>Descripción</th><th>Marca</th><th>Precio</th><th>Stock</th><th>Acción</th></tr>
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
$(document).ready(function () {

    // ── Prevenir ENTER en inputs de productos ──────────────────────────────
    $(document).on('keydown', '.cantidad-input, .precio-input, .descuento-input', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const index = $(this).closest('tr').attr('id').replace('producto-', '');
            calcularSubtotal(index);
            $(this).closest('td').next('td').find('input').focus();
            return false;
        }
    });

    // ── SELECT2 CLIENTES ───────────────────────────────────────────────────
    $('.select2-clientes').select2({
        placeholder: 'Buscar por RUC o Razón Social...',
        allowClear: true,
        ajax: {
            url: '{{ route("api.clientes.buscar") }}',
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term, page: params.page }),
            processResults: data => ({ results: data.results }),
            cache: true,
        },
        minimumInputLength: 2,
        templateResult: formatCliente,
        templateSelection: formatCliente,
    });

    function formatCliente(c) {
        if (!c.id) return c.text;
        if (c.razon) return $(`<span>${c.razon}<br><small class="text-muted">RUC: ${c.ruc}</small></span>`);
        return $(`<span>${c.text}</span>`);
    }

    // ── CARGA DINÁMICA DE DIRECCIONES ─────────────────────────────────────
    const API_DIR       = '{{ url("/api/clientes") }}';
    // Valor guardado en la proforma (null → vacío, o ID de la dirección)
    const savedDirId    = '{{ old("direccion_id", $proforma->direccion_id ?? "") }}';

    function cargarDirecciones(clienteId, preselect) {
        const sel  = $('#direccion_id');
        const hint = $('#hintDireccion');
        const wrap = $('#wrapDireccion');

        sel.empty().append('<option value="">— Cargando... —</option>');
        hint.text('').removeClass('text-danger');
        wrap.addClass('loading');

        $.getJSON(`${API_DIR}/${clienteId}/direcciones`)
            .done(function (dirs) {
                sel.empty().append('<option value="">— Sin especificar —</option>');
                dirs.forEach(d => {
                    const selected = String(preselect) === String(d.id) ? 'selected' : '';
                    sel.append(`<option value="${d.id}" ${selected}>${d.label}</option>`);
                });
                hint.text(`${dirs.length} dirección(es) disponible(s).`);
            })
            .fail(function () {
                sel.empty().append('<option value="">— Error al cargar —</option>');
                hint.addClass('text-danger').text('No se pudieron cargar las direcciones.');
            })
            .always(() => wrap.removeClass('loading'));
    }

    // Al cambiar el cliente, recargar direcciones
    $('#cliente_id').on('change', function () {
        const cid = $(this).val();
        if (cid) {
            cargarDirecciones(cid, '');   // al cambiar manualmente no preseleccionar
        } else {
            $('#direccion_id').empty().append('<option value="">— Seleccione un cliente primero —</option>');
            $('#hintDireccion').text('');
        }
    });

    // Al cargar la página: cargar las direcciones del cliente actual y preseleccionar
    @if($proforma->cliente_id)
    cargarDirecciones({{ $proforma->cliente_id }}, savedDirId);
    @else
    $('#direccion_id').empty().append('<option value="">— Seleccione un cliente primero —</option>');
    @endif

    // ── GESTIÓN DE PRODUCTOS ───────────────────────────────────────────────
    // IDs de productos ya cargados (para evitar duplicados desde modal)
    let productoCount = {{ $proforma->productos->count() }};
    let productosSeleccionados = [
        @foreach($proforma->productos as $p) '{{ $p->id }}', @endforeach
    ];

    document.querySelectorAll('.btn-seleccionar-producto').forEach(button => {
        button.addEventListener('click', function () {
            const id     = this.dataset.id;
            const codigo = this.dataset.codigo;
            const nombre = this.dataset.nombre;
            const precio = parseFloat(this.dataset.precio);
            const stock  = parseInt(this.dataset.stock);
            const desc   = parseFloat(this.dataset.descuento);

            if (productosSeleccionados.includes(id)) { alert('Este producto ya está en la lista'); return; }

            productoCount++;
            productosSeleccionados.push(id);

            document.getElementById('productosContainer').insertAdjacentHTML('beforeend', `
                <tr id="producto-${productoCount}">
                    <td>
                        <span class="badge bg-info">${codigo}</span>
                        <input type="hidden" name="productos[${productoCount}][id]" value="${id}">
                    </td>
                    <td><strong>${nombre}</strong></td>
                    <td>
                        <input type="number" name="productos[${productoCount}][cantidad]"
                               class="form-control form-control-sm cantidad-input"
                               value="1" min="1" max="${stock}" required data-stock="${stock}"
                               onchange="calcularSubtotal(${productoCount})">
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
                               step="0.01" min="0" max="${desc}" value="0.00"
                               data-max-descuento="${desc}"
                               onchange="validarDescuento(${productoCount})"
                               placeholder="Máx: ${desc}%">
                    </td>
                    <td><span class="subtotal" id="subtotal-${productoCount}">S/. ${precio.toFixed(2)}</span></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" onclick="eliminarProducto(${productoCount})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>`);
            bootstrap.Modal.getInstance(document.getElementById('modalBuscarProducto')).hide();
            calcularTotales();
        });
    });

    // Buscador modal
    document.getElementById('buscarProducto').addEventListener('keyup', function () {
        const term = this.value.toLowerCase();
        document.querySelectorAll('#listaProductos tbody tr').forEach(row => {
            row.style.display = [...row.cells].slice(0, -1).some(c => c.textContent.toLowerCase().includes(term)) ? '' : 'none';
        });
    });

    // Cálculos
    window.calcularSubtotal = function (index) {
        const qty   = parseFloat(document.querySelector(`input[name="productos[${index}][cantidad]"]`)?.value) || 0;
        const price = parseFloat(document.querySelector(`input[name="productos[${index}][precio_unitario]"]`)?.value) || 0;
        const disc  = parseFloat(document.querySelector(`input[name="productos[${index}][descuento_cliente]"]`)?.value) || 0;
        const el    = document.getElementById(`subtotal-${index}`);
        if (el) el.textContent = `S/. ${(qty * price * (1 - disc / 100)).toFixed(2)}`;
        calcularTotales();
    };

    window.validarDescuento = function (index) {
        const input = document.querySelector(`input[name="productos[${index}][descuento_cliente]"]`);
        const max   = parseFloat(input?.dataset.maxDescuento) || 100;
        if (parseFloat(input?.value) > max) { input.value = max; alert(`El descuento máximo es ${max}%`); }
        calcularSubtotal(index);
    };

    window.eliminarProducto = function (index) {
        const row = document.getElementById(`producto-${index}`);
        if (row) {
            const id = row.querySelector(`input[name="productos[${index}][id]"]`)?.value;
            if (id) productosSeleccionados.splice(productosSeleccionados.indexOf(id), 1);
            row.remove();
            calcularTotales();
        }
    };

    window.calcularTotales = function () {
        let sub = 0;
        document.querySelectorAll('.subtotal').forEach(el => {
            const v = parseFloat(el.textContent.replace('S/. ', '').replace(',', ''));
            if (!isNaN(v)) sub += v;
        });
        const igv = sub * 0.18, total = sub + igv;
        document.getElementById('subtotal').value      = `S/. ${sub.toFixed(2)}`;
        document.getElementById('igv').value           = `S/. ${igv.toFixed(2)}`;
        document.getElementById('totalResumen').value  = `S/. ${total.toFixed(2)}`;
        document.getElementById('totalGeneral').textContent = `S/. ${total.toFixed(2)}`;
        document.getElementById('sub_total_hidden').value  = sub.toFixed(2);
        document.getElementById('monto_igv_hidden').value  = igv.toFixed(2);
        document.getElementById('total_hidden').value      = total.toFixed(2);
    };

    document.getElementById('formProforma').addEventListener('submit', function (e) {
        if (!document.getElementById('cliente_id').value) {
            e.preventDefault(); alert('Debe seleccionar un cliente'); return;
        }
    });

    // Calcular totales al cargar la página con productos existentes
    calcularTotales();
});
</script>
@endpush
@endsection
