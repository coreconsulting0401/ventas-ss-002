<?php

/**
 * CONTROLADOR: ProductoImportController.php
 * Ubicación: app/Http/Controllers/ProductoImportController.php
 *
 * Maneja la importación / actualización masiva de productos desde un archivo Excel.
 * Lógica de upsert:
 *   - Si el Excel trae codigo_p  → busca por codigo_p  y actualiza / crea.
 *   - Else si trae codigo_e      → busca por codigo_e  y actualiza / crea.
 *   - Else                       → siempre crea un nuevo producto.
 */

namespace App\Http\Controllers;

use App\Models\Descuento;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductoImportController extends Controller
{
    /* ------------------------------------------------------------------ */
    /*  Columnas requeridas en el Excel                                     */
    /* ------------------------------------------------------------------ */
    private const REQUIRED_COLUMNS = ['nombre', 'marca', 'precio_lista', 'stock'];

    /* ------------------------------------------------------------------ */
    /*  Descargar plantilla Excel                                           */
    /* ------------------------------------------------------------------ */
    public function downloadTemplate()
    {
        $path = public_path('plantillas/plantilla_productos.xlsx');

        if (! file_exists($path)) {
            abort(404, 'Plantilla no encontrada.');
        }

        return response()->download($path, 'plantilla_productos.xlsx');
    }

    /* ------------------------------------------------------------------ */
    /*  Importar                                                            */
    /* ------------------------------------------------------------------ */
    public function import(Request $request)
    {
        /* ---- Validar que el archivo venga y sea Excel ---- */
        $request->validate([
            'archivo_excel' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:5120',          // 5 MB máx
            ],
        ], [
            'archivo_excel.required' => 'Debe seleccionar un archivo Excel.',
            'archivo_excel.mimes'    => 'El archivo debe ser .xlsx o .xls.',
            'archivo_excel.max'      => 'El archivo no debe superar los 5 MB.',
        ]);

        try {
            /* ---- Leer el archivo ---- */
            $spreadsheet = IOFactory::load($request->file('archivo_excel')->getRealPath());
            $sheet        = $spreadsheet->getActiveSheet();
            $rows         = $sheet->toArray(null, true, true, false); // índices numéricos

            if (empty($rows) || count($rows) < 2) {
                return back()->with('import_error', 'El archivo está vacío o solo contiene encabezados.');
            }

            /* ---- Mapear encabezados (primera fila) ---- */
            $headers = array_map(
                fn($h) => strtolower(trim((string) $h)),
                $rows[0]
            );

            // Verificar columnas obligatorias
            $missing = array_diff(self::REQUIRED_COLUMNS, $headers);
            if (! empty($missing)) {
                return back()->with(
                    'import_error',
                    'Faltan columnas obligatorias: ' . implode(', ', $missing)
                );
            }

            $colIndex = array_flip($headers); // nombre_columna → índice

            /* ---- Obtener IDs de descuentos válidos una sola vez ---- */
            $validDescuentos = Descuento::pluck('id')->toArray();

            /* ---- Procesar filas ---- */
            $resultados = [
                'creados'      => 0,
                'actualizados' => 0,
                'errores'      => [],
            ];

            DB::beginTransaction();

            foreach (array_slice($rows, 1) as $rowNum => $row) {
                $lineNumber = $rowNum + 2; // +2 porque saltamos la cabecera y Excel es 1-based

                // Ignorar filas completamente vacías
                if (empty(array_filter(array_map('trim', array_map('strval', $row))))) {
                    continue;
                }

                /* ---- Extraer valores ---- */
                $get = fn(string $col) => isset($colIndex[$col])
                    ? trim((string) ($row[$colIndex[$col]] ?? ''))
                    : '';

                $nombre      = $get('nombre');
                $marca       = $get('marca');
                $precioLista = $get('precio_lista');
                $stock       = $get('stock');
                $descuentoId = $get('descuento_id') ?: null;
                $codigoE     = $get('codigo_e')     ?: null;
                $codigoP     = $get('codigo_p')     ?: null;
                $ubicacion   = $get('ubicacion')    ?: null;

                /* ---- Validar fila ---- */
                $rowValidator = Validator::make(
                    [
                        'nombre'       => $nombre,
                        'marca'        => $marca,
                        'precio_lista' => $precioLista,
                        'stock'        => $stock,
                        'descuento_id' => $descuentoId,
                        'codigo_e'     => $codigoE,
                        'codigo_p'     => $codigoP,
                        'ubicacion'    => $ubicacion,
                    ],
                    [
                        'nombre'       => 'required|string|max:150',
                        'marca'        => 'required|string|max:50',
                        'precio_lista' => ['required', 'numeric', 'min:0', 'regex:/^\d{1,7}(\.\d{1,3})?$/'],
                        'stock'        => 'required|integer|min:0',
                        'descuento_id' => 'nullable|integer',
                        'codigo_e'     => 'nullable|string|max:17',
                        'codigo_p'     => 'nullable|string|max:17',
                        'ubicacion'    => 'nullable|string|max:20',
                    ]
                );

                if ($rowValidator->fails()) {
                    $resultados['errores'][] = [
                        'fila'   => $lineNumber,
                        'nombre' => $nombre ?: '(vacío)',
                        'errores' => $rowValidator->errors()->all(),
                    ];
                    continue;
                }

                // Validar descuento_id existe
                if ($descuentoId !== null && ! in_array((int) $descuentoId, $validDescuentos)) {
                    $resultados['errores'][] = [
                        'fila'    => $lineNumber,
                        'nombre'  => $nombre,
                        'errores' => ["El descuento_id '{$descuentoId}' no existe."],
                    ];
                    continue;
                }

                /* ---- Datos a guardar ---- */
                $data = [
                    'nombre'       => $nombre,
                    'marca'        => $marca,
                    'precio_lista' => (float) $precioLista,
                    'stock'        => (int) $stock,
                    'descuento_id' => $descuentoId ? (int) $descuentoId : null,
                    'codigo_e'     => $codigoE,
                    'codigo_p'     => $codigoP,
                    'ubicacion'    => $ubicacion,
                ];

                /* ---- Upsert ---- */
                $producto = null;

                if ($codigoP) {
                    $producto = Producto::where('codigo_p', $codigoP)->first();
                } elseif ($codigoE) {
                    $producto = Producto::where('codigo_e', $codigoE)->first();
                }

                if ($producto) {
                    $producto->update($data);
                    $resultados['actualizados']++;
                } else {
                    // Verificar unicidad de códigos antes de crear
                    if ($codigoP && Producto::where('codigo_p', $codigoP)->exists()) {
                        $resultados['errores'][] = [
                            'fila'    => $lineNumber,
                            'nombre'  => $nombre,
                            'errores' => ["El codigo_p '{$codigoP}' ya existe para otro producto."],
                        ];
                        continue;
                    }
                    if ($codigoE && Producto::where('codigo_e', $codigoE)->exists()) {
                        $resultados['errores'][] = [
                            'fila'    => $lineNumber,
                            'nombre'  => $nombre,
                            'errores' => ["El codigo_e '{$codigoE}' ya existe para otro producto."],
                        ];
                        continue;
                    }

                    Producto::create($data);
                    $resultados['creados']++;
                }
            }

            DB::commit();

            /* ---- Mensaje de resumen ---- */
            $msg = "Importación completada: {$resultados['creados']} creado(s), {$resultados['actualizados']} actualizado(s).";

            if (! empty($resultados['errores'])) {
                return redirect()->route('productos.index')
                    ->with('import_success', $msg)
                    ->with('import_errores', $resultados['errores']);
            }

            return redirect()->route('productos.index')->with('import_success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with(
                'import_error',
                'Error al procesar el archivo: ' . $e->getMessage()
            );
        }
    }
}
