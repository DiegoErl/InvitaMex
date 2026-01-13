<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Eventos - Admin</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.header')

    <div class="admin-container">
        <!-- SIDEBAR -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <div class="admin-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Admin Panel</span>
                </div>
                <h3>{{ Auth::user()->firstName }}</h3>
                <p class="admin-email">{{ Auth::user()->email }}</p>
            </div>

            <nav class="sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="menu-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.users') }}" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
                <a href="{{ route('admin.events') }}" class="menu-item active">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Eventos</span>
                </a>
                
                <div class="menu-divider"></div>
                
                <a href="{{ route('perfil') }}" class="menu-item">
                    <i class="fas fa-user"></i>
                    <span>Ver como Usuario</span>
                </a>
                <a href="{{ route('eventos') }}" class="menu-item">
                    <i class="fas fa-eye"></i>
                    <span>Ver Página Pública</span>
                </a>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="admin-main">
            <div class="admin-header">
                <h1>
                    <i class="fas fa-calendar-alt"></i>
                    Gestión de Eventos
                </h1>
                <p>Administra todos los eventos del sistema</p>
            </div>

            <!-- ALERTAS -->
            @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
            @endif

            <!-- FILTROS -->
            <div class="filters-card">
                <form method="GET" action="{{ route('admin.events') }}" class="filters-form">
                    <div class="filter-group">
                        <input type="text" 
                               name="search" 
                               placeholder="Buscar evento..." 
                               value="{{ request('search') }}"
                               class="filter-input">
                    </div>
                    
                    <div class="filter-group">
                        <select name="type" class="filter-select">
                            <option value="">Todos los tipos</option>
                            <option value="boda" {{ request('type') == 'boda' ? 'selected' : '' }}>Boda</option>
                            <option value="cumpleanos" {{ request('type') == 'cumpleanos' ? 'selected' : '' }}>Cumpleaños</option>
                            <option value="graduacion" {{ request('type') == 'graduacion' ? 'selected' : '' }}>Graduación</option>
                            <option value="corporativo" {{ request('type') == 'corporativo' ? 'selected' : '' }}>Corporativo</option>
                            <option value="social" {{ request('type') == 'social' ? 'selected' : '' }}>Social</option>
                            <option value="religioso" {{ request('type') == 'religioso' ? 'selected' : '' }}>Religioso</option>
                            <option value="otro" {{ request('type') == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <select name="payment_type" class="filter-select">
                            <option value="">Todos los pagos</option>
                            <option value="gratis" {{ request('payment_type') == 'gratis' ? 'selected' : '' }}>Gratis</option>
                            <option value="pago" {{ request('payment_type') == 'pago' ? 'selected' : '' }}>De Pago</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <select name="status" class="filter-select">
                            <option value="">Todos los estados</option>
                            <option value="borrador" {{ request('status') == 'borrador' ? 'selected' : '' }}>Borrador</option>
                            <option value="publicado" {{ request('status') == 'publicado' ? 'selected' : '' }}>Publicado</option>
                            <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search"></i>
                        Buscar
                    </button>
                    
                    @if(request()->hasAny(['search', 'type', 'payment_type', 'status']))
                    <a href="{{ route('admin.events') }}" class="btn-clear">
                        <i class="fas fa-times"></i>
                        Limpiar
                    </a>
                    @endif
                </form>
            </div>

            <!-- LISTA DE EVENTOS -->
            <div class="events-grid">
                @forelse($events as $event)
                <div class="event-card-admin">
                    <div class="event-image-admin">
                        @if($event->event_image)
                        <img src="{{ asset('storage/' . $event->event_image) }}" alt="{{ $event->title }}">
                        @else
                        <div class="event-placeholder">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        @endif
                        
                        <div class="event-badges">
                            @if($event->payment_type === 'pago')
                            <span class="badge badge-paid">
                                <i class="fas fa-dollar-sign"></i>
                                ${{ number_format($event->price, 2) }}
                            </span>
                            @else
                            <span class="badge badge-free">
                                <i class="fas fa-gift"></i>
                                Gratis
                            </span>
                            @endif

                            @if($event->status === 'publicado')
                            <span class="badge badge-success">Publicado</span>
                            @elseif($event->status === 'borrador')
                            <span class="badge badge-warning">Borrador</span>
                            @else
                            <span class="badge badge-danger">Cancelado</span>
                            @endif
                        </div>
                    </div>

                    <div class="event-content-admin">
                        <h3 class="event-title-admin">{{ $event->title }}</h3>
                        
                        <div class="event-meta">
                            <div class="meta-item">
                                <i class="fas fa-user"></i>
                                <strong>Organizador:</strong> {{ $event->host_name }}
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-envelope"></i>
                                <strong>Email:</strong> {{ $event->user->email }}
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <strong>Fecha:</strong> {{ $event->event_date->format('d/m/Y') }}
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <strong>Hora:</strong> {{ $event->event_time->format('H:i') }}
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <strong>Lugar:</strong> {{ $event->location }}
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-users"></i>
                                <strong>Asistentes:</strong> {{ $event->confirmed_invitations_count }}
                                @if($event->max_attendees)
                                / {{ $event->max_attendees }}
                                @endif
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-tag"></i>
                                <strong>Tipo:</strong> {{ ucfirst($event->type) }}
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-{{ $event->is_public ? 'globe' : 'lock' }}"></i>
                                <strong>Visibilidad:</strong> {{ $event->is_public ? 'Público' : 'Privado' }}
                            </div>
                        </div>

                        <div class="event-description">
                            <strong>Descripción:</strong>
                            <p>{{ Str::limit($event->description, 150) }}</p>
                        </div>

                        <div class="event-actions-admin">
                            <a href="{{ route('eventos.show', $event->id) }}" 
                               class="btn-action btn-primary" 
                               title="Ver detalles"
                               target="_blank">
                                <i class="fas fa-eye"></i>
                                Ver Detalles
                            </a>

                            @if($event->status !== 'publicado')
                            <form method="POST" action="{{ route('admin.events.approve', $event->id) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-action btn-success" title="Aprobar y publicar">
                                    <i class="fas fa-check"></i>
                                    Aprobar
                                </button>
                            </form>
                            @endif

                            @if($event->status === 'publicado')
                            <form method="POST" action="{{ route('admin.events.reject', $event->id) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn-action btn-warning" title="Ocultar evento">
                                    <i class="fas fa-eye-slash"></i>
                                    Ocultar
                                </button>
                            </form>
                            @endif

                            <button type="button" 
                                    class="btn-action btn-danger" 
                                    onclick="confirmDeleteEvent({{ $event->id }}, '{{ $event->title }}')"
                                    title="Eliminar evento">
                                <i class="fas fa-trash"></i>
                                Eliminar
                            </button>

                            <form id="delete-event-form-{{ $event->id }}" 
                                  method="POST" 
                                  action="{{ route('admin.events.delete', $event->id) }}" 
                                  style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No se encontraron eventos</h3>
                    <p>No hay eventos que coincidan con los filtros seleccionados</p>
                </div>
                @endforelse
            </div>

            <!-- PAGINACIÓN -->
            <div class="pagination-container">
                {{ $events->links() }}
            </div>
        </main>
    </div>

    

    <script>
    function confirmDeleteEvent(eventId, eventTitle) {
        if (confirm(`¿Estás seguro de eliminar el evento "${eventTitle}"?\n\nEsta acción no se puede deshacer y eliminará:\n- El evento\n- Todas sus invitaciones\n- Códigos QR asociados\n- Imágenes del evento`)) {
            document.getElementById('delete-event-form-' + eventId).submit();
        }
    }
    </script>
</body>
</html>