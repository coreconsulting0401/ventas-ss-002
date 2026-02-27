<?php
// ══════════════════════════════════════════════════════════════════
// ARCHIVO: app/Http/Controllers/ProfileController.php
// Reemplaza el ProfileController generado por Breeze.
// ══════════════════════════════════════════════════════════════════

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Muestra la vista de perfil del usuario autenticado.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualiza los datos personales: name, email, dni, telefono_user.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,' . $user->id,
            'dni'           => 'required|string|size:8|regex:/^[0-9]+$/|unique:users,dni,' . $user->id,
            'telefono_user' => 'nullable|string|regex:/^[0-9]+$/|max:14',
        ], [
            'dni.size'            => 'El DNI debe tener exactamente 8 dígitos.',
            'dni.regex'           => 'El DNI solo puede contener números.',
            'dni.unique'          => 'Este DNI ya está registrado por otro usuario.',
            'telefono_user.regex' => 'El teléfono solo puede contener números.',
            'telefono_user.max'   => 'El teléfono no puede superar los 14 dígitos.',
        ]);

        // Si el email cambió, limpiar verificación
        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->fill($validated);
        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Actualiza la contraseña del usuario autenticado.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => 'required|current_password',
            'password'         => 'required|string|min:8|confirmed',
        ], [
            'current_password.current_password' => 'La contraseña actual no es correcta.',
            'password.min'                      => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'                => 'Las contraseñas no coinciden.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return Redirect::route('profile.edit')
            ->with('status', 'password-updated');
    }

    /**
     * Elimina la cuenta del usuario autenticado.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => 'required|current_password',
        ], [
            'password.current_password' => 'La contraseña ingresada no es correcta.',
        ]);

        $user = $request->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
