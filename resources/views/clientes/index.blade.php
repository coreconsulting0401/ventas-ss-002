@extends('layouts.app')

@section('title', 'Clientes')

@section('page-title')
<div class="section-header d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h2 class="mb-0">
        <i class="bi bi-people"></i> Gestión de Clientes
    </h2>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success" id="btnEstadisticas"
                data-bs-toggle="modal" data-bs-target="#modalEstadisticas">
            <i class="bi bi-bar-chart-line"></i> Estadísticas
        </button>
        <a href="{{ route('clientes.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Cliente
        </a>
    </div>
</div>
@endsection

@section('content')

{{-- ══════════════════════════════════════════════════════
     PANEL DE FILTROS
══════════════════════════════════════════════════════ --}}
<div class="card mb-3 border-primary shadow-sm">
    <div class="card-header bg-primary bg-opacity-10 d-flex justify-content-between align-items-center"
         style="cursor:pointer" data-bs-toggle="collapse" data-bs-target="#filtrosPanel">
        <span class="fw-semibold text-primary">
            <i class="bi bi-funnel-fill me-1"></i> Filtros de búsqueda
            @if(request()->hasAny(['buscar','categoria_id','contacto_buscar','dir_principal','departamento_id','provincia_id','distrito_id']))
                <span class="badge bg-danger ms-2">activos</span>
            @endif
        </span>
        <i class="bi bi-chevron-down text-primary"></i>
    </div>

    <div class="collapse @if(request()->hasAny(['buscar','categoria_id','contacto_buscar','dir_principal','departamento_id','provincia_id','distrito_id'])) show @endif"
         id="filtrosPanel">
        <div class="card-body">
            <form method="GET" action="{{ route('clientes.index') }}" id="formFiltros">

                {{-- Fila 1: RUC/Razón | Categoría | Contacto --}}
                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold mb-1">
                            <i class="bi bi-building"></i> Razón Social / RUC
                        </label>
                        <input type="text"
                               class="form-control form-control-sm"
                               name="buscar"
                               value="{{ request('buscar') }}"
                               placeholder="Ingrese razón social o RUC...">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1">
                            <i class="bi bi-tag"></i> Categoría
                        </label>
                        <select class="form-select form-select-sm" name="categoria_id">
                            <option value="">— Todas las categorías —</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label small fw-semibold mb-1">
                            <i class="bi bi-person-badge"></i> Nombre de Contacto / DNI
                        </label>
                        <input type="text"
                               class="form-control form-control-sm"
                               name="contacto_buscar"
                               value="{{ request('contacto_buscar') }}"
                               placeholder="Nombre, apellido o DNI del contacto...">
                    </div>
                </div>

                {{-- Fila 2: Dirección principal | Departamento | Provincia | Distrito --}}
                <div class="row g-2 mb-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1">
                            <i class="bi bi-geo-alt"></i> Dirección principal contiene
                        </label>
                        <input type="text"
                               class="form-control form-control-sm"
                               name="dir_principal"
                               value="{{ request('dir_principal') }}"
                               placeholder="Texto en dirección principal...">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1">
                            <i class="bi bi-map"></i> Departamento (agencias)
                        </label>
                        <select class="form-select form-select-sm" name="departamento_id" id="filtro_departamento_id">
                            <option value="">— Todos los departamentos —</option>
                            @foreach($departamentos as $dep)
                                <option value="{{ $dep->id }}"
                                    {{ request('departamento_id') == $dep->id ? 'selected' : '' }}>
                                    {{ $dep->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1">
                            <i class="bi bi-pin-map"></i> Provincia (agencias)
                        </label>
                        <select class="form-select form-select-sm" name="provincia_id" id="filtro_provincia_id"
                                {{ !request('departamento_id') ? 'disabled' : '' }}>
                            <option value="">— Todas las provincias —</option>
                            {{-- Se carga vía AJAX al seleccionar departamento --}}
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1">
                            <i class="bi bi-crosshair"></i> Distrito (agencias)
                        </label>
                        <select class="form-select form-select-sm" name="distrito_id" id="filtro_distrito_id"
                                {{ !request('provincia_id') ? 'disabled' : '' }}>
                            <option value="">— Todos los distritos —</option>
                            {{-- Se carga vía AJAX al seleccionar provincia --}}
                        </select>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary btn-sm px-4">
                        <i class="bi bi-x-circle"></i> Limpiar filtros
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     TABLA PRINCIPAL
══════════════════════════════════════════════════════ --}}
<div class="card shadow-sm">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Clientes</h5>
            <small class="text-muted">
                <i class="bi bi-people"></i>
                {{ $clientes->total() }} resultado(s)
            </small>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width:50px">ID</th>
                        <th>RUC</th>
                        <th>Razón Social / Dirección</th>
                        <th>Teléfono</th>
                        <th>Categoría</th>
                        <th>Contactos</th>
                        <th>Agencias</th>
                        <th>Crédito</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                    <tr>
                        <td class="text-center text-muted small">{{ $cliente->id }}</td>

                        <td>
                            <span class="badge bg-info text-dark">{{ $cliente->ruc }}</span>
                        </td>

                        <td>
                            <strong>{{ $cliente->razon }}</strong><br>
                            <small class="text-muted">
                                <i class="bi bi-geo-alt"></i>
                                {{ Str::limit($cliente->direccion, 45) }}
                            </small>
                        </td>

                        <td class="small">
                            <i class="bi bi-telephone text-success"></i> {{ $cliente->telefono1 }}
                            @if($cliente->telefono2)
                                <br><i class="bi bi-phone text-secondary"></i>
                                <span class="text-muted">{{ $cliente->telefono2 }}</span>
                            @endif
                        </td>

                        <td>
                            @if($cliente->categoria)
                                <span class="badge bg-primary">{{ $cliente->categoria->name }}</span>
                            @else
                                <span class="badge bg-secondary">Sin categoría</span>
                            @endif
                        </td>

                        <td>
                            @if($cliente->contactos->count() > 0)
                                <span class="badge bg-success mb-1">
                                    {{ $cliente->contactos->count() }} contacto(s)
                                </span>
                                <div class="small text-muted">
                                    @foreach($cliente->contactos->take(2) as $c)
                                        <div>• {{ Str::limit($c->nombre . ' ' . $c->apellido_paterno, 22) }}</div>
                                    @endforeach
                                    @if($cliente->contactos->count() > 2)
                                        <span class="text-primary">+{{ $cliente->contactos->count() - 2 }} más</span>
                                    @endif
                                </div>
                            @else
                                <span class="badge bg-secondary">Sin contactos</span>
                            @endif
                        </td>

                        <td>
                            @if($cliente->direcciones->count() > 0)
                                <span class="badge bg-cyan text-dark mb-1"
                                      style="background-color:#0dcaf0">
                                    {{ $cliente->direcciones->count() }} agencia(s)
                                </span>
                                <div class="small text-muted">
                                    @foreach($cliente->direcciones->take(2) as $d)
                                        <div>• {{ Str::limit($d->direccion, 22) }}</div>
                                    @endforeach
                                    @if($cliente->direcciones->count() > 2)
                                        <span class="text-primary">+{{ $cliente->direcciones->count() - 2 }} más</span>
                                    @endif
                                </div>
                            @else
                                <span class="badge bg-secondary">Sin agencias</span>
                            @endif
                        </td>

                        <td>
                            @if($cliente->credito)
                                @if($cliente->credito->aprobacion)
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Aprobado
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i> Desaprobado
                                    </span>
                                @endif
                            @else
                                <span class="badge bg-secondary">Sin crédito</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('clientes.show', $cliente) }}"
                                   class="btn btn-info btn-sm" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('clientes.edit', $cliente) }}"
                                   class="btn btn-warning btn-sm" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('clientes.destroy', $cliente) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este cliente?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="bi bi-inbox" style="font-size:2.5rem;color:#ccc"></i>
                            <p class="mt-2 text-muted mb-2">No se encontraron clientes</p>
                            @if(request()->hasAny(['buscar','categoria_id','contacto_buscar','dir_principal','departamento_id','provincia_id','distrito_id']))
                                <a href="{{ route('clientes.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Limpiar filtros
                                </a>
                            @else
                                <a href="{{ route('clientes.create') }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-circle"></i> Crear primer cliente
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        <div class="row align-items-center">
            <div class="col-md-6 small text-muted">
                Mostrando {{ $clientes->firstItem() ?? 0 }}–{{ $clientes->lastItem() ?? 0 }}
                de <strong>{{ $clientes->total() }}</strong> clientes
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                {{ $clientes->links() }}
            </div>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════
     MODAL DE ESTADÍSTICAS
══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalEstadisticas" tabindex="-1"
     aria-labelledby="tituloEstadisticas" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-fullscreen-lg-down">
        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="tituloEstadisticas">
                    <i class="bi bi-bar-chart-line me-2"></i>
                    Estadísticas de Clientes
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Filtro de fechas del modal --}}
            <div class="modal-body pb-0">
                <div class="card bg-light border-0 mb-3">
                    <div class="card-body py-2 px-3">
                        <div class="row g-2 align-items-end">
                            <div class="col-auto">
                                <label class="form-label small mb-1 fw-semibold">
                                    <i class="bi bi-calendar-range"></i> Rango de fechas (proformas)
                                </label>
                            </div>
                            <div class="col-auto">
                                <label class="form-label small mb-1">Desde</label>
                                <input type="date" class="form-control form-control-sm" id="statsFechaDesde"
                                       style="width:150px">
                            </div>
                            <div class="col-auto">
                                <label class="form-label small mb-1">Hasta</label>
                                <input type="date" class="form-control form-control-sm" id="statsFechaHasta"
                                       style="width:150px">
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-sm btn-primary" id="btnActualizarStats">
                                    <i class="bi bi-arrow-clockwise"></i> Actualizar
                                </button>
                            </div>
                            <div class="col-auto ms-auto">
                                <span class="badge bg-secondary" id="statsContextoBadge"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Spinner de carga --}}
                <div id="statsLoading" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Cargando estadísticas...</p>
                </div>

                {{-- Error --}}
                <div id="statsError" class="alert alert-danger d-none">
                    <i class="bi bi-exclamation-triangle"></i>
                    Error al cargar estadísticas. Intente nuevamente.
                </div>

                {{-- ── Contenedor de gráficos ── --}}
                <div id="statsContenido">

                    {{-- Fila 1: Cotizado | Ganada | Perdida --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="card border-warning h-100">
                                <div class="card-header bg-warning bg-opacity-25 py-1">
                                    <small class="fw-bold text-warning-emphasis">
                                        <i class="bi bi-hourglass-split"></i> Proformas Cotizadas por Cliente
                                    </small>
                                </div>
                                <div class="card-body p-2">
                                    <div style="height:220px; position:relative">
                                        <canvas id="chartCotizados"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-success h-100">
                                <div class="card-header bg-success bg-opacity-10 py-1">
                                    <small class="fw-bold text-success">
                                        <i class="bi bi-trophy"></i> Proformas Ganadas por Cliente
                                    </small>
                                </div>
                                <div class="card-body p-2">
                                    <div style="height:220px; position:relative">
                                        <canvas id="chartGanadas"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-danger h-100">
                                <div class="card-header bg-danger bg-opacity-10 py-1">
                                    <small class="fw-bold text-danger">
                                        <i class="bi bi-x-circle"></i> Proformas Perdidas por Cliente
                                    </small>
                                </div>
                                <div class="card-body p-2">
                                    <div style="height:220px; position:relative">
                                        <canvas id="chartPerdidas"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fila 2: Top 10 consumo | Top 5 peores --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <div class="card border-primary h-100">
                                <div class="card-header bg-primary bg-opacity-10 py-1">
                                    <small class="fw-bold text-primary">
                                        <i class="bi bi-currency-dollar"></i>
                                        Top 10 Clientes por Mayor Consumo (Ganada)
                                    </small>
                                </div>
                                <div class="card-body p-2">
                                    <div style="height:240px; position:relative">
                                        <canvas id="chartTop10"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card border-dark h-100">
                                <div class="card-header bg-dark bg-opacity-10 py-1">
                                    <small class="fw-bold">
                                        <i class="bi bi-emoji-frown"></i>
                                        Top 5 Clientes con más Proformas Perdidas
                                    </small>
                                </div>
                                <div class="card-body p-2">
                                    <div style="height:240px; position:relative">
                                        <canvas id="chartTop5Peores"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Fila 3: Por departamento | Crédito --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <div class="card border-info h-100">
                                <div class="card-header bg-info bg-opacity-10 py-1">
                                    <small class="fw-bold text-info">
                                        <i class="bi bi-map"></i>
                                        Clientes, Agencias y Proformas Ganadas por Departamento
                                    </small>
                                </div>
                                <div class="card-body p-2">
                                    <div style="height:280px; position:relative">
                                        <canvas id="chartDepartamentos"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-secondary h-100">
                                <div class="card-header bg-secondary bg-opacity-10 py-1">
                                    <small class="fw-bold text-secondary">
                                        <i class="bi bi-credit-card"></i> Estado de Crédito
                                    </small>
                                </div>
                                <div class="card-body p-2">
                                    {{-- Donut chart --}}
                                    <div style="height:200px; position:relative" class="mb-2">
                                        <canvas id="chartCredito"></canvas>
                                    </div>
                                    {{-- Resumen numérico --}}
                                    <div id="creditoResumen" class="small text-center"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- /statsContenido --}}
            </div>{{-- /modal-body --}}

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection


@push('scripts')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<script>
/* ═══════════════════════════════════════════════════════════════
   FILTROS INDEX – Cascading ubigeo (departamento → provincia → distrito)
═══════════════════════════════════════════════════════════════ */
(function () {
    const selDept  = document.getElementById('filtro_departamento_id');
    const selProv  = document.getElementById('filtro_provincia_id');
    const selDist  = document.getElementById('filtro_distrito_id');

    const deptActual = '{{ request("departamento_id") }}';
    const provActual = '{{ request("provincia_id") }}';
    const distActual = '{{ request("distrito_id") }}';

    async function cargarProvincias(deptId, selVal = '') {
        selProv.innerHTML = '<option value="">Cargando...</option>';
        selProv.disabled  = true;
        selDist.innerHTML = '<option value="">— Todos los distritos —</option>';
        selDist.disabled  = true;

        if (!deptId) {
            selProv.innerHTML = '<option value="">— Todas las provincias —</option>';
            return;
        }

        const res  = await fetch(`/ubigeo/provincias/${deptId}`);
        const data = await res.json();

        selProv.innerHTML = '<option value="">— Todas las provincias —</option>';
        data.forEach(p => {
            const opt = new Option(p.nombre, p.id, false, String(p.id) === String(selVal));
            selProv.add(opt);
        });
        selProv.disabled = false;
    }

    async function cargarDistritos(provId, selVal = '') {
        selDist.innerHTML = '<option value="">Cargando...</option>';
        selDist.disabled  = true;

        if (!provId) {
            selDist.innerHTML = '<option value="">— Todos los distritos —</option>';
            return;
        }

        const res  = await fetch(`/ubigeo/distritos/${provId}`);
        const data = await res.json();

        selDist.innerHTML = '<option value="">— Todos los distritos —</option>';
        data.forEach(d => {
            const opt = new Option(d.nombre, d.id, false, String(d.id) === String(selVal));
            selDist.add(opt);
        });
        selDist.disabled = false;
    }

    selDept.addEventListener('change', () => {
        cargarProvincias(selDept.value);
    });

    selProv.addEventListener('change', () => {
        cargarDistritos(selProv.value);
    });

    // Restaurar selects si hay filtros activos en la URL
    if (deptActual) {
        cargarProvincias(deptActual, provActual).then(() => {
            if (provActual) cargarDistritos(provActual, distActual);
        });
    }
})();


/* ═══════════════════════════════════════════════════════════════
   MODAL DE ESTADÍSTICAS – Chart.js
═══════════════════════════════════════════════════════════════ */
(function () {
    /* ── Registro de instancias Chart.js (para destruir antes de recrear) ── */
    const charts = {};

    function destroyAll() {
        Object.values(charts).forEach(c => { if (c) c.destroy(); });
    }

    /* ── Colores ── */
    const AZUL    = 'rgba(37, 99, 235, 0.75)';
    const VERDE   = 'rgba(22, 163, 74, 0.75)';
    const ROJO    = 'rgba(220, 38, 38, 0.75)';
    const NARANJA = 'rgba(234, 88, 12, 0.75)';
    const GRIS    = 'rgba(100, 116, 139, 0.75)';
    const LILA    = 'rgba(109, 40, 217, 0.75)';
    const CIAN    = 'rgba(8, 145, 178, 0.75)';

    const PALETA_DEPTS = [
        '#1d4ed8','#15803d','#b91c1c','#c2410c','#6d28d9',
        '#0369a1','#047857','#7c3aed','#b45309','#0e7490',
        '#dc2626','#16a34a','#2563eb','#ea580c','#7c3aed',
        '#0284c7','#059669','#d97706','#4f46e5','#e11d48',
        '#0891b2','#65a30d','#9333ea','#c026d3','#64748b',
    ];

    /* ── Helper: construir horizontal bar chart ── */
    function buildHBar(canvasId, labels, data, color, symbol = '') {
        if (charts[canvasId]) charts[canvasId].destroy();
        const ctx = document.getElementById(canvasId).getContext('2d');
        charts[canvasId] = new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{ data, backgroundColor: color, borderRadius: 4 }],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => symbol
                                ? ` ${symbol} ${Number(ctx.raw).toLocaleString('es-PE', {minimumFractionDigits:2})}`
                                : ` ${ctx.raw}`,
                        },
                    },
                },
                scales: {
                    x: { ticks: { font: { size: 10 } } },
                    y: { ticks: { font: { size: 10 } } },
                },
            },
        });
    }

    /* ── Helper: construir bar chart vertical ── */
    function buildVBar(canvasId, labels, data, color) {
        if (charts[canvasId]) charts[canvasId].destroy();
        const ctx = document.getElementById(canvasId).getContext('2d');
        charts[canvasId] = new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [{ data, backgroundColor: color, borderRadius: 4 }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { font: { size: 9 }, maxRotation: 35 } },
                    y: { ticks: { font: { size: 10 } }, beginAtZero: true },
                },
            },
        });
    }

    /* ── Chart departamentos: grouped bar (clientes, agencias, proformas_ganadas) ── */
    function buildDeptChart(deptStats) {
        if (charts['chartDepartamentos']) charts['chartDepartamentos'].destroy();

        const labels = Object.keys(deptStats);
        const clientes      = labels.map(k => deptStats[k].clientes);
        const agencias      = labels.map(k => deptStats[k].agencias);
        const proformasGan  = labels.map(k => deptStats[k].proformas_ganadas);

        const ctx = document.getElementById('chartDepartamentos').getContext('2d');
        charts['chartDepartamentos'] = new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label: 'Clientes',         data: clientes,     backgroundColor: AZUL },
                    { label: 'Agencias',          data: agencias,     backgroundColor: CIAN },
                    { label: 'Proformas Ganadas', data: proformasGan, backgroundColor: VERDE },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { font: { size: 10 }, boxWidth: 12 } },
                },
                scales: {
                    x: { ticks: { font: { size: 8 }, maxRotation: 40 } },
                    y: { ticks: { font: { size: 10 } }, beginAtZero: true },
                },
            },
        });
    }

    /* ── Chart crédito: donut ── */
    function buildCreditoChart(credito) {
        if (charts['chartCredito']) charts['chartCredito'].destroy();

        const ctx = document.getElementById('chartCredito').getContext('2d');
        charts['chartCredito'] = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Sin Crédito', 'Aprobado', 'Desaprobado'],
                datasets: [{
                    data: [credito.sin_credito, credito.aprobado, credito.desaprobado],
                    backgroundColor: [GRIS, VERDE, ROJO],
                    borderWidth: 2,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 12 } },
                    tooltip: {
                        callbacks: {
                            label: ctx => {
                                const pct = credito.total > 0
                                    ? ((ctx.raw / credito.total) * 100).toFixed(1)
                                    : 0;
                                return ` ${ctx.label}: ${ctx.raw} (${pct}%)`;
                            },
                        },
                    },
                },
            },
        });

        // Resumen numérico
        const total = credito.total;
        const pct = v => total > 0 ? ((v / total) * 100).toFixed(1) : '0.0';
        document.getElementById('creditoResumen').innerHTML = `
            <div class="d-flex justify-content-center gap-3 mt-1 flex-wrap">
                <div class="text-center">
                    <div class="fw-bold text-secondary fs-5">${credito.sin_credito}</div>
                    <div class="text-muted small">Sin Crédito</div>
                    <div class="badge bg-secondary">${pct(credito.sin_credito)}%</div>
                </div>
                <div class="text-center">
                    <div class="fw-bold text-success fs-5">${credito.aprobado}</div>
                    <div class="text-muted small">Aprobado</div>
                    <div class="badge bg-success">${pct(credito.aprobado)}%</div>
                </div>
                <div class="text-center">
                    <div class="fw-bold text-danger fs-5">${credito.desaprobado}</div>
                    <div class="text-muted small">Desaprobado</div>
                    <div class="badge bg-danger">${pct(credito.desaprobado)}%</div>
                </div>
            </div>
        `;
    }

    /* ── Helper: abreviar nombre largo ── */
    function abreviar(nombre, max = 22) {
        return nombre.length > max ? nombre.substring(0, max) + '…' : nombre;
    }

    /* ── Función principal: cargar estadísticas ── */
    async function cargarEstadisticas() {
        const loading   = document.getElementById('statsLoading');
        const errorDiv  = document.getElementById('statsError');
        const contenido = document.getElementById('statsContenido');

        loading.classList.remove('d-none');
        errorDiv.classList.add('d-none');
        contenido.style.opacity = '0.4';

        // Recoger parámetros de la URL actual (filtros de la tabla)
        const urlParams = new URLSearchParams(window.location.search);

        // Añadir fechas del modal
        const fd = document.getElementById('statsFechaDesde').value;
        const fh = document.getElementById('statsFechaHasta').value;
        if (fd) urlParams.set('fecha_desde', fd);
        if (fh) urlParams.set('fecha_hasta', fh);

        // Badge de contexto
        const badge = document.getElementById('statsContextoBadge');
        let ctx = [];
        if (urlParams.get('buscar'))           ctx.push(`Búsqueda: "${urlParams.get('buscar')}"`);
        if (urlParams.get('categoria_id'))     ctx.push('Cat. filtrada');
        if (urlParams.get('contacto_buscar'))  ctx.push('Contacto filtrado');
        if (fd || fh)                          ctx.push(`Fechas: ${fd||'...'} – ${fh||'...'}`);
        badge.textContent = ctx.length ? ctx.join(' | ') : 'Todos los clientes';

        try {
            const res  = await fetch(`{{ route('api.clientes.estadisticas') }}?${urlParams.toString()}`);
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data = await res.json();

            destroyAll();

            /* Gráfico 1: Cotizados */
            buildVBar(
                'chartCotizados',
                data.cotizados.map(r => abreviar(r.razon)),
                data.cotizados.map(r => r.total),
                NARANJA
            );

            /* Gráfico 2: Ganadas */
            buildVBar(
                'chartGanadas',
                data.ganadas.map(r => abreviar(r.razon)),
                data.ganadas.map(r => r.total),
                VERDE
            );

            /* Gráfico 3: Perdidas */
            buildVBar(
                'chartPerdidas',
                data.perdidas.map(r => abreviar(r.razon)),
                data.perdidas.map(r => r.total),
                ROJO
            );

            /* Gráfico 4: Top 10 consumo (horizontal) */
            buildHBar(
                'chartTop10',
                data.top10.map(r => abreviar(r.razon, 28)),
                data.top10.map(r => parseFloat(r.monto)),
                AZUL,
                'S/.'
            );

            /* Gráfico 5: Top 5 peores (horizontal) */
            buildHBar(
                'chartTop5Peores',
                data.top5peores.map(r => abreviar(r.razon, 25)),
                data.top5peores.map(r => r.total),
                LILA
            );

            /* Gráfico 6: Por departamento */
            buildDeptChart(data.departamentos);

            /* Gráfico 7: Crédito */
            buildCreditoChart(data.credito);

        } catch (e) {
            console.error(e);
            errorDiv.classList.remove('d-none');
        } finally {
            loading.classList.add('d-none');
            contenido.style.opacity = '1';
        }
    }

    /* ── Eventos ── */
    document.getElementById('btnActualizarStats')
        .addEventListener('click', cargarEstadisticas);

    // Cargar automáticamente al abrir el modal
    document.getElementById('modalEstadisticas')
        .addEventListener('show.bs.modal', () => {
            // Solo si no hay datos ya cargados
            if (!charts['chartCredito']) {
                cargarEstadisticas();
            }
        });

    // Destruir gráficos al cerrar para liberar memoria
    document.getElementById('modalEstadisticas')
        .addEventListener('hidden.bs.modal', () => {
            destroyAll();
        });

})();
</script>
@endpush
