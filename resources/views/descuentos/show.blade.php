@extends('layouts.app')

@section('title', 'Detalle de Descuento')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-percent"></i> Detalle de Descuento
    </h2>
    <div class="btn-group">
        <a href="{{ route('descuentos.edit', $descuento->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('descuentos.index') }}" class="btn btn-secondary">
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
                    <i class="bi bi-percent"></i> Información del Descuento
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>ID:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-secondary">{{ $descuento->id }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Porcentaje:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-success fs-4">
                            <i class="bi bi-percent"></i> {{ number_format($descuento->porcentaje, 2) }}%
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Número de Productos:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($descuento->productos_count > 0)
                            <span class="badge bg-info">
                                <i class="bi bi-box-seam"></i> {{ $descuento->productos_count }} producto(s)
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="bi bi-inbox"></i> Sin productos asociados
                            </span>
                        @endif
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Fecha de Creación:</strong>
                    </div>
                    <div class="col-md-8">
                        @if($descuento->created_at)
                            <i class="bi bi-calendar-check"></i>
                            {{ $descuento->created_at->format('d/m/Y H:i:s') }}
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
                        @if($descuento->updated_at)
                            <i class="bi bi-calendar-x"></i>
                            {{ $descuento->updated_at->format('d/m/Y H:i:s') }}
                        @else
                            <i class="bi bi-calendar-x"></i>
                            <span class="text-muted">No disponible</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    @haspermission('delete descuentos')
                    <form action="{{ route('descuentos.destroy', $descuento->id) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('¿Está seguro de eliminar este descuento?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Eliminar Descuento
                        </button>
                    </form>
                    @endhaspermission
                    <a href="{{ route('descuentos.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </div>
        </div>

        <!-- Sección de Productos Relacionados -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-box-seam"></i> Productos Asociados ({{ $descuento->productos->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($descuento->productos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($descuento->productos->take(5) as $producto)
                                <tr>
                                    <td>
                                        <span class="badge bg-info">{{ $producto->id }}</span>
                                    </td>
                                    <td>
                                        {{ Str::limit($producto->nombre, 30) }}
                                    </td>
                                    <td>
                                        <strong>S/. {{ number_format($producto->precio, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $producto->stock }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('productos.show', $producto) }}"
                                           class="btn btn-sm btn-info"
                                           title="Ver producto">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($descuento->productos->count() > 5)
                        <div class="text-center mt-3">
                            <a href="{{ route('productos.index') }}?descuento_id={{ $descuento->id }}" class="btn btn-sm btn-primary">
                                Ver todos los productos ({{ $descuento->productos->count() }})
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="mt-2 text-muted">Este descuento no tiene productos asociados</p>
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
                        <i class="bi bi-percent" style="font-size: 4rem;"></i>
                    </div>
                </div>

                <div class="alert alert-primary">
                    <i class="bi bi-lightbulb"></i>
                    <strong>Tip:</strong> Los descuentos ayudan a ofrecer promociones y ofertas especiales a tus clientes.
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Importante:</strong> Al eliminar un descuento, todos los productos asociados perderán su descuento actual.
                </div>

                <div class="mt-3">
                    <h6 class="mb-2">Datos del Descuento:</h6>
                    <ul class="list-unstyled small">
                        <li><i class="bi bi-check-circle text-success"></i> ID: {{ $descuento->id }}</li>
                        <li><i class="bi bi-check-circle text-success"></i> Porcentaje: {{ number_format($descuento->porcentaje, 2) }}%</li>
                        <li><i class="bi bi-check-circle text-success"></i> Productos: {{ $descuento->productos_count }}</li>
                        @if($descuento->created_at)
                            <li><i class="bi bi-check-circle text-success"></i> Creado: {{ $descuento->created_at->format('d/m/Y H:i') }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
