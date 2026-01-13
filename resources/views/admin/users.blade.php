<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Admin</title>
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
                <a href="{{ route('admin.users') }}" class="menu-item active">
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
                    <i class="fas fa-users"></i>
                    Gestión de Usuarios
                </h1>
                <p>Administra todos los usuarios del sistema</p>
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

            @if(session('info'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                {{ session('info') }}
            </div>
            @endif

            <!-- FILTROS -->
            <div class="filters-card">
                <form method="GET" action="{{ route('admin.users') }}" class="filters-form">
                    <div class="filter-group">
                        <input type="text"
                            name="search"
                            placeholder="Buscar por nombre o email..."
                            value="{{ request('search') }}"
                            class="filter-input">
                    </div>

                    <div class="filter-group">
                        <select name="role" class="filter-select">
                            <option value="">Todos los roles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administradores</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Usuarios</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search"></i>
                        Buscar
                    </button>

                    @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('admin.users') }}" class="btn-clear">
                        <i class="fas fa-times"></i>
                        Limpiar
                    </a>
                    @endif
                </form>
            </div>

            <!-- TABLA DE USUARIOS -->
            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Eventos</th>
                            <th>Stripe</th>
                            <th>Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="user-cell">
                                    <div class="user-avatar">
                                        {{ strtoupper(substr($user->firstName, 0, 1)) }}{{ strtoupper(substr($user->lastName, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $user->firstName }} {{ $user->lastName }}</strong>
                                        @if($user->id === Auth::id())
                                        <span class="badge badge-info">Tú</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->isAdmin())
                                <span class="badge badge-admin">
                                    <i class="fas fa-crown"></i>
                                    Admin
                                </span>
                                @else
                                <span class="badge badge-user">
                                    <i class="fas fa-user"></i>
                                    Usuario
                                </span>
                                @endif

                                @if(!$user->isActive())
                                <br>
                                <span class="badge badge-danger" style="margin-top: 5px;">
                                    <i class="fas fa-ban"></i>
                                    Suspendido
                                </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-neutral">
                                    {{ $user->events_count }} eventos
                                </span>
                            </td>
                            <td>
                                @if($user->hasStripeAccount())
                                <span class="badge badge-success">
                                    <i class="fab fa-stripe"></i>
                                    Conectado
                                </span>
                                @else
                                <span class="badge badge-muted">
                                    Sin conectar
                                </span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    @if($user->isAdmin())
                                    @if($user->id !== Auth::id())
                                    <form method="POST" action="{{ route('admin.users.demote', $user->id) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-action btn-warning" title="Degradar a usuario">
                                            <i class="fas fa-arrow-down"></i>
                                            Degradar
                                        </button>
                                    </form>
                                    @endif
                                    @else
                                    <form method="POST" action="{{ route('admin.users.promote', $user->id) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-action btn-success" title="Promover a admin">
                                            <i class="fas fa-arrow-up"></i>
                                            Promover
                                        </button>
                                    </form>
                                    @endif

                                    @if($user->id !== Auth::id())
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user->id) }}" style="display: inline;">
                                        @csrf
                                        @if($user->is_active ?? true)
                                        <button type="submit" class="btn-action btn-warning" title="Suspender cuenta">
                                            <i class="fas fa-ban"></i>
                                            Suspender
                                        </button>
                                        @else
                                        <button type="submit" class="btn-action btn-info" title="Activar cuenta">
                                            <i class="fas fa-check-circle"></i>
                                            Activar
                                        </button>
                                        @endif
                                    </form>

                                    <button type="button"
                                        class="btn-action btn-danger"
                                        onclick="confirmDelete({{ $user->id }}, '{{ $user->firstName }} {{ $user->lastName }}')"
                                        title="Eliminar usuario">
                                        <i class="fas fa-trash"></i>
                                        Eliminar
                                    </button>

                                    <form id="delete-form-{{ $user->id }}"
                                        method="POST"
                                        action="{{ route('admin.users.delete', $user->id) }}"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <p style="padding: 2rem; color: var(--text-muted);">
                                    <i class="fas fa-users" style="font-size: 3rem; opacity: 0.3;"></i><br>
                                    No se encontraron usuarios
                                </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINACIÓN -->
            <div class="pagination-container">
                {{ $users->links() }}
            </div>
        </main>
    </div>

    

    <script>
        function confirmDelete(userId, userName) {
            if (confirm(`¿Estás seguro de eliminar al usuario "${userName}"?\n\nEsta acción no se puede deshacer.`)) {
                document.getElementById('delete-form-' + userId).submit();
            }
        }
    </script>
</body>

</html>