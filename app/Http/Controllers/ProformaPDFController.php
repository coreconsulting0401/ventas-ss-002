<?php

namespace App\Http\Controllers;

use App\Models\Proforma;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ProformaPDFController extends Controller
{
    /**
     * Generar PDF de la proforma
     */
    public function generarPDF(Proforma $proforma)
    {
        // Cargar la proforma con todas sus relaciones
        $proforma->load([
            'cliente',
            'user',
            'transaccion',
            'temperatura',
            'estado',
            'productos' => function($query) {
                $query->with('descuento');
            }
        ]);

        // Generar el código de cotización
        $numeroCotizacion = 'NCT-' . str_pad($proforma->id, 11, '0', STR_PAD_LEFT);

        // Preparar datos para el PDF
        $data = [
            'proforma' => $proforma,
            'numeroCotizacion' => $numeroCotizacion,
            'fechaEmision' => now()->format('d/m/Y'),
        ];

        // Configurar el PDF
        $pdf = Pdf::loadView('proformas.pdf', $data);

        // Configurar tamaño de página y orientación
        $pdf->setPaper('A4', 'portrait');

        // Nombre del archivo
        $nombreArchivo = "Cotizacion_{$numeroCotizacion}.pdf";

        // Descargar el PDF
        return $pdf->download($nombreArchivo);
    }

    /**
     * Previsualizar PDF en el navegador
     */
    public function previsualizarPDF(Proforma $proforma)
    {
        // Cargar la proforma con todas sus relaciones
        $proforma->load([
            'cliente',
            'user',
            'transaccion',
            'temperatura',
            'estado',
            'productos' => function($query) {
                $query->with('descuento');
            }
        ]);

        // Generar el código de cotización
        $numeroCotizacion = 'NCT-' . str_pad($proforma->id, 11, '0', STR_PAD_LEFT);

        // Preparar datos para el PDF
        $data = [
            'proforma' => $proforma,
            'numeroCotizacion' => $numeroCotizacion,
            'fechaEmision' => now()->format('d/m/Y'),
        ];

        // Configurar el PDF
        $pdf = Pdf::loadView('proformas.pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        // Mostrar en el navegador
        return $pdf->stream("Cotizacion_{$numeroCotizacion}.pdf");
    }
}
