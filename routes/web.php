<?php
//ANTES DE LA CORRECCION
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\QRValidationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StripeConnectController;
use App\Http\Controllers\EarningsController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

// ⭐ WEBHOOKS (ANTES de cualquier middleware, al inicio del archivo)
Route::post('/webhook/stripe', [PaymentController::class, 'webhook'])
    ->name('stripe.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::post('/webhook/stripe-connect', [StripeConnectController::class, 'webhook'])
    ->name('stripe.connect.webhook')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// ⭐ IMPORTANTE: Estas rutas públicas van AL FINAL
// NO ponerlas aquí arriba o capturarán /eventos/crear

// ============================================
// RUTAS DE AUTENTICACIÓN (Guest)
// ============================================

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

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
    // ⭐ DENTRO DE: Route::middleware(['auth', 'prevent-back'])->group(function () {
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
    // EVENTOS - Solo usuarios verificados (ANTES de las rutas públicas)
    // ⭐ EVENTOS GRATUITOS - No requieren Stripe
    Route::get('/eventos/crear', [EventController::class, 'create'])->name('eventos.create');
    
    // ⭐ GUARDAR EVENTO - Validación de Stripe en el Controller
    Route::post('/eventos', [EventController::class, 'store'])->name('eventos.store');
    
    Route::put('/eventos/{id}', [EventController::class, 'update'])->name('eventos.update');
    Route::delete('/eventos/{id}', [EventController::class, 'destroy'])->name('eventos.destroy');

    // INVITACIONES - Solo usuarios verificados
    Route::post('/eventos/{id}/solicitar-invitacion', [EventController::class, 'requestInvitation'])->name('eventos.requestInvitation');
    Route::get('/mis-invitaciones', [EventController::class, 'myInvitations'])->name('mis-invitaciones');

    // VALIDACIÓN DE QR - Solo organizadores verificados
    Route::get('/eventos/{id}/escanear', [QRValidationController::class, 'showScanner'])->name('eventos.scanner');
    Route::get('/eventos/{id}/historial', [QRValidationController::class, 'eventHistory'])->name('eventos.history');
    Route::post('/api/validate-qr', [QRValidationController::class, 'validateQR']);

    // ⭐ PAGOS - Estos endpoints SÍ requieren Stripe conectado
    Route::middleware('verified.stripe')->group(function () {
        Route::post('/eventos/{id}/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);
        Route::post('/eventos/{id}/confirm-payment', [PaymentController::class, 'confirmPayment']);
    });

    // ⭐ PANEL DE INGRESOS - Requiere Stripe
    Route::get('/mis-ingresos', [EarningsController::class, 'index'])
        ->middleware('verified.stripe')
        ->name('earnings.index');
});

// ============================================
// ⭐ RUTAS PÚBLICAS DE EVENTOS - AL FINAL
// ============================================

Route::get('/eventos', [EventController::class, 'index'])->name('eventos');
Route::get('/eventos/{id}', [EventController::class, 'show'])->name('eventos.show');
Route::get('/api/eventos', [EventController::class, 'getEventsApi'])->name('api.eventos');
