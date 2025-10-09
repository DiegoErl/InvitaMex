<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse </title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
</head>
<body>
    <!-- LOADING OVERLAY -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <h3>Creando tu cuenta...</h3>
            <p>Un momento por favor</p>
        </div>
    </div>

    <!-- HEADER -->
    @include('partials.header')

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="register-container">
            <!-- WELCOME CONTENT -->
            <div class="welcome-content animate-on-scroll">
                <h1>¡Únete a InvitaMex!</h1>
                <p>
                    Crea tu cuenta gratuita y comienza a diseñar invitaciones digitales 
                    increíbles para tus eventos más especiales. Sin compromisos, sin papeleos.
                </p>
                
                <ul class="benefits-list">
                    <li class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-gift"></i>
                        </div>
                        <div class="benefit-text">
                            <strong>Cuenta gratuita</strong><br>
                            Hasta 3 eventos por mes sin costo alguno
                        </div>
                    </li>
                    
                    <li class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <div class="benefit-text">
                            <strong>Personalización total</strong><br>
                            Colores, fuentes, imágenes y diseños únicos
                        </div>
                    </li>
                    
                    <li class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <div class="benefit-text">
                            <strong>Códigos QR automáticos</strong><br>
                            Acceso rápido y confirmación de asistencia
                        </div>
                    </li>
                    
                    <li class="benefit-item">
                        <div class="benefit-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <div class="benefit-text">
                            <strong>100% ecológico</strong><br>
                            Ayuda a salvar el planeta con cada invitación
                        </div>
                    </li>
                </ul>
            </div>
            
            <!-- REGISTER FORM -->
            <div class="register-form-container animate-on-scroll">
                <div class="form-header">
                    <h2>Crear Cuenta</h2>
                    <p>Completa tus datos para comenzar</p>
                </div>
                
                <!-- ERROR MESSAGE -->
                <div class="error-message" id="errorMessage">
                    <i class="fas fa-exclamation-circle"></i>
                    <span id="errorText">Error en el registro</span>
                </div>
                
                <!-- SUCCESS MESSAGE -->
                <div class="success-message" id="successMessage">
                    <i class="fas fa-check-circle"></i>
                    <span>¡Cuenta creada exitosamente! Verificando email...</span>
                </div>
                
                <!-- SOCIAL REGISTER -->
                <div class="social-register">
                    <button class="social-btn google-btn" onclick="registerWithGoogle()">
                        <i class="fab fa-google"></i>
                        Registrarse con Google
                    </button>
                    
                    <!-- <button class="social-btn facebook-btn" onclick="registerWithFacebook()">
                        <i class="fab fa-facebook-f"></i>
                        Registrarse con Facebook
                    </button> -->
                </div>
                
                <div class="divider">
                    <span>o regístrate con email</span>
                </div>
                
                <!-- REGISTER FORM -->
                <form class="register-form" id="registerForm"  data-register-url="{{ route('register.store') }}"
      data-login-url="{{ route('login') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName" class="form-label">Nombre *</label>
                            <input type="text" id="firstName" name="firstName" class="form-input" 
                                   placeholder="Tu nombre" required>
                            <div class="field-error" id="firstNameError">Este campo es obligatorio</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="lastName" class="form-label">Apellido *</label>
                            <input type="text" id="lastName" name="lastName" class="form-input" 
                                   placeholder="Tu apellido" required>
                            <div class="field-error" id="lastNameError">Este campo es obligatorio</div>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="email" class="form-label">Correo Electrónico *</label>
                        <input type="email" id="email" name="email" class="form-input" 
                               placeholder="tu@email.com" required>
                        <div class="field-error" id="emailError">Ingresa un email válido</div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="tel" id="phone" name="phone" class="form-input" 
                                   placeholder="+52 55 1234 5678">
                        </div>
                        
                        <div class="form-group">
                            <label for="country" class="form-label">País</label>
                            <select id="country" name="country" class="form-select">
                                <option value="">Selecciona tu país</option>
                                <option value="MX" selected>México</option>
                                <option value="US">Estados Unidos</option>
                                <option value="CA">Canadá</option>
                                <option value="ES">España</option>
                                <option value="AR">Argentina</option>
                                <option value="CO">Colombia</option>
                                <option value="PE">Perú</option>
                                <option value="CL">Chile</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="password" class="form-label">Contraseña *</label>
                        <div class="password-input-container">
                            <input type="password" id="password" name="password" class="form-input" 
                                   placeholder="Mínimo 8 caracteres" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password')" aria-label="Mostrar u ocultar contraseña">
                                <i class="fas fa-eye" id="passwordIcon"></i>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthBar"></div>
                            </div>
                            <span class="strength-text" id="strengthText">Fortaleza de contraseña</span>
                        </div>
                        <div class="field-error" id="passwordError">La contraseña debe tener al menos 8 caracteres</div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="confirmPassword" class="form-label">Confirmar Contraseña *</label>
                        <div class="password-input-container">
                            <input type="password" id="confirmPassword" name="password_confirmation" class="form-input" 
                                   placeholder="Repite tu contraseña" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')" aria-label="Mostrar u ocultar contraseña">
                                <i class="fas fa-eye" id="confirmPasswordIcon"></i>
                            </button>
                        </div>
                        <div class="field-error" id="confirmPasswordError">Las contraseñas no coinciden</div>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">
                            Acepto los <a href="#terms" onclick="showTerms()">Términos y Condiciones</a> 
                            y la <a href="#privacy" onclick="showPrivacy()">Política de Privacidad</a> *
                        </label>
                    </div>
                    
                    <button type="submit" class="register-btn" id="registerBtn">
                        <i class="fas fa-user-plus"></i>
                        Crear mi Cuenta Gratuita
                    </button>
                </form>
                
                <div class="login-link">
                    ¿Ya tienes una cuenta? 
                    <a href="{{ route('login') }}">Inicia sesión aquí</a>
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

    <script src="{{ asset('js/register.js') }}" defer></script>

    
</body>
</html>