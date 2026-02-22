<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index()
    {
        $roles = Role::with('permissions')->withCount('users')->paginate(15);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            // Agrupar por módulo (primera palabra del permiso)
            return explode(' ', $permission->name)[1] ?? 'otros';
        });

        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,name',
        ], [
            'name.unique' => 'Ya existe un rol con este nombre',
            'permissions.required' => 'Debe asignar al menos un permiso al rol',
            'permissions.min' => 'Debe asignar al menos un permiso al rol',
        ]);

        $role = Role::create(['name' => $validated['name']]);
        $role->givePermissionTo($validated['permissions']);

        return redirect()->route('roles.index')
            ->with('success', 'Rol creado exitosamente');
    }

    /**
     * Display the specified role
     */
    public function show(Role $role)
    {
        $role->load('permissions', 'users');

        // Agrupar permisos por módulo
        $permissionsByModule = $role->permissions->groupBy(function($permission) {
            return explode(' ', $permission->name)[1] ?? 'otros';
        });

        return view('roles.show', compact('role', 'permissionsByModule'));
    }

    /**
     * Show the form for editing the role
     */
    public function edit(Role $role)
    {
        $role->load('permissions');

        $permissions = Permission::all()->groupBy(function($permission) {
            return explode(' ', $permission->name)[1] ?? 'otros';
        });

        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,name',
        ], [
            'name.unique' => 'Ya existe un rol con este nombre',
            'permissions.required' => 'Debe asignar al menos un permiso al rol',
            'permissions.min' => 'Debe asignar al menos un permiso al rol',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return redirect()->route('roles.index')
            ->with('success', 'Rol actualizado exitosamente');
    }

    /**
     * Remove the specified role
     */
    public function destroy(Role $role)
    {
        // No permitir eliminar roles del sistema
        $rolesProtegidos = ['Administrador', 'Vendedor', 'Almacén', 'Visualizador', 'Gerente'];

        if (in_array($role->name, $rolesProtegidos)) {
            return redirect()->route('roles.index')
                ->with('error', 'No se puede eliminar un rol del sistema');
        }

        // Verificar si tiene usuarios asignados
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'No se puede eliminar un rol que tiene usuarios asignados');
        }

        try {
            $role->delete();
            return redirect()->route('roles.index')
                ->with('success', 'Rol eliminado exitosamente');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')
                ->with('error', 'No se pudo eliminar el rol');
        }
    }
}
