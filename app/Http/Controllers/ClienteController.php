<?php

/**
 * CONTROLADOR: ClienteController.php
 * Ubicación: app/Http/Controllers/ClienteController.php
 */

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Credito;
use App\Models\Categoria;
use App\Models\Contacto;
use App\Models\Departamento;
use App\Models\Direccion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    // ═══════════════════════════════════════════════════════════════════
    //  HELPER PRIVADO: construye la query base con todos los filtros
    // ═══════════════════════════════════════════════════════════════════

    private function buildClienteQuery(Request $request)
    {
        $query = Cliente::query();

        // ── Razón social o RUC ──────────────────────────────────────────
        if ($request->filled('buscar')) {
            $s = $request->buscar;
            $query->where(fn($q) =>
                $q->where('ruc',   'like', "%{$s}%")
                  ->orWhere('razon','like', "%{$s}%")
            );
        }

        // ── Categoría ───────────────────────────────────────────────────
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // ── Nombre de contacto o DNI ────────────────────────────────────
        if ($request->filled('contacto_buscar')) {
            $cb = $request->contacto_buscar;
            $query->whereHas('contactos', fn($q) =>
                $q->where('nombre',           'like', "%{$cb}%")
                  ->orWhere('apellido_paterno','like', "%{$cb}%")
                  ->orWhere('apellido_materno','like', "%{$cb}%")
                  ->orWhere('dni',             'like', "%{$cb}%")
            );
        }

        // ── Dirección principal contiene ────────────────────────────────
        if ($request->filled('dir_principal')) {
            $query->where('direccion', 'like', '%'.$request->dir_principal.'%');
        }

        // ── Departamento (ubigeo de agencias) ───────────────────────────
        if ($request->filled('departamento_id')) {
            $query->whereHas('direcciones.distrito.provincia.departamento', fn($q) =>
                $q->where('id', $request->departamento_id)
            );
        }

        // ── Provincia (ubigeo de agencias) ──────────────────────────────
        if ($request->filled('provincia_id')) {
            $query->whereHas('direcciones.distrito.provincia', fn($q) =>
                $q->where('id', $request->provincia_id)
            );
        }

        // ── Distrito (ubigeo de agencias) ───────────────────────────────
        if ($request->filled('distrito_id')) {
            $query->whereHas('direcciones.distrito', fn($q) =>
                $q->where('id', $request->distrito_id)
            );
        }

        return $query;
    }

    // ═══════════════════════════════════════════════════════════════════
    //  Extrae el nombre del departamento desde el texto libre de dirección
    //  Formato esperado: "... DEPARTAMENTO PROVINCIA DISTRITO"
    // ═══════════════════════════════════════════════════════════════════

    private function extractDepartamento(string $direccion): string
    {
        $peru = [
            'AMAZONAS','ANCASH','APURIMAC','AREQUIPA','AYACUCHO',
            'CAJAMARCA','CALLAO','CUSCO','HUANCAVELICA','HUANUCO',
            'ICA','JUNIN','LA LIBERTAD','LAMBAYEQUE','LIMA',
            'LORETO','MADRE DE DIOS','MOQUEGUA','PASCO','PIURA',
            'PUNO','SAN MARTIN','TACNA','TUMBES','UCAYALI',
        ];

        $upper = strtoupper(trim($direccion));

        // Primero intentar match exacto de los 25 departamentos
        foreach ($peru as $dept) {
            if (str_contains($upper, $dept)) {
                return $dept;
            }
        }

        // Fallback: 3ª palabra desde el final ("calle nro DEPT PROV DIST")
        $words = preg_split('/\s+/', $upper);
        $n = count($words);
        if ($n >= 3) {
            return $words[$n - 3];
        }
        if ($n >= 1) {
            return $words[$n - 1];
        }

        return 'DESCONOCIDO';
    }

    // ═══════════════════════════════════════════════════════════════════
    //  INDEX  –  listado con filtros avanzados
    // ═══════════════════════════════════════════════════════════════════

    public function index(Request $request)
    {
        $query = $this->buildClienteQuery($request);

        $clientes   = $query->with(['credito', 'categoria', 'contactos', 'direcciones'])
                            ->paginate(15)
                            ->withQueryString();

        $categorias    = Categoria::orderBy('name')->get();
        $departamentos = Departamento::orderBy('nombre')->get(['id', 'nombre']);

        return view('clientes.index', compact('clientes', 'categorias', 'departamentos'));
    }

    // ═══════════════════════════════════════════════════════════════════
    //  API – ESTADÍSTICAS  (responde JSON para Chart.js)
    //  GET /api/clientes/estadisticas
    // ═══════════════════════════════════════════════════════════════════

    public function estadisticasClientes(Request $request): JsonResponse
    {
        /* ── IDs de clientes que pasan los filtros de la tabla ── */
        $clienteIds = $this->buildClienteQuery($request)->pluck('id')->toArray();

        if (empty($clienteIds)) {
            $empty = collect();
            return response()->json([
                'cotizados'    => $empty,
                'ganadas'      => $empty,
                'perdidas'     => $empty,
                'top10'        => $empty,
                'top5peores'   => $empty,
                'departamentos'=> [],
                'credito'      => ['sin_credito'=>0,'aprobado'=>0,'desaprobado'=>0,'total'=>0],
            ]);
        }

        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');

        /* ── Builder base de proformas ── */
        $base = DB::table('proformas')
            ->join('estados', 'proformas.estado_id', '=', 'estados.id')
            ->join('clientes', 'proformas.cliente_id', '=', 'clientes.id')
            ->whereIn('proformas.cliente_id', $clienteIds);

        if ($fechaDesde) $base->where('proformas.fecha_creacion', '>=', $fechaDesde);
        if ($fechaHasta) $base->where('proformas.fecha_creacion', '<=', $fechaHasta);

        /* ── 1. Proformas Cotizado por cliente ── */
        $cotizados = (clone $base)
            ->where('estados.name', 'Cotizado')
            ->select('clientes.razon', DB::raw('COUNT(*) as total'))
            ->groupBy('clientes.id', 'clientes.razon')
            ->orderByDesc('total')->limit(15)->get();

        /* ── 2. Proformas Ganada por cliente ── */
        $ganadas = (clone $base)
            ->where('estados.name', 'Ganada')
            ->select('clientes.razon', DB::raw('COUNT(*) as total'))
            ->groupBy('clientes.id', 'clientes.razon')
            ->orderByDesc('total')->limit(15)->get();

        /* ── 3. Proformas Perdida por cliente ── */
        $perdidas = (clone $base)
            ->where('estados.name', 'Perdida')
            ->select('clientes.razon', DB::raw('COUNT(*) as total'))
            ->groupBy('clientes.id', 'clientes.razon')
            ->orderByDesc('total')->limit(15)->get();

        /* ── 4. Top 10 mayor consumo monetario (estado Ganada) ── */
        $top10Consumo = (clone $base)
            ->where('estados.name', 'Ganada')
            ->select('clientes.razon', DB::raw('SUM(proformas.total) as monto'))
            ->groupBy('clientes.id', 'clientes.razon')
            ->orderByDesc('monto')->limit(10)->get();

        /* ── 5. Top 5 peores (mayor número Perdida) ── */
        $top5Peores = (clone $base)
            ->where('estados.name', 'Perdida')
            ->select('clientes.razon', DB::raw('COUNT(*) as total'))
            ->groupBy('clientes.id', 'clientes.razon')
            ->orderByDesc('total')->limit(5)->get();

        /* ── 6. Por departamento ──
               - Clientes: parseando direccion principal
               - Agencias: via ubigeo de tabla direccions
               - Proformas Ganada: del cliente parseado
        ── */
        $clientesConDirs = Cliente::whereIn('id', $clienteIds)
            ->with('direcciones.distrito.provincia.departamento')
            ->get(['id', 'direccion']);

        $proformasGanadasMap = DB::table('proformas')
            ->join('estados', 'proformas.estado_id', '=', 'estados.id')
            ->where('estados.name', 'Ganada')
            ->whereIn('proformas.cliente_id', $clienteIds)
            ->when($fechaDesde, fn($q) => $q->where('proformas.fecha_creacion', '>=', $fechaDesde))
            ->when($fechaHasta, fn($q) => $q->where('proformas.fecha_creacion', '<=', $fechaHasta))
            ->select('proformas.cliente_id', DB::raw('COUNT(*) as cnt'))
            ->groupBy('proformas.cliente_id')
            ->pluck('cnt', 'cliente_id')
            ->toArray();

        $deptStats = [];

        foreach ($clientesConDirs as $c) {
            // Departamento de la dirección principal (texto libre)
            $dept = $this->extractDepartamento($c->direccion);

            if (!isset($deptStats[$dept])) {
                $deptStats[$dept] = ['clientes' => 0, 'agencias' => 0, 'proformas_ganadas' => 0];
            }
            $deptStats[$dept]['clientes']++;
            $deptStats[$dept]['proformas_ganadas'] += $proformasGanadasMap[$c->id] ?? 0;

            // Agencias (direcciones adicionales con ubigeo)
            foreach ($c->direcciones as $dir) {
                $deptAgencia = $dir->distrito?->provincia?->departamento?->nombre
                    ?? 'DESCONOCIDO';
                $deptAgencia = strtoupper($deptAgencia);

                if (!isset($deptStats[$deptAgencia])) {
                    $deptStats[$deptAgencia] = ['clientes' => 0, 'agencias' => 0, 'proformas_ganadas' => 0];
                }
                $deptStats[$deptAgencia]['agencias']++;
            }
        }

        ksort($deptStats);

        /* ── 7. Crédito: Sin / Aprobado / Desaprobado ── */
        $sinCredito  = Cliente::whereIn('id', $clienteIds)->whereNull('credito_id')->count();
        $aprobado    = Cliente::whereIn('id', $clienteIds)
                              ->whereHas('credito', fn($q) => $q->where('aprobacion', true))->count();
        $desaprobado = Cliente::whereIn('id', $clienteIds)
                              ->whereHas('credito', fn($q) => $q->where('aprobacion', false))->count();

        return response()->json([
            'cotizados'     => $cotizados,
            'ganadas'       => $ganadas,
            'perdidas'      => $perdidas,
            'top10'         => $top10Consumo,
            'top5peores'    => $top5Peores,
            'departamentos' => $deptStats,
            'credito' => [
                'sin_credito' => $sinCredito,
                'aprobado'    => $aprobado,
                'desaprobado' => $desaprobado,
                'total'       => count($clienteIds),
            ],
        ]);
    }

    // ═══════════════════════════════════════════════════════════════════
    //  CRUD estándar
    // ═══════════════════════════════════════════════════════════════════

    public function create()
    {
        $categorias    = Categoria::all();
        $creditos      = Credito::all();
        $contactos     = Contacto::all();
        $departamentos = Departamento::orderBy('nombre')->get(['id', 'nombre']);

        return view('clientes.create', compact('categorias', 'creditos', 'contactos', 'departamentos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ruc'          => 'required|string|size:11|unique:clientes,ruc',
            'razon'        => 'required|string|max:250',
            'direccion'    => 'required|string|max:200',
            'telefono1'    => 'required|string|max:15',
            'telefono2'    => 'nullable|string|max:15',
            'credito_id'   => 'nullable|exists:creditos,id',
            'categoria_id' => 'nullable|exists:categorias,id',
        ]);

        $contactosIds = $request->input('contactos')
            ? explode(',', $request->input('contactos'))
            : [];

        $direcciones = $request->input('direcciones', []);
        $distritos   = $request->input('distritos', []);

        $cliente = Cliente::create($validated);

        if (!empty($contactosIds)) {
            $cliente->contactos()->sync(array_filter($contactosIds));
        }

        foreach ($direcciones as $idx => $dir) {
            if (!empty(trim($dir))) {
                Direccion::create([
                    'direccion'   => trim($dir),
                    'cliente_id'  => $cliente->id,
                    'distrito_id' => !empty($distritos[$idx]) ? (int) $distritos[$idx] : null,
                ]);
            }
        }

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load([
            'credito',
            'categoria',
            'contactos',
            'proformas',
            'direcciones.distrito.provincia.departamento',
        ]);

        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        $categorias    = Categoria::all();
        $creditos      = Credito::all();
        $contactos     = Contacto::all();
        $departamentos = Departamento::orderBy('nombre')->get(['id', 'nombre']);

        $cliente->load([
            'contactos',
            'direcciones.distrito.provincia.departamento',
        ]);

        return view('clientes.edit', compact('cliente', 'categorias', 'creditos', 'contactos', 'departamentos'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validated = $request->validate([
            'ruc'           => 'required|string|size:11|unique:clientes,ruc,' . $cliente->id,
            'razon'         => 'required|string|max:250',
            'direccion'     => 'required|string|max:200',
            'telefono1'     => 'required|string|max:15',
            'telefono2'     => 'nullable|string|max:15',
            'credito_id'    => 'nullable|exists:creditos,id',
            'categoria_id'  => 'nullable|exists:categorias,id',
            'direcciones'   => 'nullable|array',
            'direcciones.*' => 'nullable|string|max:250',
            'distritos'     => 'nullable|array',
            'distritos.*'   => 'nullable|exists:distritos,id',
        ]);

        $contactosIds = $request->input('contactos')
            ? explode(',', $request->input('contactos'))
            : [];

        $direcciones = $request->input('direcciones', []);
        $distritos   = $request->input('distritos', []);

        $cliente->update($validated);

        if (!empty($contactosIds)) {
            $cliente->contactos()->sync(array_filter($contactosIds));
        } else {
            $cliente->contactos()->detach();
        }

        $cliente->direcciones()->delete();

        foreach ($direcciones as $idx => $dir) {
            if (!empty(trim($dir))) {
                Direccion::create([
                    'direccion'   => trim($dir),
                    'cliente_id'  => $cliente->id,
                    'distrito_id' => !empty($distritos[$idx]) ? (int) $distritos[$idx] : null,
                ]);
            }
        }

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy(Cliente $cliente)
    {
        try {
            $cliente->delete();
            return redirect()->route('clientes.index')
                ->with('success', 'Cliente eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se pudo eliminar el cliente');
        }
    }

    public function verificarRuc($ruc)
    {
        $cliente = Cliente::where('ruc', $ruc)->first();

        if ($cliente) {
            return response()->json([
                'existe'   => true,
                'cliente'  => [
                    'id'        => $cliente->id,
                    'ruc'       => $cliente->ruc,
                    'razon'     => $cliente->razon,
                    'direccion' => $cliente->direccion,
                    'telefono1' => $cliente->telefono1,
                    'telefono2' => $cliente->telefono2,
                ],
                'url_edit' => route('clientes.edit', $cliente->id),
            ]);
        }

        return response()->json(['existe' => false]);
    }
}
