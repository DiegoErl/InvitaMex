<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Mi Perfil - InvitaCleth</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/perfil.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>

<body>
    @include('partials.header')

    <div class="container">
        {{-- ⭐ BANNER DE VERIFICACIÓN --}}
        @if (!Auth::user()->hasVerifiedEmail())
        <div class="verification-banner">
            <div class="verification-content">
                <div class="verification-icon">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <div class="verification-info">
                    <h3>
                        <i class="fas fa-exclamation-triangle"></i>
                        Tu correo electrónico no está verificado
                    </h3>
                    <p>
                        Hemos enviado un correo de verificación a <strong>{{ Auth::user()->email }}</strong>.
                        Revisa tu bandeja de entrada y haz click en el enlace para activar todas las funciones.
                    </p>
                    <div class="verification-restrictions">
                        <strong>Funciones bloqueadas hasta que verifiques:</strong>
                        <ul>
                            <li><i class="fas fa-times-circle"></i> Crear eventos</li>
                            <li><i class="fas fa-times-circle"></i> Editar eventos</li>
                            <li><i class="fas fa-times-circle"></i> Confirmar asistencia a eventos</li>
                        </ul>
                    </div>
                    <form method="POST" action="{{ route('verification.send') }}" style="margin-top: 1rem;">
                        @csrf
                        <button type="submit" class="btn btn-verify">
                            <i class="fas fa-paper-plane"></i>
                            Reenviar Correo de Verificación
                        </button>
                    </form>

                    @if (session('message'))
                    <div class="verification-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('message') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- ⭐ BANNER DE STRIPE CONNECT --}}
        @if (Auth::user()->hasVerifiedEmail() && !Auth::user()->hasStripeAccount())
        <div class="stripe-connect-banner">
            <div class="stripe-content">
                <div class="stripe-icon">
                    <i class="fab fa-stripe"></i>
                </div>
                <div class="stripe-info">
                    <h3>
                        <i class="fas fa-credit-card"></i>
                        Conecta tu cuenta de Stripe para recibir pagos
                    </h3>
                    <p>
                        Para poder crear eventos de pago y recibir el dinero directamente en tu cuenta, necesitas conectar Stripe.
                    </p>
                    <ul style="margin: 1rem 0; padding-left: 1.5rem;">
                        <li>✅ Recibe el 90% de cada venta</li>
                        <li>✅ Pagos directos a tu cuenta bancaria</li>
                        <li>✅ Proceso 100% seguro con Stripe</li>
                        <li>✅ Retiros automáticos cada semana</li>
                    </ul>
                    <a href="{{ route('stripe.connect') }}" class="btn btn-stripe">
                        <i class="fab fa-stripe"></i>
                        Conectar con Stripe
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- ⭐ INFO DE CUENTA STRIPE CONECTADA --}}
        @if (Auth::user()->hasStripeAccount())
        <div class="stripe-connected-banner">
            <i class="fas fa-check-circle"></i>
            <strong>Cuenta de Stripe conectada</strong>
            Ya puedes crear eventos de pago y recibir dinero.
            <div style="margin-top: 0.5rem;">
                <a href="{{ route('stripe.dashboard') }}" class="btn-link">
                    <i class="fas fa-chart-line"></i> Ver Dashboard
                </a>
                |
                <form method="POST" action="{{ route('stripe.disconnect') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-link" style="color: #dc3545;">
                        <i class="fas fa-unlink"></i> Desconectar
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- ⭐ MENSAJE DE VERIFICACIÓN EXITOSA --}}
        @if (session('verified'))
        <div class="verification-success-banner">
            <i class="fas fa-check-circle"></i>
            <strong>¡Correo verificado exitosamente!</strong>
            Ahora tienes acceso completo a todas las funciones.
        </div>
        @endif

        {{-- ⭐ MENSAJE DE REGISTRO COMPLETADO --}}
        @if (session('registration_complete'))
        <div class="registration-complete-banner">
            <i class="fas fa-user-check"></i>
            <strong>¡Registro completado!</strong>
            Revisa tu correo para verificar tu cuenta y acceder a todas las funciones.
        </div>
        @endif

        <div class="profile-page-container">
            <div class="profile-layout">
                <!-- Columna Izquierda - Información del Perfil -->
                <div class="profile-sidebar">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Foto de perfil">
                            @else
                            {{ strtoupper(substr(Auth::user()->firstName, 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastName, 0, 1)) }}
                            @endif
                        </div>
                        <h1>{{ Auth::user()->firstName }} {{ Auth::user()->lastName }}</h1>
                        <p class="profile-email">{{ Auth::user()->email }}</p>
                    </div>

                    <div class="profile-info">
                        <div class="info-row">
                            <span class="info-label">Nombre completo</span>
                            <span class="info-value">{{ Auth::user()->firstName }} {{ Auth::user()->lastName }}</span>
                        </div>
                        <h1>
                            {{ Auth::user()->firstName }} {{ Auth::user()->lastName }}
                            @if(Auth::user()->isAdmin())
                            <!-- <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                                         color: white; 
                                         padding: 4px 12px; 
                                         border-radius: 20px; 
                                         font-size: 0.8rem; 
                                         font-weight: 600;
                                         margin-left: 10px;">
                                <i class="fas fa-crown"></i> ADMIN
                            </span> -->
                            @endif
                        </h1>
                        <div class="info-row">
                            <span class="info-label">Correo electrónico</span>
                            <span class="info-value">{{ Auth::user()->email }}</span>
                        </div>
                        @if(Auth::user()->phone)
                        <div class="info-row">
                            <span class="info-label">Teléfono</span>
                            <span class="info-value">{{ Auth::user()->phone }}</span>
                        </div>
                        @endif
                        @if(Auth::user()->country)
                        <div class="info-row">
                            <span class="info-label">País</span>
                            <span class="info-value">
                                @switch(Auth::user()->country)
                                @case('MX') México @break
                                @case('US') Estados Unidos @break
                                @case('CA') Canadá @break
                                @case('ES') España @break
                                @case('AR') Argentina @break
                                @case('CO') Colombia @break
                                @case('PE') Perú @break
                                @case('CL') Chile @break
                                @default {{ Auth::user()->country }}
                                @endswitch
                            </span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">Miembro desde</span>
                            <span class="info-value">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="logout-section">
                        <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                            @csrf
                            <button type="submit" class="btn btn-logout">
                                <i class="fas fa-sign-out-alt"></i>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Columna Derecha - Acciones -->
                <div class="profile-actions">
                    <div class="welcome-banner">
                        <h2>¡Bienvenido de vuelta, {{ Auth::user()->firstName }}! 👋</h2>
                        <p>¿Qué te gustaría hacer hoy?</p>
                    </div>

                    <div class="actions-grid">
                        @if (Auth::user()->hasVerifiedEmail())
                        <a href="{{ route('eventos.create') }}" class="action-card primary">
                            <div class="action-icon">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <h3>Crear Nuevo Evento</h3>
                            <p>Diseña un evento unico y genera invitaciones</p>
                        </a>
                        @else
                        <button class="btn btn-disabled" disabled title="Verifica tu correo para crear eventos">
                            <i class="fas fa-lock"></i>
                            Crear Nuevo Evento (Bloqueado)
                        </button>
                        @endif

                        <a href="{{ route('eventos') }}" class="action-card secondary">
                            <div class="action-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h3>Ver Eventos</h3>
                            <p>Explora todos los eventos disponibles</p>
                        </a>

                        <a href="{{ route('perfil.edit') }}" class="action-card primary">
                            <div class="action-icon">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <h3>Editar Perfil</h3>
                            <p>Actualiza tu información personal</p>
                        </a>

                        <a href="{{ route('mis-invitaciones') }}" class="action-card secondary">
                            <div class="action-icon">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <h3>Mis Invitaciones y Eventos</h3>
                            <p>Gestiona invitaciones y escanea QR de asistentes</p>
                        </a>

                        @if (Auth::user()->hasStripeAccount())
                        <a href="{{ route('earnings.index') }}" class="action-card primary">
                            <div class="action-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <h3>Mis Ingresos</h3>
                            <p>Ver ganancias y estadísticas</p>
                        </a>
                        @endif

                        @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="action-card primary">
                            <div class="action-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h3>Panel de Administración</h3>
                            <p>Gestiona usuarios y eventos del sistema</p>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @include('partials.footer')


        <script src="{{ asset('js/perfil.js') }}" defer></script>


</body>

</html>