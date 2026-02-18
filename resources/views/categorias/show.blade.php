@extends('layouts.app')

@section('title', 'Detalle de Categoría')

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-tag"></i> Detalle de Categoría
    </h2>
    <div class="btn-group">
        <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
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
                    <i class="bi bi-tag-fill"></i> Información de la Categoría
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>ID:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-secondary">{{ $categoria->id }}</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Nombre:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="badge bg-primary fs-5">
                            <i class="bi bi-tag"></i> {{ $categoria->name }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Fecha de Creación:</strong>
                    </div>
                    <div class="col-md-8">
                        <i class="bi bi-calendar-check"></i>
                        {{ $categoria->created_at->format('d/m/Y H:i:s') }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Última Actualización:</strong>
                    </div>
                    <div class="col-md-8">
                        <i class="bi bi-calendar-x"></i>
                        {{ $categoria->updated_at->format('d/m/Y H:i:s') }}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <form action="{{ route('categorias.destroy', $categoria) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('¿Está seguro de eliminar esta categoría?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Eliminar Categoría
                        </button>
                    </form>
                    <a href="{{ route('categorias.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Listado
                    </a>
                </div>
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
                        <i class="bi bi-tags" style="font-size: 4rem;"></i>
                    </div>
                </div>

                <div class="alert alert-primary">
                    <i class="bi bi-lightbulb"></i>
                    <strong>Tip:</strong> Las categorías ayudan a organizar y clasificar a los clientes en el sistema.
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Importante:</strong> Al eliminar una categoría, se perderá la relación con los productos asociados.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
