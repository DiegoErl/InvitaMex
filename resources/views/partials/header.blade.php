<header class="header" id="header">
    <nav class="navbar">
        <a href="{{ url('/') }}" class="logo-container">
            <div class="logo">IC</div>
            <h1 class="brand-name">InvitaCleth</h1>
        </a>

        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-box" placeholder="Buscar eventos...">
        </div>

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
                        <li>
                            <a href="{{ route('perfil') }}">
                                <i class="fas fa-user"></i>
                                Mi Perfil
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('perfil.edit') }}">
                                <i class="fas fa-edit"></i>
                                Editar Perfil
                            </a>
                        </li>
                        <li>
                            <a href="#" onclick="alert('Próximamente')">
                                <i class="fas fa-calendar-check"></i>
                                Mis Invitaciones
                            </a>
                        </li>
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
</header>

<style>
    /* Estilos para el menú de perfil */
    .profile-dropdown {
        position: relative;
    }

    .profile-trigger {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        transition: all 0.3s ease;
    }

    .profile-trigger:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .profile-photo {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid white;
    }

    .profile-avatar-small {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.9rem;
        border: 2px solid white;
    }

    .profile-name {
        color: white;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .profile-trigger i.fa-chevron-down {
        font-size: 0.7rem;
        transition: transform 0.3s ease;
    }

    .profile-trigger.active i.fa-chevron-down {
        transform: rotate(180deg);
    }

    .profile-menu {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        min-width: 280px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .profile-menu.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .profile-menu-header {
        padding: 1.5rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .profile-menu-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .profile-menu-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .profile-menu-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        overflow: hidden;
    }

    .profile-menu-info strong {
        color: #333;
        font-size: 1rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .profile-menu-info small {
        color: #666;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .profile-menu-list {
        list-style: none;
        padding: 0.5rem 0;
        margin: 0;
    }

    .profile-menu-list li a,
    .profile-menu-list li button {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.5rem;
        color: #333;
        text-decoration: none;
        transition: all 0.2s ease;
        width: 100%;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
        text-align: left;
    }

    .profile-menu-list li a:hover,
    .profile-menu-list li button:hover {
        background: #f8f9fa;
    }

    .profile-menu-list li a i,
    .profile-menu-list li button i {
        width: 20px;
        color: #667eea;
    }

    .profile-menu-list li.divider {
        height: 1px;
        background: #f0f0f0;
        margin: 0.5rem 0;
    }

    .profile-menu-list li:last-child a,
    .profile-menu-list li:last-child button {
        color: #dc3545;
    }

    .profile-menu-list li:last-child i {
        color: #dc3545;
    }

    @media (max-width: 768px) {
        .profile-dropdown {
            display: none;
        }
    }
</style>

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