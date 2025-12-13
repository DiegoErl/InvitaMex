<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;

class StripeConnectController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function connect()
    {
        $user = Auth::user();

        try {
            // Si ya tiene cuenta, verificar su estado
            if ($user->stripe_account_id) {
                $account = Account::retrieve($user->stripe_account_id);

                if ($account->charges_enabled && $account->details_submitted) {
                    return redirect()->route('perfil')
                        ->with('info', 'Tu cuenta de Stripe ya está conectada y activa');
                }
                
                // Si tiene cuenta pero no está completa, crear nuevo link
                $accountLink = AccountLink::create([
                    'account' => $account->id,
                    'refresh_url' => route('stripe.connect'),
                    'return_url' => route('stripe.return'),
                    'type' => 'account_onboarding',
                ]);

                return redirect($accountLink->url);
            }

            // Crear nueva cuenta Express (CAMBIO CRÍTICO)
            $account = Account::create([
                'type' => 'express', // ⭐ ERA 'standard', AHORA 'express'
                'country' => 'MX', // ⭐ ESPECIFICAR PAÍS
                'email' => $user->email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
                'business_type' => 'individual',
                'metadata' => [
                    'user_id' => $user->id,
                    'platform' => 'InvitaCleth'
                ]
            ]);

            $user->stripe_account_id = $account->id;
            $user->save();

            Log::info('Stripe account created', [
                'user_id' => $user->id,
                'stripe_account_id' => $account->id
            ]);

            // Crear link de onboarding
            $accountLink = AccountLink::create([
                'account' => $account->id,
                'refresh_url' => route('stripe.connect'),
                'return_url' => route('stripe.return'),
                'type' => 'account_onboarding',
            ]);

            return redirect($accountLink->url);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe API Error', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            
            return redirect()->route('perfil')
                ->with('error', 'Error de Stripe: ' . $e->getMessage());
                
        } catch (\Exception $e) {
            Log::error('General Error', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            
            return redirect()->route('perfil')
                ->with('error', 'Error al conectar. Intenta nuevamente.');
        }
    }

    public function return(Request $request)
    {
        $user = Auth::user();

        if (!$user->stripe_account_id) {
            return redirect()->route('perfil')
                ->with('error', 'No se encontró cuenta de Stripe');
        }

        try {
            $account = Account::retrieve($user->stripe_account_id);

            Log::info('Stripe return', [
                'user_id' => $user->id,
                'charges_enabled' => $account->charges_enabled,
                'details_submitted' => $account->details_submitted
            ]);

            if ($account->charges_enabled && $account->details_submitted) {
                $user->stripe_account_verified = true;
                $user->save();

                return redirect()->route('perfil')
                    ->with('verified', '¡Cuenta conectada! Ya puedes crear eventos de pago.');
            } else {
                return redirect()->route('perfil')
                    ->with('warning', 'Proceso incompleto. Haz clic nuevamente en "Conectar con Stripe".');
            }
        } catch (\Exception $e) {
            Log::error('Error in return', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            
            return redirect()->route('perfil')
                ->with('error', 'Error al verificar cuenta');
        }
    }

    public function dashboard()
    {
        $user = Auth::user();

        if (!$user->stripe_account_id) {
            return redirect()->route('stripe.connect')
                ->with('error', 'Primero conecta tu cuenta');
        }

        try {
            $account = Account::retrieve($user->stripe_account_id);
            $isTestMode = str_starts_with(config('services.stripe.secret'), 'sk_test_');

            if ($isTestMode) {
                return view('stripe.dashboard', [
                    'account' => $account,
                    'user' => $user,
                    'testMode' => true
                ]);
            } else {
                $loginLink = Account::createLoginLink($user->stripe_account_id);
                return redirect($loginLink->url);
            }
        } catch (\Exception $e) {
            Log::error('Error dashboard', ['error' => $e->getMessage()]);
            return redirect()->route('perfil')->with('error', 'Error al acceder');
        }
    }

    public function disconnect()
    {
        $user = Auth::user();

        if ($user->stripe_account_id) {
            Log::info('Disconnecting', [
                'user_id' => $user->id,
                'stripe_account_id' => $user->stripe_account_id
            ]);

            $user->stripe_account_id = null;
            $user->stripe_account_verified = false;
            $user->save();
        }

        return redirect()->route('perfil')
            ->with('info', 'Cuenta desconectada correctamente');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );

            Log::info('Webhook received', ['type' => $event->type]);

            switch ($event->type) {
                case 'account.updated':
                    $this->handleAccountUpdated($event->data->object);
                    break;
                    
                case 'account.application.deauthorized':
                    $this->handleAccountDeauthorized($event->data->object);
                    break;
            }

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            Log::error('Webhook error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    private function handleAccountUpdated($account)
    {
        $user = \App\Models\User::where('stripe_account_id', $account->id)->first();
        
        if ($user) {
            $user->stripe_account_verified = ($account->charges_enabled && $account->details_submitted);
            $user->save();
        }
    }

    private function handleAccountDeauthorized($account)
    {
        $user = \App\Models\User::where('stripe_account_id', $account->id)->first();
        
        if ($user) {
            $user->stripe_account_id = null;
            $user->stripe_account_verified = false;
            $user->save();
        }
    }
}