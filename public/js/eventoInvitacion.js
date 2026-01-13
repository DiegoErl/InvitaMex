// ============================================
// EVENTO INVITACION - JAVASCRIPT
// ============================================

// Variables globales (se inicializan desde el blade)
let eventId;
let eventPrice;
let paymentType;
let eventOrganizerId;
let eventImages = [];
let currentImageIndex = 0;

// Inicializar datos del evento (llamado desde el blade)
function initEventData(id, price, type, organizerId, images) {
    eventId = id;
    eventPrice = price;
    paymentType = type;
    eventOrganizerId = organizerId;
    eventImages = images;
}

// ============================================
// VISOR DE IMÁGENES
// ============================================

// Abrir visor de imágenes
function openImageViewer(index) {
    if (eventImages.length === 0) return;
    
    currentImageIndex = index;
    updateViewerImage();
    document.getElementById('imageViewerModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

// Cerrar visor
function closeImageViewer() {
    document.getElementById('imageViewerModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

// Cambiar imagen
function changeImage(direction) {
    currentImageIndex += direction;
    
    // Loop infinito
    if (currentImageIndex < 0) {
        currentImageIndex = eventImages.length - 1;
    } else if (currentImageIndex >= eventImages.length) {
        currentImageIndex = 0;
    }
    
    updateViewerImage();
}

// Actualizar imagen del visor
function updateViewerImage() {
    const viewerImage = document.getElementById('viewerImage');
    const viewerCounter = document.getElementById('viewerCounter');
    
    viewerImage.src = eventImages[currentImageIndex];
    viewerCounter.textContent = `${currentImageIndex + 1} / ${eventImages.length}`;
}

// ============================================
// CONFIRMACIÓN DE ASISTENCIA - MEJORADA
// ============================================

async function confirmAttendance() {
    // Verificar autenticación
    const isAuthenticated = document.querySelector('meta[name="user-authenticated"]');
    
    if (!isAuthenticated || isAuthenticated.content !== 'true') {
        showNotification('error', 'Debes iniciar sesión para confirmar tu asistencia');
        setTimeout(() => {
            window.location.href = '/login';
        }, 1500);
        return;
    }

    // Verificar si el usuario es el organizador del evento
    const userId = document.querySelector('meta[name="user-id"]');
    if (userId && userId.content === eventOrganizerId.toString()) {
        showNotification('warning', 'No puedes confirmar asistencia a tu propio evento');
        return;
    }

    // Deshabilitar botón para evitar múltiples clics
    const btn = event.target;
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

    // Procesar confirmación
    try {
        const response = await fetch(`/eventos/${eventId}/solicitar-invitacion`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            showNotification('success', data.message + ' Redirigiendo a tus invitaciones...');
            setTimeout(() => {
                window.location.href = '/mis-invitaciones';
            }, 2000);
        } else {
            showNotification('error', data.message);
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('error', 'Hubo un error al confirmar tu asistencia. Intenta de nuevo.');
        btn.disabled = false;
        btn.innerHTML = originalHTML;
    }
}

// ============================================
// SISTEMA DE NOTIFICACIONES
// ============================================

function showNotification(type, message) {
    // Eliminar notificación anterior si existe
    const existingNotification = document.querySelector('.event-notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Crear notificación
    const notification = document.createElement('div');
    notification.className = `event-notification event-notification-${type}`;
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };

    const colors = {
        success: '#10b981',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6'
    };

    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 10000;
        animation: slideInRight 0.3s ease;
        border-left: 4px solid ${colors[type]};
        max-width: 400px;
    `;

    notification.innerHTML = `
        <i class="fas ${icons[type]}" style="color: ${colors[type]}; font-size: 1.5rem;"></i>
        <span style="color: #333; font-weight: 500; flex: 1;">${message}</span>
        <button onclick="this.parentElement.remove()" style="
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
        " onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='none'">
            <i class="fas fa-times"></i>
        </button>
    `;

    document.body.appendChild(notification);

    // Auto-cerrar después de 5 segundos
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

// Agregar animaciones CSS dinámicamente
if (!document.getElementById('eventNotificationStyles')) {
    const style = document.createElement('style');
    style.id = 'eventNotificationStyles';
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .event-notification {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        @media (max-width: 768px) {
            .event-notification {
                left: 10px;
                right: 10px;
                max-width: none;
                top: 10px;
            }
        }
    `;
    document.head.appendChild(style);
}

// ============================================
// NAVEGACIÓN CON TECLADO
// ============================================

document.addEventListener('keydown', function(e) {
    const viewer = document.getElementById('imageViewerModal');
    if (viewer && viewer.classList.contains('show')) {
        if (e.key === 'ArrowLeft') {
            changeImage(-1);
        } else if (e.key === 'ArrowRight') {
            changeImage(1);
        } else if (e.key === 'Escape') {
            closeImageViewer();
        }
    }
});

// ============================================
// ANIMACIONES DE ENTRADA
// ============================================

// Observador de intersección para animaciones
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Aplicar animaciones a elementos con clase fade-in
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.fade-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});

// ============================================
// COPIAR ENLACE DE INVITACIÓN
// ============================================

function copyInvitationLink(url) {
    // Intentar usar la API moderna de clipboard
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(() => {
            showNotification('success', '✅ Enlace copiado al portapapeles');
        }).catch(err => {
            console.error('Error al copiar:', err);
            fallbackCopy(url);
        });
    } else {
        fallbackCopy(url);
    }
}

// Método alternativo para navegadores antiguos
function fallbackCopy(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.top = '0';
    textArea.style.left = '0';
    textArea.style.opacity = '0';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showNotification('success', '✅ Enlace copiado al portapapeles');
        } else {
            showNotification('error', 'No se pudo copiar el enlace');
        }
    } catch (err) {
        console.error('Error al copiar:', err);
        showNotification('error', 'No se pudo copiar el enlace');
    }
    
    document.body.removeChild(textArea);
}

console.log('✅ Evento Invitación cargado correctamente');