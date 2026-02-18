<?php

/**
 * CONTROLADOR: ContactoController.php
 * Ubicación: app/Http/Controllers/ContactoController.php
 */

namespace App\Http\Controllers;

use App\Models\Contacto;
use App\Http\Requests\ContactoRequest;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    /**
     * Display a listing of the resource with search and filter
     */
    public function index(Request $request)
    {
        $query = Contacto::query();

        // Búsqueda por múltiples campos
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('dni', 'like', "%{$search}%")
                  ->orWhere('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido_paterno', 'like', "%{$search}%")
                  ->orWhere('apellido_materno', 'like', "%{$search}%")
                  ->orWhere('telefono', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cargo', 'like', "%{$search}%");
            });
        }

        // Filtro por DNI específico
        if ($request->filled('dni')) {
            $query->where('dni', 'like', "%{$request->dni}%");
        }

        // Filtro por nombre
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', "%{$request->nombre}%");
        }

        // Filtro por email
        if ($request->filled('email')) {
            $query->where('email', 'like', "%{$request->email}%");
        }

        // Filtro por cargo
        if ($request->filled('cargo')) {
            $query->where('cargo', 'like', "%{$request->cargo}%");
        }

        $contactos = $query->with('clientes')->paginate(15);

        return view('contactos.index', compact('contactos'));
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        return view('contactos.create');
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        // Si es una petición AJAX (JSON), validar manualmente
        if ($request->expectsJson() || $request->isJson()) {
            $validated = $request->validate([
                'dni' => 'required|string|size:8|unique:contactos,dni',
                'nombre' => 'required|string|max:100',
                'apellido_paterno' => 'required|string|max:100',
                'apellido_materno' => 'required|string|max:100',
                'telefono' => 'required|string|max:15',
                'email' => 'required|email|max:255',
                'cargo' => 'required|string|max:50',
            ]);

            $contacto = Contacto::create($validated);
            return response()->json($contacto, 201);
        }

        // Si es un formulario normal, usar ContactoRequest
        $validated = $request->validate([
            'dni' => 'required|string|size:8|unique:contactos,dni',
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',
            'telefono' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'cargo' => 'required|string|max:50',
        ]);

        $contacto = Contacto::create($validated);

        return redirect()->route('contactos.index')
            ->with('success', 'Contacto creado exitosamente');
    }

    /**
     * Display the specified resource
     */
    public function show(Contacto $contacto)
    {
        $contacto->load('clientes');
        return view('contactos.show', compact('contacto'));
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit(Contacto $contacto)
    {
        return view('contactos.edit', compact('contacto'));
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, Contacto $contacto)
    {
        $validated = $request->validate([
            'dni' => 'required|string|size:8|unique:contactos,dni,' . $contacto->id,
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'required|string|max:100',
            'telefono' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'cargo' => 'required|string|max:50',
        ]);

        $contacto->update($validated);

        return redirect()->route('contactos.index')
            ->with('success', 'Contacto actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy(Contacto $contacto)
    {
        try {
            $contacto->delete();
            return redirect()->route('contactos.index')
                ->with('success', 'Contacto eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('contactos.index')
                ->with('error', 'No se pudo eliminar el contacto');
        }
    }

    /**
     * Buscar contacto por DNI
     */
    public function buscarPorDni($dni)
    {
        $contacto = Contacto::where('dni', $dni)->first();

        if ($contacto) {
            return response()->json([
                'success' => true,
                'contacto' => $contacto
            ]);
        }

        return response()->json([
            'success' => false,
            'contacto' => null
        ]);
    }
}
