<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - InvitaCleth</title>
</head>
<body>
    <div style="padding: 2rem;">
        <h1>¡Bienvenido, {{ Auth::user()->firstName }}!</h1>
        <p>Email: {{ Auth::user()->email }}</p>
        
        <form action="{{ route('logout') }}" method="POST" style="margin-top: 2rem;">
            @csrf
            <button type="submit" style="padding: 0.5rem 1rem; cursor: pointer;">
                Cerrar Sesión
            </button>
        </form>
    </div>
</body>
</html>