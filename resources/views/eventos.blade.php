<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Públicos - InvitaMex</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    @include('partials.header') <!-- Puedes extraer el header a un partial para no repetir código -->

    <section class="features" style="padding-top:150px;" id="events">
        <div class="features-container">
            <div class="section-header">
                <p class="section-subtitle">Eventos Públicos</p>
                <h2 class="section-title">Explora los eventos más recientes</h2>
                <p class="section-description">
                    Aquí encontrarás los eventos creados y compartidos públicamente por nuestra comunidad.
                </p>
            </div>

            <div class="features-grid">
                <!-- Evento 1 -->
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-ring"></i>
                    </div>
                    <h3 class="feature-title">Boda de Ana & Carlos</h3>
                    <p class="feature-description">
                         15 de Septiembre, 2025 <br>
                         Jardín Botánico CDMX <br>
                         ¡Celebra con nosotros un día inolvidable!
                    </p>
                </div>

                <!-- Evento 2 -->
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-birthday-cake"></i>
                    </div>
                    <h3 class="feature-title">Cumpleaños de Sofía</h3>
                    <p class="feature-description">
                         22 de Octubre, 2025 <br>
                         Terraza Roma Norte <br>
                         ¡No faltes a esta fiesta especial!
                    </p>
                </div>

                <!-- Evento 3 -->
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-music"></i>
                    </div>
                    <h3 class="feature-title">Concierto Rock Fest</h3>
                    <p class="feature-description">
                         5 de Noviembre, 2025 <br>
                         Foro Sol CDMX <br>
                         Vive la música con tus bandas favoritas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    
    
    <button class="back-to-top" id="backToTop" aria-label="Volver arriba">
        <i class="fas fa-arrow-up"></i>
    </button>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.3/TweenLite.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.3/easing/EasePack.min.js" defer></script>

    <script src="{{ asset('js/main.js') }}" defer></script>
</body>
</html>
