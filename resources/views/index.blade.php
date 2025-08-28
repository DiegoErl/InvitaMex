<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventosPro - Invitaciones Digitales Ecológicas</title>

    <!-- Estilos externos -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    <!-- Librerías externas -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <header class="header" id="header">
        <nav class="navbar">
            <div class="logo-container">
                <div class="logo">IM</div>
                <h1 class="brand-name">InvitaMex</h1>
            </div>

            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-box" placeholder="Buscar eventos...">
            </div>

            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#about" class="nav-link">
                        <i class="fas fa-question-circle"></i>
                        Sobre Nosotros
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#contact" class="nav-link">
                        <i class="fas fa-envelope"></i>
                        Contacto
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#events" class="nav-link">
                        <i class="fas fa-calendar-alt"></i>
                        Eventos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#login" class="btn-login">
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

    <!-- HERO SECTION -->
    <section class="hero" id="home">

        <canvas id="background-canvas"></canvas>

        <div class="hero-container">
            <div class="hero-content">
                <p class="hero-subtitle">Invitaciones Digitales </p>
                <h2 class="hero-title">Crea invitaciones únicas para tus eventos más especiales</h2>
                <p class="hero-description">
                    Diseña, personaliza y comparte invitaciones digitales hermosas y sostenibles.
                    Con códigos QR, confirmación de asistencia y mapas interactivos.
                    ¡Tu evento, tu estilo!
                </p>
                <div class="hero-buttons">
                    <a href="#create-event" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Crear Invitación
                    </a>
                    <a href="#features" class="btn btn-secondary">
                        <i class="fas fa-play"></i>
                        Ver Características
                    </a>
                </div>
            </div>

            <div class="hero-visual">
                <div class="invitation-mockup">
                    <h3 class="mockup-header">¡Estás Invitado/a!</h3>
                    <div class="mockup-content">
                        <p><strong>Boda de Ana y Carlos</strong></p>
                        <p>📅 15 de Septiembre, 2025</p>
                        <p>📍 Jardín Botánico CDMX</p>
                        <p>🕕 6:00 PM</p>
                    </div>
                    <div class="mockup-qr">
                        <i class="fas fa-qrcode"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section class="features" id="features">
        <div class="features-container">
            <div class="section-header">
                <p class="section-subtitle">Características</p>
                <h2 class="section-title">Todo lo que necesitas para tus eventos</h2>
                <p class="section-description">
                    Nuestra plataforma te ofrece herramientas poderosas y fáciles de usar
                    para crear experiencias inolvidables en tus eventos.
                </p>
            </div>

            <div class="features-grid">
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <h3 class="feature-title">Compartir por WhatsApp</h3>
                    <p class="feature-description">
                        Comparte tus invitaciones directamente por WhatsApp con un mensaje
                        automático que incluye todos los detalles del evento.
                    </p>
                </div>

                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="feature-title">Mapas Interactivos</h3>
                    <p class="feature-description">
                        Integración con mapas en tiempo real para que tus invitados
                        encuentren fácilmente la ubicación de tu evento.
                    </p>
                </div>

                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="feature-title">Gestión de Asistentes</h3>
                    <p class="feature-description">
                        Control total sobre las confirmaciones de asistencia con
                        estadísticas en tiempo real y exportación de listas.
                    </p>
                </div>

                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3 class="feature-title">100% Ecológico</h3>
                    <p class="feature-description">
                        Alternativa sostenible a las invitaciones impresas.
                        Cuida el medio ambiente sin comprometer la elegancia.
                    </p>
                </div>

                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    <h3 class="feature-title">Códigos QR Automáticos</h3>
                    <p class="feature-description">
                        Cada invitación genera automáticamente códigos QR únicos para
                        acceso rápido y confirmación de asistencia sin complicaciones.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS SECTION -->
    <section class="stats" id="stats">
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-number" data-count="50000">0</div>
                <div class="stat-label">Invitaciones Creadas</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="12000">0</div>
                <div class="stat-label">Usuarios Activos</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="25000">0</div>
                <div class="stat-label">Eventos Realizados</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="98">0</div>
                <div class="stat-label">% Satisfacción</div>
            </div>
        </div>
    </section>

    <!-- CTA SECTION -->
    <section class="cta" id="cta">
        <div class="cta-container">
            <h2 class="cta-title">¿Listo para crear tu primera invitación?</h2>
            <p class="cta-description">
                Únete a miles de usuarios que ya han descubierto la forma más moderna
                y ecológica de invitar a sus eventos especiales.
            </p>
            <div class="hero-buttons">
                <a href="#signup" class="btn btn-primary">
                    <i class="fas fa-rocket"></i>
                    Comenzar Gratis
                </a>
                <a href="#demo" class="btn btn-secondary">
                    <i class="fas fa-eye"></i>
                    Ver Demo
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer" id="contact">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <div class="logo">IM</div>
                        <h3 class="brand-name">InvitaMex</h3>
                    </div>
                    <p class="footer-description">
                        La plataforma de invitaciones digitales ecológicas.
                        Creamos experiencias únicas para tus eventos más especiales,
                        cuidando el medio ambiente y conectando personas.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>

                <div class="footer-section">
                    <h3>Producto</h3>
                    <ul class="footer-links">
                        <li><a href="#features">Características</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Empresa</h3>
                    <ul class="footer-links">
                        <li><a href="#about">Sobre Nosotros</a></li>
                    </ul>
                </div>

                <div class="footer-section">
                    <h3>Soporte</h3>
                    <ul class="footer-links">
                        <li><a href="#contact">Contacto</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="copyright">
                    © 2025 InvitaMex. Todos los derechos reservados.
                </div>
                <div class="footer-links">
                    <a href="#privacy">Política de Privacidad</a>
                    <span style="margin: 0 10px;">|</span>
                    <a href="#terms">Términos de Servicio</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- BACK TO TOP BUTTON -->
    <button class="back-to-top" id="backToTop" aria-label="Volver arriba">
        <i class="fas fa-arrow-up"></i>
    </button>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.3/TweenLite.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.3/easing/EasePack.min.js" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>

    <script src="{{ asset('js/main.js') }}"></script>

</body>

</html>