@extends('layouts.app')

@section('title', 'Crear Descuento')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-percent"></i> Nuevo Descuento
    </h2>
    <a href="{{ route('descuentos.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Formulario de Registro</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('descuentos.store') }}" method="POST">
            @csrf
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
                                   value="{{ old('porcentaje') }}"
                                   step="0.01"
                                   min="0"
                                   max="100"
                                   placeholder="0.00"
                                   required
                                   autofocus>
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
                <strong>Tip:</strong> Los descuentos se aplican a los productos para ofrecer promociones y ofertas especiales.
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('descuentos.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Guardar Descuento
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
