@extends('layouts.app')

@section('title', 'Ajustar Incremento — ' . \Carbon\Carbon::parse($cambio->fecha)->format('d/m/Y'))

@section('page-title')
<div class="section-header">
    <h2>
        <i class="bi bi-sliders"></i>
        Ajustar Incremento — {{ \Carbon\Carbon::parse($cambio->fecha)->format('d/m/Y') }}
    </h2>
    <a href="{{ route('cambios.show', $cambio) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">

        {{-- ── Tarjeta de referencia (solo lectura) ───────────────────────── --}}
        <div class="card shadow-sm mb-4" style="background:#f8f9fa;">
            <div class="card-body">
                <h6 class="text-muted mb-3">
                    <i class="bi bi-lock-fill"></i> Datos de referencia (solo lectura)
                </h6>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="small text-muted">Fecha</div>
                        <div class="fw-bold">{{ \Carbon\Carbon::parse($cambio->fecha)->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-4">
                        <div class="small text-muted">Venta Oficial</div>
                        <div class="fw-bold text-danger fs-5">
                            S/. {{ $cambio->venta ? number_format($cambio->venta, 4) : '—' }}
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="small text-muted">Venta+ actual</div>
                        <div class="fw-bold text-success fs-5">
                            S/. {{ $cambio->venta_mas ? number_format($cambio->venta_mas, 4) : '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Formulario del incremento ───────────────────────────────────── --}}
        <div class="card shadow-sm">
            <div class="card-header" style="background:#fff8e1;">
                <h5 class="mb-0">
                    <i class="bi bi-calculator-fill text-warning"></i>
                    Configurar Incremento sobre la Venta
                </h5>
            </div>
            <div class="card-body">

                <form action="{{ route('cambios.update-incremento', $cambio) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label for="incremento" class="form-label fw-bold">
                            Incremento <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">S/. +</span>
                            <input type="number"
                                   class="form-control @error('incremento') is-invalid @enderror"
                                   id="incremento"
                                   name="incremento"
                                   value="{{ old('incremento', $cambio->incremento) }}"
                                   step="0.0001"
                                   min="0"
                                   max="1"
                                   placeholder="0.0200"
                                   autofocus
                                   oninput="calcularPreview()">
                            @error('incremento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">
                            Valor entre 0.0000 y 1.0000 · máximo 4 decimales · valor por defecto: 0.0200
                        </small>
                    </div>

                    {{-- Preview en tiempo real --}}
                    @if($cambio->venta)
                    <div class="alert alert-success" id="previewBox">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small text-muted">Venta Oficial</div>
                                <div class="fw-bold">S/. {{ number_format($cambio->venta, 4) }}</div>
                            </div>
                            <div class="fs-3 text-muted mx-3">+</div>
                            <div>
                                <div class="small text-muted">Incremento</div>
                                <div class="fw-bold" id="previewIncremento">
                                    S/. {{ number_format($cambio->incremento, 4) }}
                                </div>
                            </div>
                            <div class="fs-3 text-muted mx-3">=</div>
                            <div>
                                <div class="small text-muted">Venta+</div>
                                <div class="fw-bold text-success fs-5" id="previewVentaMas">
                                    S/. {{ number_format($cambio->venta_mas, 4) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('cambios.show', $cambio) }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle"></i> Guardar Incremento
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
const ventaOficial = {{ (float) $cambio->venta }};

function calcularPreview() {
    const inc = parseFloat(document.getElementById('incremento').value) || 0;
    const ventaMas = ventaOficial + inc;

    document.getElementById('previewIncremento').textContent =
        'S/. ' + inc.toFixed(4);
    document.getElementById('previewVentaMas').textContent =
        'S/. ' + ventaMas.toFixed(4);
}
</script>
@endpush
