// ========================================
// REGISTRO DE USUARIOS - INVITAMEX
// ========================================

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

if (mobileMenuToggle && mobileMenu) {
    mobileMenuToggle.addEventListener('click', function() {
        mobileMenu.style.display = mobileMenu.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', function(event) {
        if (!mobileMenuToggle.contains(event.target) && !mobileMenu.contains(event.target)) {
            mobileMenu.style.display = 'none';
        }
    });
}

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
const passwordField = document.getElementById('password');
if (passwordField) {
    passwordField.addEventListener('input', updatePasswordStrength);
}

// Show/hide messages
function showError(message) {
    const errorMessage = document.getElementById('errorMessage');
    const errorText = document.getElementById('errorText');
    const successMessage = document.getElementById('successMessage');
    
    if (successMessage) successMessage.classList.remove('show');
    if (errorText) errorText.textContent = message;
    if (errorMessage) {
        errorMessage.classList.add('show');
        
        setTimeout(() => {
            errorMessage.classList.remove('show');
        }, 7000);
    }
}

function showSuccess(message = '¡Cuenta creada exitosamente!') {
    const errorMessage = document.getElementById('errorMessage');
    const successMessage = document.getElementById('successMessage');
    
    if (errorMessage) errorMessage.classList.remove('show');
    if (successMessage) {
        const span = successMessage.querySelector('span');
        if (span) span.textContent = message;
        successMessage.classList.add('show');
    }
}

// Show field error
function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const error = document.getElementById(fieldId + 'Error');
    
    if (field) field.classList.add('error');
    if (error) {
        error.textContent = message;
        error.style.display = 'block';
        error.classList.add('show');
    }
}

// Hide field error
function hideFieldError(fieldId) {
    const field = document.getElementById(fieldId);
    const error = document.getElementById(fieldId + 'Error');
    
    if (field) {
        field.classList.remove('error');
        field.classList.add('success');
    }
    if (error) {
        error.style.display = 'none';
        error.classList.remove('show');
    }
}

// ========================================
// REGISTRO DE USUARIO - MANEJADOR PRINCIPAL
// ========================================

document.getElementById('registerForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const registerUrl = form.dataset.registerUrl;
    const csrfToken = form.querySelector('input[name="_token"]').value;
    
    // Limpiar errores previos
    document.querySelectorAll('.field-error').forEach(el => {
        el.style.display = 'none';
        el.classList.remove('show');
    });
    
    const formData = new FormData(form);
    const button = document.getElementById('registerBtn');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const originalText = button.innerHTML;
    
    // Deshabilitar botón y mostrar loading
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando cuenta y enviando verificación...';
    
    if (loadingOverlay) {
        loadingOverlay.classList.add('show');
    }
    
    try {
        // ⭐ TIMEOUT AUMENTADO A 60 SEGUNDOS
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 60000); // 60 segundos
        
        const response = await fetch(registerUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData,
            signal: controller.signal
        });
        
        clearTimeout(timeoutId);
        
        const data = await response.json();
        
        if (data.success) {
            if (loadingOverlay) loadingOverlay.classList.remove('show');
            
            showSuccess('¡Cuenta creada exitosamente! Redirigiendo a tu perfil...');
            
            // ⭐ REDIRIGIR DESPUÉS DE 1 SEGUNDO
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
            
        } else {
            if (loadingOverlay) loadingOverlay.classList.remove('show');
            
            // Mostrar errores de validación
            if (data.errors) {
                for (let field in data.errors) {
                    showFieldError(field, data.errors[field][0]);
                }
                
                // Scroll al primer error
                const firstError = document.querySelector('.field-error.show');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                showError(data.message || 'Error al crear la cuenta');
            }
            
            button.disabled = false;
            button.innerHTML = originalText;
        }
        
    } catch (error) {
        if (loadingOverlay) loadingOverlay.classList.remove('show');
        
        console.error('Error:', error);
        
        if (error.name === 'AbortError') {
            showError('La operación tardó demasiado. Por favor, intenta de nuevo.');
        } else {
            showError('Hubo un error al procesar tu registro. Por favor, intenta de nuevo.');
        }
        
        button.disabled = false;
        button.innerHTML = originalText;
    }
});

// Real-time validation
document.querySelectorAll('.form-input').forEach(input => {
    input.addEventListener('blur', function() {
        const fieldId = this.id;
        const value = this.value.trim();
        
        if (fieldId === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                showFieldError(fieldId, 'Ingresa un email válido');
            } else {
                hideFieldError(fieldId);
            }
        } else if (fieldId === 'confirmPassword' && value) {
            const password = document.getElementById('password').value;
            if (password !== value) {
                showFieldError(fieldId, 'Las contraseñas no coinciden');
            } else {
                hideFieldError(fieldId);
            }
        } else if (this.hasAttribute('required') && !value) {
            showFieldError(fieldId, 'Este campo es obligatorio');
        } else if (value) {
            hideFieldError(fieldId);
        }
    });
    
    input.addEventListener('input', function() {
        if (this.classList.contains('error')) {
            hideFieldError(this.id);
        }
    });
});

console.log('✅ Sistema de registro cargado correctamente');