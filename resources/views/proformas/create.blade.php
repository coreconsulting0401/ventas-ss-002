@extends('layouts.app')
@section('title', 'Crear Proforma')
@section('page-title')
<div class="section-header">
    <h2><i class="bi bi-file-earmark-text"></i> Nueva Proforma</h2>
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
    #wrapDireccion { transition: opacity .25s; }
    #wrapDireccion.loading { opacity: .5; pointer-events: none; }
    #wrapContacto { transition: opacity .25s; }
    #wrapContacto.loading { opacity: .5; pointer-events: none; }
    #cardContactoInfo { display: none; }
</style>
@endpush

@section('content')
<form action="{{ route('proformas.store') }}" method="POST" id="formProforma">
    @csrf

    <!-- Header -->
    <div class="row mb-3">
        <div class="col-md-6 text-end">
            <div class="d-inline-block bg-light p-3 rounded">
                <h5 class="mb-0">N° COTIZACIÓN</h5>
                <h3 class="text-primary mb-0">NCT-{{ str_pad(App\Models\Proforma::count() + 1, 11, '0', STR_PAD_LEFT) }}</h3>
            </div>
        </div>
    </div>

    <!-- Información del Cliente -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-lines-fill"></i> Información del Cliente</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cliente_id" class="form-label">
                        <i class="bi bi-people"></i> Cliente <span class="text-danger">*</span>
                    </label>
                    <select class="form-select select2-clientes @error('cliente_id') is-invalid @enderror"
                            id="cliente_id" name="cliente_id" required>
                        <option value="">Seleccionar cliente...</option>
                        @if(old('cliente_id'))
                            <option value="{{ old('cliente_id') }}" selected></option>
                        @endif
                    </select>
                    @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3" id="wrapDireccion">
                    <label for="direccion_id" class="form-label">
                        <i class="bi bi-geo-alt"></i> Dirección de entrega
                    </label>
                    <select class="form-select" id="direccion_id" name="direccion_id">
                        <option value="">— Seleccione un cliente primero —</option>
                    </select>
                    <small class="text-muted" id="hintDireccion"></small>
                </div>

                {{-- ── Contacto solicitante ─────────────────────────────── --}}
                <div class="col-md-6 mb-3" id="wrapContacto">
                    <label for="contacto_id" class="form-label">
                        <i class="bi bi-person-badge"></i> Contacto solicitante
                    </label>
                    <select class="form-select @error('contacto_id') is-invalid @enderror"
                            id="contacto_id" name="contacto_id">
                        <option value="">— Seleccione un cliente primero —</option>
                    </select>
                    <small class="text-muted" id="hintContacto"></small>
                    @error('contacto_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- ── Mini-card de datos del contacto seleccionado ──────── --}}
                <div class="col-md-6 mb-3">
                    <div id="cardContactoInfo" class="card border-info">
                        <div class="card-body py-2 px-3">
                            <h6 class="card-title text-info mb-1">
                                <i class="bi bi-person-check-fill"></i>
                                <span id="contactoNombre"></span>
                            </h6>
                            <div class="row g-1 small">
                                <div class="col-12 col-md-6 text-muted">
                                    <i class="bi bi-briefcase"></i> <span id="contactoCargo"></span>
                                </div>
                                <div class="col-12 col-md-6 text-muted">
                                    <i class="bi bi-telephone"></i> <span id="contactoTelefono"></span>
                                </div>
                                <div class="col-12 text-muted">
                                    <i class="bi bi-envelope"></i> <span id="contactoEmail"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="nota" class="form-label"><i class="bi bi-chat-left-text"></i> Nota</label>
                    <input type="text" class="form-control" id="nota" name="nota"
                           value="{{ old('nota') }}" placeholder="Nota adicional">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="orden" class="form-label"><i class="bi bi-hash"></i> N° Orden</label>
                    <input type="text" class="form-control" id="orden" name="orden"
                           value="{{ old('orden') }}" placeholder="Número">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="fecha_creacion" class="form-label text-nowrap">
                        <i class="bi bi-calendar-check"></i> Fecha Creación <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control @error('fecha_creacion') is-invalid @enderror"
                           id="fecha_creacion" name="fecha_creacion"
                           value="{{ old('fecha_creacion', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="fecha_fin" class="form-label text-nowrap">
                        <i class="bi bi-calendar-x"></i> Fecha Fin <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror"
                           id="fecha_fin" name="fecha_fin"
                           value="{{ old('fecha_fin', date('Y-m-d', strtotime('+30 days'))) }}" required>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-cart-plus"></i> Productos
                <span id="badgeMoneda" class="badge bg-warning text-dark ms-2">S/ Soles</span>
            </h5>
            <button type="button" class="btn btn-sm btn-info"
                    data-bs-toggle="modal" data-bs-target="#modalBuscarProducto">
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
                            <th style="width:8%;">Acc.</th>
                        </tr>
                    </thead>
                    <tbody id="productosContainer"></tbody>
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

    <!-- Configuración y Resumen -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <!-- Configuración -->
                <div class="col-md-6">
                    <label class="form-label"><i class="bi bi-gear"></i> Configuración</label>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="transaccion_id" class="form-label"><i class="bi bi-coin"></i> Transacción</label>
                            <select class="form-select" id="transaccion_id" name="transaccion_id">
                                <option value="">Seleccionar...</option>
                                @foreach($transacciones as $t)
                                    <option value="{{ $t->id }}" {{ old('transaccion_id') == $t->id ? 'selected' : '' }}>
                                        {{ $t->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="temperatura_id" class="form-label"><i class="bi bi-thermometer-sun"></i> Temperatura</label>
                            <select class="form-select" id="temperatura_id" name="temperatura_id">
                                <option value="">Seleccionar...</option>
                                @foreach($temperaturas as $t)
                                    <option value="{{ $t->id }}" {{ old('temperatura_id') == $t->id ? 'selected' : '' }}>
                                        {{ $t->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="estado_id" class="form-label"><i class="bi bi-flag"></i> Estado</label>
                            <select class="form-select" id="estado_id" name="estado_id">
                                <option value="">Seleccionar...</option>
                                @foreach($estados as $e)
                                    <option value="{{ $e->id }}" {{ old('estado_id') == $e->id ? 'selected' : '' }}>
                                        {{ $e->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- ══ MONEDA ══════════════════════════════════════════ --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <i class="bi bi-currency-dollar"></i> Moneda
                        </label>

                        {{-- Banner tipo de cambio --}}
                        @if($tipoCambio && $tipoCambio->estado === 'ok')
                            <div class="alert alert-info alert-sm py-2 mb-2">
                                <i class="bi bi-currency-exchange"></i>
                                <strong>TC hoy {{ \Carbon\Carbon::parse($tipoCambio->fecha)->format('d/m/Y') }}:</strong>
                                Venta+ = <strong class="text-primary">S/. {{ number_format($tipoCambio->venta_mas, 4) }}</strong>
                                por $ 1 USD
                                <small class="text-muted">
                                    (venta {{ number_format($tipoCambio->venta, 4) }} + inc. {{ number_format($tipoCambio->incremento, 4) }})
                                </small>
                            </div>
                        @else
                            <div class="alert alert-warning py-2 mb-2">
                                <i class="bi bi-exclamation-triangle"></i>
                                Sin tipo de cambio para hoy — conversión USD no disponible.
                            </div>
                        @endif

                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="moneda"
                                       id="monedaSoles" value="Soles"
                                       {{ old('moneda', 'Soles') === 'Soles' ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="monedaSoles">
                                    <i class="bi bi-cash-coin text-success"></i> Soles (S/.)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="moneda"
                                       id="monedaDolares" value="Dolares"
                                       {{ old('moneda') === 'Dolares' ? 'checked' : '' }}
                                       @if(!($tipoCambio && $tipoCambio->estado === 'ok')) disabled @endif>
                                <label class="form-check-label fw-bold" for="monedaDolares">
                                    <i class="bi bi-currency-dollar text-success"></i> Dólares ($)
                                    @if(!($tipoCambio && $tipoCambio->estado === 'ok'))
                                        <span class="badge bg-secondary ms-1">No disponible</span>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                    {{-- ══ FIN MONEDA ══════════════════════════════════════ --}}
                </div>

                <!-- Resumen financiero -->
                <div class="col-md-6">
                    <label class="form-label fw-bold"><i class="bi bi-receipt"></i> Resumen</label>
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
                            <input type="text" class="form-control form-control-lg fw-bold fs-3 text-success"
                                   id="totalResumen" value="S/. 0.00" readonly>
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
                    <i class="bi bi-check-circle"></i> Generar Proforma
                </button>
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
                <input type="text" id="buscarProducto" class="form-control mb-3"
                       placeholder="Buscar por código o nombre...">
                <div class="table-responsive">
                    <table class="table table-hover" id="listaProductos">
                        <thead>
                            <tr>
                                <th>Código</th><th>Descripción</th><th>Marca</th>
                                <th>Precio (S/.)</th><th>Stock</th><th>Acción</th>
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
                                    <button type="button"
                                            class="btn btn-sm btn-success btn-seleccionar-producto"
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

    // ══════════════════════════════════════════════════════════════════════
    //  TIPO DE CAMBIO — inyectado desde PHP
    // ══════════════════════════════════════════════════════════════════════
    const VENTA_MAS = {{ ($tipoCambio && $tipoCambio->venta_mas && $tipoCambio->estado === 'ok') ? (float)$tipoCambio->venta_mas : 0 }};

    // ── Helpers ───────────────────────────────────────────────────────────
    const getMoneda  = () => document.querySelector('input[name="moneda"]:checked')?.value || 'Soles';
    const getSimbolo = () => getMoneda() === 'Dolares' ? '$' : 'S/.';

    /** Precio base (S/.) → precio en moneda activa */
    const convertir = (s) => getMoneda() === 'Dolares' && VENTA_MAS > 0 ? s / VENTA_MAS : s;

    /** Actualiza badge de tabla y símbolo del botón de moneda */
    function actualizarUI() {
        const esDolares = getMoneda() === 'Dolares';
        const badge = document.getElementById('badgeMoneda');
        badge.textContent = esDolares ? '$ Dólares (USD)' : 'S/ Soles';
        badge.className   = esDolares
            ? 'badge bg-success ms-2'
            : 'badge bg-warning text-dark ms-2';
    }

    /** Reconvierte precios de todas las filas usando data-precio-soles */
    function reconvertirPrecios() {
        document.querySelectorAll('#productosContainer tr[id^="producto-"]').forEach(row => {
            const idx    = row.id.replace('producto-', '');
            const base   = parseFloat(row.dataset.precioSoles || 0);
            const input  = row.querySelector(`input[name="productos[${idx}][precio_unitario]"]`);
            if (input && base > 0) input.value = convertir(base).toFixed(4);
            calcularSubtotal(idx);
        });
    }

    // ── Cambio de moneda ──────────────────────────────────────────────────
    document.querySelectorAll('input[name="moneda"]').forEach(r => {
        r.addEventListener('change', function () {
            if (this.value === 'Dolares' && VENTA_MAS <= 0) {
                alert('No hay tipo de cambio disponible para hoy.\nConsulta el módulo Tipo de Cambio.');
                document.getElementById('monedaSoles').checked = true;
                return;
            }
            reconvertirPrecios();
            actualizarUI();
            calcularTotales();
        });
    });

    // ── Prevenir ENTER ────────────────────────────────────────────────────
    $(document).on('keydown', '.cantidad-input,.precio-input,.descuento-input', function (e) {
        if (e.key !== 'Enter') return;
        e.preventDefault();
        const idx = $(this).closest('tr').attr('id').replace('producto-', '');
        calcularSubtotal(idx);
        $(this).closest('td').next('td').find('input').focus();
    });

    // ── SELECT2 Clientes ──────────────────────────────────────────────────
    $('.select2-clientes').select2({
        placeholder: 'Buscar por RUC o Razón Social...',
        allowClear: true,
        ajax: {
            url: '{{ route("api.clientes.buscar") }}',
            dataType: 'json', delay: 250,
            data: p => ({ q: p.term, page: p.page }),
            processResults: d => ({ results: d.results }),
            cache: true,
        },
        minimumInputLength: 2,
        templateResult: c => (!c.id ? c.text : c.razon
            ? $(`<span>${c.razon}<br><small class="text-muted">RUC: ${c.ruc}</small></span>`)
            : $(`<span>${c.text}</span>`)),
        templateSelection: c => (!c.id ? c.text : c.razon
            ? $(`<span>${c.razon}</span>`)
            : $(`<span>${c.text}</span>`)),
    });

    @if(old('cliente_id'))
    $.ajax({
        url: '{{ route("api.clientes.obtener", ["id"=>"__ID__"]) }}'.replace('__ID__','{{ old("cliente_id") }}'),
        success: d => { $('.select2-clientes').append(new Option(d.text, d.id, true, true)).trigger('change'); }
    });
    @endif

    // ── Direcciones ───────────────────────────────────────────────────────
    const API_DIR = '{{ url("/api/clientes") }}';
    const oldDir  = '{{ old("direccion_id") }}';

    $('#cliente_id').on('change', function () {
        const cid  = $(this).val();
        const sel  = $('#direccion_id');
        const hint = $('#hintDireccion');
        const wrap = $('#wrapDireccion');

        sel.empty().append('<option value="">— Cargando... —</option>');
        hint.text('').removeClass('text-danger');

        if (!cid) {
            sel.empty().append('<option value="">— Seleccione un cliente primero —</option>');
            return;
        }
        wrap.addClass('loading');
        $.getJSON(`${API_DIR}/${cid}/direcciones`)
            .done(dirs => {
                sel.empty().append('<option value="">— Sin especificar —</option>');
                dirs.length
                    ? dirs.forEach(d => sel.append(`<option value="${d.id}" ${oldDir==d.id?'selected':''}>${d.label}</option>`))
                    : hint.text('Sin direcciones registradas.');
                if (dirs.length) hint.text(`${dirs.length} dirección(es) disponible(s).`);
            })
            .fail(() => {
                sel.empty().append('<option value="">— Error al cargar —</option>');
                hint.addClass('text-danger').text('No se pudieron cargar.');
            })
            .always(() => wrap.removeClass('loading'));
    });

    // ── Contactos ─────────────────────────────────────────────────────────
    const oldContact = '{{ old("contacto_id") }}';

    function cargarContactos(cid, preselect) {
        const sel  = $('#contacto_id');
        const hint = $('#hintContacto');
        const wrap = $('#wrapContacto');
        const card = document.getElementById('cardContactoInfo');

        sel.empty().append('<option value="">— Cargando... —</option>');
        hint.text('').removeClass('text-danger');
        wrap.addClass('loading');
        card.style.display = 'none';

        $.getJSON(`${API_DIR}/${cid}/contactos`)
            .done(contacts => {
                sel.empty().append('<option value="">— Sin especificar —</option>');
                if (contacts.length) {
                    contacts.forEach(c => sel.append(
                        `<option value="${c.id}"
                                 data-nombre="${c.nombre_completo}"
                                 data-cargo="${c.cargo ?? ''}"
                                 data-telefono="${c.telefono ?? ''}"
                                 data-email="${c.email ?? ''}"
                                 ${String(preselect) === String(c.id) ? 'selected' : ''}>${c.label}</option>`
                    ));
                    hint.text(`${contacts.length} contacto(s) disponible(s).`);
                    if (preselect) mostrarInfoContacto(sel);
                } else {
                    hint.text('Este cliente no tiene contactos registrados.');
                }
            })
            .fail(() => {
                sel.empty().append('<option value="">— Error —</option>');
                hint.addClass('text-danger').text('No se pudieron cargar los contactos.');
            })
            .always(() => wrap.removeClass('loading'));
    }

    function mostrarInfoContacto(sel) {
        const opt  = sel.find('option:selected');
        const card = document.getElementById('cardContactoInfo');
        if (!opt.val()) { card.style.display = 'none'; return; }
        document.getElementById('contactoNombre').textContent   = opt.data('nombre')   || '—';
        document.getElementById('contactoCargo').textContent    = opt.data('cargo')    || '—';
        document.getElementById('contactoTelefono').textContent = opt.data('telefono') || '—';
        document.getElementById('contactoEmail').textContent    = opt.data('email')    || '—';
        card.style.display = 'block';
    }

    $('#contacto_id').on('change', function () { mostrarInfoContacto($(this)); });

    // Al cambiar cliente: recargar ambos (direcciones via .on('change') ya registrado arriba
    // + contactos)
    $('#cliente_id').on('change', function () {
        const cid = $(this).val();
        // Reset contacto
        $('#contacto_id').empty().append('<option value="">— Seleccione un cliente primero —</option>');
        $('#hintContacto').text('');
        document.getElementById('cardContactoInfo').style.display = 'none';
        if (cid) cargarContactos(cid, '');
    });

    @if(old('cliente_id'))
    setTimeout(() => {
        $('#cliente_id').trigger('change');
        cargarContactos('{{ old("cliente_id") }}', '{{ old("contacto_id") }}');
    }, 600);
    @endif

    // ══════════════════════════════════════════════════════════════════════
    //  GESTIÓN DE PRODUCTOS
    // ══════════════════════════════════════════════════════════════════════
    let productoCount = 0;
    let productosSeleccionados = [];

    document.querySelectorAll('.btn-seleccionar-producto').forEach(btn => {
        btn.addEventListener('click', function () {
            const id          = this.dataset.id;
            const codigo      = this.dataset.codigo;
            const nombre      = this.dataset.nombre;
            const precioSoles = parseFloat(this.dataset.precio);   // SIEMPRE en S/.
            const stock       = parseInt(this.dataset.stock);
            const desc        = parseFloat(this.dataset.descuento);

            if (productosSeleccionados.includes(id)) {
                alert('Este producto ya está en la lista'); return;
            }

            productoCount++;
            productosSeleccionados.push(id);

            const precioMostrar = convertir(precioSoles);
            const sym           = getSimbolo();

            document.getElementById('productosContainer').insertAdjacentHTML('beforeend', `
                <tr id="producto-${productoCount}" data-precio-soles="${precioSoles}">
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
                               step="0.0001" min="0" value="${precioMostrar.toFixed(4)}" required
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
                    <td>
                        <span class="subtotal" id="subtotal-${productoCount}">
                            ${sym} ${precioMostrar.toFixed(2)}
                        </span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger"
                                onclick="eliminarProducto(${productoCount})">
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
        const t = this.value.toLowerCase();
        document.querySelectorAll('#listaProductos tbody tr').forEach(row => {
            row.style.display = [...row.cells].slice(0,-1).some(c => c.textContent.toLowerCase().includes(t)) ? '' : 'none';
        });
    });

    // ══════════════════════════════════════════════════════════════════════
    //  CÁLCULOS
    // ══════════════════════════════════════════════════════════════════════
    window.calcularSubtotal = function (idx) {
        const qty   = parseFloat(document.querySelector(`input[name="productos[${idx}][cantidad]"]`)?.value)          || 0;
        const price = parseFloat(document.querySelector(`input[name="productos[${idx}][precio_unitario]"]`)?.value)   || 0;
        const disc  = parseFloat(document.querySelector(`input[name="productos[${idx}][descuento_cliente]"]`)?.value) || 0;
        const el    = document.getElementById(`subtotal-${idx}`);
        if (el) el.textContent = `${getSimbolo()} ${(qty * price * (1 - disc/100)).toFixed(2)}`;
        calcularTotales();
    };

    window.validarDescuento = function (idx) {
        const inp = document.querySelector(`input[name="productos[${idx}][descuento_cliente]"]`);
        const max = parseFloat(inp?.dataset.maxDescuento) || 100;
        if (parseFloat(inp?.value) > max) { inp.value = max; alert(`El descuento máximo es ${max}%`); }
        calcularSubtotal(idx);
    };

    window.eliminarProducto = function (idx) {
        const row = document.getElementById(`producto-${idx}`);
        if (!row) return;
        const id = row.querySelector(`input[name="productos[${idx}][id]"]`)?.value;
        if (id) productosSeleccionados.splice(productosSeleccionados.indexOf(id), 1);
        row.remove();
        calcularTotales();
    };

    window.calcularTotales = function () {
        const sym = getSimbolo();
        let sub = 0;
        document.querySelectorAll('.subtotal').forEach(el => {
            // Parsear tanto "S/. 12.34" como "$ 12.34"
            const v = parseFloat(el.textContent.trim().replace(/^[^\d\-]+/, '').replace(',',''));
            if (!isNaN(v)) sub += v;
        });
        const igv = sub * 0.18, total = sub + igv;

        document.getElementById('subtotal').value              = `${sym} ${sub.toFixed(2)}`;
        document.getElementById('igv').value                   = `${sym} ${igv.toFixed(2)}`;
        document.getElementById('totalResumen').value          = `${sym} ${total.toFixed(2)}`;
        document.getElementById('totalGeneral').textContent    = `${sym} ${total.toFixed(2)}`;
        document.getElementById('sub_total_hidden').value      = sub.toFixed(2);
        document.getElementById('monto_igv_hidden').value      = igv.toFixed(2);
        document.getElementById('total_hidden').value          = total.toFixed(2);
    };

    // Validación al enviar
    document.getElementById('formProforma').addEventListener('submit', function (e) {
        if (productosSeleccionados.length === 0) {
            e.preventDefault(); alert('Debe agregar al menos un producto'); return;
        }
        if (!document.getElementById('cliente_id').value) {
            e.preventDefault(); alert('Debe seleccionar un cliente'); return;
        }
    });

    // Init
    actualizarUI();
    calcularTotales();
});
</script>
@endpush
@endsection
