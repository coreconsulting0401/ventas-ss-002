@extends('layouts.app')

@section('title', 'Editar Temperatura')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-thermometer-sun"></i> Editar Temperatura
    </h2>
    <a href="{{ route('temperaturas.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Editar: <span class="text-primary">{{ $temperatura->name }}</span></h5>
    </div>
    <div class="card-body">
        <form action="{{ route('temperaturas.update', $temperatura) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-thermometer-half"></i> Nombre de la Temperatura <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $temperatura->name) }}"
                               placeholder="Ej: Frío, Ambiente, Caliente, Congelado"
                               maxlength="30"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Máximo 30 caracteres</small>
                    </div>
                </div>
            </div>

            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>ID:</strong> {{ $temperatura->id }} |
                <strong>Proformas:</strong> {{ $temperatura->proformas_count }} |
                @if($temperatura->created_at)
                    <strong>Creado:</strong> {{ $temperatura->created_at->format('d/m/Y H:i') }}
                @endif
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('temperaturas.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Actualizar Temperatura
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
