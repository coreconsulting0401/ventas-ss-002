@extends('layouts.app')

@section('title', 'Estados')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-flag"></i> Gestión de Estados
    </h2>
    @haspermission('create estados')
    <a href="{{ route('estados.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Estado
    </a>
    @endhaspermission
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Listado de Estados</h5>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Buscar estado...">
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
                <tbody id="estadosTable">
                    @forelse($estados as $estado)
                    <tr>
                        <td>{{ $estado->id }}</td>
                        <td>
                            <span class="badge bg-primary">
                                <i class="bi bi-flag-fill"></i> {{ $estado->name }}
                            </span>
                        </td>
                        <td>
                            @if($estado->proformas_count > 0)
                                <span class="badge bg-success">
                                    {{ $estado->proformas_count }} proforma(s)
                                </span>
                            @else
                                <span class="badge bg-secondary">Sin proformas</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">
                                @if($estado->created_at)
                                    {{ $estado->created_at->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-warning">Sin fecha</span>
                                @endif
                            </small>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('estados.show', $estado) }}"
                                   class="btn btn-sm btn-info"
                                   title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @haspermission('edit estados')
                                <a href="{{ route('estados.edit', $estado) }}"
                                   class="btn btn-sm btn-warning"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endhaspermission
                                @haspermission('delete estados')
                                <form action="{{ route('estados.destroy', $estado) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Está seguro de eliminar este estado?');">
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
                                <p class="mt-2 text-muted">No hay estados registrados</p>
                                <a href="{{ route('estados.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Crear Primer Estado
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
                <p class="mb-0">Total: <strong>{{ $estados->total() }}</strong> estados</p>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                {{ $estados->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#estadosTable tr');

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
