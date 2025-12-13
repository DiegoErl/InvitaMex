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
use App\Models\EventImage;

use chillerlan\QRCode\QRCode as ChillerlanQRCode;
use chillerlan\QRCode\QROptions;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('is_public', true)
            ->where('status', 'publicado')
            ->where('event_date', '>=', now()->startOfDay()) // Solo eventos futuros
            ->with(['user', 'images']) //AGREGAR 'images'
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
            'price' => 'required_if:payment_type,pago|nullable|numeric|min:10', // ⭐ Mínimo $10 (requisito Stripe)
            'description' => 'required|string|max:2000',
            'max_attendees' => 'nullable|integer|min:1',
            'event_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'additional_images' => 'nullable|array|max:5',
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
            'price.min' => 'El precio mínimo es de $10.00 MXN (requisito de Stripe)',
            'description.required' => 'La descripción es obligatoria',
            'event_image.max' => 'La imagen no debe pesar más de 5MB',
            'event_image.required' => 'La imagen de portada es obligatoria',
            'additional_images.*.image' => 'Cada archivo debe ser una imagen válida',
            'additional_images.max' => 'Puedes subir máximo 5 imágenes adicionales',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // ⭐ VALIDACIÓN MEJORADA: Si es evento de pago, verificar Stripe completo
        if ($request->payment_type === 'pago') {
            $user = Auth::user();
            if (empty($user->stripe_account_id) || empty($user->stripe_account_verified)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Para crear eventos de pago, primero debes conectar y verificar tu cuenta de Stripe.',
                    'stripe_required' => true,
                    'redirect' => route('stripe.connect')
                ], 403);
            }

            // ⭐ VALIDACIÓN ADICIONAL: Precio mínimo de Stripe
            if (empty($request->price) || $request->price < 10) {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'price' => ['El precio mínimo para eventos de pago es $10.00 MXN']
                    ]
                ], 422);
            }
        }

        // Manejar la imagen principal (portada)
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

        // ⭐ Manejar imágenes adicionales de la galería (máximo 5)
        if ($request->hasFile('additional_images')) {
            $additionalImages = $request->file('additional_images');

            foreach ($additionalImages as $index => $image) {
                if ($index >= 5) break; // Máximo 5 imágenes adicionales

                $path = $image->store('event_images', 'public');

                EventImage::create([
                    'event_id' => $event->id,
                    'image_path' => $path,
                    'order' => $index + 1,
                ]);
            }

            Log::info('Additional images saved', [
                'event_id' => $event->id,
                'images_count' => count($additionalImages)
            ]);
        }

        $message = $event->status === 'publicado'
            ? '¡Evento publicado exitosamente! Ahora es visible en la página de eventos.'
            : '¡Borrador guardado exitosamente!';

        return response()->json([
            'success' => true,
            'message' => $message,
            'event' => $event,
            'redirect' => route('eventos')
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
            ->where('event_date', '>=', now()->startOfDay()) // NUEVA LÍNEA - Solo eventos futuros
            ->with(['user', 'confirmedInvitations', 'images']); // AGREGAR 'images'

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
                'organizer_user_id' => $event->user_id, // AGREGAR ESTA LÍNEA
                'date' => $event->event_date->format('Y-m-d') . 'T' . $event->event_time->format('H:i:s'),
                'location' => $event->location,
                'description' => $event->description,
                'attendees' => $event->confirmedInvitations->count(),
                'max_attendees' => $event->max_attendees,
                'payment_type' => $event->payment_type,
                'price' => $event->price,
                'image' => $event->event_image ? asset('storage/' . $event->event_image) : null,
                'gallery_images' => $event->images->map(function ($img) { // NUEVO
                    return asset('storage/' . $img->image_path);
                })->toArray(),
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

    //.............................................................. Otros métodos de eventos

    // Actualizar evento
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // Verificar que el usuario sea el creador
        if (Auth::id() !== $event->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para editar este evento'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'host_name' => 'required|string|max:255',
            'location' => 'required|string|max:500',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'type' => 'required|in:boda,cumpleanos,graduacion,corporativo,social,religioso,otro',
            'payment_type' => 'required|in:gratis,pago',
            'price' => 'required_if:payment_type,pago|nullable|numeric|min:10',
            'description' => 'required|string|max:2000',
            'max_attendees' => 'nullable|integer|min:1',
            'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'additional_images' => 'nullable|array|max:5',
        ], [
            'price.min' => 'El precio mínimo es de $10.00 MXN (requisito de Stripe)',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // ⭐ NUEVA VALIDACIÓN: Si cambia a evento de pago, verificar Stripe
        if ($request->payment_type === 'pago' && $event->payment_type === 'gratis') {
            // Solo si está CAMBIANDO de gratis a pago
            $user = Auth::user();
            if (empty($user->stripe_account_id) || empty($user->stripe_account_verified)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Para convertir el evento a pago, primero debes conectar tu cuenta de Stripe.',
                    'stripe_required' => true,
                    'redirect' => route('stripe.connect')
                ], 403);
            }
        }

        // ⭐ VALIDACIÓN: Si ya es de pago, verificar que siga teniendo Stripe activo
        $user = Auth::user();
        if ($request->payment_type === 'pago' && (empty($user->stripe_account_id) || empty($user->stripe_account_verified))) {
            return response()->json([
                'success' => false,
                'message' => 'Tu cuenta de Stripe no está activa. Conéctala nuevamente para eventos de pago.',
                'stripe_required' => true,
                'redirect' => route('stripe.connect')
            ], 403);
        }

        // Manejar nueva imagen principal si se subió
        $imagePath = $event->event_image;
        if ($request->hasFile('event_image')) {
            if ($event->event_image) {
                Storage::disk('public')->delete($event->event_image);
            }
            $imagePath = $request->file('event_image')->store('event_images', 'public');
        }

        // Actualizar el evento
        $event->update([
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
        ]);

        // Manejar imágenes adicionales
        if ($request->hasFile('additional_images')) {
            // Eliminar imágenes antiguas de la galería
            foreach ($event->images as $oldImage) {
                Storage::disk('public')->delete($oldImage->image_path);
                $oldImage->delete();
            }

            // Agregar nuevas imágenes
            $additionalImages = $request->file('additional_images');

            foreach ($additionalImages as $index => $image) {
                if ($index >= 5) break;

                $path = $image->store('event_images', 'public');

                EventImage::create([
                    'event_id' => $event->id,
                    'image_path' => $path,
                    'order' => $index + 1,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => '¡Evento actualizado exitosamente!',
        ]);
    }

    // Eliminar evento (mantener igual)
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if (Auth::id() !== $event->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para eliminar este evento'
            ], 403);
        }

        if ($event->event_image) {
            Storage::disk('public')->delete($event->event_image);
        }

        // NUEVO: Eliminar imágenes de la galería
        foreach ($event->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $invitations = $event->invitations;
        foreach ($invitations as $invitation) {
            if ($invitation->qr_image) {
                Storage::disk('public')->delete($invitation->qr_image);
            }
        }

        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Evento eliminado exitosamente'
        ]);
    }
}
