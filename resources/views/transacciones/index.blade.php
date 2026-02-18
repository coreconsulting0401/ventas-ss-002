@extends('layouts.app')

@section('title', 'Transacciones')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-coin"></i> Gestión de Transacciones
    </h2>
    <a href="{{ route('transacciones.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Transacción
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Listado de Transacciones</h5>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Buscar transacción...">
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
                        <th>Nombre</th>
                        <th>Proformas</th>
                        <th>Fecha Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="transaccionesTable">
                    @forelse($transacciones as $transaccion)
                    <tr>
                        <td>{{ $transaccion->id }}</td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-coin"></i> {{ $transaccion->name }}
                            </span>
                        </td>
                        <td>
                            @if($transaccion->proformas_count > 0)
                                <span class="badge bg-success">
                                    {{ $transaccion->proformas_count }} proforma(s)
                                </span>
                            @else
                                <span class="badge bg-secondary">Sin proformas</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">
                                @if($transaccion->created_at)
                                    {{ $transaccion->created_at->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-warning">Sin fecha</span>
                                @endif
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('transacciones.show', $transaccion) }}"
                                   class="btn btn-sm btn-info"
                                   title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('transacciones.edit', $transaccion) }}"
                                   class="btn btn-sm btn-warning"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('transacciones.destroy', $transaccion) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Está seguro de eliminar esta transacción?');">
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
                        <td colspan="5" class="text-center">
                            <div class="py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="mt-2 text-muted">No hay transacciones registradas</p>
                                <a href="{{ route('transacciones.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Crear Primera Transacción
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
                <p class="mb-0">Total: <strong>{{ $transacciones->total() }}</strong> transacciones</p>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                {{ $transacciones->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#transaccionesTable tr');

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
});
</script>
@endpush
