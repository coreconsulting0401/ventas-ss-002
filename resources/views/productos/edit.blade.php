@extends('layouts.app')

@section('title', 'Editar Producto')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-box-seam"></i> Editar Producto
    </h2>
    <a href="{{ route('productos.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Editar: <span class="text-primary">{{ $producto->nombre }}</span></h5>
    </div>
    <div class="card-body">
        <form action="{{ route('productos.update', $producto->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="codigo_e" class="form-label">
                            <i class="bi bi-upc"></i> Código E (Externo) <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('codigo_e') is-invalid @enderror"
                               id="codigo_e"
                               name="codigo_e"
                               value="{{ old('codigo_e', $producto->codigo_e) }}"
                               placeholder="Código externo"
                               maxlength="12"
                               required>
                        @error('codigo_e')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Máximo 12 caracteres</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="codigo_p" class="form-label">
                            <i class="bi bi-upc-scan"></i> Código P (Interno) <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('codigo_p') is-invalid @enderror"
                               id="codigo_p"
                               name="codigo_p"
                               value="{{ old('codigo_p', $producto->codigo_p) }}"
                               placeholder="Código interno"
                               maxlength="12"
                               required>
                        @error('codigo_p')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Máximo 12 caracteres</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="descuento_id" class="form-label">
                            <i class="bi bi-percent"></i> Descuento
                        </label>
                        <select class="form-select @error('descuento_id') is-invalid @enderror"
                                id="descuento_id"
                                name="descuento_id">
                            <option value="">Sin descuento</option>
                            @foreach($descuentos as $descuento)
                                <option value="{{ $descuento->id }}" {{ old('descuento_id', $producto->descuento_id) == $descuento->id ? 'selected' : '' }}>
                                    {{ number_format($descuento->porcentaje, 2) }}%
                                </option>
                            @endforeach
                        </select>
                        @error('descuento_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">
                            <i class="bi bi-box"></i> Nombre del Producto <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('nombre') is-invalid @enderror"
                               id="nombre"
                               name="nombre"
                               value="{{ old('nombre', $producto->nombre) }}"
                               placeholder="Nombre completo del producto"
                               maxlength="150"
                               required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Máximo 150 caracteres</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="marca" class="form-label">
                            <i class="bi bi-tag"></i> Marca <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('marca') is-invalid @enderror"
                               id="marca"
                               name="marca"
                               value="{{ old('marca', $producto->marca) }}"
                               placeholder="Marca del producto"
                               maxlength="50"
                               required>
                        @error('marca')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Máximo 50 caracteres</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="ubicacion" class="form-label">
                            <i class="bi bi-geo-alt"></i> Ubicación <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('ubicacion') is-invalid @enderror"
                               id="ubicacion"
                               name="ubicacion"
                               value="{{ old('ubicacion', $producto->ubicacion) }}"
                               placeholder="Ej: A1-B2-C3"
                               maxlength="10"
                               required>
                        @error('ubicacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Máximo 10 caracteres</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="precio_lista" class="form-label">
                            <i class="bi bi-cash-coin"></i> Precio Lista <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">S/.</span>
                            <input type="number"
                                   class="form-control @error('precio_lista') is-invalid @enderror"
                                   id="precio_lista"
                                   name="precio_lista"
                                   value="{{ old('precio_lista', $producto->precio_lista) }}"
                                   step="0.001"
                                   min="0"
                                   placeholder="0.000"
                                   required>
                        </div>
                        @error('precio_lista')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Máximo 7 enteros y 3 decimales</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="stock" class="form-label">
                            <i class="bi bi-boxes"></i> Stock <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               class="form-control @error('stock') is-invalid @enderror"
                               id="stock"
                               name="stock"
                               value="{{ old('stock', $producto->stock) }}"
                               min="0"
                               placeholder="0"
                               required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>ID:</strong> {{ $producto->id }} |
                @if($producto->descuento)
                    <strong>Descuento:</strong> {{ number_format($producto->descuento->porcentaje, 2) }}% |
                @endif
                @if($producto->created_at)
                    <strong>Creado:</strong> {{ $producto->created_at->format('d/m/Y H:i') }}
                @endif
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Actualizar Producto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
