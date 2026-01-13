<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $event->title }} - InvitaMex</title>
    
    @auth
    <meta name="user-authenticated" content="true">
    <meta name="user-id" content="{{ Auth::id() }}">
    @endauth
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Cargar fuentes dinámicamente -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@400;600;700&family=Dancing+Script:wght@400;700&family=Lora:wght@400;600&family=Raleway:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- CSS externo -->
    <link href="{{ asset('css/eventoInvitacion.css') }}" rel="stylesheet">
    
    <style>
        /* Variables de colores dinámicas del evento */
        :root {
            --primary-color: {{ $event->primary_color ?? '#667eea' }};
            --secondary-color: {{ $event->secondary_color ?? '#764ba2' }};
            --background-color: {{ $event->background_color ?? '#ffffff' }};
        }

        body {
            font-family: '{{ $event->font_family ?? "Inter" }}', sans-serif;
            background: {{ $event->background_color ?? '#ffffff' }};
        }

        .invitation-title {
            font-size: {{ $event->font_size === 'large' ? '3.5rem' : ($event->font_size === 'small' ? '2.5rem' : '3rem') }};
        }

        .invitation-subtitle {
            font-size: {{ $event->font_size === 'large' ? '1.5rem' : ($event->font_size === 'small' ? '1.1rem' : '1.3rem') }};
        }

        .description-section h2 {
            font-size: {{ $event->font_size === 'large' ? '2.5rem' : ($event->font_size === 'small' ? '1.8rem' : '2.2rem') }};
        }

        .description-text {
            font-size: {{ $event->font_size === 'large' ? '1.2rem' : ($event->font_size === 'small' ? '0.95rem' : '1.1rem') }};
        }

        .detail-value {
            font-size: {{ $event->font_size === 'large' ? '1.3rem' : ($event->font_size === 'small' ? '1rem' : '1.15rem') }};
        }

        .gallery-section h2 {
            font-size: {{ $event->font_size === 'large' ? '2.5rem' : ($event->font_size === 'small' ? '1.8rem' : '2.2rem') }};
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="invitation-header">
        <button class="back-button" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i> Volver
        </button>
        
        <div class="header-content">
            <h1 class="invitation-title">{{ $event->title }}</h1>
            <p class="invitation-subtitle">
                <i class="fas fa-user-circle"></i> 
                Organizado por {{ $event->host_name }}
            </p>
            
            @if($event->payment_type === 'pago')
                <div class="payment-badge badge-paid">
                    <i class="fas fa-dollar-sign"></i> 
                    ${{ number_format($event->price, 2) }} MXN
                </div>
            @else
                <div class="payment-badge badge-free">
                    <i class="fas fa-gift"></i> 
                    Entrada Gratuita
                </div>
            @endif
        </div>
    </div>

    <!-- Container Principal -->
    <div class="invitation-container">
        
        <!-- Imagen Principal -->
        @if($event->event_image)
        <div class="main-image-section fade-in" onclick="openImageViewer(0)" style="cursor: pointer;">
            <img src="{{ asset('storage/' . $event->event_image) }}" alt="{{ $event->title }}">
            <div class="image-overlay">
                <h3 style="font-size: 1.5rem; margin-bottom: 0.5rem;">¡Estás invitado/a!</h3>
                <p>{{ $event->confirmedInvitations->count() }} personas confirmaron su asistencia</p>
            </div>
            <div class="zoom-indicator">
                <i class="fas fa-search-plus"></i> Click para ampliar
            </div>
        </div>
        @endif

        <!-- Detalles del Evento -->
        <div class="details-grid fade-in">
            <div class="detail-card">
                <div class="detail-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="detail-label">Fecha</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($event->event_date)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="detail-label">Hora</div>
                <div class="detail-value">
                    {{ \Carbon\Carbon::parse($event->event_time)->format('h:i A') }}
                </div>
            </div>

            <div class="detail-card">
                <div class="detail-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="detail-label">Ubicación</div>
                <div class="detail-value">{{ $event->location }}</div>
            </div>

            <div class="detail-card">
                <div class="detail-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="detail-label">Asistentes</div>
                <div class="detail-value">
                    {{ $event->confirmedInvitations->count() }} 
                    @if($event->max_attendees)
                        / {{ $event->max_attendees }}
                    @endif
                    confirmados
                </div>
            </div>
        </div>

        <!-- Descripción -->
        <div class="description-section fade-in">
            <h2><i class="fas fa-info-circle"></i> Acerca del Evento</h2>
            <p class="description-text">{{ $event->description }}</p>
        </div>

        <!-- Galería de Imágenes -->
        @if($event->images->count() > 0)
        <div class="gallery-section fade-in">
            <h2><i class="fas fa-images"></i> Galería del Evento</h2>
            <div class="gallery-grid">
                @foreach($event->images as $index => $image)
                <div class="gallery-item" onclick="openImageViewer({{ $index + 1 }})">
                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Imagen del evento {{ $index + 1 }}">
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Botones de Acción -->
        <div class="action-buttons fade-in">
    @auth
        @php
            // Verificar si el usuario YA tiene una invitación para este evento
            $userInvitation = \App\Models\Invitation::where('event_id', $event->id)
                ->where('user_id', Auth::id())
                ->first();
            $isOrganizer = (Auth::id() === $event->user_id);
        @endphp

        @if($userInvitation)
            <!-- Usuario YA tiene invitación -->
            <a href="{{ route('mis-invitaciones') }}" class="btn-action btn-success-action">
                <i class="fas fa-ticket-alt"></i>
                Ver mi Invitación
            </a>
        @elseif($isOrganizer)
            <!-- Es el organizador del evento -->
            <button class="btn-action btn-disabled" disabled>
                <i class="fas fa-user-shield"></i>
                Eres el organizador
            </button>
        @else
            <!-- Usuario NO tiene invitación - Puede confirmar -->
            @if(Auth::user()->hasVerifiedEmail())
                <button class="btn-action btn-primary-action" onclick="confirmAttendance()">
                    <i class="fas fa-check-circle"></i>
                    Confirmar Asistencia
                </button>
            @else
                <!-- Email no verificado -->
                <button class="btn-action btn-warning" onclick="alert('Debes verificar tu correo electrónico antes de confirmar asistencia a eventos.')">
                    <i class="fas fa-exclamation-triangle"></i>
                    Verificar Email Primero
                </button>
            @endif
        @endif
    @else
        <!-- Usuario NO autenticado -->
        <a href="{{ route('login') }}" class="btn-action btn-primary-action">
            <i class="fas fa-sign-in-alt"></i>
            Inicia Sesión para Confirmar
        </a>
    @endauth
    
    @php
    // URL de la invitación bonita
    $invitationUrl = url('/eventos/' . $event->id . '/invitacion');
    
    // Mensaje para compartir
    $shareMessage = '🎉 ¡Estás invitado/a! ' . $event->title . 
                   ' 📅 ' . $event->event_date->format('d/m/Y') . 
                   ' 🕐 ' . $event->event_time->format('h:i A') . 
                   ' 📍 ' . $event->location;
    
    // URLs
    $whatsappUrl = 'https://wa.me/?text=' . urlencode($shareMessage . ' 👉 ' . $invitationUrl);
    $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($event->location);
@endphp

<!-- Botón de Copiar Link -->
<button class="btn-action btn-info-action" onclick="copyInvitationLink('{{ $invitationUrl }}')">
    <i class="fas fa-link"></i>
    Copiar Enlace
</button>

<!-- Botón de WhatsApp -->
<a href="{{ $whatsappUrl }}" target="_blank" class="btn-action btn-secondary-action">
    <i class="fab fa-whatsapp"></i>
    Compartir por WhatsApp
</a>

<!-- Botón de Mapa -->
<a href="{{ $mapsUrl }}" target="_blank" class="btn-action btn-secondary-action">
    <i class="fas fa-map"></i>
    Ver Ubicación en Mapa
</a>
</div>

    </div>

    <!-- Modal Visor de Imágenes -->
    <div class="image-viewer-modal" id="imageViewerModal" onclick="closeImageViewer()">
        <div class="viewer-content" onclick="event.stopPropagation()">
            <button class="viewer-close" onclick="closeImageViewer()">&times;</button>
            
            <button class="viewer-nav prev" onclick="event.stopPropagation(); changeImage(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <div class="viewer-image-container">
                <img id="viewerImage" src="" alt="Vista ampliada">
            </div>
            
            <button class="viewer-nav next" onclick="event.stopPropagation(); changeImage(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
            
            <div class="viewer-counter" id="viewerCounter">1 / 1</div>
        </div>
    </div>

    <!-- JavaScript externo -->
    <script src="{{ asset('js/eventoInvitacion.js') }}"></script>
    
    <!-- Inicializar datos del evento -->
    <script>
        // Pasar datos del evento al JavaScript
        const eventImagesArray = [
            @if($event->event_image)
                '{{ asset('storage/' . $event->event_image) }}',
            @endif
            @foreach($event->images as $image)
                '{{ asset('storage/' . $image->image_path) }}',
            @endforeach
        ];

        initEventData(
            {{ $event->id }},
            {{ $event->price ?? 0 }},
            '{{ $event->payment_type }}',
            {{ $event->user_id }},
            eventImagesArray
        );
    </script>
</body>
</html>