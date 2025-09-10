
        // Números de WhatsApp por departamento (reemplazar con números reales)
        const whatsappNumbers = {
            general: '5215512345678',
            demo: '5215512345678',
            soporte: '5215512345679',
            ventas: '5215512345680',
            bugs: '5215512345679',
            sugerencias: '5215512345678',
            flotante: '5215512345678'
        };

        // Mensajes personalizados por departamento
        const whatsappMessages = {
            general: '¡Hola! Me interesa conocer más sobre EventosPro. ¿Podrían ayudarme?',
            demo: '¡Hola! Me gustaría solicitar una demo de EventosPro para conocer todas sus funcionalidades.',
            soporte: '¡Hola! Necesito ayuda con soporte técnico para EventosPro.',
            ventas: '¡Hola! Me interesa conocer los planes comerciales de EventosPro.',
            bugs: '¡Hola! Quiero reportar un error que encontré en la plataforma EventosPro.',
            sugerencias: '¡Hola! Tengo algunas sugerencias para mejorar EventosPro.',
            flotante: '¡Hola! Tengo una consulta sobre EventosPro. ¿Pueden ayudarme?'
        };

        // Función para contactar por WhatsApp
        function contactWhatsApp(tipo) {
            const numero = whatsappNumbers[tipo] || whatsappNumbers.general;
            const mensaje = encodeURIComponent(whatsappMessages[tipo] || whatsappMessages.general);
            const url = `https://wa.me/${numero}?text=${mensaje}`;
            window.open(url, '_blank');
        }

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

        // Contact form submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const successMessage = document.getElementById('successMessage');
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
            
            // Get form data
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            // Simulate API call
            setTimeout(() => {
                // Show success message
                successMessage.classList.add('show');
                
                // Reset form
                this.reset();
                
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar Mensaje';
                
                // Auto-hide success message
                setTimeout(() => {
                    successMessage.classList.remove('show');
                }, 5000);
                
                // Also offer WhatsApp option
                setTimeout(() => {
                    if (confirm('¿Te gustaría continuar la conversación por WhatsApp para una respuesta más rápida?')) {
                        const mensaje = `Hola, acabo de enviar el formulario de contacto sobre: ${data.subject}. Mi nombre es ${data.firstName} ${data.lastName}`;
                        const numero = whatsappNumbers.general;
                        const url = `https://wa.me/${numero}?text=${encodeURIComponent(mensaje)}`;
                        window.open(url, '_blank');
                    }
                }, 2000);
                
            }, 2000);
        });

        // FAQ Toggle
        function toggleFAQ(button) {
            const answer = button.nextElementSibling;
            const isActive = button.classList.contains('active');
            
            // Close all FAQs
            document.querySelectorAll('.faq-question').forEach(q => {
                q.classList.remove('active');
                q.nextElementSibling.classList.remove('active');
            });
            
            // Open clicked FAQ if it wasn't active
            if (!isActive) {
                button.classList.add('active');
                answer.classList.add('active');
            }
        }

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const headerHeight = document.querySelector('.header').offsetHeight;
                    const targetPosition = target.offsetTop - headerHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
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

        // Parallax effect for hero section
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.page-hero');
            const rate = scrolled * -0.3;
            
            if (hero && scrolled < hero.offsetHeight) {
                hero.style.transform = `translateY(${rate}px)`;
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

        // Form validation enhancement
        document.querySelectorAll('.form-input, .form-textarea, .form-select').forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.style.borderColor = '#e74c3c';
                } else {
                    this.style.borderColor = '#e1e5e9';
                }
            });
            
            input.addEventListener('input', function() {
                if (this.style.borderColor === 'rgb(231, 76, 60)') {
                    this.style.borderColor = '#667eea';
                }
            });
        });

        // Email validation
        document.getElementById('email').addEventListener('input', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.style.borderColor = '#e74c3c';
                this.setCustomValidity('Por favor ingresa un email válido');
            } else {
                this.style.borderColor = '#e1e5e9';
                this.setCustomValidity('');
            }
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

        // Console welcome message
        console.log('%c📞 EventosPro - Contacto', 'color: #25D366; font-size: 24px; font-weight: bold;');
        console.log('%c💬 WhatsApp es nuestro método preferido de contacto', 'color: #25D366; font-size: 16px;');
        console.log('%c📧 También puedes escribirnos por email', 'color: #EA4335; font-size: 14px;');
        console.log('%c✨ Formulario de contacto funcional', 'color: #667eea; font-size: 14px;');
    