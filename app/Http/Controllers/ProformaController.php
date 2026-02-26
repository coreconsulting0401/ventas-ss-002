<?php
/**
 * CONTROLADOR: ProformaController.php
 * Ubicación: app/Http/Controllers/ProformaController.php
 *
 * CAMBIO: se agregó 'contacto' al eager-loading en index(), show() y edit()
 */
namespace App\Http\Controllers;

use App\Models\Cambio;
use App\Models\Proforma;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Virtual;
use App\Models\Transaccion;
use App\Models\Temperatura;
use App\Models\Estado;
use App\Http\Requests\ProformaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProformaController extends Controller
{
    public function index(Request $request)
    {
        $query = Proforma::query();

        if ($request->filled('id')) {
            $rawId = preg_replace('/[^0-9]/', '', $request->id);
            if ($rawId !== '') $query->where('id', (int) $rawId);
        }
        if ($request->filled('razon')) {
            $razon = $request->razon;
            $query->whereHas('cliente', fn($q) => $q->where('razon','like',"%{$razon}%")->orWhere('ruc','like',"%{$razon}%"));
        }
        if ($request->filled('nombre')) {
            $nombre = $request->nombre;
            $query->whereHas('user', fn($q) => $q->where('name','like',"%{$nombre}%")->orWhere('dni','like',"%{$nombre}%")->orWhere('codigo','like',"%{$nombre}%"));
        }
        if ($request->filled('estado')) {
            $estado = $request->estado;
            $query->whereHas('estado', fn($q) => $q->where('name','like',"%{$estado}%"));
        }
        if ($request->filled('temperatura')) {
            $temperatura = $request->temperatura;
            $query->whereHas('temperatura', fn($q) => $q->where('name','like',"%{$temperatura}%"));
        }
        if ($request->filled('fecha_creacion_desde')) $query->where('fecha_creacion','>=',$request->fecha_creacion_desde);
        if ($request->filled('fecha_creacion_hasta')) $query->where('fecha_creacion','<=',$request->fecha_creacion_hasta);
        if ($request->filled('fecha_fin_desde'))      $query->where('fecha_fin','>=',$request->fecha_fin_desde);
        if ($request->filled('fecha_fin_hasta'))      $query->where('fecha_fin','<=',$request->fecha_fin_hasta);

        // ── contacto agregado al eager-load ───────────────────────────────
        $proformas = $query->with(['cliente','direccion','contacto','user','transaccion','temperatura','estado'])
                           ->latest()->paginate(15)->withQueryString();

        return view('proformas.index', [
            'proformas'    => $proformas,
            'clientes'     => Cliente::all(),
            'transacciones'=> Transaccion::all(),
            'temperaturas' => Temperatura::all(),
            'estados'      => Estado::all(),
        ]);
    }

    public function create()
    {
        $tipoCambio = Cambio::hoy();

        return view('proformas.create', [
            'productos'     => Producto::where('stock','>',0)->get(),
            'virtuals'      => Virtual::where('stock','>',0)->get(),
            'transacciones' => Transaccion::all(),
            'temperaturas'  => Temperatura::all(),
            'estados'       => Estado::all(),
            'tipoCambio'    => $tipoCambio,
        ]);
    }

    public function store(ProformaRequest $request)
    {
        try {
            DB::beginTransaction();

            $data            = $request->validated();
            $data['user_id'] = Auth::id();

            if (isset($data['direccion_id']) && $data['direccion_id'] === 'principal') {
                $data['direccion_id'] = null;
            }

            $productos = $data['productos'] ?? [];
            unset($data['productos']);

            $proforma = Proforma::create($data);

            if (!empty($productos)) {
                $pivot = [];
                foreach ($productos as $p) {
                    $pivot[$p['id']] = [
                        'cantidad'          => $p['cantidad'],
                        'precio_unitario'   => $p['precio_unitario'],
                        'descuento_cliente' => $p['descuento_cliente'] ?? 0,
                    ];
                }
                $proforma->productos()->attach($pivot);
            }

            DB::commit();
            return redirect()->route('proformas.index')->with('success', 'Proforma creada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al crear la proforma: ' . $e->getMessage());
        }
    }

    public function show(Proforma $proforma)
    {
        // ── contacto agregado al eager-load ───────────────────────────────
        $proforma->load([
            'cliente',
            'direccion.distrito.provincia.departamento',
            'contacto',
            'user','transaccion','temperatura','estado',
            'productos' => fn($q) => $q->with('descuento'),
        ]);
        return view('proformas.show', compact('proforma'));
    }

    public function edit(Proforma $proforma)
    {
        // ── contacto agregado al eager-load ───────────────────────────────
        $proforma->load(['productos' => fn($q) => $q->with('descuento'), 'cliente', 'direccion', 'contacto']);

        $tipoCambio = Cambio::hoy();

        return view('proformas.edit', [
            'proforma'      => $proforma,
            'productos'     => Producto::where('stock','>',0)->get(),
            'virtuals'      => Virtual::where('stock','>',0)->get(),
            'transacciones' => Transaccion::all(),
            'temperaturas'  => Temperatura::all(),
            'estados'       => Estado::all(),
            'tipoCambio'    => $tipoCambio,
        ]);
    }

    public function update(ProformaRequest $request, Proforma $proforma)
    {
        try {
            DB::beginTransaction();

            $data      = $request->validated();
            $productos = $data['productos'] ?? [];
            unset($data['productos']);

            if (isset($data['direccion_id']) && $data['direccion_id'] === 'principal') {
                $data['direccion_id'] = null;
            }

            $proforma->update($data);

            if (!empty($productos)) {
                $pivot = [];
                foreach ($productos as $p) {
                    $pivot[$p['id']] = [
                        'cantidad'          => $p['cantidad'],
                        'precio_unitario'   => $p['precio_unitario'],
                        'descuento_cliente' => $p['descuento_cliente'] ?? 0,
                    ];
                }
                $proforma->productos()->sync($pivot);
            } else {
                $proforma->productos()->detach();
            }

            DB::commit();
            return redirect()->route('proformas.index')->with('success', 'Proforma actualizada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error al actualizar la proforma: ' . $e->getMessage());
        }
    }

    public function destroy(Proforma $proforma)
    {
        try {
            $proforma->delete();
            return redirect()->route('proformas.index')->with('success', 'Proforma eliminada exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('proformas.index')->with('error', 'No se pudo eliminar la proforma');
        }
    }
}
