<header class="header" id="header">
        <nav class="navbar">
            <a href="{{ url('/') }}" class="logo-container">
                <div class="logo">IM</div>
                <h1 class="brand-name">InvitaMex</h1>
            </a>

            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-box" placeholder="Buscar eventos...">
            </div>

            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('sobre') }}" class="nav-link {{ request()->routeIs('sobre') ? 'active' : '' }}" >
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
                    <a href="{{ route('eventos') }}" class="nav-link {{ request()->routeIs('eventos') ? 'active' : '' }}" >
                        <i class="fas fa-calendar-alt"></i>
                        Eventos
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('login') }}" class="btn-login">
                        <i class="fas fa-user"></i>
                        Iniciar Sesión
                    </a>
                </li>
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