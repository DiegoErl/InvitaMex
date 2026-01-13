<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Restablecer Contraseña - InvitaCleth</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    @include('partials.header')

    <main class="main-content">
        <div class="login-container">
            <div class="welcome-content animate-on-scroll">
                <h1>Nueva Contraseña</h1>
                <p>
                    Estás a un paso de recuperar tu cuenta. Crea una nueva contraseña 
                    segura y vuelve a disfrutar de InvitaCleth.
                </p>

                <div class="features-preview">
                    <div class="feature-preview">
                        <div class="feature-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="feature-text">Mínimo 8 caracteres</div>
                    </div>

                    <div class="feature-preview">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="feature-text">Combinación de letras y números</div>
                    </div>

                    <div class="feature-preview">
                        <div class="feature-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="feature-text">Totalmente seguro</div>
                    </div>
                </div>
            </div>

            <div class="login-form-container animate-on-scroll">
                <div class="form-header">
                    <h2>Restablecer Contraseña</h2>
                    <p>Crea tu nueva contraseña</p>
                </div>

                <!-- ERROR MESSAGE -->
                <div class="error-message" id="errorMessage" style="display: none;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="errorText"></span>
                </div>

                <!-- SUCCESS MESSAGE -->
                <div class="success-message" id="successMessage" style="display: none;">
                    <i class="fas fa-check-circle"></i>
                    <span>¡Contraseña actualizada! Redirigiendo al login...</span>
                </div>

                <form class="login-form" id="resetPasswordForm">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="form-group">
                        <label for="email_display" class="form-label">Correo Electrónico</label>
                        <input type="email" id="email_display" class="form-input"
                            value="{{ $email }}" disabled>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <div class="password-input-container">
                            <input type="password" id="password" name="password" class="form-input"
                                placeholder="Mínimo 8 caracteres" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password', 'passwordIcon')">
                                <i class="fas fa-eye" id="passwordIcon"></i>
                            </button>
                        </div>
                        <div class="field-error" id="passwordError" style="display: none;"></div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                        <div class="password-input-container">
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                                placeholder="Confirma tu contraseña" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', 'passwordConfirmIcon')">
                                <i class="fas fa-eye" id="passwordConfirmIcon"></i>
                            </button>
                        </div>
                        <div class="field-error" id="passwordConfirmError" style="display: none;"></div>
                    </div>

                    <button type="submit" class="login-btn" id="submitBtn">
                        <i class="fas fa-key"></i>
                        Restablecer Contraseña
                    </button>
                </form>
            </div>
        </div>
    </main>

    @include('partials.footer')

    <script>
        const form = document.getElementById('resetPasswordForm');
        const submitBtn = document.getElementById('submitBtn');
        const errorMessage = document.getElementById('errorMessage');
        const successMessage = document.getElementById('successMessage');

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Ocultar mensajes
            errorMessage.style.display = 'none';
            successMessage.style.display = 'none';

            // Validar que las contraseñas coincidan
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;

            if (password !== passwordConfirm) {
                errorMessage.style.display = 'flex';
                document.getElementById('errorText').textContent = 'Las contraseñas no coinciden';
                return;
            }

            if (password.length < 8) {
                errorMessage.style.display = 'flex';
                document.getElementById('errorText').textContent = 'La contraseña debe tener al menos 8 caracteres';
                return;
            }

            // Deshabilitar botón
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';

            const formData = new FormData(form);

            try {
                const response = await fetch("{{ route('password.update') }}", {
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
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    errorMessage.style.display = 'flex';
                    document.getElementById('errorText').textContent = data.message;
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-key"></i> Restablecer Contraseña';
                }
            } catch (error) {
                errorMessage.style.display = 'flex';
                document.getElementById('errorText').textContent = 'Error al actualizar la contraseña. Intenta de nuevo.';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-key"></i> Restablecer Contraseña';
            }
        });
    </script>
</body>

</html>