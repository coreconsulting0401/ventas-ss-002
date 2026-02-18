@extends('layouts.app')

@section('title', 'Crear Transacción')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-coin"></i> Nueva Transacción
    </h2>
    <a href="{{ route('transacciones.index') }}" class="btn btn-secondary">
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
        <form action="{{ route('transacciones.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-coin"></i> Nombre de la Transacción <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Ej: Contado, Crédito, Transferencia, Depósito"
                               maxlength="30"
                               required
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Máximo 30 caracteres</small>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>Tip:</strong> Las transacciones se utilizan para clasificar el método de pago o tipo de transacción de las proformas.
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('transacciones.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Guardar Transacción
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
