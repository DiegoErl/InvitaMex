// ============================================
// EVENTO DETALLE - JAVASCRIPT
// ============================================

// Variables globales para el carrusel
let currentSlide = 0;
let autoPlayInterval;

// ============================================
// CARRUSEL DE IMÁGENES
// ============================================

function initCarousel() {
    const slides = document.querySelectorAll('.carousel-slide');
    const indicators = document.querySelectorAll('.indicator');
    const carousel = document.querySelector('.event-carousel');
    
    if (slides.length === 0) return;

    // Mostrar primer slide
    showSlide(0);

    // Auto-play
    startAutoPlay();

    // Pausar auto-play al pasar el mouse
    if (carousel) {
        carousel.addEventListener('mouseenter', () => {
            clearInterval(autoPlayInterval);
        });

        carousel.addEventListener('mouseleave', () => {
            startAutoPlay();
        });
    }

    // Soporte para teclado
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            changeSlide(-1);
        } else if (e.key === 'ArrowRight') {
            changeSlide(1);
        }
    });
}

function showSlide(n) {
    const slides = document.querySelectorAll('.carousel-slide');
    const indicators = document.querySelectorAll('.indicator');
    const totalSlides = slides.length;

    if (totalSlides === 0) return;

    // Ajustar índice si está fuera de rango
    if (n >= totalSlides) {
        currentSlide = 0;
    } else if (n < 0) {
        currentSlide = totalSlides - 1;
    } else {
        currentSlide = n;
    }

    // Ocultar todas las slides
    slides.forEach(slide => {
        slide.classList.remove('active');
    });

    // Desactivar todos los indicadores
    indicators.forEach(indicator => {
        indicator.classList.remove('active');
    });

    // Mostrar slide actual
    slides[currentSlide].classList.add('active');

    // Activar indicador actual
    if (indicators[currentSlide]) {
        indicators[currentSlide].classList.add('active');
    }
}

function changeSlide(direction) {
    showSlide(currentSlide + direction);
}

function goToSlide(n) {
    showSlide(parseInt(n));
}

function startAutoPlay() {
    autoPlayInterval = setInterval(() => {
        changeSlide(1);
    }, 5000); // Cambia cada 5 segundos
}

// ============================================
// MODAL DE EDICIÓN
// ============================================

function openEditModal() {
    document.getElementById('editModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

function togglePriceField() {
    const paymentType = document.getElementById('payment_type').value;
    const priceGroup = document.getElementById('price_group');
    priceGroup.style.display = paymentType === 'pago' ? 'block' : 'none';
}

// ============================================
// COMPARTIR POR WHATSAPP
// ============================================

function shareWhatsApp() {
    // Esta función será llamada desde el blade con datos dinámicos
    console.log('Compartir por WhatsApp');
}

// ============================================
// INICIALIZACIÓN
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar carrusel
    initCarousel();

    // Cerrar modal con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('editModal');
            if (modal && modal.classList.contains('show')) {
                closeEditModal();
            }
        }
    });

    console.log('✅ Evento Detalle cargado correctamente');
});