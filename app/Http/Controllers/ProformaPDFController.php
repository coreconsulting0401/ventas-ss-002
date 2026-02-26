<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Proforma;
use Barryvdh\DomPDF\Facade\Pdf;

class ProformaPDFController extends Controller
{
    /**
     * Carga todas las relaciones de la proforma necesarias para el PDF.
     */
    private function cargarRelaciones(Proforma $proforma): void
    {
        $proforma->load([
            'cliente',
            'direccion.distrito.provincia.departamento',
            'contacto',
            'user',
            'transaccion',
            'temperatura',
            'estado',
            'productos' => fn($q) => $q->with('descuento'),
        ]);
    }

    /**
     * Prepara el array de datos para la vista PDF.
     *
     * Relaciones del modelo Empresa:
     *   emails()    → HasMany EmailEmpresa  (campos: area, email, activo)
     *   telefonos() → HasMany TelefonoEmpresa (campos: area, telefono, descripcion, activo)
     */
    private function prepararDatos(Proforma $proforma): array
    {
        // Carga empresa con sus relaciones reales: emails() y telefonos()
        $empresa = Empresa::with([
            'emails'    => fn($q) => $q->where('activo', true),
            'telefonos' => fn($q) => $q->where('activo', true),
        ])->find(1);

        // Resuelve rutas absolutas para DomPDF (requiere paths del sistema de archivos, no URLs)
        $imgPath = fn(?string $rel): ?string =>
            $rel ? public_path('storage/' . $rel) : null;

        return [
            'proforma'         => $proforma,
            'empresa'          => $empresa,
            'numeroCotizacion' => 'NTC-' . str_pad($proforma->id, 11, '0', STR_PAD_LEFT),
            'fechaEmision'     => now()->format('d/m/Y'),
            'logoPath'         => $empresa ? $imgPath($empresa->uri_img_logo)          : null,
            'publicidadPath'   => $empresa ? $imgPath($empresa->uri_img_publicidad)    : null,
            'condicionesPath'  => $empresa ? $imgPath($empresa->uri_img_condiciones)   : null,
            'cuentasPath'      => $empresa ? $imgPath($empresa->uri_cuentas_bancarias) : null,
        ];
    }

    /**
     * Descargar PDF de la proforma.
     */
    public function generarPDF(Proforma $proforma)
    {
        $this->cargarRelaciones($proforma);
        $data = $this->prepararDatos($proforma);

        $pdf = Pdf::loadView('proformas.pdf', $data)
                  ->setPaper('A4', 'portrait');

        return $pdf->download("Cotizacion_{$data['numeroCotizacion']}.pdf");
    }

    /**
     * Previsualizar PDF en el navegador.
     */
    public function previsualizarPDF(Proforma $proforma)
    {
        $this->cargarRelaciones($proforma);
        $data = $this->prepararDatos($proforma);

        $pdf = Pdf::loadView('proformas.pdf', $data)
                  ->setPaper('A4', 'portrait');

        return $pdf->stream("Cotizacion_{$data['numeroCotizacion']}.pdf");
    }
}
