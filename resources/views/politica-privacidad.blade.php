<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad - InvitaMex</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
            padding-top: 100px;
        }

        .legal-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 3rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .legal-header {
            text-align: center;
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 3px solid #667eea;
        }

        .legal-header h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .legal-header .last-update {
            color: #666;
            font-size: 0.95rem;
        }

        .legal-section {
            margin-bottom: 2.5rem;
        }

        .legal-section h2 {
            color: #667eea;
            font-size: 1.8rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .legal-section h3 {
            color: #333;
            font-size: 1.3rem;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .legal-section p {
            color: #555;
            line-height: 1.8;
            margin-bottom: 1rem;
            font-size: 1.05rem;
        }

        .legal-section ul {
            margin-left: 2rem;
            margin-bottom: 1rem;
        }

        .legal-section li {
            color: #555;
            line-height: 1.8;
            margin-bottom: 0.5rem;
        }

        .highlight-box {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 8px;
        }

        .highlight-box p {
            margin: 0;
            color: #333;
        }

        .contact-info {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-top: 3rem;
            text-align: center;
        }

        .contact-info h3 {
            color: white;
            margin-bottom: 1rem;
        }

        .contact-info a {
            color: white;
            text-decoration: underline;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #764ba2;
            transform: translateX(-5px);
        }

        @media (max-width: 768px) {
            body {
                padding-top: 80px;
            }

            .legal-container {
                margin: 1rem;
                padding: 2rem 1.5rem;
            }

            .legal-header h1 {
                font-size: 2rem;
            }

            .legal-section h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="legal-container">
        <a href="{{ route('home') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Volver al Inicio
        </a>

        <div class="legal-header">
            <h1><i class="fas fa-shield-alt"></i> Política de Privacidad</h1>
            <p class="last-update">Última actualización: {{ date('d/m/Y') }}</p>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-info-circle"></i> 1. Introducción</h2>
            <p>
                En InvitaMex, nos comprometemos a proteger tu privacidad y tus datos personales. 
                Esta Política de Privacidad explica cómo recopilamos, usamos, compartimos y protegemos 
                tu información personal cuando utilizas nuestra plataforma.
            </p>
            <div class="highlight-box">
                <p><strong>Importante:</strong> Al utilizar InvitaMex, aceptas las prácticas descritas en esta Política de Privacidad.</p>
            </div>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-database"></i> 2. Información que Recopilamos</h2>
            
            <h3>2.1 Información que Proporcionas Directamente</h3>
            <ul>
                <li><strong>Cuenta de Usuario:</strong> Nombre, correo electrónico, contraseña, foto de perfil</li>
                <li><strong>Eventos:</strong> Título, descripción, fecha, ubicación, imágenes del evento</li>
                <li><strong>Información de Pago:</strong> Procesada de forma segura a través de Stripe (no almacenamos datos de tarjetas)</li>
                <li><strong>Invitaciones:</strong> Códigos QR generados, confirmaciones de asistencia</li>
            </ul>

            <!-- <h3>2.2 Información Recopilada Automáticamente</h3>
            <ul>
                <li>Dirección IP y ubicación geográfica aproximada</li>
                <li>Tipo de dispositivo y navegador</li>
                <li>Páginas visitadas y tiempo de navegación</li>
                <li>Cookies y tecnologías similares</li>
            </ul> -->
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-tasks"></i> 3. Cómo Usamos tu Información</h2>
            <p>Utilizamos tu información personal para:</p>
            <ul>
                <li>Crear y gestionar tu cuenta de usuario</li>
                <li>Procesar pagos y generar invitaciones</li>
                <!-- <li>Enviar notificaciones sobre tus eventos</li> -->
                <li>Mejorar nuestros servicios y experiencia de usuario</li>
                <li>Cumplir con obligaciones legales</li>
                <li>Prevenir fraudes y garantizar la seguridad</li>
            </ul>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-share-alt"></i> 4. Compartir tu Información</h2>
            <p>No vendemos tu información personal. Solo la compartimos en los siguientes casos:</p>
            <ul>
                <li><strong>Proveedores de Servicios:</strong> Stripe para procesar pagos</li>
                <li><strong>Eventos Públicos:</strong> La información de eventos públicos es visible para todos los usuarios</li>
                <li><strong>Requisitos Legales:</strong> Cuando sea requerido por ley o autoridades</li>
            </ul>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-lock"></i> 5. Seguridad de los Datos</h2>
            <p>
                Implementamos medidas de seguridad técnicas y organizativas para proteger tu información:
            </p>
            <ul>
                <li>Cifrado SSL/TLS para todas las transmisiones de datos</li>
                <li>Almacenamiento seguro de contraseñas con hash</li>
                <li>Acceso restringido a datos personales</li>
                <li>Monitoreo continuo de seguridad</li>
            </ul>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-user-shield"></i> 6. Tus Derechos</h2>
            <p>Tienes derecho a:</p>
            <ul>
                <li><strong>Acceder</strong> a tu información personal</li>
                <li><strong>Rectificar</strong> datos incorrectos o incompletos</li>
                
                <li><strong>Oponerte</strong> al procesamiento de tus datos</li>
                <li><strong>Portabilidad</strong> de tus datos a otra plataforma</li>
                <li><strong>Retirar consentimiento</strong> en cualquier momento</li>
            </ul>
            <div class="highlight-box">
                <p>Para ejercer estos derechos, contáctanos a través de nuestro correo de soporte.</p>
            </div>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-cookie-bite"></i> 7. Cookies</h2>
            <p>
                Utilizamos cookies y tecnologías similares para mejorar tu experiencia. Puedes configurar 
                tu navegador para rechazar cookies, aunque esto puede afectar la funcionalidad del sitio.
            </p>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-child"></i> 8. Menores de Edad</h2>
            <p>
                Nuestros servicios están dirigidos a personas mayores de 18 años. No recopilamos 
                intencionalmente información de menores de edad.
            </p>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-edit"></i> 9. Cambios a esta Política</h2>
            <p>
                Podemos actualizar esta Política de Privacidad periódicamente. Te notificaremos sobre 
                cambios significativos a través de correo electrónico o un aviso en nuestra plataforma.
            </p>
        </div>

        <div class="contact-info">
            <h3><i class="fas fa-envelope"></i> ¿Tienes Preguntas?</h3>
            <p>
                Si tienes dudas sobre esta Política de Privacidad o cómo manejamos tus datos, 
                contáctanos en:
            </p>
            <p><strong>Email:</strong> <a href="mailto:invitacleth@gmail.com">invitacleth@gmail.com</a></p>
            <p><strong>También puedes visitarnos en:</strong> <a href="{{ route('contacto') }}">Página de Contacto</a></p>
        </div>
    </div>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>