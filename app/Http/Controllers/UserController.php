<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%");
            });
        }

        // Filtro por rol
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        $users = $query->paginate(15);
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'dni'      => 'required|string|size:8|unique:users,dni',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'codigo'   => 'nullable|string|unique:users,codigo',
            'telefono_user' => 'nullable|string|regex:/^[0-9]+$/|max:14',
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'exists:roles,name',
        ], [
            'roles.required'         => 'Debe asignar al menos un rol al usuario',
            'roles.min'              => 'Debe asignar al menos un rol al usuario',
            'telefono_user.regex'    => 'El teléfono solo puede contener números',
            'telefono_user.max'      => 'El teléfono no puede superar los 14 dígitos',
        ]);

        // Generar código automático si no se proporciona
        if (!$request->codigo) {
            $iniciales = strtoupper(substr($validated['name'], 0, 2));
            $count = User::count() + 1;
            $validated['codigo'] = "USR-{$count}{$iniciales}";
        }

        $user = User::create([
            'name'          => $validated['name'],
            'dni'           => $validated['dni'],
            'email'         => $validated['email'],
            'password'      => Hash::make($validated['password']),
            'codigo'        => $validated['codigo'],
            'telefono_user' => $validated['telefono_user'] ?? null,
        ]);

        // Asignar roles
        $user->syncRoles($validated['roles']);

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load('roles.permissions', 'proformas');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the user
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'dni'      => 'required|string|size:8|unique:users,dni,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'codigo'   => 'nullable|string|unique:users,codigo,' . $user->id,
            'telefono_user' => 'nullable|string|regex:/^[0-9]+$/|max:14',
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'exists:roles,name',
        ], [
            'roles.required'         => 'Debe asignar al menos un rol al usuario',
            'roles.min'              => 'Debe asignar al menos un rol al usuario',
            'telefono_user.regex'    => 'El teléfono solo puede contener números',
            'telefono_user.max'      => 'El teléfono no puede superar los 14 dígitos',
        ]);

        $user->update([
            'name'          => $validated['name'],
            'dni'           => $validated['dni'],
            'email'         => $validated['email'],
            'codigo'        => $validated['codigo'],
            'telefono_user' => $validated['telefono_user'] ?? null,
        ]);

        // Solo actualizar password si se proporciona
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        // Sincronizar roles
        $user->syncRoles($validated['roles']);

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // No permitir eliminar al usuario autenticado
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'No puedes eliminar tu propia cuenta');
        }

        try {
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'Usuario eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'No se pudo eliminar el usuario');
        }
    }
}
