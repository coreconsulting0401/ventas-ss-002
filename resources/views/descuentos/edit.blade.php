@extends('layouts.app')

@section('title', 'Editar Descuento')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-percent"></i> Editar Descuento
    </h2>
    <a href="{{ route('descuentos.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Editar: <span class="text-success">{{ number_format($descuento->porcentaje, 2) }}%</span></h5>
    </div>
    <div class="card-body">
        <form action="{{ route('descuentos.update', $descuento->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="porcentaje" class="form-label">
                            <i class="bi bi-percent"></i> Porcentaje de Descuento <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number"
                                   class="form-control @error('porcentaje') is-invalid @enderror"
                                   id="porcentaje"
                                   name="porcentaje"
                                   value="{{ old('porcentaje', $descuento->porcentaje) }}"
                                   step="0.01"
                                   min="0"
                                   max="100"
                                   placeholder="0.00"
                                   required>
                            <span class="input-group-text">%</span>
                        </div>
                        @error('porcentaje')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Ingrese un valor entre 0.00% y 100.00%</small>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>ID:</strong> {{ $descuento->id }} |
                <strong>Productos:</strong> {{ $descuento->productos_count }} |
                @if($descuento->created_at)
                    <strong>Creado:</strong> {{ $descuento->created_at->format('d/m/Y H:i') }}
                @endif
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('descuentos.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Actualizar Descuento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
