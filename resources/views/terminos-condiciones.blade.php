<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Condiciones - InvitaMex</title>
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
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 8px;
        }

        .highlight-box p {
            margin: 0;
            color: #856404;
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
            <h1><i class="fas fa-file-contract"></i> Términos y Condiciones</h1>
            <p class="last-update">Última actualización: {{ date('d/m/Y') }}</p>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-handshake"></i> 1. Aceptación de los Términos</h2>
            <p>
                Al acceder y utilizar InvitaMex, aceptas estar sujeto a estos Términos y Condiciones. 
                Si no estás de acuerdo con alguno de estos términos, no utilices nuestra plataforma.
            </p>
            <div class="highlight-box">
                <p><strong>Importante:</strong> El uso continuado de la plataforma implica la aceptación de estos términos y sus actualizaciones.</p>
            </div>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-user-check"></i> 2. Elegibilidad</h2>
            <p>Para usar InvitaMex debes:</p>
            <ul>
                <li>Tener al menos 18 años de edad</li>
                <li>Proporcionar información veraz y actualizada</li>
                <li>Tener capacidad legal para celebrar contratos vinculantes</li>
                <li>No estar suspendido o baneado de la plataforma</li>
            </ul>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-user-circle"></i> 3. Cuenta de Usuario</h2>
            
            <h3>3.1 Registro</h3>
            <p>
                Al crear una cuenta, te comprometes a proporcionar información precisa y mantenerla actualizada.
                Eres responsable de mantener la confidencialidad de tu contraseña.
            </p>

            <h3>3.2 Responsabilidad</h3>
            <ul>
                <li>Eres responsable de toda actividad en tu cuenta</li>
                <li>Debes notificarnos inmediatamente sobre cualquier uso no autorizado</li>
                <li>No puedes compartir tu cuenta con terceros</li>
                <li>Debes mantener tu información de contacto actualizada</li>
            </ul>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-calendar-check"></i> 4. Uso de la Plataforma</h2>
            
            <h3>4.1 Creación de Eventos</h3>
            <ul>
                <li>Puedes crear eventos gratuitos o de pago</li>
                <li>Los eventos de pago requieren una cuenta de Stripe Connect verificada</li>
                <li>Debes proporcionar información veraz sobre tus eventos</li>
                <li>No puedes crear eventos falsos o engañosos</li>
            </ul>

            <h3>4.2 Contenido Prohibido</h3>
            <p>Está prohibido publicar contenido que:</p>
            <ul>
                <li>Sea ilegal, fraudulento o engañoso</li>
                <li>Promueva violencia, discriminación u odio</li>
                <li>Viole derechos de propiedad intelectual</li>
                <li>Contenga malware o código malicioso</li>
                <li>Sea obsceno o inapropiado</li>
            </ul>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-credit-card"></i> 5. Pagos y Comisiones</h2>
            
            <h3>5.1 Procesamiento de Pagos</h3>
            <ul>
                <li>Los pagos se procesan a través de Stripe</li>
                <li>InvitaMex cobra una comisión del 10% por cada transacción</li>
                <li>El organizador recibe el 90% del monto total</li>
                <li>Los pagos son finales y no reembolsables salvo excepciones legales</li>
            </ul>

            <h3>5.2 Reembolsos</h3>
            <p>
                Los reembolsos solo se otorgan en casos excepcionales como:
            </p>
            <ul>
                <li>Cancelación del evento por parte del organizador</li>
                <li>Error en el procesamiento del pago</li>
                <li>Fraude comprobado</li>
            </ul>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-qrcode"></i> 6. Códigos QR e Invitaciones</h2>
            <ul>
                <li>Los códigos QR son únicos y personales</li>
                <li>No compartas tus códigos QR con terceros no autorizados</li>
                <li>El organizador puede validar códigos QR en tiempo real</li>
                <li>InvitaMex no es responsable del uso indebido de códigos QR</li>
            </ul>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-copyright"></i> 7. Propiedad Intelectual</h2>
            <p>
                Todo el contenido de InvitaMex (diseño, logotipos, código) es propiedad de InvitaMex 
                o sus licenciantes y está protegido por leyes de propiedad intelectual.
            </p>
            <ul>
                <li>Conservas los derechos sobre el contenido que subas (fotos, descripciones)</li>
                <li>Nos otorgas una licencia para usar ese contenido en nuestra plataforma</li>
                <li>No puedes usar nuestro contenido sin autorización</li>
            </ul>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-ban"></i> 8. Prohibiciones</h2>
            <p>No está permitido:</p>
            <ul>
                <li>Realizar ingeniería inversa de la plataforma</li>
                <li>Usar bots o scripts automatizados</li>
                <li>Recopilar datos de otros usuarios sin consentimiento</li>
                <li>Interferir con el funcionamiento de la plataforma</li>
                <li>Crear múltiples cuentas para evadir restricciones</li>
                <li>Revender o transferir tu cuenta</li>
            </ul>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-exclamation-triangle"></i> 9. Limitación de Responsabilidad</h2>
            <p>
                InvitaMex se proporciona "tal cual" sin garantías de ningún tipo. No somos responsables de:
            </p>
            <ul>
                <li>Cancelaciones de eventos por parte de organizadores</li>
                <li>Disputas entre usuarios</li>
                <li>Pérdida de datos debido a problemas técnicos</li>
                <li>Daños indirectos o consecuentes</li>
                <li>Eventos que no cumplan las expectativas</li>
            </ul>
            <div class="highlight-box">
                <p><strong>Nota:</strong> Nuestra responsabilidad está limitada al monto pagado por el servicio.</p>
            </div>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-user-slash"></i> 10. Suspensión y Terminación</h2>
            <p>Nos reservamos el derecho de:</p>
            <ul>
                <li>Suspender o terminar cuentas que violen estos términos</li>
                <li>Eliminar contenido inapropiado sin previo aviso</li>
                <li>Modificar o descontinuar servicios</li>
            </ul>
            <!-- <p>
                Los usuarios pueden cerrar su cuenta en cualquier momento desde su perfil.
            </p> -->
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-balance-scale"></i> 11. Ley Aplicable</h2>
            <p>
                Estos términos se rigen por las leyes de México. Cualquier disputa será resuelta 
                en los tribunales competentes de México.
            </p>
        </div>

        <div class="legal-section">
            <h2><i class="fas fa-sync-alt"></i> 12. Modificaciones</h2>
            <p>
                Podemos modificar estos términos en cualquier momento. Los cambios significativos 
                se notificarán por correo electrónico o mediante un aviso en la plataforma.
            </p>
            <p>
                El uso continuado de InvitaMex después de los cambios constituye aceptación de los nuevos términos.
            </p>
        </div>

        <div class="contact-info">
            <h3><i class="fas fa-question-circle"></i> ¿Tienes Dudas?</h3>
            <p>
                Si tienes preguntas sobre estos Términos y Condiciones, contáctanos:
            </p>
            <p><strong>Email:</strong> <a href="mailto:invitacleth@gmail.com">invitacleth@gmail.com</a></p>
            <p><strong>Contacto:</strong> <a href="{{ route('contacto') }}">Página de Contacto</a></p>
        </div>
    </div>

    @include('partials.footer')
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>