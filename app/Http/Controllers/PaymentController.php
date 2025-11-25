<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Payment;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // Crear Payment Intent para el modal
    public function createPaymentIntent(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        
        // Verificar que el evento sea de pago
        if ($event->payment_type !== 'pago') {
            return response()->json(['error' => 'Este evento es gratuito'], 400);
        }

        // Verificar que el usuario NO sea el organizador
        if (Auth::id() === $event->user_id) {
            return response()->json(['error' => 'No puedes asistir a tu propio evento'], 403);
        }

        // Verificar si ya tiene invitación
        $existingInvitation = Invitation::where('event_id', $eventId)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingInvitation) {
            return response()->json(['error' => 'Ya tienes una invitación para este evento'], 400);
        }

        // Verificar capacidad
        if ($event->max_attendees) {
            $confirmedCount = $event->confirmedInvitations()->count();
            if ($confirmedCount >= $event->max_attendees) {
                return response()->json(['error' => 'El evento ha alcanzado su capacidad máxima'], 400);
            }
        }

        try {
            $amount = $event->price * 100; // Stripe usa centavos

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'mxn',
                'description' => 'Invitación a: ' . $event->title,
                'metadata' => [
                    'event_id' => $event->id,
                    'user_id' => Auth::id(),
                    'organizer_id' => $event->user_id,
                ],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'publishableKey' => config('services.stripe.key'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Confirmar pago exitoso
    public function confirmPayment(Request $request, $eventId)
    {
        $request->validate([
            'payment_intent_id' => 'required|string'
        ]);

        try {
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($paymentIntent->status === 'succeeded') {
                $event = Event::findOrFail($eventId);
                $amount = $paymentIntent->amount / 100;
                $platformFee = $amount * 0.10; // 10% para ti
                $organizerAmount = $amount * 0.90; // 90% para organizador

                // Crear registro de pago
                $payment = Payment::create([
                    'user_id' => Auth::id(),
                    'event_id' => $eventId,
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'amount' => $amount,
                    'platform_fee' => $platformFee,
                    'organizer_amount' => $organizerAmount,
                    'status' => 'succeeded',
                    'paid_at' => now(),
                ]);

                // Generar código QR único
                $qrCode = Str::uuid()->toString();
                
                $invitation = Invitation::create([
                    'event_id' => $eventId,
                    'user_id' => Auth::id(),
                    'qr_code' => $qrCode,
                    'status' => 'confirmado',
                    'confirmed_at' => now(),
                ]);

                // Generar imagen QR
                try {
                    $directory = storage_path('app/public/qr_codes');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }

                    $options = new \chillerlan\QRCode\QROptions([
                        'outputType' => \chillerlan\QRCode\QRCode::OUTPUT_IMAGE_PNG,
                        'eccLevel' => \chillerlan\QRCode\QRCode::ECC_H,
                        'scale' => 10,
                        'imageBase64' => false,
                    ]);

                    $qrcode = new \chillerlan\QRCode\QRCode($options);
                    $qrImageData = $qrcode->render($qrCode);

                    $filename = $qrCode . '.png';
                    $qrPath = 'qr_codes/' . $filename;
                    $fullPath = storage_path('app/public/' . $qrPath);
                    
                    file_put_contents($fullPath, $qrImageData);
                    
                    $invitation->qr_image = $qrPath;
                    $invitation->save();

                    // Asociar invitación con el pago
                    $payment->invitation_id = $invitation->id;
                    $payment->save();
                } catch (\Exception $e) {
                    Log::error('Error generando QR: ' . $e->getMessage());
                }

                return response()->json([
                    'success' => true,
                    'message' => '¡Pago exitoso! Tu invitación ha sido generada',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'El pago no se completó'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}