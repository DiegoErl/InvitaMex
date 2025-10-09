
        // Variables globales
        let allEvents = [];
        let filteredEvents = [];
        let currentPage = 1;
        let eventsPerPage = 12;
        let currentView = 'grid';
        let currentEventId = null;

        // Eventos demo
        const demoEvents = [
            {
                id: 1,
                title: 'Boda de Ana y Carlos',
                type: 'boda',
                organizer: 'Ana García',
                date: new Date('2025-09-15T18:00'),
                location: 'Jardín Botánico, Ciudad de México',
                description: 'Celebra con nosotros este momento tan especial. Una ceremonia íntima seguida de una recepción llena de alegría, música y amor.',
                attendees: 85,
                status: 'upcoming',
                color: '#ff6b9d',
                icon: 'fas fa-heart'
            },
            {
                id: 2,
                title: 'Cumpleaños 30 de Pedro',
                type: 'cumpleanos',
                organizer: 'Pedro Sánchez',
                date: new Date('2025-09-20T19:00'),
                location: 'Salón de Fiestas Las Flores, Roma Norte',
                description: '¡Celebremos los 30 años de Pedro con una gran fiesta! Habrá música en vivo, comida deliciosa y muchas sorpresas.',
                attendees: 42,
                status: 'upcoming',
                color: '#4ecdc4',
                icon: 'fas fa-birthday-cake'
            },
            {
                id: 3,
                title: 'Graduación Ingeniería 2025',
                type: 'graduacion',
                organizer: 'Universidad Nacional',
                date: new Date('2025-08-30T10:00'),
                location: 'Auditorio Principal UNAM, CU',
                description: 'Ceremonia de graduación de la Facultad de Ingeniería. Un momento histórico para celebrar el esfuerzo y dedicación.',
                attendees: 320,
                status: 'upcoming',
                color: '#45b7d1',
                icon: 'fas fa-graduation-cap'
            },
            {
                id: 4,
                title: 'Conferencia Tech Innovation',
                type: 'corporativo',
                organizer: 'TechCorp México',
                date: new Date('2025-10-05T09:00'),
                location: 'Centro de Convenciones WTC, CDMX',
                description: 'Evento corporativo sobre las últimas tendencias en tecnología e innovación. Speakers internacionales y networking.',
                attendees: 150,
                status: 'upcoming',
                color: '#6c5ce7',
                icon: 'fas fa-briefcase'
            },
            {
                id: 5,
                title: 'Festival de Música Indie',
                type: 'social',
                organizer: 'Cultura Musical MX',
                date: new Date('2025-11-12T17:00'),
                location: 'Parque Hundido, Ciudad de México',
                description: 'Un festival al aire libre con las mejores bandas indie nacionales e internacionales. Food trucks y artesanías.',
                attendees: 500,
                status: 'upcoming',
                color: '#fd79a8',
                icon: 'fas fa-music'
            },
            {
                id: 6,
                title: 'Primera Comunión de Sofía',
                type: 'religioso',
                organizer: 'Familia Rodríguez',
                date: new Date('2025-09-25T11:00'),
                location: 'Parroquia San José, Coyoacán',
                description: 'Celebración religiosa de la Primera Comunión de nuestra pequeña Sofía. Misa seguida de convivio familiar.',
                attendees: 35,
                status: 'upcoming',
                color: '#74b9ff',
                icon: 'fas fa-church'
            },
            {
                id: 7,
                title: 'Baby Shower de María',
                type: 'social',
                organizer: 'Amigas de María',
                date: new Date('2025-08-15T15:00'),
                location: 'Jardín Privado, Las Lomas',
                description: 'Celebración del embarazo de María. Juegos, regalos y mucha diversión en un ambiente lleno de amor.',
                attendees: 28,
                status: 'upcoming',
                color: '#00b894',
                icon: 'fas fa-baby'
            },
            {
                id: 8,
                title: 'Aniversario 25 años Empresa XYZ',
                type: 'corporativo',
                organizer: 'Empresa XYZ',
                date: new Date('2025-12-10T19:00'),
                location: 'Hotel Four Seasons, Polanco',
                description: 'Celebración de 25 años de éxito empresarial. Cena de gala, reconocimientos y entretenimiento de primer nivel.',
                attendees: 200,
                status: 'upcoming',
                color: '#a29bfe',
                icon: 'fas fa-trophy'
            },
            {
                id: 9,
                title: 'Quinceaños de Isabella',
                type: 'cumpleanos',
                organizer: 'Familia Martínez',
                date: new Date('2025-07-20T20:00'),
                location: 'Salón Versailles, Satélite',
                description: 'Los quince años de Isabella serán una noche mágica llena de baile, música y celebración familiar.',
                attendees: 120,
                status: 'past',
                color: '#e84393',
                icon: 'fas fa-crown'
            },
            {
                id: 10,
                title: 'Workshop de Fotografía',
                type: 'corporativo',
                organizer: 'Academia Visual',
                date: new Date('2025-09-28T10:00'),
                location: 'Estudio Creativo, Roma Sur',
                description: 'Taller intensivo de fotografía profesional. Técnicas avanzadas, iluminación y post-producción.',
                attendees: 25,
                status: 'today',
                color: '#00cec9',
                icon: 'fas fa-camera'
            }
        ];

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            allEvents = [...demoEvents];
            filteredEvents = [...allEvents];
            renderEvents();
            updateResultsCount();
            setupEventListeners();
        });

        // Configurar event listeners
        function setupEventListeners() {
            // Filtros en tiempo real
            document.getElementById('searchFilter').addEventListener('input', debounce(applyFilters, 300));
            document.getElementById('typeFilter').addEventListener('change', applyFilters);
            document.getElementById('dateFromFilter').addEventListener('change', applyFilters);
            document.getElementById('dateToFilter').addEventListener('change', applyFilters);
            document.getElementById('locationFilter').addEventListener('input', debounce(applyFilters, 300));
            document.getElementById('organizerFilter').addEventListener('input', debounce(applyFilters, 300));

            // Búsqueda global
            document.getElementById('globalSearch').addEventListener('input', function(e) {
                document.getElementById('searchFilter').value = e.target.value;
                applyFilters();
            });
        }

        // Función debounce para optimizar búsquedas
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Aplicar filtros
        function applyFilters() {
            const search = document.getElementById('searchFilter').value.toLowerCase();
            const type = document.getElementById('typeFilter').value;
            const dateFrom = document.getElementById('dateFromFilter').value;
            const dateTo = document.getElementById('dateToFilter').value;
            const location = document.getElementById('locationFilter').value.toLowerCase();
            const organizer = document.getElementById('organizerFilter').value.toLowerCase();

            filteredEvents = allEvents.filter(event => {
                // Filtro por búsqueda de texto
                if (search && !event.title.toLowerCase().includes(search)) {
                    return false;
                }

                // Filtro por tipo
                if (type && event.type !== type) {
                    return false;
                }

                // Filtro por fecha desde
                if (dateFrom) {
                    const eventDate = event.date.toISOString().split('T')[0];
                    if (eventDate < dateFrom) {
                        return false;
                    }
                }

                // Filtro por fecha hasta
                if (dateTo) {
                    const eventDate = event.date.toISOString().split('T')[0];
                    if (eventDate > dateTo) {
                        return false;
                    }
                }

                // Filtro por ubicación
                if (location && !event.location.toLowerCase().includes(location)) {
                    return false;
                }

                // Filtro por organizador
                if (organizer && !event.organizer.toLowerCase().includes(organizer)) {
                    return false;
                }

                return true;
            });

            currentPage = 1;
            renderEvents();
            updateResultsCount();
            updatePagination();
        }

        // Limpiar filtros
        function clearFilters() {
            document.getElementById('searchFilter').value = '';
            document.getElementById('typeFilter').value = '';
            document.getElementById('dateFromFilter').value = '';
            document.getElementById('dateToFilter').value = '';
            document.getElementById('locationFilter').value = '';
            document.getElementById('organizerFilter').value = '';
            document.getElementById('globalSearch').value = '';

            filteredEvents = [...allEvents];
            currentPage = 1;
            renderEvents();
            updateResultsCount();
            updatePagination();
        }

        // Cambiar vista (grid/list)
        function toggleView(view) {
            currentView = view;
            
            document.getElementById('gridViewBtn').classList.toggle('active', view === 'grid');
            document.getElementById('listViewBtn').classList.toggle('active', view === 'list');
            
            document.getElementById('eventsGrid').classList.toggle('active', view === 'grid');
            document.getElementById('eventsList').classList.toggle('active', view === 'list');

            renderEvents();
        }

        // Renderizar eventos
        function renderEvents() {
            const startIndex = (currentPage - 1) * eventsPerPage;
            const endIndex = startIndex + eventsPerPage;
            const eventsToShow = filteredEvents.slice(startIndex, endIndex);

            if (eventsToShow.length === 0) {
                document.getElementById('noResults').style.display = 'block';
                document.getElementById('eventsGrid').style.display = 'none';
                document.getElementById('eventsList').style.display = 'none';
                document.getElementById('pagination').style.display = 'none';
                return;
            }

            document.getElementById('noResults').style.display = 'none';
            document.getElementById('pagination').style.display = 'flex';

            if (currentView === 'grid') {
                renderGridView(eventsToShow);
            } else {
                renderListView(eventsToShow);
            }

            updatePagination();
        }

        // Renderizar vista de grid
        function renderGridView(events) {
            const container = document.getElementById('eventsGrid');
            let html = '';

            events.forEach(event => {
                const statusText = getStatusText(event.status);
                const statusClass = `status-${event.status}`;
                const eventIcon = event.icon || 'fas fa-calendar-alt';

                html += `
                    <div class="event-card animate-on-scroll" onclick="showEventPreview(${event.id})">
                        <div class="event-image" style="background: ${event.color}">
                            <i class="${eventIcon}"></i>
                            <div class="event-type-badge">${getTypeText(event.type)}</div>
                            <div class="event-status ${statusClass}">${statusText}</div>
                        </div>
                        
                        <div class="event-content">
                            <div class="event-header">
                                <h3 class="event-title">${event.title}</h3>
                                <p class="event-organizer">
                                    <i class="fas fa-user"></i>
                                    ${event.organizer}
                                </p>
                            </div>
                            
                            <div class="event-details">
                                <div class="event-detail">
                                    <i class="fas fa-calendar"></i>
                                    <span>${formatDate(event.date)}</span>
                                </div>
                                <div class="event-detail">
                                    <i class="fas fa-clock"></i>
                                    <span>${formatTime(event.date)}</span>
                                </div>
                                <div class="event-detail">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>${event.location}</span>
                                </div>
                            </div>
                            
                            <p class="event-description">${event.description}</p>
                            
                            <div class="event-stats">
                                <div class="stat-item">
                                    <span class="stat-number">${event.attendees}</span>
                                    <span class="stat-label">Asistentes</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number">${getTypeText(event.type)}</span>
                                    <span class="stat-label">Categoría</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number">${getDaysUntil(event.date)}</span>
                                    <span class="stat-label">Días</span>
                                </div>
                            </div>
                            
                            <div class="event-actions">
                                <button class="btn btn-primary btn-small" onclick="event.stopPropagation(); showEventPreview(${event.id})">
                                    <i class="fas fa-eye"></i>
                                    Ver Evento
                                </button>
                                <button class="btn btn-outline btn-small" onclick="event.stopPropagation(); quickShare(${event.id})">
                                    <i class="fas fa-share"></i>
                                    Compartir
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
            container.style.display = 'grid';
            document.getElementById('eventsList').style.display = 'none';
        }

        // Renderizar vista de lista
        function renderListView(events) {
            const container = document.getElementById('eventsList');
            let html = '';

            events.forEach(event => {
                const statusText = getStatusText(event.status);
                const statusClass = `status-${event.status}`;
                const eventIcon = event.icon || 'fas fa-calendar-alt';

                html += `
                    <div class="event-card-list animate-on-scroll" onclick="showEventPreview(${event.id})">
                        <div class="event-image-small" style="background: ${event.color}">
                            <i class="${eventIcon}"></i>
                            <div class="event-status ${statusClass}" style="position: absolute; top: 5px; left: 5px; font-size: 0.7rem; padding: 3px 8px;">
                                ${statusText}
                            </div>
                        </div>
                        
                        <div class="event-info-list">
                            <div>
                                <h3 class="event-title" style="margin-bottom: 5px;">${event.title}</h3>
                                <p class="event-organizer" style="margin-bottom: 10px;">
                                    <i class="fas fa-user"></i>
                                    ${event.organizer}
                                </p>
                                <div class="event-details">
                                    <div class="event-detail" style="margin-bottom: 5px;">
                                        <i class="fas fa-calendar"></i>
                                        <span>${formatDate(event.date)} - ${formatTime(event.date)}</span>
                                    </div>
                                    <div class="event-detail">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>${event.location}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="display: flex; gap: 20px; align-items: center; font-size: 0.9rem; color: var(--text-muted);">
                                <span><strong>${event.attendees}</strong> asistentes</span>
                                <span><strong>${getTypeText(event.type)}</strong></span>
                                <span><strong>${getDaysUntil(event.date)}</strong> días</span>
                            </div>
                        </div>
                        
                        <div class="event-actions-list">
                            <button class="btn btn-primary btn-small" onclick="event.stopPropagation(); showEventPreview(${event.id})">
                                <i class="fas fa-eye"></i>
                                Ver Evento
                            </button>
                            <button class="btn btn-outline btn-small" onclick="event.stopPropagation(); quickShare(${event.id})">
                                <i class="fas fa-share"></i>
                                Compartir
                            </button>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
            container.style.display = 'flex';
            document.getElementById('eventsGrid').style.display = 'none';
        }

        // Mostrar vista previa del evento
        function showEventPreview(eventId) {
            const event = allEvents.find(e => e.id === eventId);
            if (!event) return;

            currentEventId = eventId;

            // Actualizar contenido del modal
            document.getElementById('modalHeader').style.background = event.color;
            document.getElementById('modalHeader').innerHTML = `<i class="${event.icon || 'fas fa-calendar-alt'}"></i>`;
            document.getElementById('modalTitle').textContent = event.title;
            document.getElementById('modalOrganizer').textContent = event.organizer;
            document.getElementById('modalDate').textContent = formatFullDate(event.date);
            document.getElementById('modalLocation').textContent = event.location;
            document.getElementById('modalType').textContent = getTypeText(event.type);
            document.getElementById('modalAttendees').textContent = event.attendees;
            document.getElementById('modalDescription').textContent = event.description;

            // Mostrar modal
            document.getElementById('eventModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        // Cerrar modal
        function closeModal() {
            document.getElementById('eventModal').classList.remove('show');
            document.body.style.overflow = 'auto';
            currentEventId = null;
        }

        // Confirmar asistencia
        function confirmAttendance() {
            if (!currentEventId) return;

            const event = allEvents.find(e => e.id === currentEventId);
            if (!event) return;

            const name = prompt('Ingresa tu nombre completo para confirmar asistencia:');
            if (!name) return;

            // Incrementar asistentes (simulación)
            event.attendees++;

            alert(`¡Gracias ${name}! Tu asistencia ha sido confirmada para "${event.title}".`);
            
            // Actualizar la vista
            renderEvents();
            
            // Actualizar modal si está abierto
            document.getElementById('modalAttendees').textContent = event.attendees;
        }

        // Compartir por WhatsApp
        function shareWhatsApp() {
            if (!currentEventId) return;

            const event = allEvents.find(e => e.id === currentEventId);
            if (!event) return;

            const mensaje = `🎉 ¡Te invito a ${event.title}! 🎉%0A%0A` +
                          `📅 *Fecha:* ${formatFullDate(event.date)}%0A` +
                          `📍 *Lugar:* ${event.location}%0A` +
                          `👤 *Organizador:* ${event.organizer}%0A%0A` +
                          `📝 *Descripción:* ${event.description}%0A%0A` +
                          `¡Espero verte ahí! 🥳`;

            const url = `https://wa.me/?text=${mensaje}`;
            window.open(url, '_blank');
        }

        // Compartir rápido
        function quickShare(eventId) {
            const event = allEvents.find(e => e.id === eventId);
            if (!event) return;

            const mensaje = `🎉 Mira este evento: ${event.title}%0A%0A` +
                          `📅 ${formatFullDate(event.date)}%0A` +
                          `📍 ${event.location}%0A` +
                          `👤 Por: ${event.organizer}`;

            const url = `https://wa.me/?text=${mensaje}`;
            window.open(url, '_blank');
        }

        // Ver ubicación
        function viewLocation() {
            if (!currentEventId) return;

            const event = allEvents.find(e => e.id === currentEventId);
            if (!event) return;

            const query = encodeURIComponent(event.location);
            const url = `https://www.google.com/maps/search/${query}`;
            window.open(url, '_blank');
        }

        // Actualizar contador de resultados
        function updateResultsCount() {
            document.getElementById('resultsCount').textContent = filteredEvents.length;
        }

        // Paginación
        function updatePagination() {
            const totalPages = Math.ceil(filteredEvents.length / eventsPerPage);
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const pageNumbers = document.getElementById('pageNumbers');

            prevBtn.disabled = currentPage <= 1;
            nextBtn.disabled = currentPage >= totalPages;

            let paginationHTML = '';
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);

            for (let i = startPage; i <= endPage; i++) {
                paginationHTML += `
                    <button class="pagination-btn ${i === currentPage ? 'active' : ''}" 
                            onclick="goToPage(${i})">
                        ${i}
                    </button>
                `;
            }

            pageNumbers.innerHTML = paginationHTML;

            // Ocultar paginación si hay pocas páginas
            if (totalPages <= 1) {
                document.getElementById('pagination').style.display = 'none';
            } else {
                document.getElementById('pagination').style.display = 'flex';
            }
        }

        function changePage(direction) {
            const totalPages = Math.ceil(filteredEvents.length / eventsPerPage);
            const newPage = currentPage + direction;

            if (newPage >= 1 && newPage <= totalPages) {
                currentPage = newPage;
                renderEvents();
                window.scrollTo({ top: 400, behavior: 'smooth' });
            }
        }

        function goToPage(page) {
            currentPage = page;
            renderEvents();
            window.scrollTo({ top: 400, behavior: 'smooth' });
        }

        // Funciones auxiliares
        function getStatusText(status) {
            const statusMap = {
                'upcoming': 'Próximo',
                'today': 'Hoy',
                'past': 'Pasado'
            };
            return statusMap[status] || 'Desconocido';
        }

        function getTypeText(type) {
            const typeMap = {
                'boda': 'Boda',
                'cumpleanos': 'Cumpleaños',
                'graduacion': 'Graduación',
                'corporativo': 'Corporativo',
                'social': 'Social',
                'religioso': 'Religioso',
                'otro': 'Otro'
            };
            return typeMap[type] || 'Evento';
        }

        function formatDate(date) {
            return date.toLocaleDateString('es-ES', {
                weekday: 'long',
                day: 'numeric',
                month: 'long'
            });
        }

        function formatTime(date) {
            return date.toLocaleTimeString('es-ES', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function formatFullDate(date) {
            return date.toLocaleDateString('es-ES', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function getDaysUntil(date) {
            const today = new Date();
            const eventDate = new Date(date);
            const diffTime = eventDate - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays < 0) {
                return 'Pasado';
            } else if (diffDays === 0) {
                return 'Hoy';
            } else if (diffDays === 1) {
                return '1';
            } else {
                return diffDays.toString();
            }
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

        // Close modal when clicking outside
        document.getElementById('eventModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Escape key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
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
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.querySelectorAll('.animate-on-scroll').forEach(el => {
                    observer.observe(el);
                });
            }, 100);
        });

        // Search functionality
        document.querySelector('.search-box').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    document.getElementById('searchFilter').value = searchTerm;
                    applyFilters();
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

        // Auto-refresh events every 30 seconds (simulation)
        setInterval(() => {
            console.log('🔄 Actualizando eventos automáticamente...');
            // En un proyecto real, aquí se haría una llamada a la API
        }, 30000);

        // Console welcome message
        setTimeout(() => {
            console.log('%c🎉 EventosPro - Eventos Públicos', 'color: #4facfe; font-size: 24px; font-weight: bold;');
            console.log('%c📅 Eventos demo cargados:', 'color: #28a745; font-size: 16px;');
            console.log(`%c• ${allEvents.length} eventos disponibles`, 'color: #17a2b8; font-size: 14px;');
            console.log('%c🔍 Filtros avanzados activados', 'color: #ffc107; font-size: 14px;');
            console.log('%c👁️ Vista previa de eventos funcional', 'color: #667eea; font-size: 14px;');
            console.log('%c📱 Diseño responsive optimizado', 'color: #e84393; font-size: 14px;');
        }, 1000);
   