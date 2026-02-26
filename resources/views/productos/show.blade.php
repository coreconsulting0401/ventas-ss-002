@extends('layouts.app')

@section('title', 'Detalle de Producto')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-box-seam"></i> Detalle de Producto
    </h2>
    <div class="btn-group">
        @haspermission('edit productos')
        <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        @endhaspermission
        <a href="{{ route('productos.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-box"></i> Información del Producto
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Código E:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-info fs-5">
                            <i class="bi bi-upc"></i> {{ $producto->codigo_e }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Código P:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-secondary fs-5">
                            <i class="bi bi-upc-scan"></i> {{ $producto->codigo_p }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Nombre:</strong>
                    </div>
                    <div class="col-md-8">
                        <h5 class="mb-0">{{ $producto->nombre }}</h5>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Marca:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-primary">{{ $producto->marca }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Ubicación:</strong>
                    </div>
                    <div class="col-md-8">
                        <i class="bi bi-geo-alt"></i> {{ $producto->ubicacion }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Precio Lista:</strong>
                    </div>
                    <div class="col-md-8">
                        <strong class="text-success fs-4">S/. {{ number_format($producto->precio_lista, 3) }}</strong>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Stock Actual:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($producto->stock > 10)
                            <span class="badge bg-success fs-4">
                                <i class="bi bi-boxes"></i> {{ $producto->stock }} unidades
                            </span>
                        @elseif($producto->stock > 0)
                            <span class="badge bg-warning fs-4">
                                <i class="bi bi-exclamation-triangle"></i> {{ $producto->stock }} unidades (Stock bajo)
                            </span>
                        @else
                            <span class="badge bg-danger fs-4">
                                <i class="bi bi-x-circle"></i> AGOTADO
                            </span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Descuento:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($producto->descuento)
                            <span class="badge bg-success fs-4">
                                <i class="bi bi-percent"></i> {{ number_format($producto->descuento->porcentaje, 2) }}%
                            </span>
                            <br>
                            <small class="text-muted">
                                Precio con descuento: S/. {{ number_format($producto->precio_lista * (1 - $producto->descuento->porcentaje / 100), 3) }}
                            </small>
                        @else
                            <span class="badge bg-secondary">Sin descuento</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Fecha de Creación:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($producto->created_at)
                            <i class="bi bi-calendar-check"></i>
                            {{ $producto->created_at->format('d/m/Y H:i:s') }}
                        @else
                            <i class="bi bi-calendar-x"></i>
                            <span class="text-muted">No disponible</span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Última Actualización:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($producto->updated_at)
                            <i class="bi bi-calendar-x"></i>
                            {{ $producto->updated_at->format('d/m/Y H:i:s') }}
                        @else
                            <i class="bi bi-calendar-x"></i>
                            <span class="text-muted">No disponible</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">

                    @haspermission('delete productos')
                    <form action="{{ route('productos.destroy', $producto->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('¿Está seguro de eliminar este producto?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Eliminar Producto
                        </button>
                    </form>
                    @endhaspermission
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>
        </div>

        <!-- Sección de Proformas Relacionadas -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-file-earmark-text"></i> Proformas Asociadas ({{ $producto->proformas->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($producto->proformas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unit.</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($producto->proformas->take(5) as $proforma)
                                <tr>
                                    <td>
                                        <span class="badge bg-info">{{ $proforma->id }}</span>
                                    </td>
                                    <td>
                                        @if($proforma->pivot)
                                            {{ $proforma->pivot->cantidad }} unidades
                                        @endif
                                    </td>
                                    <td>
                                        @if($proforma->cliente)
                                            {{ Str::limit($proforma->cliente->razon, 25) }}
                                        @else
                                            <span class="text-muted">Sin cliente</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>S/. {{ number_format($proforma->pivot->precio_unitario ?? 0, 3) }}</strong>
                                    </td>
                                    <td>
                                        {{ $proforma->created_at->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('proformas.show', $proforma) }}"
                                           class="btn btn-sm btn-info"
                                           title="Ver proforma">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($producto->proformas->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('proformas.index') }}" class="btn btn-sm btn-primary">
                                Ver todas las proformas ({{ $producto->proformas->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="mt-2 text-muted">Este producto no ha sido incluido en ninguna proforma</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle"></i> Información Adicional
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="card-img">
                        <i class="bi bi-box-seam" style="font-size: 4rem;"></i>
                    </div>
                </div>

                <div class="alert alert-primary">
                    <i class="bi bi-lightbulb"></i>
                    <strong>Tip:</strong> Los productos son los artículos que se incluyen en las proformas para los clientes.
                </div>
                @haspermission('delete productos')
                <div class="alert alert-info">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Importante:</strong> Al eliminar un producto, se eliminará de todas las proformas donde esté incluido.
                </div>
                @endhaspermission
                <div class="mt-3">
                    <h6 class="mb-2">Datos del Producto:</h6>
                    <ul class="list-unstyled small">
                        <li><i class="bi bi-check-circle text-success"></i> ID: {{ $producto->id }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Código E: {{ $producto->codigo_e }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Código P: {{ $producto->codigo_p }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Nombre: {{ Str::limit($producto->nombre, 30) }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Marca: {{ $producto->marca }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Precio: S/. {{ number_format($producto->precio_lista, 3) }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Stock: {{ $producto->stock }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Ubicación: {{ $producto->ubicacion }}</li>
                        @if($producto->descuento)
                            <li><i class="bi bi-check-circle text-success"></i> Descuento: {{ number_format($producto->descuento->porcentaje, 2) }}%</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
