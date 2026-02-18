@extends('layouts.app')

@section('title', 'Productos')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-box-seam"></i> Gestión de Productos
    </h2>
    <a href="{{ route('productos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Producto
    </a>
</div>
@endsection

@section('content')
<!-- Filtros de Búsqueda -->
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

<!-- Tabla de Productos -->
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
                                <a href="{{ route('productos.edit', $producto->id) }}"
                                   class="btn btn-sm btn-warning"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#productosTable tr');

    // Búsqueda en tiempo real en la tabla
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();

        tableRows.forEach(row => {
            const cells = row.getElementsByTagName('td');
            let found = false;

            for (let i = 0; i < cells.length; i++) {
                if (cells[i].textContent.toLowerCase().includes(searchTerm)) {
                    found = true;
                    break;
                }
            }

            row.style.display = found ? '' : 'none';
        });
    });

    // Botón TODOS - limpiar todos los filtros
    document.getElementById('btnTodos').addEventListener('click', function() {
        document.querySelectorAll('#formFiltros input').forEach(input => {
            input.value = '';
        });
        document.getElementById('formFiltros').submit();
    });

    // Botón Solo Stock - filtrar productos con stock > 0
    document.getElementById('btnSoloStock').addEventListener('click', function() {
        // Limpiar otros filtros
        document.querySelectorAll('#formFiltros input').forEach(input => {
            if (input.name !== 'stock_min') {
                input.value = '';
            }
        });

        // Agregar filtro de stock mínimo
        let stockInput = document.querySelector('input[name="stock_min"]');
        if (!stockInput) {
            stockInput = document.createElement('input');
            stockInput.type = 'hidden';
            stockInput.name = 'stock_min';
            stockInput.value = '1';
            document.getElementById('formFiltros').appendChild(stockInput);
        } else {
            stockInput.value = '1';
        }

        document.getElementById('formFiltros').submit();
    });
});
</script>
@endpush
