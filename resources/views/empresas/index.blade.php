@extends('layouts.app')
@section('title', 'Configuración de Empresa')

@section('page-title')
    <i class="bi bi-building"></i> Configuración de Empresa
    <a href="{{ route('empresas.edit', $empresa) }}" class="btn btn-sm btn-primary float-end">
        <i class="bi bi-pencil-square"></i> Editar
    </a>
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle-fill me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="bi bi-info-circle-fill me-1"></i> {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">

    {{-- =====================================================================
         INFORMACIÓN BÁSICA
    ====================================================================== --}}
    <div class="col-md-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-building"></i> Información Básica</h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    {{-- Logo --}}
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        @if($empresa->uri_img_logo)
                            <img src="{{ asset('storage/' . $empresa->uri_img_logo) }}"
                                 alt="Logo de la empresa"
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 120px; object-fit: contain;">
                        @else
                            <div class="bg-light p-3 rounded border">
                                <i class="bi bi-image text-muted" style="font-size: 2.5rem;"></i>
                                <p class="text-muted mt-1 mb-0 small">Sin logo</p>
                            </div>
                        @endif
                    </div>

                    {{-- Datos --}}
                    <div class="col-md-10">
                        <h4 class="fw-bold mb-2">{{ $empresa->razon_social }}</h4>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <span class="text-muted small"><i class="bi bi-card-text text-secondary"></i> <strong>RUC:</strong></span>
                                <span class="ms-1">{{ $empresa->ruc ?? '—' }}</span>
                            </div>
                            <div class="col-md-4">
                                <span class="text-muted small"><i class="bi bi-globe text-primary"></i> <strong>Página Web:</strong></span>
                                @if($empresa->pagina_web)
                                    <a href="{{ $empresa->pagina_web }}" target="_blank" class="ms-1 small">{{ $empresa->pagina_web }}</a>
                                @else
                                    <span class="ms-1">—</span>
                                @endif
                            </div>
                            <div class="col-md-12 mt-1">
                                <span class="text-muted small"><i class="bi bi-geo-alt-fill text-danger"></i> <strong>Dirección:</strong></span>
                                <span class="ms-1">{{ $empresa->direccion ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =====================================================================
         CORREOS ELECTRÓNICOS
    ====================================================================== --}}
    <div class="col-md-6">
        <div class="card shadow-sm mb-4 h-100">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-envelope-fill"></i> Correos Electrónicos</h5>
            </div>
            <div class="card-body p-0">
                @if($empresa->emails->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Área</th>
                                    <th>Correo</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($empresa->emails as $email)
                                    <tr>
                                        <td>{{ $email->area }}</td>
                                        <td>{{ $email->email }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $email->activo ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $email->activo ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-envelope-slash" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">No hay correos registrados.</p>
                        <a href="{{ route('empresas.edit', $empresa) }}" class="btn btn-sm btn-outline-info mt-2">
                            <i class="bi bi-plus-circle"></i> Agregar correos
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- =====================================================================
         TELÉFONOS
    ====================================================================== --}}
    <div class="col-md-6">
        <div class="card shadow-sm mb-4 h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-telephone-fill"></i> Teléfonos</h5>
            </div>
            <div class="card-body p-0">
                @if($empresa->telefonos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Área</th>
                                    <th>Teléfono</th>
                                    <th>Descripción</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($empresa->telefonos as $tel)
                                    <tr>
                                        <td>{{ $tel->area }}</td>
                                        <td>{{ $tel->telefono }}</td>
                                        <td>{{ $tel->descripcion ?? '—' }}</td>
                                        <td class="text-center">
                                            <span class="badge {{ $tel->activo ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $tel->activo ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-4 text-center text-muted">
                        <i class="bi bi-telephone-x" style="font-size: 2rem;"></i>
                        <p class="mt-2 mb-0">No hay teléfonos registrados.</p>
                        <a href="{{ route('empresas.edit', $empresa) }}" class="btn btn-sm btn-outline-success mt-2">
                            <i class="bi bi-plus-circle"></i> Agregar teléfonos
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- =====================================================================
         IMÁGENES
    ====================================================================== --}}
    <div class="col-md-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-images"></i> Imágenes</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">

                    {{-- Logo --}}
                    <div class="col-md-3 mb-3">
                        <p class="fw-semibold mb-2"><i class="bi bi-badge-tm"></i> Logo</p>
                        @if($empresa->uri_img_logo)
                            <img src="{{ asset('storage/' . $empresa->uri_img_logo) }}"
                                 alt="Logo" class="img-thumbnail"
                                 style="max-height: 110px; object-fit: contain;">
                        @else
                            <div class="bg-light border rounded p-3">
                                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0 small mt-1">No cargado</p>
                            </div>
                        @endif
                    </div>

                    {{-- Publicidad --}}
                    <div class="col-md-3 mb-3">
                        <p class="fw-semibold mb-2"><i class="bi bi-megaphone"></i> Publicidad</p>
                        @if($empresa->uri_img_publicidad)
                            <img src="{{ asset('storage/' . $empresa->uri_img_publicidad) }}"
                                 alt="Publicidad" class="img-thumbnail"
                                 style="max-height: 110px; object-fit: contain;">
                        @else
                            <div class="bg-light border rounded p-3">
                                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0 small mt-1">No cargada</p>
                            </div>
                        @endif
                    </div>

                    {{-- Condiciones --}}
                    <div class="col-md-3 mb-3">
                        <p class="fw-semibold mb-2"><i class="bi bi-file-earmark-check"></i> Condiciones</p>
                        @if($empresa->uri_img_condiciones)
                            <img src="{{ asset('storage/' . $empresa->uri_img_condiciones) }}"
                                 alt="Condiciones" class="img-thumbnail"
                                 style="max-height: 110px; object-fit: contain;">
                        @else
                            <div class="bg-light border rounded p-3">
                                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0 small mt-1">No cargada</p>
                            </div>
                        @endif
                    </div>

                    {{-- Cuentas Bancarias --}}
                    <div class="col-md-3 mb-3">
                        <p class="fw-semibold mb-2"><i class="bi bi-bank"></i> Cuentas Bancarias</p>
                        @if($empresa->uri_cuentas_bancarias)
                            <img src="{{ asset('storage/' . $empresa->uri_cuentas_bancarias) }}"
                                 alt="Cuentas bancarias" class="img-thumbnail"
                                 style="max-height: 110px; object-fit: contain;">
                        @else
                            <div class="bg-light border rounded p-3">
                                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0 small mt-1">No cargada</p>
                            </div>
                        @endif
                    </div>

                </div>

                @php
                    $imgFaltantes = collect([
                        'logo'             => $empresa->uri_img_logo,
                        'publicidad'       => $empresa->uri_img_publicidad,
                        'condiciones'      => $empresa->uri_img_condiciones,
                        'cuentas bancarias'=> $empresa->uri_cuentas_bancarias,
                    ])->filter(fn($v) => is_null($v))->keys();
                @endphp

                @if($imgFaltantes->isNotEmpty())
                    <div class="alert alert-warning mt-2 mb-0 py-2">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        Imágenes pendientes de cargar: <strong>{{ $imgFaltantes->join(', ') }}</strong>.
                        <a href="{{ route('empresas.edit', $empresa) }}" class="alert-link">Cargar ahora.</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- =====================================================================
         ZONA DE PELIGRO
    ====================================================================== --}}
    <div class="col-md-12">
        <div class="card shadow-sm border-danger mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle-fill"></i> Zona de Peligro</h5>
            </div>
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <strong>Eliminar registro de empresa</strong>
                    <p class="mb-0 text-muted small">
                        Esta acción eliminará permanentemente el registro y todas sus imágenes, correos y teléfonos asociados.
                    </p>
                </div>
                <form action="{{ route('empresas.destroy', $empresa) }}" method="POST"
                      onsubmit="return confirm('¿Está seguro de que desea eliminar el registro de empresa? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash3"></i> Eliminar Empresa
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
