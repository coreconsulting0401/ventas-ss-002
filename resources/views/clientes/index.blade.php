@extends('layouts.app')

@section('title', 'Clientes')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-people"></i> Gestión de Clientes
    </h2>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Cliente
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Listado de Clientes</h5>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Buscar cliente...">
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
                        <th>RUC</th>
                        <th>Razón Social</th>
                        <th>Teléfono</th>
                        <th>Categoría</th>
                        <th>Contactos</th>
                        <th>Agencias</th>
                        <th>Crédito</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="clientesTable">
                    @forelse($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->id }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ $cliente->ruc }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ $cliente->razon }}</strong><br>
                            <small class="text-muted">{{ Str::limit($cliente->direccion, 40) }}</small>
                        </td>
                        <td>
                            <i class="bi bi-telephone"></i> {{ $cliente->telefono1 }}<br>
                            @if($cliente->telefono2)
                                <small class="text-muted">
                                    <i class="bi bi-phone"></i> {{ $cliente->telefono2 }}
                                </small>
                            @endif
                        </td>
                        <td>
                            @if($cliente->categoria)
                                <span class="badge bg-primary">
                                    {{ $cliente->categoria->name }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Sin categoría</span>
                            @endif
                        </td>
                        <td>
                            @if($cliente->contactos->count() > 0)
                                <span class="badge bg-success">
                                    {{ $cliente->contactos->count() }} contacto(s)
                                </span>
                                <div class="small text-muted mt-1">
                                    @foreach($cliente->contactos->take(2) as $contacto)
                                        <div>• {{ Str::limit($contacto->nombre . ' ' . $contacto->apellido_paterno, 25) }}</div>
                                    @endforeach
                                    @if($cliente->contactos->count() > 2)
                                        <div class="text-primary">+{{ $cliente->contactos->count() - 2 }} más</div>
                                    @endif
                                </div>
                            @else
                                <span class="badge bg-secondary">Sin contactos</span>
                            @endif
                        </td>
                        <td>
                            @if($cliente->direcciones->count() > 0)
                                <span class="badge bg-info">
                                    {{ $cliente->direcciones->count() }} dirección(es)
                                </span>
                                <div class="small text-muted mt-1">
                                    @foreach($cliente->direcciones->take(2) as $direccion)
                                        <div>• {{ Str::limit($direccion->direccion, 25) }}</div>
                                    @endforeach
                                    @if($cliente->direcciones->count() > 2)
                                        <div class="text-primary">+{{ $cliente->direcciones->count() - 2 }} más</div>
                                    @endif
                                </div>
                            @else
                                <span class="badge bg-secondary">Sin direcciones</span>
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
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('clientes.show', $cliente) }}"
                                   class="btn btn-sm btn-info"
                                   title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('clientes.edit', $cliente) }}"
                                   class="btn btn-sm btn-warning"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('clientes.destroy', $cliente) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Está seguro de eliminar este cliente?');">
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
                                <p class="mt-2 text-muted">No hay clientes registrados</p>
                                <a href="{{ route('clientes.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Crear Primer Cliente
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
                <p class="mb-0">Total: <strong>{{ $clientes->total() }}</strong> clientes</p>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                {{ $clientes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#clientesTable tr');

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
