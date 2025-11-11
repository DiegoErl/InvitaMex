<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión </title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>
    <!-- LOADING OVERLAY -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <h3>Iniciando sesión...</h3>
            <p>Un momento por favor</p>
        </div>
    </div>

    <!-- HEADER -->
    @include('partials.header')

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="login-container">
            <!-- WELCOME CONTENT -->
            <div class="welcome-content animate-on-scroll">
                <h1>¡Bienvenido de Vuelta!</h1>
                <p>
                    Accede a tu cuenta de InvitaMex y continúa creando invitaciones
                    digitales increíbles para tus eventos más especiales.
                </p>

                <div class="features-preview">
                    <div class="feature-preview">
                        <div class="feature-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <div class="feature-text">Códigos QR Automáticos</div>
                    </div>

                    <div class="feature-preview">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="feature-text">Gestión de Invitados</div>
                    </div>

                    <div class="feature-preview">
                        <div class="feature-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <div class="feature-text">100% Ecológico</div>
                    </div>
                </div>
            </div>

            <!-- LOGIN FORM -->
            <div class="login-form-container animate-on-scroll">
                <div class="form-header">
                    <h2>Iniciar Sesión</h2>
                    <p>Ingresa tus credenciales para acceder a tu cuenta</p>
                </div>

                <!-- ERROR MESSAGE -->
                <div class="error-message" id="errorMessage">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="errorText">Error en las credenciales</span>
                </div>

                <!-- SUCCESS MESSAGE -->
                <div class="success-message" id="successMessage">
                    <i class="fas fa-check-circle"></i>
                    <span>¡Inicio de sesión exitoso! Redirigiendo...</span>
                </div>

                <!-- SOCIAL LOGIN -->
                <div class="social-login">
                    <button class="social-btn google-btn" onclick="loginWithGoogle()">
                        <i class="fab fa-google"></i>
                        Continuar con Google
                    </button>

                    <!-- <button class="social-btn facebook-btn" onclick="loginWithFacebook()">
                        <i class="fab fa-facebook-f"></i>
                        Continuar con Facebook
                    </button> -->
                </div>

                <div class="divider">
                    <span>o continúa con email</span>
                </div>

                <!-- LOGIN FORM -->
                <form class="login-form" id="loginForm" data-login-url="{{ route('login.store') }}" data-perfil-url="{{ route('perfil') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" id="email" name="email" class="form-input"
                            placeholder="tu@email.com" required>
                        <div class="field-error" id="emailError" style="display: none;">Este campo es obligatorio</div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="password-input-container">
                            <input type="password" id="password" name="password" class="form-input"
                                placeholder="Tu contraseña" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="passwordIcon"></i>
                            </button>
                        </div>
                        <div class="field-error" id="passwordError" style="display: none;">Este campo es obligatorio</div>
                    </div>

                    <div id="generalError" class="field-error" style="display: none; margin-bottom: 1rem;"></div>

                    <div class="form-options">
                        <div class="checkbox-group">
                            <input type="checkbox" id="remember" name="remember" value="1">
                            <label for="remember">Recordarme</label>
                        </div>
                        <a href="#forgot-password" class="forgot-password" onclick="showForgotPassword()">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <button type="submit" class="login-btn" id="loginBtn">
                        <i class="fas fa-sign-in-alt"></i>
                        Iniciar Sesión
                    </button>
                </form>

                <div class="signup-link">
                    ¿No tienes una cuenta?
                    <a href="{{ route('register') }}" onclick="showRegisterForm()">Regístrate aquí</a>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    @include('partials.footer')

    <!-- BACK TO TOP BUTTON -->
    <button class="back-to-top" id="backToTop" aria-label="Volver arriba">
        <i class="fas fa-chevron-up"></i>
    </button>

    <script src="{{ asset('js/login.js') }}" defer></script>


</body>

</html>