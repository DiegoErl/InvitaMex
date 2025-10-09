
        // Registered users storage (simulation)
        let registeredUsers = [
            { email: 'admin@eventospro.com', name: 'Administrador' },
            { email: 'usuario@demo.com', name: 'Usuario Demo' },
            { email: 'test@test.com', name: 'Usuario Test' }
        ];

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Back to top button
        const backToTopButton = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopButton.classList.add('visible');
            } else {
                backToTopButton.classList.remove('visible');
            }
        });

        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Mobile menu toggle
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu');
        
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.style.display = mobileMenu.style.display === 'block' ? 'none' : 'block';
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mobileMenuToggle.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenu.style.display = 'none';
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
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const passwordIcon = document.getElementById(fieldId + 'Icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'fas fa-eye';
            }
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            let feedback = [];

            if (password.length >= 8) strength++;
            else feedback.push('8+ caracteres');

            if (/[a-z]/.test(password)) strength++;
            else feedback.push('minúsculas');

            if (/[A-Z]/.test(password)) strength++;
            else feedback.push('mayúsculas');

            if (/[0-9]/.test(password)) strength++;
            else feedback.push('números');

            if (/[^A-Za-z0-9]/.test(password)) strength++;
            else feedback.push('símbolos');

            return { strength, feedback };
        }

        // Update password strength indicator
        function updatePasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');

            if (!password) {
                strengthBar.className = 'strength-fill';
                strengthText.textContent = 'Fortaleza de contraseña';
                return;
            }

            const { strength, feedback } = checkPasswordStrength(password);

            // Update visual indicator
            strengthBar.className = 'strength-fill';
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Débil - Necesita: ' + feedback.slice(0, 3).join(', ');
                strengthText.style.color = '#e74c3c';
            } else if (strength === 3) {
                strengthBar.classList.add('strength-fair');
                strengthText.textContent = 'Regular - Falta: ' + feedback.slice(0, 2).join(', ');
                strengthText.style.color = '#f39c12';
            } else if (strength === 4) {
                strengthBar.classList.add('strength-good');
                strengthText.textContent = 'Buena - Falta: ' + feedback.join(', ');
                strengthText.style.color = '#f1c40f';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Excelente - Contraseña segura';
                strengthText.style.color = '#28a745';
            }
        }

        // Password strength event listener
        document.getElementById('password').addEventListener('input', updatePasswordStrength);

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
            }, 7000);
        }

        function showSuccess(message = '¡Cuenta creada exitosamente! Verificando email...') {
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');
            
            errorMessage.classList.remove('show');
            successMessage.querySelector('span').textContent = message;
            successMessage.classList.add('show');
        }

        // Show field error
        function showFieldError(fieldId, message) {
            const field = document.getElementById(fieldId);
            const error = document.getElementById(fieldId + 'Error');
            
            field.classList.add('error');
            if (error) {
                error.textContent = message;
                error.classList.add('show');
            }
        }

        // Hide field error
        function hideFieldError(fieldId) {
            const field = document.getElementById(fieldId);
            const error = document.getElementById(fieldId + 'Error');
            
            field.classList.remove('error');
            field.classList.add('success');
            if (error) {
                error.classList.remove('show');
            }
        }

        // Form validation
        function validateForm() {
            let isValid = true;
            
            // Clear previous errors
            document.querySelectorAll('.form-input').forEach(input => {
                input.classList.remove('error', 'success');
            });
            document.querySelectorAll('.field-error').forEach(error => {
                error.classList.remove('show');
            });

            // Validate first name
            const firstName = document.getElementById('firstName').value.trim();
            if (!firstName) {
                showFieldError('firstName', 'Este campo es obligatorio');
                isValid = false;
            } else if (firstName.length < 2) {
                showFieldError('firstName', 'Debe tener al menos 2 caracteres');
                isValid = false;
            } else {
                hideFieldError('firstName');
            }

            // Validate last name
            const lastName = document.getElementById('lastName').value.trim();
            if (!lastName) {
                showFieldError('lastName', 'Este campo es obligatorio');
                isValid = false;
            } else if (lastName.length < 2) {
                showFieldError('lastName', 'Debe tener al menos 2 caracteres');
                isValid = false;
            } else {
                hideFieldError('lastName');
            }

            // Validate email
            const email = document.getElementById('email').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) {
                showFieldError('email', 'Este campo es obligatorio');
                isValid = false;
            } else if (!emailRegex.test(email)) {
                showFieldError('email', 'Ingresa un email válido');
                isValid = false;
            } else if (registeredUsers.some(user => user.email === email)) {
                showFieldError('email', 'Este email ya está registrado');
                isValid = false;
            } else {
                hideFieldError('email');
            }

            // Validate password
            const password = document.getElementById('password').value;
            if (!password) {
                showFieldError('password', 'Este campo es obligatorio');
                isValid = false;
            } else if (password.length < 8) {
                showFieldError('password', 'Debe tener al menos 8 caracteres');
                isValid = false;
            } else {
                const { strength } = checkPasswordStrength(password);
                if (strength < 3) {
                    showFieldError('password', 'La contraseña es muy débil');
                    isValid = false;
                } else {
                    hideFieldError('password');
                }
            }

            // Validate password confirmation
            const confirmPassword = document.getElementById('confirmPassword').value;
            if (!confirmPassword) {
                showFieldError('confirmPassword', 'Confirma tu contraseña');
                isValid = false;
            } else if (password !== confirmPassword) {
                showFieldError('confirmPassword', 'Las contraseñas no coinciden');
                isValid = false;
            } else {
                hideFieldError('confirmPassword');
            }

            // Validate terms acceptance
            const terms = document.getElementById('terms').checked;
            if (!terms) {
                showError('Debes aceptar los términos y condiciones');
                isValid = false;
            }

            return isValid;
        }

        // Register form submission
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                return;
            }
            
            const formData = new FormData(this);
            const userData = Object.fromEntries(formData);
            const registerBtn = document.getElementById('registerBtn');
            const loadingOverlay = document.getElementById('loadingOverlay');
            
            // Disable button and show loading
            registerBtn.disabled = true;
            registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando cuenta...';
            
            // Show loading overlay
            setTimeout(() => {
                loadingOverlay.classList.add('show');
            }, 500);
            
            // Simulate API call
            setTimeout(() => {
                // Success - add user to registered users
                registeredUsers.push({
                    email: userData.email,
                    name: userData.firstName + ' ' + userData.lastName,
                    phone: userData.phone,
                    country: userData.country,
                    eventType: userData.eventType,
                    newsletter: userData.newsletter === 'on',
                    registrationDate: new Date().toISOString()
                });
                
                loadingOverlay.classList.remove('show');
                showSuccess('¡Cuenta creada exitosamente! Se ha enviado un email de verificación.');
                
                // Reset button
                registerBtn.disabled = false;
                registerBtn.innerHTML = '<i class="fas fa-user-plus"></i> Crear mi Cuenta Gratuita';
                
            }, 2500);
        });

        // Social register functions
        function registerWithGoogle() {
            showInfo('Funcionalidad de Google OAuth en desarrollo');
        }

        function registerWithFacebook() {
            showInfo('Funcionalidad de Facebook OAuth en desarrollo');
        }

        function showInfo(message) {
            alert(message + '\n\nPor ahora puedes completar el formulario de registro manual para probar la funcionalidad.');
        }

        // Show terms and privacy
        function showTerms() {
            alert('Términos y Condiciones de EventosPro\n\n1. Uso de la plataforma\n2. Responsabilidades del usuario\n3. Política de contenido\n4. Limitaciones de responsabilidad\n5. Modificaciones al servicio\n\n[En un proyecto real, esto sería una página dedicada]');
        }

        function showPrivacy() {
            alert('Política de Privacidad de EventosPro\n\n1. Recopilación de datos\n2. Uso de la información\n3. Compartir datos con terceros\n4. Cookies y tecnologías similares\n5. Derechos del usuario\n6. Seguridad de datos\n\n[En un proyecto real, esto sería una página dedicada]');
        }

        // Real-time validation
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('blur', function() {
                const fieldId = this.id;
                
                if (fieldId === 'email') {
                    const email = this.value.trim();
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    
                    if (email && !emailRegex.test(email)) {
                        showFieldError(fieldId, 'Ingresa un email válido');
                    } else if (email && registeredUsers.some(user => user.email === email)) {
                        showFieldError(fieldId, 'Este email ya está registrado');
                    } else if (email) {
                        hideFieldError(fieldId);
                    }
                } else if (fieldId === 'confirmPassword') {
                    const password = document.getElementById('password').value;
                    const confirmPassword = this.value;
                    
                    if (confirmPassword && password !== confirmPassword) {
                        showFieldError(fieldId, 'Las contraseñas no coinciden');
                    } else if (confirmPassword) {
                        hideFieldError(fieldId);
                    }
                } else if (this.hasAttribute('required') && !this.value.trim()) {
                    showFieldError(fieldId, 'Este campo es obligatorio');
                }
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    hideFieldError(this.id);
                }
            });
        });

        // Phone formatting (Mexican format)
        document.getElementById('phone').addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value.length <= 2) {
                    value = `+${value}`;
                } else if (value.length <= 4) {
                    value = `+${value.slice(0, 2)} ${value.slice(2)}`;
                } else if (value.length <= 6) {
                    value = `+${value.slice(0, 2)} ${value.slice(2, 4)} ${value.slice(4)}`;
                } else if (value.length <= 10) {
                    value = `+${value.slice(0, 2)} ${value.slice(2, 4)} ${value.slice(4, 8)} ${value.slice(8)}`;
                } else {
                    value = `+${value.slice(0, 2)} ${value.slice(2, 4)} ${value.slice(4, 8)} ${value.slice(8, 12)}`;
                }
            }
            this.value = value;
        });

        // Search functionality
        const searchBox = document.querySelector('.search-box');
        searchBox.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            if (searchTerm.length > 2) {
                console.log('Buscando:', searchTerm);
            }
        });

        searchBox.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    window.location.href = `#search?q=${encodeURIComponent(searchTerm)}`;
                }
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

        // Auto-focus on first name field
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('firstName').focus();
            }, 500);
        });

        // Enhanced form interaction
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && !e.target.closest('.password-toggle')) {
                const form = document.getElementById('registerForm');
                if (document.activeElement && form.contains(document.activeElement)) {
                    e.preventDefault();
                    // Find next input field
                    const inputs = form.querySelectorAll('input, select');
                    const currentIndex = Array.from(inputs).indexOf(document.activeElement);
                    if (currentIndex < inputs.length - 1) {
                        inputs[currentIndex + 1].focus();
                    } else {
                        form.dispatchEvent(new Event('submit'));
                    }
                }
            }
        });

        // Welcome animation for benefits
        setTimeout(() => {
            const benefitItems = document.querySelectorAll('.benefit-item');
            benefitItems.forEach((item, index) => {
                setTimeout(() => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(-20px)';
                    item.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    }, 100);
                }, index * 200);
            });
        }, 1000);

        // Email availability checker (simulated)
        let emailCheckTimeout;
        document.getElementById('email').addEventListener('input', function() {
            clearTimeout(emailCheckTimeout);
            const email = this.value.trim();
            
            if (email && email.includes('@')) {
                emailCheckTimeout = setTimeout(() => {
                    // Simulate API call to check email availability
                    const isRegistered = registeredUsers.some(user => user.email === email);
                    
                    if (isRegistered) {
                        showFieldError('email', 'Este email ya está registrado');
                    } else {
                        hideFieldError('email');
                        // Show available indicator
                        this.style.borderColor = '#28a745';
                        setTimeout(() => {
                            if (!this.classList.contains('error')) {
                                this.style.borderColor = '#e1e5e9';
                            }
                        }, 2000);
                    }
                }, 1000);
            }
        });

        // Form analytics (simulation)
        function trackFormInteraction(action, field = null) {
            console.log(`📊 Form Analytics: ${action}`, field ? `Field: ${field}` : '');
            // En un proyecto real, aquí se enviarían eventos a Google Analytics, etc.
        }

        // Track form interactions
        document.querySelectorAll('.form-input, .form-select').forEach(input => {
            input.addEventListener('focus', () => trackFormInteraction('field_focus', input.id));
            input.addEventListener('blur', () => trackFormInteraction('field_blur', input.id));
        });

        // Social buttons analytics
        document.querySelectorAll('.social-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const provider = btn.classList.contains('google-btn') ? 'google' : 'facebook';
                trackFormInteraction('social_register_attempt', provider);
            });
        });

        // Password strength tips
        function showPasswordTips() {
            const tips = [
                "💡 Usa una combinación de letras mayúsculas y minúsculas",
                "🔢 Incluye números en tu contraseña",
                "🔣 Añade símbolos especiales como @, #, $, etc.",
                "📏 Mínimo 8 caracteres, pero 12+ es mejor",
                "🚫 Evita información personal como nombres o fechas"
            ];
            
            alert("Consejos para una contraseña segura:\n\n" + tips.join("\n"));
        }

        // Add password tips button (optional)
        document.querySelector('label[for="password"]').addEventListener('click', function(e) {
            if (e.detail === 2) { // Double click
                showPasswordTips();
            }
        });

        // Form completion progress
        function updateFormProgress() {
            const requiredFields = document.querySelectorAll('[required]');
            const completedFields = Array.from(requiredFields).filter(field => {
                if (field.type === 'checkbox') {
                    return field.checked;
                }
                return field.value.trim() !== '';
            });
            
            const progress = (completedFields.length / requiredFields.length) * 100;
            
            // Update button text based on progress
            const registerBtn = document.getElementById('registerBtn');
            if (progress === 100) {
                registerBtn.innerHTML = '<i class="fas fa-rocket"></i> ¡Crear mi Cuenta Gratuita!';
                registerBtn.style.background = 'var(--success-gradient)';
            } else {
                registerBtn.innerHTML = '<i class="fas fa-user-plus"></i> Crear mi Cuenta Gratuita';
                registerBtn.style.background = 'var(--secondary-gradient)';
            }
            
            console.log(`Form progress: ${Math.round(progress)}%`);
        }

        // Monitor form completion
        document.querySelectorAll('[required]').forEach(field => {
            field.addEventListener('input', updateFormProgress);
            field.addEventListener('change', updateFormProgress);
        });

        // Initialize form progress
        updateFormProgress();

        // Country-specific phone formatting
        document.getElementById('country').addEventListener('change', function() {
            const phoneField = document.getElementById('phone');
            const country = this.value;
            
            // Reset phone field
            phoneField.value = '';
            
            // Set placeholder based on country
            const placeholders = {
                'MX': '+52 55 1234 5678',
                'US': '+1 (555) 123-4567',
                'ES': '+34 912 345 678',
                'AR': '+54 11 1234-5678',
                'CO': '+57 1 234 5678'
            };
            
            phoneField.placeholder = placeholders[country] || '+1 234 567 8900';
        });

        // Console welcome message
        setTimeout(() => {
            console.log('%c📝 EventosPro - Registro de Usuario', 'color: #f5576c; font-size: 24px; font-weight: bold;');
            console.log('%c🎁 Cuenta gratuita incluye:', 'color: #28a745; font-size: 16px;');
            console.log('%c• 3 eventos por mes gratis', 'color: #17a2b8; font-size: 14px;');
            console.log('%c• Personalización completa', 'color: #17a2b8; font-size: 14px;');
            console.log('%c• Códigos QR automáticos', 'color: #17a2b8; font-size: 14px;');
            console.log('%c• Soporte por WhatsApp', 'color: #17a2b8; font-size: 14px;');
            console.log('%c🔒 Validación completa de formulario activa', 'color: #ffc107; font-size: 14px;');
        }, 1000);

        // Easter egg: Konami Code for admin features
        let konamiCode = [];
        const konamiSequence = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65]; // ↑ ↑ ↓ ↓ ← → ← → B A

        document.addEventListener('keydown', function(e) {
            konamiCode.push(e.keyCode);
            if (konamiCode.length > konamiSequence.length) {
                konamiCode.shift();
            }
            
            if (konamiCode.length === konamiSequence.length && 
                konamiCode.every((key, index) => key === konamiSequence[index])) {
                
                // Easter egg activated
                document.body.style.animation = 'rainbow 2s infinite';
                setTimeout(() => {
                    alert('🎉 ¡Easter Egg Activado!\n\n🚀 Modo Desarrollador Desbloqueado\n📊 Formulario con datos de prueba pre-llenado');
                    
                    // Fill form with test data
                    document.getElementById('firstName').value = 'John';
                    document.getElementById('lastName').value = 'Doe';
                    document.getElementById('email').value = 'john.doe@example.com';
                    document.getElementById('phone').value = '+52 55 1234 5678';
                    document.getElementById('country').value = 'MX';
                    document.getElementById('password').value = 'TestPassword123!';
                    document.getElementById('confirmPassword').value = 'TestPassword123!';
                    document.getElementById('eventType').value = 'personal';
                    document.getElementById('terms').checked = true;
                    document.getElementById('newsletter').checked = true;
                    
                    updatePasswordStrength();
                    updateFormProgress();
                    
                    document.body.style.animation = '';
                }, 1000);
                
                konamiCode = [];
            }
        });

        // Add rainbow animation for easter egg
        const style = document.createElement('style');
        style.textContent = `
            @keyframes rainbow {
                0% { filter: hue-rotate(0deg); }
                100% { filter: hue-rotate(360deg); }
            }
        `;
        document.head.appendChild(style);

        // registro de usuario ________________________________________________________________________________________________________

        document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const registerUrl = form.dataset.registerUrl;
    const loginUrl = form.dataset.loginUrl;
    const csrfToken = form.querySelector('input[name="_token"]').value;
    
    // Limpiar errores previos
    document.querySelectorAll('.field-error').forEach(el => el.style.display = 'none');
    
    const formData = new FormData(form);
    const button = document.getElementById('registerBtn');
    const originalText = button.innerHTML;
    
    // Deshabilitar botón
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registrando...';
    
    try {
        const response = await fetch(registerUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('¡Registro exitoso! Tu cuenta ha sido creada.');
            window.location.href = data.redirect || loginUrl;
        } else {
            if (data.errors) {
                for (let field in data.errors) {
                    const errorElement = document.getElementById(field + 'Error');
                    if (errorElement) {
                        errorElement.textContent = data.errors[field][0];
                        errorElement.style.display = 'block';
                    }
                }
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Hubo un error al procesar tu registro. Por favor, intenta de nuevo.');
    } finally {
        button.disabled = false;
        button.innerHTML = originalText;
    }
});