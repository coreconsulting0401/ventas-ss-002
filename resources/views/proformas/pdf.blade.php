<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cotización {{ $numeroCotizacion }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: #333;
        }

        @page {
            margin: 25mm 25mm;
            size: A4 portrait;
        }

        .container {
            width: 100%;
            max-width: 100%;
        }

        /* Header */
        .header {
            width: 100%;
            margin-bottom: 8px;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 6px;
        }

        .header table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-left {
            width: 55%;
            vertical-align: top;
        }

        .header-right {
            width: 45%;
            vertical-align: top;
            text-align: right;
        }

        .logo {
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 2px;
        }

        .company-info {
            font-size: 6.5px;
            line-height: 1.4;
            color: #666;
        }

        .cotizacion-title {
            font-size: 22px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 2px;
        }

        .cotizacion-number {
            font-size: 10px;
            color: #ef4444;
            font-weight: bold;
        }

        /* Info sections */
        .info-section {
            width: 100%;
            margin-bottom: 6px;
        }

        .info-section table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-left, .info-right {
            width: 50%;
            padding: 0 3px;
            vertical-align: top;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 5px;
            font-size: 7.5px;
        }

        .info-title {
            font-weight: bold;
            font-size: 8px;
            color: #1e3a8a;
            margin-bottom: 3px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 2px;
        }

        .info-row {
            margin-bottom: 2px;
            line-height: 1.3;
        }

        .info-label {
            font-weight: bold;
            color: #475569;
            display: inline-block;
            width: 38%;
            vertical-align: top;
        }

        .info-value {
            color: #1e293b;
            display: inline-block;
            width: 60%;
            vertical-align: top;
        }

        /* Products table */
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
            font-size: 7.5px;
        }

        .products-table thead {
            background: #1e3a8a;
            color: white;
        }

        .products-table th {
            padding: 3px 2px;
            text-align: left;
            font-size: 7.5px;
            font-weight: bold;
            border: 1px solid #1e3a8a;
        }

        .products-table td {
            padding: 2px 2px;
            border: 1px solid #e2e8f0;
            font-size: 7px;
            line-height: 1.2;
        }

        .products-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .small-text {
            font-size: 6px;
            color: #64748b;
        }

        /* Totals section */
        .totales-section {
            width: 100%;
            margin-top: 6px;
        }

        .totales-section table {
            width: 100%;
            border-collapse: collapse;
        }

        .totales-left {
            width: 57%;
            padding-right: 4px;
            vertical-align: top;
        }

        .totales-right {
            width: 43%;
            vertical-align: top;
        }

        .condiciones {
            background: #fffbeb;
            border: 1px solid #fbbf24;
            padding: 5px;
            font-size: 7px;
        }

        .condiciones-title {
            font-weight: bold;
            font-size: 8px;
            color: #92400e;
            margin-bottom: 3px;
        }

        .condiciones-item {
            margin-bottom: 2px;
            line-height: 1.3;
        }

        .condiciones-label {
            font-weight: bold;
            color: #78350f;
        }

        .totales-box {
            background: #f8fafc;
            border: 2px solid #1e3a8a;
            padding: 5px;
        }

        .total-row {
            margin-bottom: 2px;
            font-size: 7.5px;
        }

        .total-row table {
            width: 100%;
            border-collapse: collapse;
        }

        .total-label {
            font-weight: bold;
            color: #475569;
            width: 50%;
        }

        .total-value {
            text-align: right;
            color: #1e293b;
            width: 50%;
        }

        .total-final {
            border-top: 2px solid #1e3a8a;
            padding-top: 3px;
            margin-top: 3px;
        }

        .total-final .total-label {
            font-size: 9px;
            color: #1e3a8a;
        }

        .total-final .total-value {
            font-size: 10px;
            font-weight: bold;
            color: #16a34a;
        }

        /* Bank accounts */
        .cuentas-bancarias {
            margin-top: 6px;
            border: 1px solid #cbd5e1;
        }

        .cuentas-title {
            background: #1e3a8a;
            color: white;
            padding: 3px 5px;
            font-weight: bold;
            font-size: 8px;
        }

        .cuentas-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cuentas-table td {
            padding: 1.5px 3px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 6.5px;
            line-height: 1.2;
        }

        .cuentas-table tr:nth-child(even) {
            background: #f8fafc;
        }

        .banco-logo {
            font-weight: bold;
            color: #1e3a8a;
            font-size: 7px;
        }

        /* Footer */
        .footer {
            margin-top: 8px;
            padding-top: 4px;
            border-top: 1px solid #e2e8f0;
            font-size: 6.5px;
            color: #64748b;
            text-align: center;
            line-height: 1.3;
        }

        .badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 6.5px;
            font-weight: bold;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <table>
                <tr>
                    <td class="header-left">
                        <div class="logo">FESEPSA S.A.</div>
                        <div class="company-info">
                            <strong>R.U.C. 20100004080</strong><br>
                            Av. Elmer Faucett T Nro.390 Urb. La colonial Prov. Const. Del Callao - Callao<br>
                            Correo: ventas@fesepsa.pe | calibracion@fesepsa.pe<br>
                            Teléfono: 451-1052 / 451-4787 | Web: www.fesepsa.com.pe
                        </div>
                    </td>
                    <td class="header-right">
                        <div class="cotizacion-title">COTIZACIÓN</div>
                        <div class="cotizacion-number">{{ $numeroCotizacion }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Información del Cliente y Orden -->
        <div class="info-section">
            <table>
                <tr>
                    <td class="info-left">
                        <div class="info-box">
                            <div class="info-title">INFORMACIÓN DEL CLIENTE</div>
                            <div class="info-row">
                                <span class="info-label">R.U.C. / D.N.I.:</span>
                                <span class="info-value">{{ $proforma->cliente->ruc }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Cliente:</span>
                                <span class="info-value">{{ $proforma->cliente->razon }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Dirección:</span>
                                <span class="info-value">
                                    @if($proforma->cliente->direcciones && $proforma->cliente->direcciones->count() > 0)
                                        {{ Str::limit($proforma->cliente->direcciones->first()->direccion, 2000) }}
                                    @else
                                        No especificada
                                    @endif
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Correo:</span>
                                <span class="info-value">
                                    @if($proforma->cliente->contactos && $proforma->cliente->contactos->count() > 0)
                                        {{ $proforma->cliente->contactos->first()->email ?? 'No especificado' }}
                                    @else
                                        No especificado
                                    @endif
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Fec. Emisión:</span>
                                <span class="info-value">{{ $fechaEmision }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="info-right">
                        <div class="info-box">
                            <div class="info-title">ORDEN DE COMPRA</div>
                            <div class="info-row">
                                <span class="info-label">Moneda:</span>
                                <span class="info-value">{{ $proforma->moneda }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Nom. Vendedor:</span>
                                <span class="info-value">{{ $proforma->user->name ?? 'No asignado' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">E-mail Vendedor:</span>
                                <span class="info-value">{{ $proforma->user->email ?? 'No disponible' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Fec. Vencimiento:</span>
                                <span class="info-value">{{ $proforma->fecha_fin->format('d/m/Y') }}</span>
                            </div>
                            @if($proforma->orden)
                            <div class="info-row">
                                <span class="info-label">N° Orden:</span>
                                <span class="info-value">{{ $proforma->orden }}</span>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Tabla de Productos -->
        <table class="products-table">
            <thead>
                <tr>
                    <th style="width: 4%;">Item</th>
                    <th style="width: 11%;">Código</th>
                    <th style="width: 42%;">Descripción</th>
                    <th style="width: 7%;" class="text-center">Cant.</th>
                    <th style="width: 12%;" class="text-right">P. Normal</th>
                    <th style="width: 12%;" class="text-right">P. Especial</th>
                    <th style="width: 12%;" class="text-right">Importe</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $itemNumber = 1;
                @endphp
                @foreach($proforma->productos as $producto)
                <tr>
                    <td class="text-center">{{ $itemNumber++ }}</td>
                    <td>{{ $producto->codigo_p }}</td>
                    <td>
                        <strong>{{ Str::limit($producto->nombre,400) }}</strong>
                        @if($producto->marca)
                            <br><span class="small-text">{{ $producto->marca }}</span>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($producto->pivot->cantidad, 2) }}</td>
                    <td class="text-right">{{ number_format($producto->precio_lista, 2) }}</td>
                    <td class="text-right">{{ number_format($producto->pivot->precio_unitario, 2) }}</td>
                    <td class="text-right">
                        @php
                            $descuento = $producto->pivot->descuento_cliente ?? 0;
                            $subtotal = $producto->pivot->cantidad * $producto->pivot->precio_unitario;
                            $importe = $subtotal * (1 - $descuento / 100);
                        @endphp
                        {{ number_format($importe, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totales y Condiciones -->
        <div class="totales-section">
            <table>
                <tr>
                    <td class="totales-left">
                        <div class="condiciones">
                            <div class="condiciones-title">CONDICIONES COMERCIALES</div>
                            <div class="condiciones-item">
                                <span class="condiciones-label">Forma de Pago:</span> CONTADO
                            </div>
                            <div class="condiciones-item">
                                <span class="condiciones-label">Validez de la oferta:</span> {{ $proforma->fecha_fin->format('d/m/Y') }}
                            </div>
                            <div class="condiciones-item">
                                <span class="condiciones-label">Observaciones:</span>
                                {{ $proforma->nota ?? 'EN STOCK' }}
                            </div>
                            @if($proforma->temperatura)
                            <div class="condiciones-item">
                                <span class="condiciones-label">Temperatura:</span>
                                <span class="badge badge-warning">{{ $proforma->temperatura->name }}</span>
                            </div>
                            @endif
                            @if($proforma->estado)
                            <div class="condiciones-item">
                                <span class="condiciones-label">Estado:</span>
                                <span class="badge badge-success">{{ $proforma->estado->name }}</span>
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="totales-right">
                        <div class="totales-box">
                            <div class="total-row">
                                <table>
                                    <tr>
                                        <td class="total-label">OP. GRAVADAS:</td>
                                        <td class="total-value">
                                            {{ $proforma->moneda == 'Dolares' ? '$' : 'S/.' }} {{ number_format($proforma->sub_total, 2) }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="total-row">
                                <table>
                                    <tr>
                                        <td class="total-label">I.G.V. 18%:</td>
                                        <td class="total-value">
                                            {{ $proforma->moneda == 'Dolares' ? '$' : 'S/.' }} {{ number_format($proforma->monto_igv, 2) }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="total-row total-final">
                                <table>
                                    <tr>
                                        <td class="total-label">IMPORTE TOTAL:</td>
                                        <td class="total-value">
                                            {{ $proforma->moneda == 'Dolares' ? '$' : 'S/.' }} {{ number_format($proforma->total, 2) }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Cuentas Bancarias -->
        <div class="cuentas-bancarias">
            <div class="cuentas-title">CUENTAS BANCARIAS</div>
            <table class="cuentas-table">
                <tr>
                    <td style="width: 18%;" class="banco-logo">BCP</td>
                    <td style="width: 14%;">SOLES</td>
                    <td style="width: 28%;">192-0348160-0-67</td>
                    <td style="width: 40%;">002-192000348160067-38</td>
                </tr>
                <tr>
                    <td class="banco-logo">BCP</td>
                    <td>DOLARES</td>
                    <td>192-0726718-1-94</td>
                    <td>002-192000726718194-34</td>
                </tr>
                <tr>
                    <td class="banco-logo">Interbank</td>
                    <td>SOLES</td>
                    <td>100-0003248927</td>
                    <td>003-100-000003248927-50</td>
                </tr>
                <tr>
                    <td class="banco-logo">Interbank</td>
                    <td>DOLARES</td>
                    <td>100-0003248926</td>
                    <td>003-100-000003248926-52</td>
                </tr>
                <tr>
                    <td class="banco-logo">BBVA</td>
                    <td>SOLES</td>
                    <td>011-0166-0100000370</td>
                    <td>011-166-000100000370-65</td>
                </tr>
                <tr>
                    <td class="banco-logo">BBVA</td>
                    <td>DOLARES</td>
                    <td>011-0166-0100000133</td>
                    <td>011-166-000100000133-67</td>
                </tr>
                <tr>
                    <td class="banco-logo">Bco. de la Nación</td>
                    <td>SOLES</td>
                    <td>00-000-427462</td>
                    <td></td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Este documento es una cotización y no representa un compromiso de venta hasta la confirmación del pedido.</p>
            <p>Para más información, contáctenos a través de nuestros canales de atención.</p>
        </div>
    </div>
</body>
</html>
