<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recuperar Contraseña - InvitaCleth</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    @include('partials.header')

    <main class="main-content">
        <div class="login-container">
            <div class="welcome-content animate-on-scroll">
                <h1>¿Olvidaste tu Contraseña?</h1>
                <p>
                    No te preocupes, es normal. Ingresa tu correo electrónico y te 
                    enviaremos un enlace para que puedas restablecer tu contraseña.
                </p>

                <div class="features-preview">
                    <div class="feature-preview">
                        <div class="feature-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="feature-text">Recibe el enlace por correo</div>
                    </div>

                    <div class="feature-preview">
                        <div class="feature-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="feature-text">Crea una nueva contraseña</div>
                    </div>

                    <div class="feature-preview">
                        <div class="feature-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="feature-text">Recupera tu cuenta</div>
                    </div>
                </div>
            </div>

            <div class="login-form-container animate-on-scroll">
                <div class="form-header">
                    <h2>Recuperar Contraseña</h2>
                    <p>Ingresa tu correo electrónico registrado</p>
                </div>

                <!-- ERROR MESSAGE -->
                <div class="error-message" id="errorMessage" style="display: none;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="errorText"></span>
                </div>

                <!-- SUCCESS MESSAGE -->
                <div class="success-message" id="successMessage" style="display: none;">
                    <i class="fas fa-check-circle"></i>
                    <span id="successText"></span>
                </div>

                <form class="login-form" id="forgotPasswordForm">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" id="email" name="email" class="form-input"
                            placeholder="tu@email.com" required autofocus>
                        <div class="field-error" id="emailError" style="display: none;"></div>
                    </div>

                    <button type="submit" class="login-btn" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        Enviar Enlace de Recuperación
                    </button>
                </form>

                <div class="signup-link">
                    ¿Recordaste tu contraseña?
                    <a href="{{ route('login') }}">Inicia sesión aquí</a>
                </div>
            </div>
        </div>
    </main>

    @include('partials.footer')

    <script>
        const form = document.getElementById('forgotPasswordForm');
        const submitBtn = document.getElementById('submitBtn');
        const errorMessage = document.getElementById('errorMessage');
        const successMessage = document.getElementById('successMessage');
        const emailError = document.getElementById('emailError');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Ocultar mensajes
            errorMessage.style.display = 'none';
            successMessage.style.display = 'none';
            emailError.style.display = 'none';

            // Deshabilitar botón
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';

            const formData = new FormData(form);

            try {
                const response = await fetch("{{ route('password.email') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    successMessage.style.display = 'flex';
                    document.getElementById('successText').textContent = data.message;
                    form.reset();
                } else {
                    errorMessage.style.display = 'flex';
                    document.getElementById('errorText').textContent = data.message;
                }
            } catch (error) {
                errorMessage.style.display = 'flex';
                document.getElementById('errorText').textContent = 'Error al enviar el correo. Intenta de nuevo.';
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar Enlace de Recuperación';
            }
        });
    </script>
</body>

</html>