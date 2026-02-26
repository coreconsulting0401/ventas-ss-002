@extends('layouts.app')

@section('title', 'Productos')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-box-seam"></i> Gestión de Productos
    </h2>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportarExcel">
            <i class="bi bi-file-earmark-excel"></i> Carga masiva Excel
        </button>
        <a href="{{ route('productos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Producto
        </a>
    </div>
</div>
@endsection

@section('content')

{{-- =====================================================================
     ALERTAS DE IMPORTACIÓN
     ===================================================================== --}}
@if(session('import_success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <strong>{{ session('import_success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('import_error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle-fill me-2"></i>
        <strong>{{ session('import_error') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('import_errores'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <strong>Se encontraron errores en {{ count(session('import_errores')) }} fila(s):</strong>
        <ul class="mb-0 mt-2">
            @foreach(session('import_errores') as $err)
                <li>
                    <strong>Fila {{ $err['fila'] }}</strong> — {{ $err['nombre'] }}:
                    {{ implode(' | ', $err['errores']) }}
                </li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- =====================================================================
     FILTROS DE BÚSQUEDA
     ===================================================================== --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('productos.index') }}" method="GET" id="formFiltros">
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label class="form-label">
                        <i class="bi bi-upc"></i> Código E
                    </label>
                    <input type="text"
                           name="codigo_e"
                           class="form-control"
                           value="{{ request('codigo_e') }}"
                           placeholder="Buscar...">
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">
                        <i class="bi bi-upc-scan"></i> Código P
                    </label>
                    <input type="text"
                           name="codigo_p"
                           class="form-control"
                           value="{{ request('codigo_p') }}"
                           placeholder="Buscar...">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        <i class="bi bi-box"></i> Descripción
                    </label>
                    <input type="text"
                           name="nombre"
                           class="form-control"
                           value="{{ request('nombre') }}"
                           placeholder="Buscar...">
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">
                        <i class="bi bi-tag"></i> Marca
                    </label>
                    <input type="text"
                           name="marca"
                           class="form-control"
                           value="{{ request('marca') }}"
                           placeholder="Buscar...">
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">
                        <i class="bi bi-geo-alt"></i> Ubicación
                    </label>
                    <input type="text"
                           name="ubicacion"
                           class="form-control"
                           value="{{ request('ubicacion') }}"
                           placeholder="Buscar...">
                </div>

                <div class="col-md-1 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100" title="Buscar">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary" id="btnTodos">
                            <i class="bi bi-grid"></i> TODOS
                        </button>
                        <button type="button" class="btn btn-outline-success" id="btnSoloStock">
                            <i class="bi bi-check-circle"></i> Solo Stock
                        </button>
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Limpiar Filtros
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- =====================================================================
     TABLA DE PRODUCTOS
     ===================================================================== --}}
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Listado de Productos</h5>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Buscar en resultados...">
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Código E</th>
                        <th>Código P</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Ubicación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="productosTable">
                    @forelse($productos as $producto)
                    <tr>
                        <td>{{ $producto->id }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ $producto->codigo_e }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                {{ $producto->codigo_p }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ Str::limit($producto->nombre) }}</strong>
                        </td>
                        <td>
                            <span class="badge bg-primary">
                                {{ $producto->marca }}
                            </span>
                        </td>
                        <td>
                            <strong class="text-success">S/. {{ number_format($producto->precio_lista, 2) }}</strong>
                        </td>
                        <td>
                            @if($producto->stock > 10)
                                <span class="badge bg-success">{{ $producto->stock }}</span>
                            @elseif($producto->stock > 0)
                                <span class="badge bg-warning">{{ $producto->stock }}</span>
                            @else
                                <span class="badge bg-danger">AGOTADO</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $producto->ubicacion }}</small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('productos.show', $producto->id) }}"
                                   class="btn btn-sm btn-info"
                                   title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @haspermission('edit productos')
                                <a href="{{ route('productos.edit', $producto->id) }}"
                                   class="btn btn-sm btn-warning"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endhaspermission
                                @haspermission('delete productos')
                                <form action="{{ route('productos.destroy', $producto->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Está seguro de eliminar este producto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endhaspermission
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            <div class="py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="mt-2 text-muted">No hay productos registrados</p>
                                <a href="{{ route('productos.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Crear Primer Producto
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-0">Total: <strong>{{ $productos->total() }}</strong> productos</p>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                {{ $productos->links() }}
            </div>
        </div>
    </div>
</div>

{{-- =====================================================================
     MODAL: CARGA MASIVA EXCEL
     ===================================================================== --}}
<div class="modal fade" id="modalImportarExcel" tabindex="-1" aria-labelledby="modalImportarExcelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalImportarExcelLabel">
                    <i class="bi bi-file-earmark-excel-fill me-2"></i> Carga Masiva de Productos desde Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">

                {{-- Instrucciones --}}
                <div class="alert alert-info">
                    <h6 class="alert-heading"><i class="bi bi-info-circle-fill me-1"></i> ¿Cómo funciona?</h6>
                    <ul class="mb-0 small">
                        <li>Sube un archivo <strong>.xlsx</strong> o <strong>.xls</strong> con los productos.</li>
                        <li>
                            <strong>Columnas obligatorias:</strong>
                            <code>nombre</code>, <code>marca</code>, <code>precio_lista</code>, <code>stock</code>
                        </li>
                        <li>
                            <strong>Columnas opcionales:</strong>
                            <code>codigo_e</code>, <code>codigo_p</code>, <code>ubicacion</code>, <code>descuento_id</code>
                        </li>
                        <li>
                            <strong>Lógica de actualización:</strong>
                            si el producto ya existe (identificado por <code>codigo_p</code> o <code>codigo_e</code>),
                            se actualizará; de lo contrario se creará como nuevo.
                        </li>
                        <li>Tamaño máximo: <strong>5 MB</strong>.</li>
                    </ul>
                </div>

                {{-- Descarga de plantilla --}}
                <div class="mb-4 d-flex align-items-center gap-3">
                    <span class="text-muted small">¿Necesitas la plantilla?</span>
                    <a href="{{ route('productos.import.template') }}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-download me-1"></i> Descargar plantilla Excel
                    </a>
                </div>

                {{-- Formulario de carga --}}
                <form action="{{ route('productos.import') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="formImportarExcel">
                    @csrf

                    <div class="mb-3">
                        <label for="archivo_excel" class="form-label fw-semibold">
                            <i class="bi bi-upload me-1"></i> Seleccionar archivo Excel
                        </label>
                        <input type="file"
                               class="form-control @error('archivo_excel') is-invalid @enderror"
                               id="archivo_excel"
                               name="archivo_excel"
                               accept=".xlsx,.xls">
                        @error('archivo_excel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Vista previa del archivo seleccionado --}}
                    <div id="archivoSeleccionado" class="d-none">
                        <div class="d-flex align-items-center gap-2 p-2 border rounded bg-light">
                            <i class="bi bi-file-earmark-excel text-success fs-4"></i>
                            <div>
                                <div class="fw-semibold small" id="nombreArchivo"></div>
                                <div class="text-muted" style="font-size: 0.75rem;" id="tamanoArchivo"></div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-auto" id="btnQuitarArchivo">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="submit"
                        form="formImportarExcel"
                        class="btn btn-success"
                        id="btnImportar"
                        disabled>
                    <span id="btnImportarTexto">
                        <i class="bi bi-cloud-upload me-1"></i> Importar productos
                    </span>
                    <span id="btnImportarCargando" class="d-none">
                        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                        Procesando...
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ------------------------------------------------------------------ */
    /*  Búsqueda en tiempo real en la tabla                                 */
    /* ------------------------------------------------------------------ */
    const searchInput = document.getElementById('searchInput');
    const tableRows   = document.querySelectorAll('#productosTable tr');

    searchInput.addEventListener('keyup', function () {
        const term = this.value.toLowerCase();
        tableRows.forEach(row => {
            const text = Array.from(row.getElementsByTagName('td'))
                              .map(td => td.textContent.toLowerCase())
                              .join(' ');
            row.style.display = text.includes(term) ? '' : 'none';
        });
    });

    /* ------------------------------------------------------------------ */
    /*  Botón TODOS                                                         */
    /* ------------------------------------------------------------------ */
    document.getElementById('btnTodos').addEventListener('click', function () {
        document.querySelectorAll('#formFiltros input').forEach(i => (i.value = ''));
        document.getElementById('formFiltros').submit();
    });

    /* ------------------------------------------------------------------ */
    /*  Botón Solo Stock                                                    */
    /* ------------------------------------------------------------------ */
    document.getElementById('btnSoloStock').addEventListener('click', function () {
        document.querySelectorAll('#formFiltros input').forEach(i => {
            if (i.name !== 'stock_min') i.value = '';
        });
        let stockInput = document.querySelector('input[name="stock_min"]');
        if (!stockInput) {
            stockInput = document.createElement('input');
            stockInput.type  = 'hidden';
            stockInput.name  = 'stock_min';
            stockInput.value = '1';
            document.getElementById('formFiltros').appendChild(stockInput);
        } else {
            stockInput.value = '1';
        }
        document.getElementById('formFiltros').submit();
    });

    /* ------------------------------------------------------------------ */
    /*  Modal de importación — lógica del input file                        */
    /* ------------------------------------------------------------------ */
    const inputArchivo         = document.getElementById('archivo_excel');
    const divArchivoSeleccionado = document.getElementById('archivoSeleccionado');
    const spanNombre           = document.getElementById('nombreArchivo');
    const spanTamano           = document.getElementById('tamanoArchivo');
    const btnImportar          = document.getElementById('btnImportar');
    const btnQuitarArchivo     = document.getElementById('btnQuitarArchivo');
    const formImportar         = document.getElementById('formImportarExcel');

    inputArchivo.addEventListener('change', function () {
        if (this.files.length > 0) {
            const file = this.files[0];
            spanNombre.textContent  = file.name;
            spanTamano.textContent  = formatBytes(file.size);
            divArchivoSeleccionado.classList.remove('d-none');
            btnImportar.disabled    = false;
        } else {
            resetFileInput();
        }
    });

    btnQuitarArchivo.addEventListener('click', function () {
        resetFileInput();
    });

    formImportar.addEventListener('submit', function () {
        btnImportar.disabled = true;
        document.getElementById('btnImportarTexto').classList.add('d-none');
        document.getElementById('btnImportarCargando').classList.remove('d-none');
    });

    function resetFileInput() {
        inputArchivo.value          = '';
        divArchivoSeleccionado.classList.add('d-none');
        btnImportar.disabled        = true;
        spanNombre.textContent      = '';
        spanTamano.textContent      = '';
    }

    function formatBytes(bytes) {
        if (bytes < 1024)       return bytes + ' B';
        if (bytes < 1048576)    return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    /* ---- Abrir modal si hay errores de validación del archivo ---- */
    @if($errors->has('archivo_excel'))
        var modal = new bootstrap.Modal(document.getElementById('modalImportarExcel'));
        modal.show();
    @endif
});
</script>
@endpush
