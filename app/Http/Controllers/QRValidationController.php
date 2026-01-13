<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QRValidationController extends Controller
{
    // Mostrar el escáner (solo para el creador del evento)
    public function showScanner($eventId)
    {
        $event = Event::findOrFail($eventId);
        
        // Solo el creador del evento puede escanear
        if (Auth::id() !== $event->user_id) {
            abort(403, 'No tienes permiso para escanear invitaciones de este evento');
        }
        
        return view('qr-scanner', compact('eventId', 'event'));
    }

    // Validar código QR escaneado
    public function validateQR(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string'
        ]);

        $invitation = Invitation::where('qr_code', $request->qr_code)
            ->with('event', 'user')
            ->first();

        if (!$invitation) {
            return response()->json([
                'success' => false,
                'message' => '❌ Código QR no válido',
                'status' => 'invalid'
            ], 404);
        }

        // ⭐ NUEVA VALIDACIÓN: Verificar si está PENDIENTE
        if ($invitation->status === 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => '⏳ Esta invitación aún no ha sido confirmada por el usuario',
                'status' => 'pending',
                'invitation' => [
                    'user_name' => $invitation->user->firstName . ' ' . $invitation->user->lastName,
                    'user_email' => $invitation->user->email,
                    'event_name' => $invitation->event->title,
                    'status' => 'pendiente'
                ]
            ], 400);
        }

        // ⭐ NUEVA VALIDACIÓN: Verificar si está RECHAZADA
        if ($invitation->status === 'rechazado') {
            return response()->json([
                'success' => false,
                'message' => '❌ Esta invitación fue rechazada por el usuario',
                'status' => 'rejected',
                'invitation' => [
                    'user_name' => $invitation->user->firstName . ' ' . $invitation->user->lastName,
                    'user_email' => $invitation->user->email,
                    'event_name' => $invitation->event->title,
                    'status' => 'rechazado'
                ]
            ], 400);
        }

        // Verificar si ya fue usado
        if ($invitation->status === 'usado') {
            return response()->json([
                'success' => false,
                'message' => '⚠️ Esta invitación ya fue utilizada',
                'status' => 'already_used',
                'invitation' => [
                    'user_name' => $invitation->user->firstName . ' ' . $invitation->user->lastName,
                    'event_name' => $invitation->event->title,
                    'used_at' => $invitation->used_at->format('d/m/Y H:i'),
                ]
            ], 400);
        }

        // Verificar si está cancelada
        if ($invitation->status === 'cancelado') {
            return response()->json([
                'success' => false,
                'message' => '❌ Esta invitación fue cancelada',
                'status' => 'canceled'
            ], 400);
        }

        // ⭐ VALIDACIÓN ADICIONAL: Solo permitir escaneo si está CONFIRMADO
        if ($invitation->status !== 'confirmado') {
            return response()->json([
                'success' => false,
                'message' => '❌ Esta invitación no está confirmada',
                'status' => 'not_confirmed',
                'invitation' => [
                    'user_name' => $invitation->user->firstName . ' ' . $invitation->user->lastName,
                    'current_status' => $invitation->status
                ]
            ], 400);
        }

        // ✅ TODO CORRECTO - Marcar como usado
        $invitation->status = 'usado';
        $invitation->used_at = now();
        $invitation->save();

        return response()->json([
            'success' => true,
            'message' => '✅ Invitación válida - Acceso concedido',
            'status' => 'valid',
            'invitation' => [
                'user_name' => $invitation->user->firstName . ' ' . $invitation->user->lastName,
                'user_email' => $invitation->user->email,
                'event_name' => $invitation->event->title,
                'event_date' => $invitation->event->event_date->format('d/m/Y'),
                'event_time' => $invitation->event->event_time->format('H:i'),
                'confirmed_at' => $invitation->confirmed_at ? $invitation->confirmed_at->format('d/m/Y H:i') : 'No confirmado',
                'used_at' => $invitation->used_at->format('d/m/Y H:i'),
            ]
        ], 200);
    }

    // ⭐ NUEVO: Obtener estadísticas de escaneo del evento
    public function getEventStats($eventId)
    {
        $event = Event::findOrFail($eventId);
        
        // Verificar permisos
        if (Auth::id() !== $event->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado'
            ], 403);
        }
        
        // Contar invitaciones por estado
        $stats = [
            'total' => Invitation::where('event_id', $eventId)->count(),
            'usado' => Invitation::where('event_id', $eventId)->where('status', 'usado')->count(),
            'confirmado' => Invitation::where('event_id', $eventId)->where('status', 'confirmado')->count(),
            'pendiente' => Invitation::where('event_id', $eventId)->where('status', 'pendiente')->count(),
            'rechazado' => Invitation::where('event_id', $eventId)->where('status', 'rechazado')->count(),
            'cancelado' => Invitation::where('event_id', $eventId)->where('status', 'cancelado')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    // Ver historial de QR escaneados para un evento
    public function eventHistory($eventId)
    {
        $event = Event::findOrFail($eventId);
        
        // Solo el creador del evento puede ver el historial
        if (Auth::id() !== $event->user_id) {
            abort(403, 'No tienes permiso para ver el historial de este evento');
        }
        
        $invitations = Invitation::where('event_id', $eventId)
            ->with('user')
            ->orderBy('used_at', 'desc')
            ->get();

        return view('qr-history', compact('invitations', 'eventId', 'event'));
    }
}