<?php
// ══════════════════════════════════════════════════════════════════
// ARCHIVO: app/Http/Controllers/NotificacionController.php
// ══════════════════════════════════════════════════════════════════

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    /**
     * Vista completa de notificaciones del usuario autenticado.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $notificaciones = $user->notifications()
            ->latest()
            ->paginate(20);

        $totalNoLeidas = $user->unreadNotifications()->count();

        return view('notificaciones.index', compact('notificaciones', 'totalNoLeidas'));
    }

    /**
     * JSON: lista de notificaciones no leídas para el dropdown del topbar.
     * GET /notificaciones/recientes
     */
    public function recientes(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $notificaciones = $user->unreadNotifications()
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($n) => [
                'id'         => $n->id,
                'titulo'     => $n->data['titulo']     ?? 'Notificación',
                'mensaje'    => $n->data['mensaje']    ?? '',
                'icono'      => $n->data['icono']      ?? 'bi-bell',
                'color'      => $n->data['color']      ?? 'primary',
                'tipo'       => $n->data['tipo']       ?? '',
                'proforma_id'=> $n->data['proforma_id'] ?? null,
                'leida'      => !is_null($n->read_at),
                'tiempo'     => $n->created_at->diffForHumans(),
                'created_at' => $n->created_at->format('d/m/Y H:i'),
            ]);

        return response()->json([
            'notificaciones' => $notificaciones,
            'total_no_leidas'=> $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Marca una notificación específica como leída.
     * POST /notificaciones/{id}/leer
     */
    public function marcarLeida(string $id): JsonResponse
    {


        /** @var \App\Models\User $user */
        $user = Auth::user();

        $notificacion = $user->notifications()->findOrFail($id);
        $notificacion->markAsRead();

        return response()->json(['ok' => true]);
    }

    /**
     * Marca TODAS las notificaciones no leídas como leídas.
     * POST /notificaciones/leer-todas
     */
    public function marcarTodasLeidas(): JsonResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['ok' => true]);
    }

    /**
     * Elimina una notificación.
     * DELETE /notificaciones/{id}
     */
    public function destroy(string $id): JsonResponse
    {

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $notificacion = $user->notifications()->findOrFail($id);
        $notificacion->delete();

        return response()->json(['ok' => true]);
    }

    /**
     * Elimina todas las notificaciones leídas del usuario.
     * DELETE /notificaciones/limpiar
     */
    public function limpiar(): JsonResponse
    {

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->readNotifications()->delete();

        return response()->json(['ok' => true]);
    }
}
