<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ⭐ AGREGAR ESTA LÍNEA
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\QRValidationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StripeConnectController;
use App\Http\Controllers\EarningsController;
use App\Http\Controllers\AdminController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SocialAuthController;

// ============================================
// RUTAS PÚBLICAS (Sin autenticación)
// ============================================

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/sobre', function () {
    return view('sobre');
})->name('sobre');

Route::get('/contacto', function () {
    return view('contacto');
})->name('contacto');

Route::get('/politica-privacidad', function () {
    return view('politica-privacidad');
})->name('politica-privacidad');

Route::get('/terminos-condiciones', function () {
    return view('terminos-condiciones');
})->name('terminos-condiciones');

// Rutas de Google OAuth
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])
    ->name('auth.google');

Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])
    ->name('auth.google.callback');

// Rutas de recuperación de contraseña
Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [PasswordResetController::class, 'reset'])
    ->middleware('guest')
    ->name('password.update');

// Redirección inteligente para crear invitación
Route::get('/crear-invitacion', function () {
    if (Auth::check()) {
        // Si está autenticado, ir al perfil
        return redirect()->route('perfil');
    }
    // Si no está autenticado, ir al login
    return redirect()->route('login');
})->name('crear.invitacion');

// WEBHOOKS 
Route::post('/webhook/stripe', [PaymentController::class, 'webhook'])
    ->name('stripe.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::post('/webhook/stripe-connect', [StripeConnectController::class, 'webhook'])
    ->name('stripe.connect.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// ============================================
// RUTAS DE AUTENTICACIÓN (Guest)
// ============================================

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout')
    ->withoutMiddleware([\App\Http\Middleware\CheckUserActive::class]);

// RUTA DE CUENTA SUSPENDIDA (sin middleware para evitar loops)
Route::get('/account-suspended', function () {
    // Si no hay usuario autenticado, redirigir a home
    if (!Auth::check()) {
        return redirect()->route('home');
    }

    // Si el usuario está activo, redirigir a perfil
    if (Auth::user()->isActive()) {
        return redirect()->route('perfil');
    }

    // Mostrar vista de suspensión
    return view('account-suspended');
})->name('account.suspended')->withoutMiddleware([\App\Http\Middleware\CheckUserActive::class]);

// ============================================
// RUTAS CON AUTENTICACIÓN (Sin verificar email)
// ============================================

Route::middleware(['auth', 'prevent-back'])->group(function () {
    // PERFIL - Accesible SIN verificar email
    Route::get('/perfil', function () {
        return view('perfil');
    })->name('perfil');

    Route::get('/perfil/editar', [ProfileController::class, 'edit'])->name('perfil.edit');
    Route::post('/perfil/actualizar', [ProfileController::class, 'update'])->name('perfil.update');
    Route::delete('/perfil/foto', [ProfileController::class, 'deletePhoto'])->name('perfil.deletePhoto');

    // VERIFICACIÓN DE EMAIL
    Route::get('/email/verify', function () {
        return redirect()->route('perfil');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('perfil')->with('verified', true);
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        try {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('message', 'Correo de verificación reenviado!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar el correo. Intenta más tarde.');
        }
    })->name('verification.send');

    // STRIPE CONNECT
    Route::get('/stripe/connect', [StripeConnectController::class, 'connect'])
        ->name('stripe.connect');

    Route::get('/stripe/return', [StripeConnectController::class, 'return'])
        ->name('stripe.return');

    Route::get('/stripe/dashboard', [StripeConnectController::class, 'dashboard'])
        ->name('stripe.dashboard');

    Route::post('/stripe/disconnect', [StripeConnectController::class, 'disconnect'])
        ->name('stripe.disconnect');
});

// ============================================
// RUTAS CON AUTENTICACIÓN Y EMAIL VERIFICADO
// ============================================

Route::middleware(['auth', 'verified', 'prevent-back'])->group(function () {
    // EVENTOS - Solo usuarios verificados
    Route::get('/eventos/crear', [EventController::class, 'create'])->name('eventos.create');
    Route::post('/eventos', [EventController::class, 'store'])->name('eventos.store');
    Route::put('/eventos/{id}', [EventController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{id}', [EventController::class, 'destroy'])->name('eventos.destroy');

    // INVITACIONES - Solo usuarios verificados
    Route::post('/eventos/{id}/solicitar-invitacion', [EventController::class, 'requestInvitation'])->name('eventos.requestInvitation');
    Route::get('/mis-invitaciones', [EventController::class, 'myInvitations'])->name('mis-invitaciones');

    // NUEVAS RUTAS RSVP
    Route::post('/invitations/{id}/confirm-rsvp', [EventController::class, 'confirmRsvp'])->name('invitations.confirmRsvp');
    Route::post('/invitations/{id}/reject-rsvp', [EventController::class, 'rejectRsvp'])->name('invitations.rejectRsvp');
    Route::get('/eventos/{id}/attendees', [EventController::class, 'showAttendees'])->name('eventos.attendees');

    Route::get('/eventos/{id}/stats', [QRValidationController::class, 'getEventStats'])->name('eventos.stats');

    // VALIDACIÓN DE QR - Solo organizadores verificados
    Route::get('/eventos/{id}/escanear', [QRValidationController::class, 'showScanner'])->name('eventos.scanner');
    Route::get('/eventos/{id}/historial', [QRValidationController::class, 'eventHistory'])->name('eventos.history');
    Route::post('/api/validate-qr', [QRValidationController::class, 'validateQR']);

    // PAGOS - Cualquier usuario autenticado puede pagar
    // La validación de Stripe del ORGANIZADOR está en el PaymentController
    Route::post('/eventos/{id}/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);
    Route::post('/eventos/{id}/confirm-payment', [PaymentController::class, 'confirmPayment']);

    // PANEL DE INGRESOS - Requiere Stripe
    Route::get('/mis-ingresos', [EarningsController::class, 'index'])
        ->middleware('verified.stripe')
        ->name('earnings.index');
});

// ============================================
// RUTAS DE ADMINISTRADOR
// ============================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Gestión de usuarios
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{id}/promote', [AdminController::class, 'promoteToAdmin'])->name('users.promote');
    Route::post('/users/{id}/demote', [AdminController::class, 'demoteToUser'])->name('users.demote');
    Route::post('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');

    // Gestión de eventos
    Route::get('/events', [AdminController::class, 'events'])->name('events');
    Route::post('/events/{id}/approve', [AdminController::class, 'approveEvent'])->name('events.approve');
    Route::post('/events/{id}/reject', [AdminController::class, 'rejectEvent'])->name('events.reject');
    Route::delete('/events/{id}', [AdminController::class, 'deleteEvent'])->name('events.delete');
});

// ============================================
// RUTAS PÚBLICAS DE EVENTOS - AL FINAL
// ============================================

Route::get('/eventos', [EventController::class, 'index'])->name('eventos');
Route::get('/eventos/{id}', [EventController::class, 'show'])->name('eventos.show');
Route::get('/api/eventos', [EventController::class, 'getEventsApi'])->name('api.eventos');

// Después de la ruta de eventos show
Route::get('/eventos/{id}/invitacion', [EventController::class, 'showInvitation'])->name('eventos.invitation');
