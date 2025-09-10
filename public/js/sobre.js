
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

        // Counter animation for stats
        function animateCounters() {
            const counters = document.querySelectorAll('.story-stat-number');
            
            const countUp = (element, target) => {
                const increment = target / 100;
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        const originalText = element.textContent;
                        if (originalText.includes('%')) {
                            element.textContent = target + '%';
                        } else if (originalText.includes('+')) {
                            element.textContent = target.toLocaleString() + '+';
                        } else {
                            element.textContent = target.toLocaleString();
                        }
                        clearInterval(timer);
                    } else {
                        const originalText = element.textContent;
                        if (originalText.includes('%')) {
                            element.textContent = Math.floor(current) + '%';
                        } else if (originalText.includes('+')) {
                            element.textContent = Math.floor(current).toLocaleString() + '+';
                        } else {
                            element.textContent = Math.floor(current).toLocaleString();
                        }
                    }
                }, 20);
            };

            counters.forEach(counter => {
                const originalText = counter.textContent;
                const target = parseInt(originalText.replace(/[^\d]/g, ''));
                if (target > 0) {
                    countUp(counter, target);
                }
            });
        }

        // Trigger counter animation when stats section is visible
        const statsSection = document.querySelector('.story-stats');
        const statsObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        if (statsSection) {
            statsObserver.observe(statsSection);
        }

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

        // Team card hover effects
        document.querySelectorAll('.team-member').forEach(member => {
            member.addEventListener('mouseenter', function() {
                const avatar = this.querySelector('.team-avatar');
                avatar.style.transform = 'scale(1.1) rotate(5deg)';
            });
            
            member.addEventListener('mouseleave', function() {
                const avatar = this.querySelector('.team-avatar');
                avatar.style.transform = 'scale(1) rotate(0deg)';
            });
        });

        // Values animation on scroll
        document.querySelectorAll('.value-item').forEach((item, index) => {
            setTimeout(() => {
                item.style.animationDelay = `${index * 0.2}s`;
            }, 100);
        });

        // Console welcome message
        console.log('%c🎉 EventosPro - Sobre Nosotros', 'color: #667eea; font-size: 24px; font-weight: bold;');
        console.log('%c✨ Página "Sobre Nosotros" cargada correctamente', 'color: #28a745; font-size: 16px;');
        console.log('%c👥 Conoce a nuestro increíble equipo', 'color: #17a2b8; font-size: 14px;');
        console.log('%c🌱 Comprometidos con la sostenibilidad', 'color: #28a745; font-size: 14px;');
    