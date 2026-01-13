<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - InvitaCleth</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
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
                <a href="{{ route('admin.dashboard') }}" class="menu-item active">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.users') }}" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
                <a href="{{ route('admin.events') }}" class="menu-item">
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
                    <i class="fas fa-chart-line"></i>
                    Panel de Administración
                </h1>
                <p>Vista general del sistema</p>
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

            <!-- ESTADÍSTICAS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: var(--primary-gradient);">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $stats['total_users'] }}</h3>
                        <p>Total Usuarios</p>
                        <span class="stat-detail">{{ $stats['total_admins'] }} admins</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: var(--secondary-gradient);">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $stats['total_events'] }}</h3>
                        <p>Total Eventos</p>
                        <span class="stat-detail">{{ $stats['pending_events'] }} pendientes</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: var(--success-gradient);">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-content">
                        <h3>${{ number_format($stats['total_revenue'], 2) }}</h3>
                        <p>Ingresos Totales</p>
                        <span class="stat-detail">{{ $stats['paid_events'] }} eventos de pago</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: var(--accent-gradient);">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div class="stat-content">
                        <h3>{{ $stats['free_events'] }}</h3>
                        <p>Eventos Gratuitos</p>
                        <span class="stat-detail">Sin costo</span>
                    </div>
                </div>
            </div>

            <!-- ACCESOS RÁPIDOS -->
            <div class="quick-actions">
                <h2>Acciones Rápidas</h2>
                <div class="actions-grid">
                    <a href="{{ route('admin.users') }}" class="action-card">
                        <i class="fas fa-users"></i>
                        <h3>Gestionar Usuarios</h3>
                        <p>Ver, editar y administrar usuarios</p>
                    </a>
                    <a href="{{ route('admin.events') }}" class="action-card">
                        <i class="fas fa-calendar-check"></i>
                        <h3>Gestionar Eventos</h3>
                        <p>Aprobar, rechazar y eliminar eventos</p>
                    </a>
                    <a href="{{ route('eventos') }}" class="action-card">
                        <i class="fas fa-eye"></i>
                        <h3>Ver Sitio Público</h3>
                        <p>Ver la página como usuario normal</p>
                    </a>
                    <a href="{{ route('perfil') }}" class="action-card">
                        <i class="fas fa-user-circle"></i>
                        <h3>Mi Perfil</h3>
                        <p>Ver y editar tu información</p>
                    </a>
                </div>
            </div>
        </main>
    </div>

   
</body>
</html>