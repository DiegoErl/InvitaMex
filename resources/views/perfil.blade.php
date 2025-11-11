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
                    <a href="{{ route('eventos.create') }}" class="action-card primary">
                        <div class="action-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <h3>Crear Evento</h3>
                        <p>Diseña un evento único y genera invitaciones</p>
                    </a>

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
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')


    <script src="{{ asset('js/perfil.js') }}" defer></script>


</body>

</html>