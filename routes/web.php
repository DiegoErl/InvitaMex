<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\QRValidationController;

// Ruta principal
Route::get('/', function () {
    return view('index');
});

// Rutas públicas
Route::get('/sobre', function () {
    return view('sobre');
})->name('sobre');

Route::get('/contacto', function () {
    return view('contacto');
})->name('contacto');

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth', 'prevent-back'])->group(function () {
    // Perfil
    Route::get('/perfil', function () {
        return view('perfil');
    })->name('perfil');
    
    Route::get('/perfil/editar', [ProfileController::class, 'edit'])->name('perfil.edit');
    Route::post('/perfil/actualizar', [ProfileController::class, 'update'])->name('perfil.update');
    Route::delete('/perfil/foto', [ProfileController::class, 'deletePhoto'])->name('perfil.deletePhoto');

    // Eventos - Rutas protegidas (crear eventos y solicitar invitaciones)
    Route::get('/eventos/crear', [EventController::class, 'create'])->name('eventos.create');
    Route::post('/eventos', [EventController::class, 'store'])->name('eventos.store');
    Route::post('/eventos/{id}/solicitar-invitacion', [EventController::class, 'requestInvitation'])->name('eventos.requestInvitation');
    Route::get('/mis-invitaciones', [EventController::class, 'myInvitations'])->name('mis-invitaciones');

    // Validación de QR - AGREGAR ESTAS LÍNEAS
    Route::get('/eventos/{id}/escanear', [QRValidationController::class, 'showScanner'])->name('eventos.scanner');
    Route::get('/eventos/{id}/historial', [QRValidationController::class, 'eventHistory'])->name('eventos.history');
});

// Rutas públicas de eventos (FUERA del middleware, cualquiera puede verlas)
Route::get('/eventos', [EventController::class, 'index'])->name('eventos');
Route::get('/eventos/{id}', [EventController::class, 'show'])->name('eventos.show');

// API de eventos
// API para obtener eventos (pública)
Route::get('/api/eventos', [EventController::class, 'getEventsApi'])->name('api.eventos');

// API para validar QR - AGREGAR ESTA LÍNEA
Route::post('/api/validate-qr', [QRValidationController::class, 'validateQR']);