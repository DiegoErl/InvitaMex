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

        // Marcar como usado
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
                'confirmed_at' => $invitation->confirmed_at->format('d/m/Y H:i'),
                'used_at' => $invitation->used_at->format('d/m/Y H:i'),
            ]
        ], 200);
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