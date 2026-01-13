
        

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        

        

        // Animate elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.6s ease-out forwards';
                }
            });
        }, observerOptions);

        // Observe all elements with animate-on-scroll class
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Password toggle functionality
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'fas fa-eye';
            }
        }

        // Show/hide messages
        function showError(message) {
            const errorMessage = document.getElementById('errorMessage');
            const errorText = document.getElementById('errorText');
            const successMessage = document.getElementById('successMessage');
            
            successMessage.classList.remove('show');
            errorText.textContent = message;
            errorMessage.classList.add('show');
            
            setTimeout(() => {
                errorMessage.classList.remove('show');
            }, 5000);
        }

        function showSuccess(message = '¡Inicio de sesión exitoso! Redirigiendo...') {
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');
            
            errorMessage.classList.remove('show');
            successMessage.querySelector('span').textContent = message;
            successMessage.classList.add('show');
        }

        // Form validation
        function validateForm() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                showError('Por favor completa todos los campos');
                return false;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showError('Por favor ingresa un email válido');
                return false;
            }
            
            if (password.length < 6) {
                showError('La contraseña debe tener al menos 6 caracteres');
                return false;
            }
            
            return true;
        }

        // Login form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                return;
            }
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;
            const loginBtn = document.getElementById('loginBtn');
            const loadingOverlay = document.getElementById('loadingOverlay');
            
            // Disable button and show loading
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Iniciando sesión...';
            
            // Show loading overlay
            setTimeout(() => {
                loadingOverlay.classList.add('show');
            }, 500);
            
            
        });

        // Social login functions
        function loginWithGoogle() {
            showInfo('Funcionalidad de Google OAuth en desarrollo');
            // En un proyecto real, implementar Google OAuth
        }

        function loginWithFacebook() {
            showInfo('Funcionalidad de Facebook Login en desarrollo');
            // En un proyecto real, implementar Facebook Login
        }

        

        // Forgot password
        function showForgotPassword() {
            const email = prompt('Ingresa tu email para recuperar tu contraseña:');
            if (email) {
                if (email.includes('@')) {
                    showSuccess('Se ha enviado un enlace de recuperación a tu email');
                } else {
                    showError('Por favor ingresa un email válido');
                }
            }
        }

        

        // Form input validation styling
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.classList.add('error');
                } else {
                    this.classList.remove('error');
                }
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    this.classList.remove('error');
                }
            });
        });

        // Email validation styling
        document.getElementById('email').addEventListener('input', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.classList.add('error');
            } else {
                this.classList.remove('error');
            }
        });

        // Parallax effect for main content
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const mainContent = document.querySelector('.main-content');
            const rate = scrolled * -0.3;
            
            if (mainContent && scrolled < window.innerHeight) {
                mainContent.style.transform = `translateY(${rate}px)`;
            }
        });

        // Add loading animation
        window.addEventListener('load', function() {
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.5s ease-in-out';
            
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
        });

        // Enter key to submit form
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.target.closest('.password-toggle')) {
                const form = document.getElementById('loginForm');
                if (document.activeElement && form.contains(document.activeElement)) {
                    form.dispatchEvent(new Event('submit'));
                }
            }
        });

        // Auto-focus on email field
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('email').focus();
            }, 500);
        });

        
    
        // prueba login del blade __________________________________________________________________________________________________________

        document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const loginUrl = form.dataset.loginUrl;
    const perfilUrl = form.dataset.perfilUrl;
    const csrfToken = form.querySelector('input[name="_token"]').value;
    
    // Limpiar errores previos
    document.querySelectorAll('.field-error').forEach(el => el.style.display = 'none');
    
    const formData = new FormData(form);
    const button = document.getElementById('loginBtn');
    const originalText = button.innerHTML;
    
    // Deshabilitar botón
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Iniciando sesión...';
    
    try {
        const response = await fetch(loginUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Mostrar mensaje de éxito
            alert(data.message || '¡Bienvenido de nuevo!');
            // Redirigir al dashboard
            window.location.href = data.redirect || perfilUrl;
        } else {
            // Mostrar errores específicos de campos
            if (data.errors) {
                for (let field in data.errors) {
                    const errorElement = document.getElementById(field + 'Error');
                    if (errorElement) {
                        errorElement.textContent = data.errors[field][0];
                        errorElement.style.display = 'block';
                    }
                }
            }
            
            // Mostrar mensaje de error general
            if (data.message) {
                const generalError = document.getElementById('generalError');
                if (generalError) {
                    generalError.textContent = data.message;
                    generalError.style.display = 'block';
                }
            }
        }
    } catch (error) {
        console.error('Error:', error);
        const generalError = document.getElementById('generalError');
        if (generalError) {
            generalError.textContent = 'Hubo un error al procesar tu solicitud. Por favor, intenta de nuevo.';
            generalError.style.display = 'block';
        }
    } finally {
        // Rehabilitar botón
        button.disabled = false;
        button.innerHTML = originalText;
    }
});