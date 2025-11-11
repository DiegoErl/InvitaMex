<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $event->title }} - InvitaCleth</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            padding-top: 100px;
        }

        .event-detail-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .event-header {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .event-image {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 5rem;
            position: relative;
        }

        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .event-content {
            padding: 2rem;
        }

        .event-title {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .event-organizer {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 1.5rem;
        }

        .event-badges {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .badge {
            padding: 0.5rem 1.2rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .badge-type {
            background: #e3f2fd;
            color: #1976d2;
        }

        .badge-gratis {
            background: #d4edda;
            color: #155724;
        }

        .badge-pago {
            background: #fff3cd;
            color: #856404;
        }

        .badge-status {
            background: #d1ecf1;
            color: #0c5460;
        }

        .event-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .detail-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            display: flex;
            align-items: start;
            gap: 1rem;
        }

        .detail-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .detail-info h4 {
            color: #666;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .detail-info p {
            color: #333;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .event-description {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .event-description h3 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .event-description p {
            color: #666;
            line-height: 1.8;
            font-size: 1.05rem;
        }

        .event-actions {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            margin: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #28a745;
            color: white;
        }

        .btn-secondary:hover {
            background: #218838;
            transform: translateY(-3px);
        }

        .btn-outline {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-outline:hover {
            background: #667eea;
            color: white;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            display: none;
            align-items: center;
            gap: 0.5rem;
        }

        .success-message.show {
            display: flex;
        }

        @media (max-width: 768px) {
            body {
                padding-top: 80px;
            }

            .event-detail-container {
                padding: 0 1rem;
            }

            .event-image {
                height: 250px;
            }

            .event-title {
                font-size: 1.8rem;
            }

            .event-details-grid {
                grid-template-columns: 1fr;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="event-detail-container">
        <div class="event-header">
            <div class="event-image">
                @if($event->event_image)
                    <img src="{{ asset('storage/' . $event->event_image) }}" alt="{{ $event->title }}">
                @else
                    <i class="fas fa-calendar-alt"></i>
                @endif
            </div>

            <div class="event-content">
                <h1 class="event-title">{{ $event->title }}</h1>
                <p class="event-organizer">
                    <i class="fas fa-user"></i>
                    Organizado por {{ $event->host_name }}
                </p>

                <div class="event-badges">
                    <span class="badge badge-type">
                        <i class="fas fa-tag"></i>
                        @switch($event->type)
                            @case('boda') Boda @break
                            @case('cumpleanos') Cumpleaños @break
                            @case('graduacion') Graduación @break
                            @case('corporativo') Corporativo @break
                            @case('social') Social @break
                            @case('religioso') Religioso @break
                            @default Otro
                        @endswitch
                    </span>
                    
                    @if($event->payment_type === 'gratis')
                        <span class="badge badge-gratis">
                            <i class="fas fa-gift"></i> Entrada Gratuita
                        </span>
                    @else
                        <span class="badge badge-pago">
                            <i class="fas fa-dollar-sign"></i> ${{ number_format($event->price, 2) }}
                        </span>
                    @endif

                    <span class="badge badge-status">
                        <i class="fas fa-check-circle"></i>
                        {{ ucfirst($event->status) }}
                    </span>
                </div>

                <div class="event-details-grid">
                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div class="detail-info">
                            <h4>Fecha</h4>
                            <p>{{ $event->event_date->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="detail-info">
                            <h4>Hora</h4>
                            <p>{{ $event->event_time->format('H:i') }}</p>
                        </div>
                    </div>

                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="detail-info">
                            <h4>Ubicación</h4>
                            <p>{{ $event->location }}</p>
                        </div>
                    </div>

                    @if($event->max_attendees)
                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="detail-info">
                            <h4>Capacidad</h4>
                            <p>{{ $event->confirmedInvitations->count() }} / {{ $event->max_attendees }}</p>
                        </div>
                    </div>
                    @else
                    <div class="detail-card">
                        <div class="detail-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="detail-info">
                            <h4>Confirmados</h4>
                            <p>{{ $event->confirmedInvitations->count() }} personas</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="event-description">
            <h3><i class="fas fa-info-circle"></i> Descripción del Evento</h3>
            <p>{{ $event->description }}</p>
        </div>

        @auth
            @if(Auth::id() !== $event->user_id)
                {{-- Usuario normal - puede confirmar asistencia --}}
                <div class="event-actions">
                    <div id="successMessage" class="success-message">
                        <i class="fas fa-check-circle"></i>
                        <span>¡Invitación confirmada! Revisa tu código QR en "Mis Invitaciones"</span>
                    </div>

                    <h3 style="margin-bottom: 1.5rem; color: #333;">¿Asistirás a este evento?</h3>
                    
                    <button class="btn btn-primary" id="confirmBtn" 
                            data-event-id="{{ $event->id }}"
                            data-request-url="{{ route('eventos.requestInvitation', $event->id) }}">
                        <i class="fas fa-ticket-alt"></i>
                        Confirmar Asistencia y Obtener Invitación QR
                    </button>
                    
                    <button class="btn btn-secondary" onclick="shareWhatsApp()">
                        <i class="fab fa-whatsapp"></i>
                        Compartir por WhatsApp
                    </button>
                </div>
            @else
                {{-- Organizador - botones de gestión --}}
                <div class="event-actions">
                    <h3 style="margin-bottom: 1.5rem; color: #333;">Este es tu evento</h3>
                    
                    <a href="{{ route('eventos.scanner', $event->id) }}" class="btn btn-primary">
                        <i class="fas fa-qrcode"></i>
                        Escanear Invitaciones
                    </a>
                    
                    <a href="{{ route('mis-invitaciones') }}" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Volver a Mis Eventos
                    </a>
                </div>
            @endif
        @endauth

        @guest
            {{-- Usuario no autenticado --}}
            <div class="event-actions">
                <h3 style="margin-bottom: 1.5rem; color: #333;">Inicia sesión para confirmar tu asistencia</h3>
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar Sesión
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline">
                    <i class="fas fa-user-plus"></i>
                    Registrarse
                </a>
            </div>
        @endguest
    </div>

    @include('partials.footer')

    <script>
        function shareWhatsApp() {
            const title = "{{ $event->title }}";
            const date = "{{ $event->event_date->format('d/m/Y') }}";
            const url = window.location.href;
            const message = `¡Te invito a ${title}! 📅 Fecha: ${date}. Más información: ${url}`;
            window.open(`https://wa.me/?text=${encodeURIComponent(message)}`, '_blank');
        }

        @auth
            @if(Auth::id() !== $event->user_id)
                // Script para usuarios invitados
                const confirmBtn = document.getElementById('confirmBtn');
                if (confirmBtn) {
                    confirmBtn.addEventListener('click', async function() {
                        const button = this;
                        const requestUrl = button.dataset.requestUrl;
                        const originalText = button.innerHTML;

                        button.disabled = true;
                        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

                        try {
                            const response = await fetch(requestUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                }
                            });

                            const data = await response.json();

                            if (data.success) {
                                document.getElementById('successMessage').classList.add('show');
                                button.innerHTML = '<i class="fas fa-check"></i> Invitación Confirmada';
                                
                                setTimeout(() => {
                                    window.location.href = '{{ route("mis-invitaciones") }}';
                                }, 2000);
                            } else {
                                alert(data.message);
                                button.disabled = false;
                                button.innerHTML = originalText;
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            alert('Hubo un error al procesar tu solicitud');
                            button.disabled = false;
                            button.innerHTML = originalText;
                        }
                    });
                }
            @endif
        @endauth
    </script>
</body>
</html>