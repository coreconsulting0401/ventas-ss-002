{{--
    Vista: resources/views/proformas/pdf.blade.php
    Motor: DomPDF (Barryvdh\DomPDF)
    Tamaño: A4  210 × 297 mm
    Márgenes: 20 mm en los 4 lados  →  área útil: 170 × 257 mm

    Variables recibidas del controller:
        $proforma          – Proforma con relaciones cargadas
        $empresa           – Empresa (id=1) con emails() y telefonos() activos
        $numeroCotizacion  – 'NTC-00000000001'
        $fechaEmision      – 'dd/mm/yyyy'
        $logoPath          – ruta absoluta a uri_img_logo (o null)
        $publicidadPath    – ruta absoluta a uri_img_publicidad (o null)
        $condicionesPath   – ruta absoluta a uri_img_condiciones (o null)
        $cuentasPath       – ruta absoluta a uri_cuentas_bancarias (o null)

    Campos empresa (tabla empresas):
        razon_social, ruc, direccion, pagina_web,
        uri_img_logo, uri_img_publicidad, uri_img_condiciones, uri_cuentas_bancarias

    Campos email_empresas:  empresa_id | area | email | activo
    Campos telefono_empresas: empresa_id | area | telefono | descripcion | activo
--}}
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Cotización {{ $numeroCotizacion }}</title>
<style>
/* ══════════════════════════════════════════════════════
   RESET
══════════════════════════════════════════════════════ */
* { margin: 0; padding: 0; box-sizing: border-box; }

/*
 * MÁRGENES EN DOMPDF
 * ------------------
 * DomPDF no respeta @page margin de forma consistente.
 * La solución correcta es poner margin: 0 en @page y
 * declarar los márgenes directamente en body.
 */
@page {
    size: A4 portrait;   /* 210 × 297 mm */
    margin: 0;
}

body {
    font-family: 'DejaVu Sans', Arial, sans-serif;
    font-size: 8.5px;
    line-height: 1.4;
    color: #1e293b;
    /* Margen de 20 mm aplicado via body — técnica correcta para DomPDF */
    margin: 20mm 20mm 20mm 20mm;
}

/* ══════════════════════════════════════════════════════
   UTILIDADES
══════════════════════════════════════════════════════ */
.tr    { text-align: right  !important; }
.tc    { text-align: center !important; }
.bold  { font-weight: bold; }
.gray  { color: #64748b; }
.small { font-size: 6.5px; }

/* ══════════════════════════════════════════════════════
   SALTOS DE PÁGINA
══════════════════════════════════════════════════════ */
.salto { page-break-after: always; }

/* ══════════════════════════════════════════════════════
   1 · ENCABEZADO  (logo izq | RUC · COTIZACIÓN · NRO der)
══════════════════════════════════════════════════════ */
.hdr-tbl { width: 100%; border-collapse: collapse; }

.hdr-logo-td { width: 42%; vertical-align: middle; }
.hdr-logo-td img {
    max-height: 58px;
    max-width: 170px;
    display: block;
}
.hdr-logo-fallback {
    font-size: 18px;
    font-weight: bold;
    color: #1e3a8a;
}

.hdr-right-td { width: 58%; vertical-align: top; text-align: right; }
.hdr-ruc {
    font-size: 10px;
    font-weight: bold;
    color: #1e293b;
    margin-bottom: 0;
}
.hdr-titulo {
    font-size: 30px;
    font-weight: bold;
    color: #2563eb;
    line-height: 1;
    letter-spacing: 1.5px;
}
.hdr-nro {
    font-size: 10.5px;
    color: #dc2626;
    font-weight: bold;
}

.hdr-sep {
    border-bottom: 2.5px solid #1e3a8a;
    padding-bottom: 5px;
    margin-bottom: 0;
}

/* ══════════════════════════════════════════════════════
   2 · DATOS DE EMPRESA  (franja entre header y cuadros)
══════════════════════════════════════════════════════ */
.empresa-franja {
    width: 100%;
    font-size: 7.5px;
    color: #374151;
    line-height: 1.65;
    padding: 5px 0 5px 0;
    border-bottom: 1px solid #e2e8f0;
    margin-bottom: 7px;
}

/* ══════════════════════════════════════════════════════
   3 · CUADROS CLIENTE / VENDEDOR
══════════════════════════════════════════════════════ */
.cv-tbl {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 7px;
}
.cv-left { width: 50%; padding-right: 4px; vertical-align: top; }
.cv-right{ width: 50%; padding-left:  4px; vertical-align: top; }

.info-box {
    border: 1px solid #cbd5e1;
    padding: 5px 6px 6px 6px;
    background: #f8fafc;
    /* height: 100% eliminado — causaba expansión a página completa en DomPDF */
}
.info-box-tit {
    font-size: 7.5px;
    font-weight: bold;
    color: #1e3a8a;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    border-bottom: 1px solid #cbd5e1;
    padding-bottom: 3px;
    margin-bottom: 4px;
}
.irow { margin-bottom: 2px; font-size: 8px; }
.ilbl { font-weight: bold; color: #374151; }
.ival { color: #111; }

/* ══════════════════════════════════════════════════════
   4 · GRILLA DE PRODUCTOS
══════════════════════════════════════════════════════ */
.prod-tbl { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
.prod-tbl thead tr { background: #1e3a8a; color: #fff; }
.prod-tbl th {
    padding: 3.5px 3px;
    font-size: 7.5px;
    font-weight: bold;
    border: 1px solid #1e3a8a;
}
.prod-tbl td {
    padding: 2.5px 3px;
    border: 1px solid #e2e8f0;
    font-size: 7.5px;
    vertical-align: middle;
}
.prod-tbl tbody tr:nth-child(even) { background: #f1f5f9; }

/* ══════════════════════════════════════════════════════
   5 · CONDICIONES COMERCIALES (izq) + TOTALES (der)
══════════════════════════════════════════════════════ */
.bot-tbl  { width: 100%; border-collapse: collapse; margin-top: 4px; }
.bot-left { width: 57%; padding-right: 5px; vertical-align: top; }
.bot-right{ width: 43%; vertical-align: top; }

/* Condiciones */
.cond-box {
    border: 1px solid #fbbf24;
    background: #fffbeb;
    padding: 6px 7px;
}
.cond-tit {
    font-size: 8px;
    font-weight: bold;
    color: #92400e;
    text-transform: uppercase;
    margin-bottom: 4px;
}
.crow { margin-bottom: 2.5px; font-size: 8px; }
.clbl { font-weight: bold; color: #78350f; }
.cval { color: #111; }

/* Totales */
.tot-box {
    border: 2px solid #1e3a8a;
    background: #f8fafc;
    padding: 7px;
}
.tot-inner { width: 100%; border-collapse: collapse; margin-bottom: 2px; }
.tot-lbl { font-size: 8px; font-weight: bold; color: #475569; width: 58%; }
.tot-val { font-size: 8px; text-align: right; color: #111; width: 42%; }

.tot-final { border-top: 2px solid #1e3a8a; padding-top: 4px; margin-top: 4px; }
.tot-final .tot-lbl { font-size: 9px;  color: #1e3a8a; }
.tot-final .tot-val { font-size: 10px; font-weight: bold; color: #16a34a; }

/* ══════════════════════════════════════════════════════
   6 · IMAGEN CUENTAS BANCARIAS  (ancho completo)
══════════════════════════════════════════════════════ */
.cuentas-wrap { width: 100%; margin-top: 8px; }
.cuentas-wrap img { width: 100%; display: block; }
.cuentas-placeholder {
    width: 100%;
    margin-top: 8px;
    border: 1px dashed #cbd5e1;
    padding: 6px;
    text-align: center;
    color: #94a3b8;
    font-size: 7px;
}

/* ══════════════════════════════════════════════════════
   PÁGINAS 2 y 3 – imagen a ancho y alto completo
   Con body margin 20mm cada lado:
     ancho útil  = 210 - 40 = 170 mm
     alto útil   = 297 - 40 = 257 mm
══════════════════════════════════════════════════════ */
.full-img {
    display: block;
    width:  170mm;
    height: 257mm;
}
</style>
</head>
<body>

@php
    /* ── Símbolo de moneda ── */
    $sym = ($proforma->moneda === 'Dolares') ? '$' : 'S/.';

    /* ── Dirección de entrega ── */
    if ($proforma->direccion ?? null) {
        $dirEntrega = $proforma->direccion->direccion;
        if ($proforma->direccion->distrito ?? null) {
            $dirEntrega .= ', ' . $proforma->direccion->distrito->nombre;
            if ($proforma->direccion->distrito->provincia ?? null) {
                $dirEntrega .= ' - ' . $proforma->direccion->distrito->provincia->nombre;
                if ($proforma->direccion->distrito->provincia->departamento ?? null) {
                    $dirEntrega .= ' - ' . $proforma->direccion->distrito->provincia->departamento->nombre;
                }
            }
        }
    } elseif ($proforma->cliente ?? null) {
        $dirEntrega = $proforma->cliente->direccion ?? 'No especificada';
    } else {
        $dirEntrega = 'No especificada';
    }

    /* ── Contacto ── */
    $contacto       = $proforma->contacto ?? null;
    $contactoNombre = $contacto
        ? trim("{$contacto->nombre} {$contacto->apellido_paterno} " . ($contacto->apellido_materno ?? ''))
        : '—';
    $contactoTel    = $contacto?->telefono ?? '—';
    $contactoEmail  = $contacto?->email    ?? '—';

    /* ── Empresa: correos (relación emails())
         Campos: area | email | activo
         Formato: email1 | email2 | ...                        ── */
    $correosStr = '';
    if ($empresa && $empresa->emails && $empresa->emails->count()) {
        $correosStr = $empresa->emails->pluck('email')->filter()->implode(' | ');
    }

    /* ── Empresa: teléfonos (relación telefonos())
         Campos: area | telefono | descripcion | activo
         Formato: 451-1052 / 451-4787                          ── */
    $telefonosStr = '';
    if ($empresa && $empresa->telefonos && $empresa->telefonos->count()) {
        $telefonosStr = $empresa->telefonos
            ->map(fn($t) => $t->telefono)
            ->filter()
            ->implode(' / ');
    }
@endphp

{{-- ════════════════════════════════════════════
     PÁGINA 1 – COTIZACIÓN
════════════════════════════════════════════ --}}

{{-- ── 1. ENCABEZADO ── --}}
<div class="hdr-sep">
    <table class="hdr-tbl">
        <tr>
            {{-- Izquierda: logo --}}
            <td class="hdr-logo-td">
                @if($logoPath && file_exists($logoPath))
                    <img src="{{ $logoPath }}" alt="Logo">
                @else
                    <span class="hdr-logo-fallback">{{ $empresa->razon_social ?? 'EMPRESA' }}</span>
                @endif
            </td>
            {{-- Derecha: RUC · COTIZACIÓN · NRO --}}
            <td class="hdr-right-td">
                <div class="hdr-ruc">R.U.C. {{ $empresa->ruc ?? '' }}</div>
                <div class="hdr-titulo">COTIZACIÓN</div>
                <div class="hdr-nro">{{ $numeroCotizacion }}</div>
            </td>
        </tr>
    </table>
</div>

{{-- ── 2. DATOS DE EMPRESA ── --}}
<div class="empresa-franja">
    @if($empresa)
        {{-- Dirección --}}
        @if($empresa->direccion)
            {{ $empresa->direccion }}<br>
        @endif
        {{-- Correos: area1@dom | area2@dom --}}
        @if($correosStr)
            Correo: {{ $correosStr }}<br>
        @endif
        {{-- Teléfonos --}}
        @if($telefonosStr)
            Telefono: {{ $telefonosStr }}<br>
        @endif
        {{-- Página web --}}
        @if($empresa->pagina_web)
            Pag. Web: {{ $empresa->pagina_web }}
        @endif
    @endif
</div>

{{-- ── 3. CUADROS CLIENTE / VENDEDOR ── --}}
<table class="cv-tbl">
    <tr>
        {{-- Izquierda: datos del cliente --}}
        <td class="cv-left">
            <div class="info-box">
                <div class="info-box-tit">Información del Cliente</div>
                <div class="irow">
                    <span class="ilbl">R.U.C. / D.N.I.: </span>
                    <span class="ival">{{ $proforma->cliente->ruc ?? '—' }}</span>
                </div>
                <div class="irow">
                    <span class="ilbl">Cliente: </span>
                    <span class="ival">{{ $proforma->cliente->razon ?? '—' }}</span>
                </div>
                <div class="irow">
                    <span class="ilbl">Dirección Asociada: </span>
                    <span class="ival">{{ $dirEntrega }}</span>
                </div>
                <div class="irow">
                    <span class="ilbl">Nombre del Contacto: </span>
                    <span class="ival">{{ $contactoNombre }}</span>
                </div>
                <div class="irow">
                    <span class="ilbl">Teléfono Contacto: </span>
                    <span class="ival">{{ $contactoTel }}</span>
                </div>
                <div class="irow">
                    <span class="ilbl">Email Contacto: </span>
                    <span class="ival">{{ $contactoEmail }}</span>
                </div>
            </div>
        </td>
        {{-- Derecha: datos del vendedor --}}
        <td class="cv-right">
            <div class="info-box">
                <div class="info-box-tit">Información del Vendedor</div>
                <div class="irow">
                    <span class="ilbl">Nombre Vendedor: </span>
                    <span class="ival">{{ $proforma->user->name ?? '—' }}</span>
                </div>
                <div class="irow">
                    <span class="ilbl">E-mail Vendedor: </span>
                    <span class="ival">{{ $proforma->user->email ?? '—' }}</span>
                </div>
            </div>
        </td>
    </tr>
</table>

{{-- ── 4. GRILLA DE PRODUCTOS ── --}}
<table class="prod-tbl">
    <thead>
        <tr>
            <th style="width:4%"  class="tc">Item</th>
            <th style="width:11%">Código</th>
            <th style="width:40%">Descripción</th>
            <th style="width:7%"  class="tc">Cant.</th>
            <th style="width:12%" class="tr">P. Normal</th>
            <th style="width:12%" class="tr">P. Especial</th>
            <th style="width:14%" class="tr">Importe</th>
        </tr>
    </thead>
    <tbody>
        @php $item = 1; @endphp
        @foreach($proforma->productos as $prod)
        @php
            /* precio_lista = P. Normal (precio unitario sin descuento) */
            $pNormal   = (float)($prod->precio_lista ?? 0);
            /* descuento_cliente en el pivot, en porcentaje */
            $descPct   = (float)($prod->pivot->descuento_cliente ?? 0);
            /* P. Especial = precio_lista aplicando el descuento */
            $pEspecial = $pNormal * (1 - $descPct / 100);
            $cantidad  = (float)($prod->pivot->cantidad ?? 1);
            /* Importe = P. Especial × Cantidad */
            $importe   = $pEspecial * $cantidad;
            /* Código: usa codigo_p si no es null, sino codigo_e */
            $codigo    = ($prod->codigo_p ?? null) ?: ($prod->codigo_e ?? '—');
        @endphp
        <tr>
            <td class="tc">{{ $item++ }}</td>
            <td>{{ $codigo }}</td>
            <td>
                <strong>{{ $prod->nombre }}</strong>
                @if($prod->marca ?? null)
                    <br><span class="small gray">{{ $prod->marca }}</span>
                @endif
            </td>
            <td class="tc">{{ number_format($cantidad, 2) }}</td>
            <td class="tr">{{ $sym }}&nbsp;{{ number_format($pNormal,   2) }}</td>
            <td class="tr">{{ $sym }}&nbsp;{{ number_format($pEspecial, 2) }}</td>
            <td class="tr">{{ $sym }}&nbsp;{{ number_format($importe,   2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- ── 5. CONDICIONES COMERCIALES (izq) + TOTALES (der) ── --}}
<table class="bot-tbl">
    <tr>
        {{-- Condiciones --}}
        <td class="bot-left">
            <div class="cond-box">
                <div class="cond-tit">Condiciones Comerciales</div>
                <div class="crow">
                    <span class="clbl">Moneda: </span>
                    <span class="cval">{{ $proforma->moneda }}</span>
                </div>
                <div class="crow">
                    <span class="clbl">Forma de Pago: </span>
                    <span class="cval">{{ $proforma->transaccion->name ?? '—' }}</span>
                </div>
                <div class="crow">
                    <span class="clbl">Orden de Compra: </span>
                    <span class="cval">{{ $proforma->orden ?? '—' }}</span>
                </div>
                <div class="crow">
                    <span class="clbl">Fecha de Inicio: </span>
                    <span class="cval">{{ $proforma->fecha_creacion->format('d/m/Y') }}</span>
                </div>
                <div class="crow">
                    <span class="clbl">Fecha de Vencimiento: </span>
                    <span class="cval">{{ $proforma->fecha_fin->format('d/m/Y') }}</span>
                </div>
            </div>
        </td>
        {{-- Totales --}}
        <td class="bot-right">
            <div class="tot-box">
                <table class="tot-inner">
                    <tr>
                        <td class="tot-lbl">OP. GRAVADAS:</td>
                        <td class="tot-val">{{ $sym }}&nbsp;{{ number_format($proforma->sub_total, 2) }}</td>
                    </tr>
                </table>
                <table class="tot-inner">
                    <tr>
                        <td class="tot-lbl">I.G.V. 18%:</td>
                        <td class="tot-val">{{ $sym }}&nbsp;{{ number_format($proforma->monto_igv, 2) }}</td>
                    </tr>
                </table>
                <div class="tot-final">
                    <table class="tot-inner">
                        <tr>
                            <td class="tot-lbl">IMPORTE TOTAL:</td>
                            <td class="tot-val">{{ $sym }}&nbsp;{{ number_format($proforma->total, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
    </tr>
</table>

{{-- ── 6. IMAGEN CUENTAS BANCARIAS (ancho completo) ── --}}
@if($cuentasPath && file_exists($cuentasPath))
    <div class="cuentas-wrap">
        <img src="{{ $cuentasPath }}" alt="Cuentas Bancarias">
    </div>
@else
    <div class="cuentas-placeholder">
        [ Imagen de Cuentas Bancarias no disponible ]
    </div>
@endif

<p style="font-size:9px;color:#94a3b8;text-align:center;margin-top:4px;">
    Este documento es una cotización y no representa un compromiso de venta hasta la confirmación del pedido.
Para más información, contáctenos a través de nuestros canales de atención.
</p>

{{-- ════════════════════════════════════════════
     SALTO → PÁGINA 2: PUBLICIDAD
════════════════════════════════════════════ --}}
<div class="salto"></div>

@if($publicidadPath && file_exists($publicidadPath))
    <img class="full-img" src="{{ $publicidadPath }}" alt="Publicidad">
@else
    <p style="color:#94a3b8;font-size:9px;text-align:center;padding-top:60mm;">
        [ Imagen de publicidad no disponible ]
    </p>
@endif

{{-- ════════════════════════════════════════════
     SALTO → PÁGINA 3: CONDICIONES GENERALES
════════════════════════════════════════════ --}}
<div class="salto"></div>

@if($condicionesPath && file_exists($condicionesPath))
    <img class="full-img" src="{{ $condicionesPath }}" alt="Condiciones Generales">
@else
    <p style="color:#94a3b8;font-size:9px;text-align:center;padding-top:60mm;">
        [ Imagen de condiciones no disponible ]
    </p>
@endif

</body>
</html>
