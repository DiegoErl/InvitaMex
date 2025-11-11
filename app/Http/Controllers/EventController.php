<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use chillerlan\QRCode\QRCode as ChillerlanQRCode;
use chillerlan\QRCode\QROptions;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('is_public', true)
            ->where('status', 'publicado')
            ->with('user')
            ->orderBy('event_date', 'desc')
            ->paginate(12);

        return view('eventos', compact('events'));
    }

    public function create()
    {
        return view('crear-evento');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'host_name' => 'required|string|max:255',
            'location' => 'required|string|max:500',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'required',
            'type' => 'required|in:boda,cumpleanos,graduacion,corporativo,social,religioso,otro',
            'payment_type' => 'required|in:gratis,pago',
            'price' => 'required_if:payment_type,pago|nullable|numeric|min:0',
            'description' => 'required|string|max:2000',
            'max_attendees' => 'nullable|integer|min:1',
            'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_public' => 'boolean',
        ], [
            'title.required' => 'El título del evento es obligatorio',
            'host_name.required' => 'El nombre del anfitrión es obligatorio',
            'location.required' => 'La ubicación es obligatoria',
            'event_date.required' => 'La fecha del evento es obligatoria',
            'event_date.after_or_equal' => 'La fecha debe ser hoy o posterior',
            'event_time.required' => 'La hora del evento es obligatoria',
            'type.required' => 'El tipo de evento es obligatorio',
            'payment_type.required' => 'Debes indicar si el evento es gratis o de pago',
            'price.required_if' => 'El precio es obligatorio para eventos de pago',
            'description.required' => 'La descripción es obligatoria',
            'event_image.max' => 'La imagen no debe pesar más de 5MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Manejar la imagen
        $imagePath = null;
        if ($request->hasFile('event_image')) {
            $imagePath = $request->file('event_image')->store('event_images', 'public');
        }

        // Crear el evento
        $event = Event::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'host_name' => $request->host_name,
            'location' => $request->location,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'event_image' => $imagePath,
            'type' => $request->type,
            'payment_type' => $request->payment_type,
            'price' => $request->payment_type === 'pago' ? $request->price : null,
            'description' => $request->description,
            'max_attendees' => $request->max_attendees,
            'is_public' => $request->has('is_public') ? true : false,
            'status' => $request->status ?? 'borrador',
        ]);

        $message = $event->status === 'publicado'
            ? '¡Evento publicado exitosamente! Ahora es visible en la página de eventos.'
            : '¡Borrador guardado exitosamente!';

        return response()->json([
            'success' => true,
            'message' => $message,
            'event' => $event,
            'redirect' => route('eventos') // CAMBIO AQUÍ: redirige a la página de eventos
        ], 201);
    }

    public function show($id)
    {
        $event = Event::with(['user', 'confirmedInvitations'])->findOrFail($id);
        return view('evento-detalle', compact('event'));
    }

    //QR ACTUALIZADO
    public function requestInvitation(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = Auth::user();

        // Verificar si ya tiene una invitación
        $existingInvitation = Invitation::where('event_id', $eventId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingInvitation) {
            return response()->json([
                'success' => false,
                'message' => 'Ya tienes una invitación para este evento'
            ], 400);
        }

        // Verificar capacidad máxima
        if ($event->max_attendees) {
            $confirmedCount = $event->confirmedInvitations()->count();
            if ($confirmedCount >= $event->max_attendees) {
                return response()->json([
                    'success' => false,
                    'message' => 'El evento ha alcanzado su capacidad máxima'
                ], 400);
            }
        }

        // Generar código QR único
        $qrCode = Str::uuid()->toString();

        // Crear invitación primero
        $invitation = Invitation::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'qr_code' => $qrCode,
            'status' => 'confirmado',
            'confirmed_at' => now(),
        ]);

        try {
            // Asegurarse de que el directorio existe
            $directory = storage_path('app/public/qr_codes');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Configurar opciones del QR
            $options = new QROptions([
                'outputType' => ChillerlanQRCode::OUTPUT_IMAGE_PNG,
                'eccLevel' => ChillerlanQRCode::ECC_H,
                'scale' => 10,
                'imageBase64' => false,
            ]);

            // Generar QR Code
            $qrcode = new ChillerlanQRCode($options);
            $qrImageData = $qrcode->render($qrCode);

            // Guardar imagen
            $filename = $qrCode . '.png';
            $qrPath = 'qr_codes/' . $filename;
            $fullPath = storage_path('app/public/' . $qrPath);

            file_put_contents($fullPath, $qrImageData);

            // Actualizar la invitación con la ruta de la imagen
            $invitation->qr_image = $qrPath;
            $invitation->save();

            return response()->json([
                'success' => true,
                'message' => '¡Invitación confirmada! Tu código QR ha sido generado',
                'invitation' => $invitation,
                'qr_url' => asset('storage/' . $qrPath)
            ], 201);
        } catch (\Exception $e) {
            // Si falla la generación del QR, eliminar la invitación
            $invitation->delete();

            Log::error('Error generando QR: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al generar el código QR: ' . $e->getMessage()
            ], 500);
        }
    }

    public function myInvitations()
    {
        // Invitaciones donde soy invitado
        $invitations = Invitation::where('user_id', Auth::id())
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->get();

        // Eventos que he creado (donde soy organizador)
        $myEvents = Event::where('user_id', Auth::id())
            ->withCount(['invitations', 'confirmedInvitations'])
            ->orderBy('event_date', 'desc')
            ->get();

        return view('mis-invitaciones', compact('invitations', 'myEvents'));
    }

    // Otros métodos del controlador..............................................................

    public function getEventsApi(Request $request)
    {
        $query = Event::where('is_public', true)
            ->where('status', 'publicado')
            ->with(['user', 'confirmedInvitations']);

        // Aplicar filtros
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('organizer')) {
            $query->where('host_name', 'like', '%' . $request->organizer . '%');
        }

        $events = $query->orderBy('event_date', 'desc')->get();

        // Formatear los datos para que coincidan con el formato del JS
        $formattedEvents = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'type' => $event->type,
                'organizer' => $event->host_name,
                'date' => $event->event_date->format('Y-m-d') . 'T' . $event->event_time->format('H:i:s'),
                'location' => $event->location,
                'description' => $event->description,
                'attendees' => $event->confirmedInvitations->count(),
                'max_attendees' => $event->max_attendees,
                'payment_type' => $event->payment_type,
                'price' => $event->price,
                'image' => $event->event_image ? asset('storage/' . $event->event_image) : null,
                'status' => $this->getEventStatus($event->event_date),
                'color' => $this->getEventColor($event->type),
                'icon' => $this->getEventIcon($event->type)
            ];
        });

        return response()->json($formattedEvents);
    }

    private function getEventStatus($eventDate)
    {
        $today = now()->startOfDay();
        $eventDay = $eventDate->startOfDay();

        if ($eventDay->isSameDay($today)) {
            return 'today';
        } elseif ($eventDay->isBefore($today)) {
            return 'past';
        } else {
            return 'upcoming';
        }
    }

    private function getEventColor($type)
    {
        $colors = [
            'boda' => '#ff6b9d',
            'cumpleanos' => '#4ecdc4',
            'graduacion' => '#45b7d1',
            'corporativo' => '#6c5ce7',
            'social' => '#fd79a8',
            'religioso' => '#74b9ff',
            'otro' => '#00cec9'
        ];
        return $colors[$type] ?? '#667eea';
    }

    private function getEventIcon($type)
    {
        $icons = [
            'boda' => 'fas fa-heart',
            'cumpleanos' => 'fas fa-birthday-cake',
            'graduacion' => 'fas fa-graduation-cap',
            'corporativo' => 'fas fa-briefcase',
            'social' => 'fas fa-music',
            'religioso' => 'fas fa-church',
            'otro' => 'fas fa-calendar-alt'
        ];
        return $icons[$type] ?? 'fas fa-calendar-alt';
    }
}
