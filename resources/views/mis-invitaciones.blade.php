<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis Invitaciones y Eventos - InvitaCleth</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/misInvitaciones.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
                                @case('rechazado')
                                <i class="fas fa-times-circle"></i> Rechazado
                                @break
                                @case('usado')
                                <i class="fas fa-check-double"></i> Usado
                                @break
                                @case('cancelado')
                                <i class="fas fa-times-circle"></i> Cancelado
                                @break
                                @endswitch
                            </span>

                            <!-- NUEVO: Mostrar deadline si existe -->
                            @if($invitation->event->rsvp_deadline && $invitation->status === 'pendiente')
                            <div style="margin-top: 1rem; padding: 0.75rem; background: #fff3cd; border-radius: 8px; text-align: center;">
                                <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                                <strong style="display: block; color: #856404; font-size: 0.9rem;">
                                    Fecha límite: {{ $invitation->event->rsvp_deadline->format('d/m/Y H:i') }}
                                </strong>
                            </div>
                            @endif
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

                        <!-- NUEVO: Botón de Confirmar Asistencia (solo si está pendiente) -->
                        @if($invitation->status === 'pendiente')
                        <div style="margin: 1.5rem 0;">
                            <button class="btn btn-primary" style="width: 100%;" onclick="openRsvpModal('{{ $invitation->id }}', '{{ addslashes($invitation->event->title) }}')">
                                <i class="fas fa-check-circle"></i>
                                Confirmar mi Asistencia
                            </button>
                        </div>
                        @endif

                        <!-- NUEVO: Mensaje si está rechazado -->
                        @if($invitation->status === 'rechazado')
                        <div style="margin: 1.5rem 0; padding: 1rem; background: #f8d7da; border-radius: 8px; text-align: center;">
                            <i class="fas fa-times-circle" style="color: #721c24; font-size: 1.5rem;"></i>
                            <p style="color: #721c24; margin-top: 0.5rem; font-weight: 600;">
                                Has rechazado esta invitación
                            </p>
                            <p style="color: #721c24; font-size: 0.85rem; margin-top: 0.25rem;">
                                Tu código QR no es válido para el evento
                            </p>
                        </div>
                        @endif

                        <!-- NUEVO: Mensaje si está confirmado -->
                        @if($invitation->status === 'confirmado')
                        <div style="margin: 1.5rem 0; padding: 1rem; background: #d4edda; border-radius: 8px; text-align: center;">
                            <i class="fas fa-check-circle" style="color: #155724; font-size: 1.5rem;"></i>
                            <p style="color: #155724; margin-top: 0.5rem; font-weight: 600;">
                                ¡Asistencia confirmada!
                            </p>
                            <p style="color: #155724; font-size: 0.85rem; margin-top: 0.25rem;">
                                Tu código QR está activo. Preséntalo el día del evento.
                            </p>
                        </div>
                        @endif

                        <div class="invitation-actions">
                            <button class="btn btn-primary" onclick="printInvitation(this)">
                                <i class="fas fa-print"></i>
                                Imprimir
                            </button>
                            <button class="btn btn-secondary" onclick="downloadQR(this)">
                                <i class="fas fa-download"></i>
                                Descargar QR
                            </button>
                            <!-- <button class="btn btn-outline" onclick="shareInvitation('{{ $invitation->event->id }}')">
                                <i class="fas fa-share"></i>
                                Compartir
                            </button> -->
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
                        <!-- NUEVO: Estadísticas actualizadas con "Asistieron" -->
                        <div class="event-stats" style="grid-template-columns: repeat(4, 1fr);">
                            <div class="stat-box stat-used">
                                <span class="stat-number">{{ $event->invitations->where('status', 'usado')->count() }}</span>
                                <span class="stat-label">Asistieron</span>
                            </div>
                            <div class="stat-box stat-confirmed">
                                <span class="stat-number">{{ $event->confirmedInvitations->count() }}</span>
                                <span class="stat-label">Confirmados</span>
                            </div>
                            <div class="stat-box stat-pending">
                                <span class="stat-number">{{ $event->pendingInvitations->count() }}</span>
                                <span class="stat-label">Pendientes</span>
                            </div>
                            <div class="stat-box stat-rejected">
                                <span class="stat-number">{{ $event->rejectedInvitations->count() }}</span>
                                <span class="stat-label">Rechazados</span>
                            </div>
                        </div>

                        <!-- NUEVO: Mostrar deadline si existe -->
                        @if($event->rsvp_deadline)
                        <div style="margin: 1rem 0; padding: 0.75rem; background: #e7f3ff; border-left: 3px solid #2196F3; border-radius: 4px;">
                            <i class="fas fa-clock" style="color: #2196F3;"></i>
                            <strong style="font-size: 0.9rem; color: #1976D2;">Límite de confirmación:</strong>
                            <span style="font-size: 0.9rem; color: #555;">{{ $event->rsvp_deadline->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif

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
                            <!-- NUEVO: Botón Ver Asistentes -->
                            <button class="btn btn-info" onclick="showAttendees('{{ $event->id }}', '{{ addslashes($event->title) }}')">
                                <i class="fas fa-users"></i>
                                Ver Asistentes
                            </button>
                            <a href="{{ route('eventos.scanner', $event->id) }}" class="btn btn-primary">
                                <i class="fas fa-qrcode"></i>
                                Escanear QR
                            </a>
                            <a href="{{ route('eventos.show', $event->id) }}" class="btn btn-outline">
                                <i class="fas fa-eye"></i>
                                Ver Evento
                            </a>
                            <button class="btn btn-danger" onclick="confirmDeleteEvent('{{ $event->id }}', '{{ addslashes($event->title) }}')">
                                <i class="fas fa-trash"></i>
                                Eliminar
                            </button>
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

    <!-- ============================================ -->
    <!-- NUEVO: MODAL DE CONFIRMACIÓN RSVP -->
    <!-- ============================================ -->
    <div id="rsvpModal" class="modal" style="display: none;">
        <div class="modal-overlay" onclick="closeRsvpModal()"></div>
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3><i class="fas fa-question-circle"></i> Confirmar Asistencia</h3>
                <button class="modal-close" onclick="closeRsvpModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p style="font-size: 1.1rem; margin-bottom: 1.5rem; text-align: center;">
                    ¿Estás seguro de confirmar tu asistencia al evento <strong id="rsvpEventTitle"></strong>?
                </p>

                <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <p style="color: #856404; font-size: 0.95rem; margin: 0; line-height: 1.6;">
                        <i class="fas fa-info-circle"></i>
                        <strong>Nota Importante:</strong> Al confirmar tu asistencia, te comprometes a asistir al evento. Tu código QR será validado y solo podrás ingresar si has confirmado.
                    </p>
                </div>

                <div class="modal-actions" style="display: flex; gap: 1rem;">
                    <button class="btn btn-success" style="flex: 1;" onclick="confirmAttendance()">
                        <i class="fas fa-check"></i>
                        Sí, Asistiré
                    </button>
                    <button class="btn btn-danger" style="flex: 1;" onclick="rejectInvitation()">
                        <i class="fas fa-times"></i>
                        Rechazar Invitación
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- NUEVO: MODAL DE LISTA DE ASISTENTES -->
    <!-- ============================================ -->
    <div id="attendeesModal" class="modal" style="display: none;">
        <div class="modal-overlay" onclick="closeAttendeesModal()"></div>
        <div class="modal-content" style="max-width: 800px; max-height: 90vh; overflow-y: auto;">
            <div class="modal-header">
                <h3><i class="fas fa-users"></i> Lista de Asistentes</h3>
                <button class="modal-close" onclick="closeAttendeesModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="attendeesEventInfo" style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <!-- Info del evento se cargará aquí -->
                </div>

                <!-- Resumen de estadísticas -->
                <div id="attendeesStats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                    <!-- Las estadísticas se cargarán aquí -->
                </div>

                <!-- Tabs para filtrar -->
                <div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; border-bottom: 2px solid #e0e0e0;">
                    <button class="attendees-tab active" data-status="all" onclick="filterAttendees('all')">
                        Todos
                    </button>
                    <button class="attendees-tab" data-status="confirmado" onclick="filterAttendees('confirmado')">
                        <i class="fas fa-check-circle"></i> Confirmados
                    </button>
                    <button class="attendees-tab" data-status="pendiente" onclick="filterAttendees('pendiente')">
                        <i class="fas fa-clock"></i> Pendientes
                    </button>
                    <button class="attendees-tab" data-status="rechazado" onclick="filterAttendees('rechazado')">
                        <i class="fas fa-times-circle"></i> Rechazados
                    </button>
                </div>

                <!-- Lista de asistentes -->
                <div id="attendeesList">
                    <!-- La lista se cargará aquí dinámicamente -->
                </div>

                <div id="attendeesLoading" style="text-align: center; padding: 2rem;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #667eea;"></i>
                    <p style="margin-top: 1rem; color: #666;">Cargando asistentes...</p>
                </div>

                <div id="attendeesEmpty" style="display: none; text-align: center; padding: 2rem; color: #999;">
                    <i class="fas fa-users-slash" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                    <p>No hay asistentes en esta categoría</p>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <script src="{{ asset('js/misInvitaciones.js') }}"></script>

</body>

</html>