<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $event->title }} - InvitaCleth</title>
    
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/eventoDetalle.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

    <!-- JavaScript externo -->
    <script src="{{ asset('js/eventoDetalle.js') }}"></script>

    <!-- Scripts con datos dinámicos de Laravel -->
    <script>
        // Función de compartir con datos del evento
        function shareWhatsApp() {
            const title = "{{ $event->title }}";
            const date = "{{ $event->event_date->format('d/m/Y') }}";
            const url = window.location.href;
            const message = `¡Te invito a ${title}! 📅 Fecha: ${date}. Más información: ${url}`;
            window.open(`https://wa.me/?text=${encodeURIComponent(message)}`, '_blank');
        }

        @auth
        @if(Auth::id() !== $event->user_id)
        // Script para usuarios invitados - Confirmar asistencia
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
        // Script para el organizador - Editar evento
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
        @endif
        @endauth
    </script>
</body>
</html>