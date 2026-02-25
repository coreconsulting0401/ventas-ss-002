<?php

/**
 * COMANDO: ConsultarTipoCambioCommand.php
 * Ubicación: app/Console/Commands/ConsultarTipoCambioCommand.php
 *
 * Uso:
 *   php artisan cambio:consultar
 *
 * Comportamiento:
 *   1. Crea o recupera el registro del día (fecha = hoy).
 *   2. Si ya tiene estado=ok, sale inmediatamente (evita dobles consultas).
 *   3. Llama a la API con reintentos:
 *        - Si recibe 429 (Too Many Requests) espera 2 seg y reintenta (max 5 veces).
 *        - Si otro error HTTP o de red, registra error y sale.
 *   4. Persiste los datos y calcula venta_mas = venta + incremento.
 */

namespace App\Console\Commands;

use App\Models\Cambio;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ConsultarTipoCambioCommand extends Command
{
    protected $signature   = 'cambio:consultar';
    protected $description = 'Consulta el tipo de cambio USD/PEN desde la API de SUNAT y lo almacena en la BD';

    private const API_URL      = 'https://api.apis.net.pe/v1/tipo-cambio-sunat';
    private const MAX_INTENTOS = 5;
    private const ESPERA_SEG   = 2;   // segundos entre reintentos

    public function handle(): int
    {
        $hoy = now()->toDateString();

        // ── 1. Obtener o crear registro del día ──────────────────────────────
        /** @var Cambio $cambio */
        $cambio = Cambio::firstOrCreate(
            ['fecha' => $hoy],
            [
                'estado'     => 'pendiente',
                'intentos'   => 0,
                'incremento' => 0.0200,
            ]
        );

        // ── 2. Si ya fue consultado con éxito hoy, no repetir ────────────────
        if ($cambio->estado === 'ok') {
            $this->info("✅ El tipo de cambio del {$hoy} ya fue registrado (S/. {$cambio->venta}).");
            return self::SUCCESS;
        }

        $this->info("📡 Consultando tipo de cambio para el {$hoy}...");

        // ── 3. Llamada a la API con lógica de reintentos ─────────────────────
        $respuesta = $this->llamarApiConReintentos($cambio);

        if ($respuesta === null) {
            // Ya se guardó el error dentro de llamarApiConReintentos()
            return self::FAILURE;
        }

        // ── 4. Guardar datos y calcular venta_mas ────────────────────────────
        $cambio->origen         = $respuesta['origen']  ?? 'SUNAT';
        $cambio->compra         = $respuesta['compra']  ?? null;
        $cambio->venta          = $respuesta['venta']   ?? null;
        $cambio->moneda         = $respuesta['moneda']  ?? 'USD';
        $cambio->estado         = 'ok';
        $cambio->error_mensaje  = null;
        $cambio->save();

        // recalcularVentaMas llama a saveQuietly()
        $cambio->recalcularVentaMas();

        $this->info("✅ Guardado: Compra S/. {$cambio->compra} | Venta S/. {$cambio->venta} | Venta+ S/. {$cambio->venta_mas}");

        Log::info('cambio:consultar OK', [
            'fecha'     => $hoy,
            'compra'    => $cambio->compra,
            'venta'     => $cambio->venta,
            'venta_mas' => $cambio->venta_mas,
        ]);

        return self::SUCCESS;
    }

    /**
     * Realiza la llamada HTTP con reintentos en caso de 429.
     *
     * @return array<string,mixed>|null  datos de la API, o null si falló definitivamente
     */
    private function llamarApiConReintentos(Cambio $cambio): ?array
    {
        for ($intento = 1; $intento <= self::MAX_INTENTOS; $intento++) {

            $cambio->intentos = $intento;
            $cambio->saveQuietly();

            try {
                /** @var \Illuminate\Http\Client\Response $response */
                $response = Http::timeout(10)->get(self::API_URL);

                // ── 429 Too Many Requests ────────────────────────────────────
                if ($response->status() === 429) {
                    $this->warn("⚠️  429 Too Many Requests (intento {$intento}/" . self::MAX_INTENTOS . "). Esperando " . self::ESPERA_SEG . " seg...");
                    Log::warning('cambio:consultar — 429 recibido', ['intento' => $intento]);

                    if ($intento < self::MAX_INTENTOS) {
                        sleep(self::ESPERA_SEG);
                        continue;
                    }

                    // Agotados todos los intentos por 429
                    $this->registrarError($cambio, "429 Too Many Requests después de " . self::MAX_INTENTOS . " intentos.");
                    return null;
                }

                // ── Otros errores HTTP ───────────────────────────────────────
                if (! $response->successful()) {
                    $msg = "HTTP {$response->status()}: " . $response->body();
                    $this->error("❌ Error en la API: {$msg}");
                    $this->registrarError($cambio, $msg);
                    return null;
                }

                // ── Respuesta exitosa ────────────────────────────────────────
                $data = $response->json();

                if (empty($data['venta'])) {
                    $msg = 'Respuesta inesperada de la API: ' . json_encode($data);
                    $this->error("❌ {$msg}");
                    $this->registrarError($cambio, $msg);
                    return null;
                }

                return $data;

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                $msg = "Error de conexión: " . $e->getMessage();
                $this->error("❌ {$msg}");

                if ($intento < self::MAX_INTENTOS) {
                    $this->warn("↩  Reintentando en " . self::ESPERA_SEG . " seg...");
                    sleep(self::ESPERA_SEG);
                    continue;
                }

                $this->registrarError($cambio, $msg);
                return null;
            }
        }

        return null;
    }

    /**
     * Persiste el estado de error en la BD y logea.
     */
    private function registrarError(Cambio $cambio, string $mensaje): void
    {
        $cambio->estado        = 'error';
        $cambio->error_mensaje = $mensaje;
        $cambio->saveQuietly();

        $this->error("💾 Registro guardado como error: {$mensaje}");
        Log::error('cambio:consultar FALLÓ', [
            'fecha'   => $cambio->fecha,
            'mensaje' => $mensaje,
        ]);
    }
}
