@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-tag"></i> Editar Categoría
    </h2>
    <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Editar: <span class="text-primary">{{ $categoria->name }}</span></h5>
    </div>
    <div class="card-body">
        <form action="{{ route('categorias.update', $categoria) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-tag-fill"></i> Nombre de la Categoría <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name', $categoria->name) }}"
                               placeholder="Ej: Electrónica, Ropa, Alimentos"
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
                <strong>ID:</strong> {{ $categoria->id }} |
                <strong>Creado:</strong> {{ $categoria->created_at->format('d/m/Y H:i') }} |
                <strong>Actualizado:</strong> {{ $categoria->updated_at->format('d/m/Y H:i') }}
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Actualizar Categoría
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
