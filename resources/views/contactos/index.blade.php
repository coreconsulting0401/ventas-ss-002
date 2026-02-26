@extends('layouts.app')

@section('title', 'Contactos')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-person-lines-fill"></i> Gestión de Contactos
    </h2>
    <a href="{{ route('contactos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Contacto
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">Listado de Contactos</h5>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Buscar contacto...">
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
                        <th>DNI</th>
                        <th>Nombre Completo</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Cargo</th>
                        <th>Clientes</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="contactosTable">
                    @forelse($contactos as $contacto)
                    <tr>
                        <td>{{ $contacto->id }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ $contacto->dni }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ $contacto->nombre }}</strong><br>
                            <small class="text-muted">
                                {{ $contacto->apellido_paterno }} {{ $contacto->apellido_materno }}
                            </small>
                        </td>
                        <td>
                            <i class="bi bi-telephone"></i> {{ $contacto->telefono }}
                        </td>
                        <td>
                            <i class="bi bi-envelope"></i> {{ $contacto->email }}
                        </td>
                        <td>
                            <span class="badge bg-warning text-dark">
                                {{ $contacto->cargo }}
                            </span>
                        </td>
                        <td>
                            @if($contacto->clientes->count() > 0)
                                <span class="badge bg-success">
                                    {{ $contacto->clientes->count() }} cliente(s)
                                </span>
                                <div class="small text-muted mt-1">
                                    @foreach($contacto->clientes->take(2) as $cliente)
                                        <div>• {{ Str::limit($cliente->razon, 25) }}</div>
                                    @endforeach
                                    @if($contacto->clientes->count() > 2)
                                        <div class="text-primary">+{{ $contacto->clientes->count() - 2 }} más</div>
                                    @endif
                                </div>
                            @else
                                <span class="badge bg-secondary">Sin clientes</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('contactos.show', $contacto) }}"
                                   class="btn btn-sm btn-info"
                                   title="Ver detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('contactos.edit', $contacto) }}"
                                   class="btn btn-sm btn-warning"
                                   title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @haspermission('delete contactos')
                                <form action="{{ route('contactos.destroy', $contacto) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Está seguro de eliminar este contacto?');">
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
                        <td colspan="8" class="text-center">
                            <div class="py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="mt-2 text-muted">No hay contactos registrados</p>
                                <a href="{{ route('contactos.create') }}" class="btn btn-primary mt-2">
                                    <i class="bi bi-plus-circle"></i> Crear Primer Contacto
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
                <p class="mb-0">Total: <strong>{{ $contactos->total() }}</strong> contactos</p>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                {{ $contactos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#contactosTable tr');

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
