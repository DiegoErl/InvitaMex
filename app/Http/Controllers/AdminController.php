<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    /**
     * Dashboard principal del admin
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_events' => Event::count(),
            'pending_events' => Event::where('status', 'borrador')->count(),
            'paid_events' => Event::where('payment_type', 'pago')->count(),
            'free_events' => Event::where('payment_type', 'gratis')->count(),
            'total_revenue' => Event::where('payment_type', 'pago')
                ->where('status', 'publicado')
                ->sum('price'),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Gestión de usuarios
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstName', 'like', "%{$search}%")
                    ->orWhere('lastName', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->withCount('events')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * Promover usuario a admin
     */
    public function promoteToAdmin($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->isAdmin()) {
                return back()->with('info', 'Este usuario ya es administrador');
            }

            $user->role = 'admin';
            $user->save();

            Log::info('User promoted to admin', [
                'admin_id' => Auth::id(),
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            return back()->with('success', "Usuario {$user->firstName} {$user->lastName} promovido a administrador");
        } catch (\Exception $e) {
            Log::error('Error promoting user', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al promover usuario');
        }
    }

    /**
     * Degradar admin a usuario
     */
    public function demoteToUser($id)
    {
        try {
            $user = User::findOrFail($id);

            // No permitir que el admin se degrade a sí mismo
            if ($user->id === Auth::id()) {
                return back()->with('error', 'No puedes quitarte el rol de administrador a ti mismo');
            }

            if (!$user->isAdmin()) {
                return back()->with('info', 'Este usuario ya es un usuario común');
            }

            $user->role = 'user';
            $user->save();

            Log::info('Admin demoted to user', [
                'admin_id' => Auth::id(),
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            return back()->with('success', "Usuario {$user->firstName} {$user->lastName} degradado a usuario común");
        } catch (\Exception $e) {
            Log::error('Error demoting user', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al degradar usuario');
        }
    }

    /**
     * Eliminar usuario
     */
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);

            // No permitir que el admin se elimine a sí mismo
            if ($user->id === Auth::id()) {
                return back()->with('error', 'No puedes eliminarte a ti mismo');
            }

            $userName = $user->firstName . ' ' . $user->lastName;
            $user->delete();

            Log::warning('User deleted by admin', [
                'admin_id' => Auth::id(),
                'deleted_user_id' => $id,
                'deleted_user_email' => $user->email
            ]);

            return back()->with('success', "Usuario {$userName} eliminado exitosamente");
        } catch (\Exception $e) {
            Log::error('Error deleting user', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al eliminar usuario');
        }
    }

    /**
     * Gestión de eventos
     */
    public function events(Request $request)
    {
        $query = Event::with(['user']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('host_name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->withCount('confirmedInvitations')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.events', compact('events'));
    }

    /**
     * Aprobar evento
     */
    public function approveEvent($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->status = 'publicado';
            $event->save();

            Log::info('Event approved', [
                'admin_id' => Auth::id(),
                'event_id' => $event->id,
                'event_title' => $event->title
            ]);

            return back()->with('success', "Evento '{$event->title}' aprobado y publicado");
        } catch (\Exception $e) {
            Log::error('Error approving event', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al aprobar evento');
        }
    }

    /**
     * Rechazar/Ocultar evento
     */
    public function rejectEvent($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->status = 'borrador';
            $event->is_public = false;
            $event->save();

            Log::info('Event rejected', [
                'admin_id' => Auth::id(),
                'event_id' => $event->id,
                'event_title' => $event->title
            ]);

            return back()->with('success', "Evento '{$event->title}' ocultado");
        } catch (\Exception $e) {
            Log::error('Error rejecting event', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al rechazar evento');
        }
    }

    /**
     * Eliminar evento
     */
    public function deleteEvent($id)
    {
        try {
            $event = Event::findOrFail($id);
            $eventTitle = $event->title;

            // Eliminar imagen si existe
            if ($event->event_image) {
                Storage::disk('public')->delete($event->event_image);
            }

            // Eliminar imágenes de galería
            foreach ($event->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            $event->delete();

            Log::warning('Event deleted by admin', [
                'admin_id' => Auth::id(),
                'event_id' => $id,
                'event_title' => $eventTitle
            ]);

            return back()->with('success', "Evento '{$eventTitle}' eliminado exitosamente");
        } catch (\Exception $e) {
            Log::error('Error deleting event', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al eliminar evento');
        }
    }

    /**
     * Suspender/Activar usuario
     */
    public function toggleUserStatus($id)
    {
        try {
            $user = User::findOrFail($id);

            // No permitir suspender al admin actual
            if ($user->id === Auth::id()) {
                return back()->with('error', 'No puedes suspender tu propia cuenta');
            }

            $user->is_active = !$user->is_active;
            $user->save();

            $status = $user->is_active ? 'activado' : 'suspendido';
            $action = $user->is_active ? 'activated' : 'suspended';

            Log::info("User {$action}", [
                'admin_id' => Auth::id(),
                'user_id' => $user->id,
                'user_email' => $user->email,
                'new_status' => $user->is_active
            ]);

            return back()->with('success', "Usuario {$user->firstName} {$user->lastName} {$status} exitosamente");
        } catch (\Exception $e) {
            Log::error('Error toggling user status', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al cambiar estado del usuario');
        }
    }
}
