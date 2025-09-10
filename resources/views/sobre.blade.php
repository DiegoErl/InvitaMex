<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros - InvitaMex</title>

    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sobre.css') }}" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
</head>
<body>
    @include('partials.header')


    <!-- PAGE HERO -->
    <section class="page-hero">
        <div class="page-hero-content">
            <h1>Sobre Nosotros</h1>
            <p>
                Somos pioneros en invitaciones digitales ecológicas, transformando la manera 
                en que las personas celebran sus momentos más especiales mientras cuidamos nuestro planeta.
            </p>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="container">
            <!-- STORY SECTION -->
            <section class="story-section">
                <div class="story-grid">
                    <div class="story-content">
                        <h2>Nuestra Historia</h2>
                        <p>
                            InvitaMex nació en 2023 con una visión simple pero poderosa: hacer que cada celebración 
                            sea memorable sin dañar el medio ambiente. Fundada por un equipo apasionado de desarrolladores, 
                            diseñadores y ecologistas, comenzamos como un pequeño proyecto universitario que 
                            rápidamente se convirtió en la plataforma líder de invitaciones digitales en México.
                        </p>
                        <p>
                            Lo que comenzó como una solución para reducir el desperdicio de papel en eventos, 
                            evolucionó hacia una plataforma completa que combina tecnología, diseño y sustentabilidad. 
                            Cada invitación digital que creamos no solo es hermosa, sino que también representa 
                            nuestro compromiso con un futuro más verde.
                        </p>
                        <p>
                            Hoy, después de más de 10,000 eventos exitosos, continuamos innovando para ofrecer 
                            la mejor experiencia tanto a organizadores como a invitados, manteniendo siempre 
                            nuestro compromiso con la sostenibilidad y la excelencia.
                        </p>
                    </div>
                    <div class="story-visual">
                        <i class="fas fa-seedling"></i>
                        <h3>Impacto Ambiental</h3>
                        <p>
                            Con cada invitación digital, ayudamos a salvar árboles, reducir residuos 
                            y crear un mundo más sustentable para las futuras generaciones.
                        </p>
                    </div>
                </div>
                
                
            </section>

            <!-- MISSION SECTION -->
            <section class="mission-section">
                <h2>Nuestra Misión y Visión</h2>
                <p style="font-size: 1.2rem; max-width: 800px; margin: 0 auto 3rem; color: var(--text-muted);">
                    Trabajamos cada día para revolucionar la industria de eventos con soluciones 
                    digitales innovadoras que respeten nuestro planeta.
                </p>
                
                <div class="mission-grid">
                    <div class="mission-card animate-on-scroll">
                        <div class="mission-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3>Misión</h3>
                        <p>
                            Democratizar la creación de invitaciones hermosas y personalizadas, 
                            haciendo que cada evento sea único mientras protegemos el medio ambiente 
                            a través de soluciones 100% digitales y sustentables.
                        </p>
                    </div>
                    
                    <div class="mission-card animate-on-scroll">
                        <div class="mission-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3>Visión</h3>
                        <p>
                            Ser la plataforma global líder en invitaciones digitales, transformando 
                            la industria de eventos hacia un modelo completamente sustentable y 
                            accesible para todos, sin comprometer la creatividad ni la personalización.
                        </p>
                    </div>
                    
                    <div class="mission-card animate-on-scroll">
                        <div class="mission-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3>Propósito</h3>
                        <p>
                            Conectar personas en sus momentos más especiales mientras contribuimos 
                            a un mundo más sostenible. Cada invitación es una oportunidad de crear 
                            memorias extraordinarias sin dejar huella negativa en el planeta.
                        </p>
                    </div>
                </div>
            </section>

            <!-- TEAM SECTION -->
            <section class="team-section">
                <h2>Nuestro Equipo</h2>
                <div class="team-grid">
                    <div class="team-member animate-on-scroll">
                        <div class="team-avatar">DE</div>
                        <div class="team-info">
                            <h3 class="team-name">Diego Emilio Rojas López</h3>
                            <p class="team-role">CEO & Co-Fundador</p>
                            <p class="team-description">
                                Ingeniero en Sistemas Computacionales con pasión por la tecnología y 
                                lidera nuestra misión de transformar la industria de eventos.
                            </p>
                            <div class="team-social">
                                <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="team-member animate-on-scroll">
                        <div class="team-avatar">CM</div>
                        <div class="team-info">
                            <h3 class="team-name">Carlos Mendoza</h3>
                            <p class="team-role">CTO & Co-Fundador</p>
                            <p class="team-description">
                                Arquitecto de software especializado en experiencias de usuario. 
                                Responsable de la tecnología que impulsa nuestra plataforma.
                            </p>
                            <div class="team-social">
                                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </section>

            <!-- VALUES SECTION -->
            <section class="values-section">
                <h2>Nuestros Valores</h2>
                <div class="values-grid">
                    <div class="value-item animate-on-scroll">
                        <i class="value-icon fas fa-leaf"></i>
                        <h3 class="value-title">Sostenibilidad</h3>
                        <p class="value-description">
                            Cada decisión que tomamos tiene el medio ambiente en mente. 
                            Innovamos para un futuro más verde y sustentable.
                        </p>
                    </div>
                    
                    <div class="value-item animate-on-scroll">
                        <i class="value-icon fas fa-users"></i>
                        <h3 class="value-title">Comunidad</h3>
                        <p class="value-description">
                            Creemos en el poder de las conexiones humanas y trabajamos 
                            para facilitar momentos memorables entre las personas.
                        </p>
                    </div>
                    
                    <div class="value-item animate-on-scroll">
                        <i class="value-icon fas fa-lightbulb"></i>
                        <h3 class="value-title">Innovación</h3>
                        <p class="value-description">
                            Constantemente exploramos nuevas tecnologías y enfoques 
                            para mejorar la experiencia de nuestros usuarios.
                        </p>
                    </div>
                    
                    <div class="value-item animate-on-scroll">
                        <i class="value-icon fas fa-heart"></i>
                        <h3 class="value-title">Pasión</h3>
                        <p class="value-description">
                            Amamos lo que hacemos y esa pasión se refleja en cada 
                            característica y servicio que ofrecemos.
                        </p>
                    </div>
                    
                    <div class="value-item animate-on-scroll">
                        <i class="value-icon fas fa-shield-alt"></i>
                        <h3 class="value-title">Confiabilidad</h3>
                        <p class="value-description">
                            Construimos productos robustos y seguros en los que nuestros 
                            usuarios pueden confiar para sus eventos más importantes.
                        </p>
                    </div>
                    
                    <div class="value-item animate-on-scroll">
                        <i class="value-icon fas fa-star"></i>
                        <h3 class="value-title">Excelencia</h3>
                        <p class="value-description">
                            Nos esforzamos por superar las expectativas en cada 
                            interacción y constantemente mejoramos nuestros servicios.
                        </p>
                    </div>
                </div>
            </section>

            <!-- CONTACT CTA -->
            <section class="contact-cta">
                <h2>¿Quieres Conocer Más?</h2>
                <p>
                    Estamos siempre abiertos a conversar sobre nuestra misión, tecnología 
                    y cómo podemos trabajar juntos para crear un futuro más sustentable.
                </p>
                <div>
                    <a href="{{ route('contacto') }}" class="btn btn-secondary">
                        <i class="fas fa-envelope"></i>
                        Contáctanos
                    </a>
                    
                </div>
            </section>
        </div>
    </main>

    @include('partials.footer')

    <!-- BACK TO TOP BUTTON -->
    <button class="back-to-top" id="backToTop" aria-label="Volver arriba">
        <i class="fas fa-chevron-up"></i>
    </button>

    <script src="{{ asset('js/main.js') }}" defer></script>
    <script src="{{ asset('js/sobre.js') }}" defer></script>

    
</body>
</html>