@extends('layouts.app')

@section('title', 'Temperaturas')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-thermometer-sun"></i> Gestión de Temperaturas
    </h2>
    <a href="{{ route('temperaturas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nueva Temperatura
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Listado de Temperaturas</h5>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Buscar temperatura...">
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
                <tbody id="temperaturasTable">
                    @forelse($temperaturas as $temperatura)
                    <tr>
                        <td>{{ $temperatura->id }}</td>
                        <td>
                            <span class="badge bg-info">
                                <i class="bi bi-thermometer-half"></i> {{ $temperatura->name }}
                            </span>
                        </td>
                        <td>
                            @if($temperatura->proformas_count > 0)
                                <span class="badge bg-success">
                                    {{ $temperatura->proformas_count }} proforma(s)
                                </span>
                            @else
                                <span class="badge bg-secondary">Sin proformas</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">
                                @if($temperatura->created_at)
                                    {{ $temperatura->created_at->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-warning">Sin fecha</span>
                                @endif
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('temperaturas.show', $temperatura) }}"
                                   class="btn btn-sm btn-info"
                                   title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @haspermission('edit temperaturas')
                                <a href="{{ route('temperaturas.edit', $temperatura) }}"
                                   class="btn btn-sm btn-warning"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endhaspermission
                                @haspermission('delete temperaturas')
                                <form action="{{ route('temperaturas.destroy', $temperatura) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Está seguro de eliminar esta temperatura?');">
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
                        <td colspan="5" class="text-center">
                            <div class="py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="mt-2 text-muted">No hay temperaturas registradas</p>
                                <a href="{{ route('temperaturas.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Crear Primera Temperatura
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
                <p class="mb-0">Total: <strong>{{ $temperaturas->total() }}</strong> temperaturas</p>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                {{ $temperaturas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#temperaturasTable tr');

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
