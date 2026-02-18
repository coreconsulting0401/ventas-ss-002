<?php
/**
 * CONTROLADOR: ApiExternaController.php
 * Ubicación: app/Http/Controllers/ApiExternaController.php
 *
 * Proxy seguro para consultas a APIs externas (RENIEC, RUC)
 * Los tokens están en .env y nunca se exponen al cliente.
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;

class ApiExternaController extends Controller
{
    /**
     * Consultar DNI en RENIEC
     *
     * GET /api-externa/consultar-dni/{dni}
     */
    public function consultarDni($dni)
    {
        // Validar formato de DNI
        if (!preg_match('/^\d{8}$/', $dni)) {
            return response()->json([
                'success' => false,
                'message' => 'El DNI debe tener exactamente 8 dígitos'
            ], 400);
        }

        try {
            $token = config('services.apiperu.token');

            if (!$token) {
                Log::error('APIPERU_TOKEN no configurado en .env');
                return response()->json([
                    'success' => false,
                    'message' => 'Servicio de consulta no disponible. Contacte al administrador.'
                ], 500);
            }

            // ✅ URL correcta: dniruc.apisperu.com con token como query param
            $url = "https://dniruc.apisperu.com/api/v1/dni/{$dni}?token={$token}";

            /** @var Response $response */
            $response = Http::timeout(10)
                ->withHeaders(['Accept' => 'application/json'])
                ->get($url);

            if ($response->successful()) {
                $data = $response->json();

                // ✅ dniruc.apisperu.com devuelve { success, dni, nombres, apellidoPaterno, apellidoMaterno }
                if (!empty($data['success']) && !empty($data['dni'])) {
                    return response()->json([
                        'success' => true,
                        'data' => [
                            'dni'              => $data['dni']              ?? '',
                            'nombre_completo'  => trim(($data['nombres'] ?? '') . ' ' . ($data['apellidoPaterno'] ?? '') . ' ' . ($data['apellidoMaterno'] ?? '')),
                            'apellido_paterno' => $data['apellidoPaterno']  ?? '',
                            'apellido_materno' => $data['apellidoMaterno']  ?? '',
                            'nombres'          => $data['nombres']          ?? '',
                        ]
                    ]);
                }
            }

            // DNI no encontrado o respuesta inválida
            return response()->json([
                'success' => false,
                'message' => 'No se encontró información para el DNI proporcionado'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error consultando RENIEC: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al consultar el servicio. Intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Consultar RUC en SUNAT
     *
     * GET /api-externa/consultar-ruc/{ruc}
     */
    public function consultarRuc($ruc)
    {
        // Validar formato de RUC
        if (!preg_match('/^\d{11}$/', $ruc)) {
            return response()->json([
                'success' => false,
                'message' => 'El RUC debe tener exactamente 11 dígitos'
            ], 400);
        }

        try {
            $token = config('services.apiperu.token');

            if (!$token) {
                Log::error('APIPERU_TOKEN no configurado en .env');
                return response()->json([
                    'success' => false,
                    'message' => 'Servicio de consulta no disponible. Contacte al administrador.'
                ], 500);
            }

            // ✅ URL correcta: dniruc.apisperu.com con token como query param
            $url = "https://dniruc.apisperu.com/api/v1/ruc/{$ruc}?token={$token}";

            /** @var Response $response */
            $response = Http::timeout(10)
                ->withHeaders(['Accept' => 'application/json'])
                ->get($url);

            if ($response->successful()) {
                $data = $response->json();

                // ✅ dniruc.apisperu.com devuelve { ruc, razonSocial, nombreComercial, direccion, ... }
                if (!empty($data['ruc'])) {
                    return response()->json([
                        'success' => true,
                        'data' => [
                            'ruc'              => $data['ruc']              ?? '',
                            'razon_social'     => $data['razonSocial']      ?? '',   // ✅ razonSocial, no nombre
                            'nombre_comercial' => $data['nombreComercial']  ?? '',
                            'direccion'        => $data['direccion']        ?? '',
                            'departamento'     => $data['departamento']     ?? '',
                            'provincia'        => $data['provincia']        ?? '',
                            'distrito'         => $data['distrito']         ?? '',
                            'estado'           => $data['estado']           ?? '',
                            'condicion'        => $data['condicion']        ?? '',
                            'ubigeo'           => $data['ubigeo']           ?? '',
                        ]
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'No se encontró información para el RUC proporcionado'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error consultando RUC: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al consultar el servicio. Intente nuevamente.'
            ], 500);
        }
    }
}
