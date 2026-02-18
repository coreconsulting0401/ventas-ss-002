<?php
/**
 * CONTROLADOR: ProformaController.php
 * Ubicación: app/Http/Controllers/ProformaController.php
 */
namespace App\Http\Controllers;

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
    /**
     * Display a listing with search and filters
     */
    public function index(Request $request)
    {
        $query = Proforma::query();

        // ── Filtro por N° Cotización (id numérico, ignora prefijo NCT- y ceros)
        if ($request->filled('id')) {
            $rawId = preg_replace('/[^0-9]/', '', $request->id); // extrae solo dígitos
            if ($rawId !== '') {
                $query->where('id', (int) $rawId);
            }
        }

        // ── Filtro por cliente: busca en razón social Y en ruc
        if ($request->filled('razon')) {
            $razon = $request->razon;
            $query->whereHas('cliente', function ($q) use ($razon) {
                $q->where('razon', 'like', "%{$razon}%")
                  ->orWhere('ruc',   'like', "%{$razon}%");
            });
        }

        // ── Filtro por usuario: busca en name, dni y codigo del user
        if ($request->filled('nombre')) {
            $nombre = $request->nombre;
            $query->whereHas('user', function ($q) use ($nombre) {
                $q->where('name',   'like', "%{$nombre}%")
                  ->orWhere('dni',   'like', "%{$nombre}%")
                  ->orWhere('codigo','like', "%{$nombre}%");
            });
        }

        // ── Filtro por estado (nombre del estado)
        if ($request->filled('estado')) {
            $estado = $request->estado;
            $query->whereHas('estado', function ($q) use ($estado) {
                $q->where('name', 'like', "%{$estado}%");
            });
        }

        // ── Filtro por temperatura (nombre de la temperatura)
        if ($request->filled('temperatura')) {
            $temperatura = $request->temperatura;
            $query->whereHas('temperatura', function ($q) use ($temperatura) {
                $q->where('name', 'like', "%{$temperatura}%");
            });
        }

        // ── Filtro por rango de fecha_creacion
        if ($request->filled('fecha_creacion_desde')) {
            $query->where('fecha_creacion', '>=', $request->fecha_creacion_desde);
        }
        if ($request->filled('fecha_creacion_hasta')) {
            $query->where('fecha_creacion', '<=', $request->fecha_creacion_hasta);
        }

        // ── Filtro por rango de fecha_fin
        if ($request->filled('fecha_fin_desde')) {
            $query->where('fecha_fin', '>=', $request->fecha_fin_desde);
        }
        if ($request->filled('fecha_fin_hasta')) {
            $query->where('fecha_fin', '<=', $request->fecha_fin_hasta);
        }

        // ── Mantener paginación con los filtros activos en la URL
        $proformas = $query->with([
            'cliente',
            'user',
            'transaccion',
            'temperatura',
            'estado',
        ])->latest()->paginate(15)->withQueryString();

        $clientes     = Cliente::all();
        $transacciones = Transaccion::all();
        $temperaturas  = Temperatura::all();
        $estados       = Estado::all();

        return view('proformas.index', compact(
            'proformas',
            'clientes',
            'transacciones',
            'temperaturas',
            'estados'
        ));
    }

    public function create()
    {
        $clientes      = Cliente::all();
        $productos     = Producto::where('stock', '>', 0)->get();
        $virtuals      = Virtual::where('stock', '>', 0)->get();
        $transacciones = Transaccion::all();
        $temperaturas  = Temperatura::all();
        $estados       = Estado::all();

        return view('proformas.create', compact(
            'clientes',
            'productos',
            'virtuals',
            'transacciones',
            'temperaturas',
            'estados'
        ));
    }

    public function store(ProformaRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['user_id'] = Auth::id();

            $productos = $data['productos'] ?? [];
            unset($data['productos']);

            $proforma = Proforma::create($data);

            if (!empty($productos)) {
                $productosData = [];
                foreach ($productos as $producto) {
                    $productosData[$producto['id']] = [
                        'cantidad'          => $producto['cantidad'],
                        'precio_unitario'   => $producto['precio_unitario'],
                        'descuento_cliente' => $producto['descuento_cliente'] ?? 0,
                    ];
                }
                $proforma->productos()->attach($productosData);
            }

            DB::commit();

            return redirect()->route('proformas.index')
                ->with('success', 'Proforma creada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al crear la proforma: ' . $e->getMessage());
        }
    }

    public function show(Proforma $proforma)
    {
        $proforma->load([
            'cliente',
            'user',
            'transaccion',
            'temperatura',
            'estado',
            'productos' => fn($q) => $q->with('descuento'),
        ]);

        return view('proformas.show', compact('proforma'));
    }

    public function edit(Proforma $proforma)
    {
        $proforma->load([
            'productos' => fn($q) => $q->with('descuento'),
        ]);

        $clientes      = Cliente::all();
        $productos     = Producto::where('stock', '>', 0)->get();
        $virtuals      = Virtual::where('stock', '>', 0)->get();
        $transacciones = Transaccion::all();
        $temperaturas  = Temperatura::all();
        $estados       = Estado::all();

        return view('proformas.edit', compact(
            'proforma',
            'clientes',
            'productos',
            'virtuals',
            'transacciones',
            'temperaturas',
            'estados'
        ));
    }

    public function update(ProformaRequest $request, Proforma $proforma)
    {
        try {
            DB::beginTransaction();

            $data      = $request->validated();
            $productos = $data['productos'] ?? [];
            unset($data['productos']);

            $proforma->update($data);

            if (!empty($productos)) {
                $productosData = [];
                foreach ($productos as $producto) {
                    $productosData[$producto['id']] = [
                        'cantidad'          => $producto['cantidad'],
                        'precio_unitario'   => $producto['precio_unitario'],
                        'descuento_cliente' => $producto['descuento_cliente'] ?? 0,
                    ];
                }
                $proforma->productos()->sync($productosData);
            } else {
                $proforma->productos()->detach();
            }

            DB::commit();

            return redirect()->route('proformas.index')
                ->with('success', 'Proforma actualizada exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar la proforma: ' . $e->getMessage());
        }
    }

    public function destroy(Proforma $proforma)
    {
        try {
            $proforma->delete();
            return redirect()->route('proformas.index')
                ->with('success', 'Proforma eliminada exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('proformas.index')
                ->with('error', 'No se pudo eliminar la proforma');
        }
    }
}
