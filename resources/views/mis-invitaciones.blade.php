<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis Invitaciones y Eventos - InvitaCleth</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding-top: 100px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            flex: 1;
        }

        .page-header {
            text-align: center;
            color: white;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Pestañas deslizantes */
        .tabs-container {
            background: white;
            border-radius: 25px;
            padding: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .tabs {
            display: flex;
            position: relative;
            background: #f0f0f0;
            border-radius: 20px;
            padding: 0.5rem;
        }

        .tab-button {
            flex: 1;
            padding: 1rem 2rem;
            border: none;
            background: transparent;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .tab-button.active {
            color: white;
        }

        .tab-slider {
            position: absolute;
            top: 0.5rem;
            left: 0.5rem;
            height: calc(100% - 1rem);
            width: calc(50% - 0.5rem);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            transition: transform 0.3s ease;
            z-index: 0;
        }

        .tab-slider.slide-right {
            transform: translateX(100%);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Cards de invitaciones */
        .invitations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }

        .invitation-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .invitation-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
        }

        .invitation-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
        }

        .invitation-header h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .invitation-header .event-date {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .invitation-body {
            padding: 2rem;
        }

        .qr-section {
            text-align: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 15px;
        }

        .qr-code {
            width: 200px;
            height: 200px;
            margin: 0 auto 1rem;
            border: 4px solid #667eea;
            border-radius: 15px;
            padding: 10px;
            background: white;
        }

        .qr-code img {
            width: 100%;
            height: 100%;
            display: block;
        }

        .qr-code-text {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #666;
            word-break: break-all;
            margin-top: 0.5rem;
        }

        .invitation-details {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            align-items: start;
            gap: 1rem;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .detail-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        .detail-content {
            flex: 1;
        }

        .detail-label {
            font-size: 0.85rem;
            color: #888;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-size: 1rem;
            color: #333;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .status-confirmado {
            background: #d4edda;
            color: #155724;
        }

        .status-pendiente {
            background: #fff3cd;
            color: #856404;
        }

        .status-usado {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-cancelado {
            background: #f8d7da;
            color: #721c24;
        }

        .btn {
            flex: 1;
            min-width: 120px;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #28a745;
            color: white;
        }

        .btn-secondary:hover {
            background: #218838;
            transform: translateY(-2px);
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

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .invitation-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        /* Cards de eventos creados */
        .event-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .event-card:hover {
            transform: translateY(-10px);
        }

        .event-card-header {
            padding: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .event-card-header h3 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .event-card-body {
            padding: 2rem;
        }

        .event-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-box {
            text-align: center;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
            display: block;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #666;
            margin-top: 0.5rem;
        }

        .event-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .empty-state i {
            font-size: 5rem;
            color: #667eea;
            margin-bottom: 1.5rem;
        }

        .empty-state h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            body {
                padding-top: 80px;
            }

            .container {
                padding: 1rem;
            }

            .invitations-grid {
                grid-template-columns: 1fr;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .tab-button {
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
            }

            .invitation-actions,
            .event-actions {
                flex-direction: column;
            }

            .btn {
                min-width: 100%;
            }

            .event-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-ticket-alt"></i> Mis Invitaciones y Eventos</h1>
            <p>Administra tus invitaciones y eventos creados</p>
        </div>

        <!-- Pestañas -->
        <div class="tabs-container">
            <div class="tabs">
                <div class="tab-slider" id="tabSlider"></div>
                <button class="tab-button active" onclick="switchTab('invitations')">
                    <i class="fas fa-ticket-alt"></i> Mis Invitaciones
                </button>
                <button class="tab-button" onclick="switchTab('events')">
                    <i class="fas fa-calendar-plus"></i> Mis Eventos
                </button>
            </div>
        </div>

        <!-- Contenido: Mis Invitaciones -->
        <div id="invitations-content" class="tab-content active">
            @if($invitations->count() > 0)
                <div class="invitations-grid">
                    @foreach($invitations as $invitation)
                        <div class="invitation-card">
                            <div class="invitation-header">
                                <h3>{{ $invitation->event->title }}</h3>
                                <div class="event-date">
                                    <i class="fas fa-calendar"></i>
                                    {{ $invitation->event->event_date->format('d/m/Y') }} - 
                                    {{ $invitation->event->event_time->format('H:i') }}
                                </div>
                            </div>

                            <div class="invitation-body">
                                <div class="qr-section">
                                    <h4 style="margin-bottom: 1rem; color: #333;">
                                        <i class="fas fa-qrcode"></i> Tu Código QR
                                    </h4>
                                    <div class="qr-code">
                                        @if($invitation->qr_image)
                                            <img src="{{ asset('storage/' . $invitation->qr_image) }}" alt="QR Code">
                                        @else
                                            <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #ccc;">
                                                <i class="fas fa-qrcode" style="font-size: 3rem;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="qr-code-text">
                                        {{ $invitation->qr_code }}
                                    </div>
                                    <span class="status-badge status-{{ $invitation->status }}">
                                        @switch($invitation->status)
                                            @case('confirmado')
                                                <i class="fas fa-check-circle"></i> Confirmado
                                                @break
                                            @case('pendiente')
                                                <i class="fas fa-clock"></i> Pendiente
                                                @break
                                            @case('usado')
                                                <i class="fas fa-check-double"></i> Usado
                                                @break
                                            @case('cancelado')
                                                <i class="fas fa-times-circle"></i> Cancelado
                                                @break
                                        @endswitch
                                    </span>
                                </div>

                                <div class="invitation-details">
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-label">Organizador</div>
                                            <div class="detail-value">{{ $invitation->event->host_name }}</div>
                                        </div>
                                    </div>

                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="detail-content">
                                            <div class="detail-label">Ubicación</div>
                                            <div class="detail-value">{{ $invitation->event->location }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="invitation-actions">
                                    <button class="btn btn-primary" onclick="printInvitation(this)">
                                        <i class="fas fa-print"></i>
                                        Imprimir
                                    </button>
                                    <button class="btn btn-secondary" onclick="downloadQR(this)">
                                        <i class="fas fa-download"></i>
                                        Descargar QR
                                    </button>
                                    <button class="btn btn-outline" onclick="shareInvitation('{{ $invitation->event->id }}')">
                                        <i class="fas fa-share"></i>
                                        Compartir
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-ticket-alt"></i>
                    <h2>No tienes invitaciones aún</h2>
                    <p>Explora los eventos disponibles y confirma tu asistencia</p>
                    <a href="{{ route('eventos') }}" class="btn btn-primary" style="display: inline-flex;">
                        <i class="fas fa-calendar-alt"></i>
                        Ver Eventos Disponibles
                    </a>
                </div>
            @endif
        </div>

        <!-- Contenido: Mis Eventos -->
        <div id="events-content" class="tab-content">
            @if($myEvents->count() > 0)
                <div class="invitations-grid">
                    @foreach($myEvents as $event)
                        <div class="event-card">
                            <div class="event-card-header">
                                <h3>{{ $event->title }}</h3>
                                <div style="margin-top: 0.5rem; opacity: 0.9;">
                                    <i class="fas fa-calendar"></i>
                                    {{ $event->event_date->format('d/m/Y') }} - {{ $event->event_time->format('H:i') }}
                                </div>
                            </div>

                            <div class="event-card-body">
                                <div class="event-stats">
                                    <div class="stat-box">
                                        <span class="stat-number">{{ $event->confirmed_invitations_count }}</span>
                                        <span class="stat-label">Confirmados</span>
                                    </div>
                                    <div class="stat-box">
                                        <span class="stat-number">{{ $event->invitations_count }}</span>
                                        <span class="stat-label">Total Invitaciones</span>
                                    </div>
                                    <div class="stat-box">
                                        <span class="stat-number">
                                            @if($event->max_attendees)
                                                {{ $event->max_attendees }}
                                            @else
                                                ∞
                                            @endif
                                        </span>
                                        <span class="stat-label">Capacidad</span>
                                    </div>
                                </div>

                                <div class="detail-item" style="margin-bottom: 1.5rem;">
                                    <div class="detail-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="detail-content">
                                        <div class="detail-label">Ubicación</div>
                                        <div class="detail-value">{{ $event->location }}</div>
                                    </div>
                                </div>

                                <div class="event-actions">
                                    <a href="{{ route('eventos.scanner', $event->id) }}" class="btn btn-primary">
                                        <i class="fas fa-qrcode"></i>
                                        Escanear QR
                                    </a>
                                    <a href="{{ route('eventos.show', $event->id) }}" class="btn btn-outline">
                                        <i class="fas fa-eye"></i>
                                        Ver Evento
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-calendar-plus"></i>
                    <h2>No has creado eventos aún</h2>
                    <p>Crea tu primer evento y comienza a generar invitaciones</p>
                    <a href="{{ route('eventos.create') }}" class="btn btn-primary" style="display: inline-flex;">
                        <i class="fas fa-plus-circle"></i>
                        Crear Evento
                    </a>
                </div>
            @endif
        </div>
    </div>

    @include('partials.footer')

    <script>
        // Cambiar entre pestañas
        function switchTab(tab) {
            const slider = document.getElementById('tabSlider');
            const tabs = document.querySelectorAll('.tab-button');
            const contents = document.querySelectorAll('.tab-content');

            // Actualizar botones
            tabs.forEach(t => t.classList.remove('active'));
            if (tab === 'invitations') {
                tabs[0].classList.add('active');
                slider.classList.remove('slide-right');
            } else {
                tabs[1].classList.add('active');
                slider.classList.add('slide-right');
            }

            // Actualizar contenido
            contents.forEach(c => c.classList.remove('active'));
            document.getElementById(tab + '-content').classList.add('active');
        }

        // Imprimir invitación individual
        function printInvitation(button) {
            const card = button.closest('.invitation-card');
            const allCards = document.querySelectorAll('.invitation-card');
            
            allCards.forEach(c => {
                if (c !== card) c.style.display = 'none';
            });

            window.print();

            setTimeout(() => {
                allCards.forEach(c => c.style.display = 'block');
            }, 100);
        }

        // Descargar QR individual
        function downloadQR(button) {
            const card = button.closest('.invitation-card');
            const qrImage = card.querySelector('.qr-code img');
            
            if (qrImage && qrImage.src) {
                fetch(qrImage.src)
                    .then(response => response.blob())
                    .then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        const link = document.createElement('a');
                        link.href = url;
                        link.download = `invitacion-qr-${Date.now()}.png`;
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        window.URL.revokeObjectURL(url);
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert('Error al descargar el código QR');
                    });
            } else {
                alert('No se encontró el código QR');
            }
        }

        // Compartir invitación
        function shareInvitation(eventId) {
            const url = `${window.location.origin}/eventos/${eventId}`;
            const message = `¡Mira este evento! ${url}`;
            
            if (navigator.share) {
                navigator.share({
                    title: 'Invitación a Evento',
                    text: message,
                    url: url
                }).catch(err => console.log('Error al compartir:', err));
            } else {
                navigator.clipboard.writeText(url).then(() => {
                    alert('Enlace copiado al portapapeles');
                }).catch(() => {
                    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
                    window.open(whatsappUrl, '_blank');
                });
            }
        }

        // Restaurar vista después de imprimir
        window.addEventListener('afterprint', function() {
            document.querySelectorAll('.invitation-card').forEach(card => {
                card.style.display = 'block';
            });
        });
    </script>
</body>
</html>