<header class="header" id="header">
    <nav class="navbar">
        <a href="{{ url('/') }}" class="logo-container">
            <img src="{{ asset('img/logo.png') }}" alt="InvitaCleth" class="logo-img">
            <h1 class="brand-name">InvitaCleth</h1>
        </a>


        <!-- <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-box" placeholder="Buscar eventos...">
        </div> -->

        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('sobre') }}" class="nav-link {{ request()->routeIs('sobre') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    Sobre Nosotros
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('contacto') }}" class="nav-link {{ request()->routeIs('contacto') ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i>
                    Contacto
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('eventos') }}" class="nav-link {{ request()->routeIs('eventos') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    Eventos
                </a>
            </li>

            @auth
            <!-- Usuario autenticado - Mostrar menú de perfil -->
            <li class="nav-item profile-dropdown">
                <button class="profile-trigger" id="profileTrigger">
                    @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Perfil" class="profile-photo">
                    @else
                    <div class="profile-avatar-small">
                        {{ strtoupper(substr(Auth::user()->firstName, 0, 1)) }}
                    </div>
                    @endif
                    <span class="profile-name">{{ Auth::user()->firstName }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>

                <div class="profile-menu" id="profileMenu">
                    <div class="profile-menu-header">
                        <div class="profile-menu-avatar">
                            @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Perfil">
                            @else
                            {{ strtoupper(substr(Auth::user()->firstName, 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastName, 0, 1)) }}
                            @endif
                        </div>
                        <div class="profile-menu-info">
                            <strong>{{ Auth::user()->firstName }} {{ Auth::user()->lastName }}</strong>
                            <small>{{ Auth::user()->email }}</small>
                        </div>
                    </div>
                    <ul class="profile-menu-list">
                        @auth
                        <li><a href="{{ route('perfil') }}"><i class="fas fa-user"></i> Mi Perfil</a></li>
                        <li><a href="{{ route('mis-invitaciones') }}"><i class="fas fa-ticket-alt"></i> Mis Invitaciones</a></li>

                        {{-- ⭐ AGREGAR ESTE ENLACE --}}
                        @if(Auth::user()->hasStripeAccount())
                        <li><a href="{{ route('earnings.index') }}"><i class="fas fa-dollar-sign"></i> Mis Ingresos</a></li>
                        @endif

                        <li><a href="{{ route('eventos.create') }}"><i class="fas fa-plus"></i> Crear Evento</a></li>
                        @endauth
                        <li class="divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" id="logoutFormHeader">
                                @csrf
                                <button type="submit">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </li>
            @else
            <!-- Usuario no autenticado - Mostrar botón de login -->
            <li class="nav-item">
                <a href="{{ route('login') }}" class="btn-login">
                    <i class="fas fa-user"></i>
                    Iniciar Sesión
                </a>
            </li>
            @endauth
        </ul>

        <div class="mobile-menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div class="mobile-menu">
            <ul class="mobile-nav-menu">
                <li><a href="{{ route('sobre') }}" class="nav-link"><i class="fas fa-question-circle"></i> Sobre Nosotros</a></li>
                <li><a href="{{ route('contacto') }}" class="nav-link"><i class="fas fa-envelope"></i> Contacto</a></li>
                <li><a href="{{ route('eventos') }}" class="nav-link"><i class="fas fa-calendar-alt"></i> Eventos</a></li>
                @auth
                <li><a href="{{ route('perfil') }}" class="nav-link"><i class="fas fa-user"></i> Mi Perfil</a></li>
                <li><a href="{{ route('perfil.edit') }}" class="nav-link"><i class="fas fa-edit"></i> Editar Perfil</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="padding: 0;">
                        @csrf
                        <button type="submit" class="nav-link" style="width: 100%; text-align: left; background: none; border: none; color: inherit; cursor: pointer; padding: 0.8rem 1.5rem;">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </button>
                    </form>
                </li>
                @else
                <li><a href="{{ route('login') }}" class="nav-link"><i class="fas fa-user"></i> Iniciar Sesión</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    @auth
    @if (!Auth::user()->hasVerifiedEmail())
    <div style="background: #fff3cd; color: #856404; padding: 1rem; text-align: center; border-bottom: 2px solid #ffc107;">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Tu correo no está verificado.</strong>
        Tu acceso está limitado hasta que verifiques tu email.
        <a href="{{ route('verification.notice') }}" style="color: #667eea; text-decoration: underline; font-weight: bold;">
            Verificar ahora
        </a>
    </div>
    @endif
    @endauth
</header>



<script>
    // Script para el menú desplegable de perfil
    document.addEventListener('DOMContentLoaded', function() {
        const profileTrigger = document.getElementById('profileTrigger');
        const profileMenu = document.getElementById('profileMenu');

        if (profileTrigger && profileMenu) {
            profileTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                profileTrigger.classList.toggle('active');
                profileMenu.classList.toggle('active');
            });

            // Cerrar al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (!profileMenu.contains(e.target) && !profileTrigger.contains(e.target)) {
                    profileTrigger.classList.remove('active');
                    profileMenu.classList.remove('active');
                }
            });
        }
    });
</script>
</ul>

<div class="mobile-menu-toggle">
    <span></span>
    <span></span>
    <span></span>
</div>

<div class="mobile-menu">
    <ul class="mobile-nav-menu">
        <li><a href="#about" class="nav-link"><i class="fas fa-question-circle"></i> Sobre Nosotros</a></li>
        <li><a href="#contact" class="nav-link"><i class="fas fa-envelope"></i> Contacto</a></li>
        <li><a href="#events" class="nav-link"><i class="fas fa-calendar-alt"></i> Eventos</a></li>
        <li><a href="#login" class="nav-link"><i class="fas fa-user"></i> Iniciar Sesión</a></li>
    </ul>
</div>
</nav>
</header>