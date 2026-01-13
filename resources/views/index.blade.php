<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvitaCleth </title>

    <!-- Estilos externos -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    <!-- Librerías externas -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

    @include('partials.header')

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
                    <a href="{{ route('crear.invitacion') }}" class="btn btn-primary">
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

                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3 class="feature-title">Diseños Personalizables</h3>
                    <p class="feature-description">
                        Elige entre múltiples plantillas elegantes y personaliza cada
                        detalle: colores, fuentes, imágenes y animaciones para reflejar tu estilo único.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS SECTION -->
    <!-- <section class="stats" id="stats">
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
    </section> -->

    <!-- CTA SECTION -->
    <section class="cta" id="cta">
        <div class="cta-container">
            <h2 class="cta-title">¿Listo para crear tu primera invitación?</h2>
            <p class="cta-description">
                Únete a miles de usuarios que ya han descubierto la forma más moderna
                y ecológica de invitar a sus eventos especiales.
            </p>
            <div class="cta-buttons">
                <a href="{{ route('crear.invitacion') }}" class="btn btn-primary">
                    <i class="fas fa-rocket"></i>
                    Comenzar Gratis
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    @include('partials.footer')


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