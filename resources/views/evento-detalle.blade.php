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

        .event-image-container {
            position: relative;
        }

        .event-carousel {
            position: relative;
            width: 100%;
            height: 400px;
            overflow: hidden;
        }

        .carousel-slides {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .carousel-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            display: none;
        }

        .carousel-slide.active {
            opacity: 1;
            display: block;
        }

        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .carousel-control {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            z-index: 10;
        }

        .carousel-control:hover {
            background: rgba(0, 0, 0, 0.8);
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-control.prev {
            left: 20px;
        }

        .carousel-control.next {
            right: 20px;
        }

        .carousel-indicators {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        .indicator:hover {
            background: rgba(255, 255, 255, 0.8);
            transform: scale(1.2);
        }

        .indicator.active {
            background: white;
            width: 14px;
            height: 14px;
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

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-3px);
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

        /* ESTILOS DEL MODAL DE EDICIÓN */
        .edit-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            z-index: 10000;
            overflow-y: auto;
            padding: 2rem 0;
        }

        .edit-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content-edit {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 800px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
            animation: modalSlideIn 0.3s ease;
            max-height: 90vh;
            overflow-y: auto;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header-edit {
            padding: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header-edit h2 {
            margin: 0;
            font-size: 1.8rem;
        }

        .modal-close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .modal-close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        .modal-body-edit {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-label.required::after {
            content: '*';
            color: #dc3545;
            margin-left: 4px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.875rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .form-error {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: none;
        }

        .form-error.show {
            display: block;
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            border-top: 1px solid #e0e0e0;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
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

            .form-row {
                grid-template-columns: 1fr;
            }

            .modal-content-edit {
                width: 95%;
                margin: 1rem;
            }
        }
    </style>
</head>

<body>
    @include('partials.header')

    <div class="event-detail-container">
        <div class="event-header">
            <div class="event-image-container">
                @if($event->images->count() > 0 || $event->event_image)
                <!-- CARRUSEL DE IMÁGENES -->
                <div class="event-carousel">
                    <div class="carousel-slides">
                        <!-- Imagen principal (portada) -->
                        @if($event->event_image)
                        <div class="carousel-slide active" data-slide="0">
                            <img src="{{ asset('storage/' . $event->event_image) }}" alt="{{ $event->title }}">
                        </div>
                        @endif

                        <!-- Imágenes adicionales de la galería -->
                        @foreach($event->images as $index => $image)
                        <div class="carousel-slide" data-slide="{{ $index + 1 }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $event->title }}">
                        </div>
                        @endforeach
                    </div>

                    @if($event->images->count() > 0)
                    <!-- Controles del carrusel -->
                    <button class="carousel-control prev" onclick="changeSlide(-1)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="carousel-control next" onclick="changeSlide(1)">
                        <i class="fas fa-chevron-right"></i>
                    </button>

            <!-- Indicadores de slides -->
            <div class="carousel-indicators">
                <span class="indicator active" data-slide="0" onclick="goToSlide(0)"></span>
                @foreach($event->images as $index => $image)
                <span class="indicator" data-slide="{{ $index + 1 }}" onclick="goToSlide('{{ $index + 1 }}')"></span>
                @endforeach
            </div>
            @endif
        </div>
    @else
        <!-- Imagen por defecto si no hay ninguna -->
        <div class="event-image">
            <i class="fas fa-calendar-alt"></i>
        </div>
    @endif
</div>

            <div class="event-content">
                <h1 class="event-title">{{ $event->title }}</h1>
                <p class=" event-organizer">
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

                    <button class="btn btn-secondary" onclick="openEditModal()">
                        <i class="fas fa-edit"></i>
                        Editar Evento
                    </button>

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

            <!-- MODAL DE EDICIÓN -->
            <div class="edit-modal" id="editModal">
                <div class="modal-content-edit">
                    <div class="modal-header-edit">
                        <h2><i class="fas fa-edit"></i> Editar Evento</h2>
                        <button class="modal-close-btn" onclick="closeEditModal()">&times;</button>
                    </div>

                    <form id="editEventForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="modal-body-edit">
                            <div class="form-group">
                                <label class="form-label required">Título del Evento</label>
                                <input type="text" name="title" class="form-input" value="{{ $event->title }}" required>
                                <div class="form-error" id="error-title"></div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Nombre del Anfitrión</label>
                                    <input type="text" name="host_name" class="form-input" value="{{ $event->host_name }}" required>
                                    <div class="form-error" id="error-host_name"></div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label required">Tipo de Evento</label>
                                    <select name="type" class="form-select" required>
                                        <option value="boda" {{ $event->type === 'boda' ? 'selected' : '' }}>Boda</option>
                                        <option value="cumpleanos" {{ $event->type === 'cumpleanos' ? 'selected' : '' }}>Cumpleaños</option>
                                        <option value="graduacion" {{ $event->type === 'graduacion' ? 'selected' : '' }}>Graduación</option>
                                        <option value="corporativo" {{ $event->type === 'corporativo' ? 'selected' : '' }}>Corporativo</option>
                                        <option value="social" {{ $event->type === 'social' ? 'selected' : '' }}>Social</option>
                                        <option value="religioso" {{ $event->type === 'religioso' ? 'selected' : '' }}>Religioso</option>
                                        <option value="otro" {{ $event->type === 'otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    <div class="form-error" id="error-type"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Ubicación</label>
                                <input type="text" name="location" class="form-input" value="{{ $event->location }}" required>
                                <div class="form-error" id="error-location"></div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Fecha del Evento</label>
                                    <input type="date" name="event_date" class="form-input" value="{{ $event->event_date->format('Y-m-d') }}" required>
                                    <div class="form-error" id="error-event_date"></div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label required">Hora del Evento</label>
                                    <input type="time" name="event_time" class="form-input" value="{{ $event->event_time->format('H:i') }}" required>
                                    <div class="form-error" id="error-event_time"></div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label required">Tipo de Pago</label>
                                    <select name="payment_type" class="form-select" id="payment_type" required onchange="togglePriceField()">
                                        <option value="gratis" {{ $event->payment_type === 'gratis' ? 'selected' : '' }}>Gratis</option>
                                        <option value="pago" {{ $event->payment_type === 'pago' ? 'selected' : '' }}>De Pago</option>
                                    </select>
                                    <div class="form-error" id="error-payment_type"></div>
                                </div>

                                <div class="form-group" id="price_group" @if($event->payment_type !== 'pago') style="display: none;" @endif>
                                    <label class="form-label">Precio (MXN)</label>
                                    <input type="number" name="price" class="form-input" value="{{ $event->price }}" step="0.01" min="10">
                                    <div class="form-error" id="error-price"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Capacidad Máxima (opcional)</label>
                                <input type="number" name="max_attendees" class="form-input" value="{{ $event->max_attendees }}" min="1">
                                <div class="form-error" id="error-max_attendees"></div>
                            </div>

                            <div class="form-group">
                                <label class="form-label required">Descripción</label>
                                <textarea name="description" class="form-textarea" required>{{ $event->description }}</textarea>
                                <div class="form-error" id="error-description"></div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Imagen del Evento</label>
                                <input type="file" name="event_image" class="form-input" accept="image/*">
                                <div style="font-size: 0.85rem; color: #666; margin-top: 0.5rem;">
                                    @if($event->event_image)
                                    Imagen actual: <a href="{{ asset('storage/' . $event->event_image) }}" target="_blank">Ver imagen</a>
                                    @else
                                    Sin imagen actual
                                    @endif
                                </div>
                                <div class="form-error" id="error-event_image"></div>
                            </div>

                            <div class="form-group">
                                <div class="checkbox-group">
                                    <input type="checkbox" name="is_public" id="is_public" {{ $event->is_public ? 'checked' : '' }}>
                                    <label for="is_public" style="margin: 0; font-weight: normal;">Evento público (visible en la página de eventos)</label>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline" onclick="closeEditModal()">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitEditBtn">
                                <i class="fas fa-save"></i>
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
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
                @if(Auth::id() !== $event -> user_id)
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
                @else
                // Scripts para el organizador - Modal de Edición
                function openEditModal() {
                    document.getElementById('editModal').classList.add('show');
                    document.body.style.overflow = 'hidden';
                }

                function closeEditModal() {
                    document.getElementById('editModal').classList.remove('show');
                    document.body.style.overflow = 'auto';
                }

                function togglePriceField() {
                    const paymentType = document.getElementById('payment_type').value;
                    const priceGroup = document.getElementById('price_group');
                    priceGroup.style.display = paymentType === 'pago' ? 'block' : 'none';
                }

                // Enviar formulario de edición
                document.getElementById('editEventForm').addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const submitBtn = document.getElementById('submitEditBtn');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

                    // Limpiar errores previos
                    document.querySelectorAll('.form-error').forEach(el => el.classList.remove('show'));

                    const formData = new FormData(this);

                    try {
                        const response = await fetch('{{ route("eventos.update", $event->id) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (data.success) {
                            alert(data.message);
                            window.location.reload();
                        } else if (data.errors) {
                            // Mostrar errores de validación
                            Object.keys(data.errors).forEach(key => {
                                const errorEl = document.getElementById(`error-${key}`);
                                if (errorEl) {
                                    errorEl.textContent = data.errors[key][0];
                                    errorEl.classList.add('show');
                                }
                            });
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        } else {
                            alert(data.message || 'Error al actualizar el evento');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Hubo un error al actualizar el evento');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });

                // Cerrar modal con ESC
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeEditModal();
                    }
                });
                @endif
                @endauth

                // ============================================
                // CARRUSEL DE IMÁGENES
                // ============================================
                let currentSlide = 0;
                const slides = document.querySelectorAll('.carousel-slide');
                const indicators = document.querySelectorAll('.indicator');
                const totalSlides = slides.length;

                function showSlide(n) {
                    if (totalSlides === 0) return;

                    // Ajustar índice si está fuera de rango
                    if (n >= totalSlides) {
                        currentSlide = 0;
                    } else if (n < 0) {
                        currentSlide = totalSlides - 1;
                    } else {
                        currentSlide = n;
                    }

                    // Ocultar todas las slides
                    slides.forEach(slide => {
                        slide.classList.remove('active');
                    });

                    // Desactivar todos los indicadores
                    indicators.forEach(indicator => {
                        indicator.classList.remove('active');
                    });

                    // Mostrar slide actual
                    slides[currentSlide].classList.add('active');

                    // Activar indicador actual
                    if (indicators[currentSlide]) {
                        indicators[currentSlide].classList.add('active');
                    }
                }

                function changeSlide(direction) {
                    showSlide(currentSlide + direction);
                }

                function goToSlide(n) {
                    showSlide(n);
                }

                // Auto-play (opcional)
                let autoPlayInterval = setInterval(() => {
                    changeSlide(1);
                }, 5000); // Cambia cada 5 segundos

                // Pausar auto-play al pasar el mouse
                const carousel = document.querySelector('.event-carousel');
                if (carousel) {
                    carousel.addEventListener('mouseenter', () => {
                        clearInterval(autoPlayInterval);
                    });

                    carousel.addEventListener('mouseleave', () => {
                        autoPlayInterval = setInterval(() => {
                            changeSlide(1);
                        }, 5000);
                    });
                }

                // Soporte para teclado
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowLeft') {
                        changeSlide(-1);
                    } else if (e.key === 'ArrowRight') {
                        changeSlide(1);
                    }
                });
            </script>
</body>

</html>