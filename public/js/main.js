        document.addEventListener("DOMContentLoaded", function () {
    // ===== Fondo animado del HERO (canvas) =====
    (function () {
        var hero = document.querySelector('.hero');
        var canvas = document.getElementById('background-canvas');
        if (!hero || !canvas) return;

        var ctx, points, target = { x: 0, y: 0 }, animateHeader = true;
        var pointDistance = 45;
        var pointRadius = 2;

        // Usaremos el tamaño real del HERO (no el de la ventana)
        function sizeFromHero() {
            return {
                width: hero.clientWidth,
                height: hero.clientHeight
            };
        }

        function initHeader() {
            var s = sizeFromHero();
            canvas.width = s.width;
            canvas.height = s.height;
            ctx = canvas.getContext('2d');
            initPoints();
        }

        function addListeners() {
            if (!('ontouchstart' in window)) {
                window.addEventListener('mousemove', mouseMove);
            }
            window.addEventListener('resize', resize);
        }

        function initAnimation() {
            animate();
        }

        function animate() {
            if (animateHeader) {
                drawPoints();
            }
            requestAnimationFrame(animate);
        }

        function mouseMove(e) {
            var rect = hero.getBoundingClientRect();
            target.x = e.clientX - rect.left;
            target.y = e.clientY - rect.top;
        }

        function resize() {
            // Recalcular canvas al tamaño del HERO
            var s = sizeFromHero();
            canvas.width = s.width;
            canvas.height = s.height;

            // Cortar tweens anteriores y re-crear puntos
            if (points) {
                for (var i = 0; i < points.length; i++) {
                    if (window.TweenLite) TweenLite.killTweensOf(points[i]);
                }
            }
            initPoints();
        }

        function initPoints() {
            var s = sizeFromHero();
            var width = s.width, height = s.height;

            points = [];
            for (var x = 0; x <= width / pointDistance; x++) {
                for (var y = 0; y < height / pointDistance; y++) {
                    var px = x * pointDistance;
                    var py = y * pointDistance;
                    var p = { x: px, originX: px, y: py, originY: py };
                    points.push(p);
                }
            }

            // Calcular los 5 más cercanos para cada punto
            for (var i = 0; i < points.length; i++) {
                var closest = [];
                var p1 = points[i];
                for (var j = 0; j < points.length; j++) {
                    var p2 = points[j];
                    if (p1 === p2) continue;
                    var placed = false;
                    for (var k = 0; k < 5; k++) {
                        if (!placed) {
                            if (closest[k] === undefined) {
                                closest[k] = p2;
                                placed = true;
                            }
                        }
                    }
                    for (var k2 = 0; k2 < 5; k2++) {
                        if (!placed) {
                            if (getDistance(p1, p2) < getDistance(p1, closest[k2])) {
                                closest[k2] = p2;
                                placed = true;
                            }
                        }
                    }
                }
                p1.closest = closest;
            }

            // Circulitos
            for (var i2 = 0; i2 < points.length; i2++) {
                var c = new Circle(points[i2], pointRadius, 'rgba(255,255,255,0.3)');
                points[i2].circle = c;
            }

            for (var i3 = 0; i3 < points.length; i3++) {
                shiftPoint(points[i3]);
            }
        }

        function drawPoints() {
            var s = sizeFromHero();
            ctx.clearRect(0, 0, s.width, s.height);

            for (var i = 0; i < points.length; i++) {
                if (target) {
                    var d = Math.abs(getDistance(target, points[i]));
                    if (d < 4000) {
                        points[i].opacity = 0.3;
                        points[i].circle.opacity = 1;
                    } else if (d < 20000) {
                        points[i].opacity = 0.1;
                        points[i].circle.opacity = 1;
                    } else if (d < 40000) {
                        points[i].opacity = 0.02;
                        points[i].circle.opacity = 0.8;
                    } else {
                        points[i].opacity = 0;
                        points[i].circle.opacity = 0.7;
                    }
                }

                points[i].circle.color = 'rgba(156,217,249,1)';
                drawLines(points[i]);
                points[i].circle.draw();
            }
        }

        function shiftPoint(p) {
            if (window.TweenLite) {
                TweenLite.to(p, 1 + 1 * Math.random(), {
                    x: p.originX + Math.random() * (pointDistance / 2),
                    y: p.originY + Math.random() * (pointDistance / 2),
                    ease: Circ.easeInOut,
                    onComplete: function () { shiftPoint(p); }
                });
            }
        }

        function drawLines(p) {
            if (!target) return;
            for (var i = 0; i < p.closest.length; i++) {
                ctx.beginPath();
                ctx.moveTo(p.x, p.y);
                ctx.lineTo(p.closest[i].x, p.closest[i].y);
                ctx.strokeStyle = 'rgba(156,217,249,' + p.opacity + ')';
                ctx.stroke();
            }
        }

        function Circle(pos, rad, color) {
            this.pos = pos || null;
            this.radius = rad || null;
            this.color = color || null;
            this.opacity = 1;
            this.draw = function () {
                ctx.beginPath();
                ctx.arc(this.pos.x, this.pos.y, this.radius, 0, Math.PI * 2, false);
                ctx.fillStyle = 'rgba(156,217,249,' + this.opacity + ')';
                ctx.fill();
            };
        }

        function getDistance(p1, p2) {
            return Math.pow(p1.x - p2.x, 2) + Math.pow(p1.y - p2.y, 2);
        }

        // Inicializar
        initHeader();
        initAnimation();
        addListeners();
    })();
});

        
        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.getElementById('header');
            const backToTop = document.getElementById('backToTop');
            
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
                backToTop.classList.add('visible');
            } else {
                header.classList.remove('scrolled');
                backToTop.classList.remove('visible');
            }
        });

        // Back to top functionality
        document.getElementById('backToTop').addEventListener('click', function() {
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

        // Animate numbers in stats section
        function animateNumbers() {
            const statNumbers = document.querySelectorAll('.stat-number');
            
            const observerOptions = {
                threshold: 0.7
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = entry.target;
                        const finalNumber = parseInt(target.dataset.count);
                        const duration = 2000; // 2 seconds
                        const frameDuration = 1000 / 60; // 60 FPS
                        const totalFrames = Math.round(duration / frameDuration);
                        let frame = 0;

                        const counter = setInterval(() => {
                            frame++;
                            const progress = frame / totalFrames;
                            const currentNumber = Math.round(finalNumber * progress);
                            
                            if (finalNumber === 98) {
                                target.textContent = currentNumber + '%';
                            } else if (finalNumber >= 1000) {
                                target.textContent = (currentNumber / 1000).toFixed(0) + 'K+';
                            } else {
                                target.textContent = currentNumber.toLocaleString();
                            }

                            if (frame === totalFrames) {
                                clearInterval(counter);
                            }
                        }, frameDuration);

                        observer.unobserve(target);
                    }
                });
            }, observerOptions);

            statNumbers.forEach(number => {
                observer.observe(number);
            });
        }

        // Animate elements on scroll
        function animateOnScroll() {
            const animatedElements = document.querySelectorAll('.animate-on-scroll');
            
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationDelay = '0.1s';
                        entry.target.style.opacity = '1';
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            animatedElements.forEach(element => {
                observer.observe(element);
            });
        }

        // Search functionality
        const searchBox = document.querySelector('.search-box');
        searchBox.addEventListener('focus', function() {
            this.placeholder = 'Buscar eventos, organizadores...';
        });

        searchBox.addEventListener('blur', function() {
            this.placeholder = 'Buscar eventos...';
        });

        searchBox.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    // Here you would typically redirect to search results
                    console.log('Searching for:', searchTerm);
                    // For demo purposes, show alert
                    alert(`Buscando eventos relacionados con: "${searchTerm}"`);
                }
            }
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                
                if (target) {
                    const offsetTop = target.offsetTop - 80; // Account for fixed header
                    
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
                
                // Close mobile menu if open
                mobileMenu.style.display = 'none';
            });
        });

        // Add hover effects to feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-15px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Initialize all animations and effects
        document.addEventListener('DOMContentLoaded', function() {
            animateNumbers();
            animateOnScroll();
            
            // Add loading animation to page
            document.body.style.opacity = '0';
            setTimeout(() => {
                document.body.style.transition = 'opacity 0.5s ease-in-out';
                document.body.style.opacity = '1';
            }, 100);
        });

        // Add parallax effect to hero section
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero');
            const rate = scrolled * -0.3;
            
            if (hero) {
                hero.style.transform = `translateY(${rate}px)`;
            }
        });

        // Add typing effect to hero title
        function typeWriter() {
            const text = "Crea invitaciones únicas para tus eventos más especiales";
            const heroTitle = document.querySelector('.hero-title');
            let i = 0;
            
            heroTitle.textContent = '';
            
            function type() {
                if (i < text.length) {
                    heroTitle.textContent += text.charAt(i);
                    i++;
                    setTimeout(type, 50);
                }
            }
            
            // Start typing after a delay
            setTimeout(type, 1000);
        }

        // Intersection Observer for better performance
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        // Add entrance animations to sections
        const sections = document.querySelectorAll('section');
        const sectionObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        sections.forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(30px)';
            section.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
            sectionObserver.observe(section);
        });

        // Form validation for future login/signup forms
        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        // Add click tracking for analytics (placeholder)
        document.addEventListener('click', function(e) {
            const element = e.target;
            
            // Track button clicks
            if (element.classList.contains('btn') || element.closest('.btn')) {
                const buttonText = element.textContent || element.closest('.btn').textContent;
                console.log('Button clicked:', buttonText.trim());
            }
            
            // Track navigation clicks
            if (element.classList.contains('nav-link') || element.closest('.nav-link')) {
                const linkText = element.textContent || element.closest('.nav-link').textContent;
                console.log('Navigation clicked:', linkText.trim());
            }
        });

        // Add loading states for interactive elements
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function(e) {
                // Don't prevent default for anchor tags with href
                if (this.tagName === 'A' && this.getAttribute('href').startsWith('#')) {
                    return;
                }
                
                e.preventDefault();
                
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Cargando...';
                this.disabled = true;
                
                // Simulate loading
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 2000);
            });
        });

        console.log('🚀 EventosPro Landing Page initialized successfully!');
        console.log('📱 Features loaded:');
        console.log('   ✅ Responsive design');
        console.log('   ✅ Smooth scrolling');
        console.log('   ✅ Mobile menu');
        console.log('   ✅ Back to top button');
        console.log('   ✅ Animated counters');
        console.log('   ✅ Search functionality');
        console.log('   ✅ Loading animations');
        console.log('   ✅ Parallax effects');
        console.log('   ✅ Interactive elements');
    