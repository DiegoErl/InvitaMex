<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Suspendida - InvitaCleth</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Inter', sans-serif;
        }

        .suspended-container {
            max-width: 600px;
            margin: 2rem;
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .suspended-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #ef5350 0%, #e53935 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 4rem;
            box-shadow: 0 10px 30px rgba(239, 83, 80, 0.3);
        }

        .suspended-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .suspended-message {
            color: #6c757d;
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .suspended-details {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: left;
        }

        .suspended-details h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .suspended-details ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .suspended-details li {
            padding: 0.5rem 0;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .suspended-details li i {
            color: #ef5350;
            font-size: 1.2rem;
        }

        .contact-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1.5rem;
            border-radius: 10px;
            color: white;
            margin-bottom: 2rem;
        }

        .contact-info h3 {
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .contact-info p {
            margin: 0.5rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .contact-info a {
            color: white;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-logout {
            background: linear-gradient(135deg, #ef5350 0%, #e53935 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(239, 83, 80, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-logout:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(239, 83, 80, 0.5);
        }

        .user-info {
            background: rgba(102, 126, 234, 0.1);
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            border-left: 4px solid #667eea;
        }

        .user-info strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="suspended-container">
        <div class="suspended-icon">
            <i class="fas fa-ban"></i>
        </div>

        <h1 class="suspended-title">Cuenta Suspendida</h1>

        <p class="suspended-message">
            Tu cuenta ha sido suspendida temporalmente por el equipo de soporte de InvitaCleth.
            No puedes acceder a ninguna funcionalidad del sistema hasta que se reactive tu cuenta.
        </p>

        @if(Auth::check())
        <div class="user-info">
            <strong>Cuenta:</strong> {{ Auth::user()->email }}
        </div>
        @endif

        <div class="suspended-details">
            <h3><i class="fas fa-exclamation-triangle"></i> Restricciones Activas</h3>
            <ul>
                <li>
                    <i class="fas fa-times-circle"></i>
                    <span>No puedes crear eventos</span>
                </li>
                <li>
                    <i class="fas fa-times-circle"></i>
                    <span>No puedes confirmar asistencia a eventos</span>
                </li>
                <li>
                    <i class="fas fa-times-circle"></i>
                    <span>No puedes editar tu perfil</span>
                </li>
                <li>
                    <i class="fas fa-times-circle"></i>
                    <span>No puedes realizar pagos</span>
                </li>
                <li>
                    <i class="fas fa-times-circle"></i>
                    <span>Acceso limitado a todas las funciones</span>
                </li>
            </ul>
        </div>

        <div class="contact-info">
            <h3><i class="fas fa-headset"></i> ¿Necesitas ayuda?</h3>
            <p>
                <i class="fas fa-envelope"></i>
                <a href="mailto:soporte@invitacleth.com">invitacleth@gmail.com</a>
            </p>
            <p>
                <i class="fas fa-phone"></i>
                <a href="tel:+525512345678">+52 55 1234 5678</a>
            </p>
            <p style="margin-top: 1rem; font-size: 0.9rem; opacity: 0.9;">
                Nuestro equipo de soporte revisará tu caso y te contactará a la brevedad.
            </p>
        </div>

        <form action="{{ route('logout') }}" method="POST" id="logout-form">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                Cerrar Sesión
            </button>
        </form>

        <p style="margin-top: 2rem; color: #6c757d; font-size: 0.9rem;">
            Si crees que esto es un error, por favor contacta a soporte.
        </p>
    </div>

    <script>
        // NO prevenir el envío del formulario de logout
        document.getElementById('logout-form').addEventListener('submit', function(e) {
            // Permitir que el formulario se envíe normalmente
            return true;
        });

        // Prevenir que el usuario regrese con el botón atrás
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script>
</body>
</html>