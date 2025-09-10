// --- eventos.js ---

// Datos de ejemplo de eventos
const eventsData = [
    {
        id: 1,
        title: "Boda de Ana y Carlos",
        description: "Celebra con nosotros este momento único en el hermoso Jardín Botánico de la CDMX.",
        category: "boda",
        date: "2025-09-15",
        time: "18:00",
        location: "Jardín Botánico CDMX",
        price: "Gratis",
        icon: "fas fa-heart",
        attendees: 150
    },
    {
        id: 2,
        title: "Conferencia Tech Innovation 2025",
        description: "Las últimas tendencias en tecnología e innovación digital presentadas por expertos.",
        category: "conferencia",
        date: "2025-10-20",
        time: "09:00",
        location: "Centro de Convenciones WTC",
        price: "$500",
        icon: "fas fa-laptop",
        attendees: 300
    },
    {
        id: 3,
        title: "Cumpleaños de María - 25 años",
        description: "Una celebración especial llena de música, baile y diversión para celebrar los 25 años.",
        category: "cumpleanos",
        date: "2025-08-30",
        time: "20:00",
        location: "Salón de Fiestas La Terraza",
        price: "Solo invitados",
        icon: "fas fa-birthday-cake",
        attendees: 80
    },
    {
        id: 4,
        title: "Concierto de Jazz en el Parque",
        description: "Una noche mágica bajo las estrellas con los mejores músicos de jazz de la ciudad.",
        category: "concierto",
        date: "2025-09-05",
        time: "19:30",
        location: "Parque México, Condesa",
        price: "$200",
        icon: "fas fa-music",
        attendees: 500
    },
    {
        id: 5,
        title: "Evento Corporativo - Fin de Año",
        description: "Celebración anual de la empresa con cena, entretenimiento y premios especiales.",
        category: "corporativo",
        date: "2025-12-15",
        time: "19:00",
        location: "Hotel Four Seasons",
        price: "Solo empleados",
        icon: "fas fa-building",
        attendees: 200
    },
    {
        id: 6,
        title: "Festival Gastronómico Mexicano",
        description: "Descubre los sabores auténticos de México con chefs reconocidos internacionalmente.",
        category: "otro",
        date: "2025-09-28",
        time: "12:00",
        location: "Plaza de la Constitución",
        price: "$150",
        icon: "fas fa-utensils",
        attendees: 1000
    }
];

let currentPage = 1;
const eventsPerPage = 6;
let filteredEvents = [...eventsData];

// busca el input de búsqueda: usa id 'searchInput' si existe o fallback a '.search-box'
const searchInputEl = document.getElementById('searchInput') || document.querySelector('.search-box') || null;

function renderEvents() {
    const eventsGrid = document.getElementById('eventsGrid');
    const startIndex = (currentPage - 1) * eventsPerPage;
    const endIndex = startIndex + eventsPerPage;
    const eventsToShow = filteredEvents.slice(startIndex, endIndex);

    if (eventsToShow.length === 0) {
        eventsGrid.innerHTML = `
            <div class="no-results">
                <i class="fas fa-calendar-times"></i>
                <h3>No se encontraron eventos</h3>
                <p>Intenta ajustar tus filtros de búsqueda</p>
            </div>
        `;
        return;
    }

    eventsGrid.innerHTML = eventsToShow.map(event => `
        <div class="event-card" onclick="viewEvent(${event.id})">
            <div class="event-image">
                <i class="${event.icon}"></i>
            </div>
            <div class="event-content">
                <div class="event-category">${getCategoryName(event.category)}</div>
                <h3 class="event-title">${event.title}</h3>
                <p class="event-description">${event.description}</p>
                <div class="event-details">
                    <div class="event-detail">
                        <i class="fas fa-calendar"></i>
                        <span>${formatDate(event.date)}</span>
                    </div>
                    <div class="event-detail">
                        <i class="fas fa-clock"></i>
                        <span>${event.time}</span>
                    </div>
                    <div class="event-detail">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>${event.location}</span>
                    </div>
                    <div class="event-detail">
                        <i class="fas fa-users"></i>
                        <span>${event.attendees} asistentes</span>
                    </div>
                </div>
                <div class="event-footer">
                    <div class="event-price">${event.price}</div>
                    <a href="#" class="event-btn" onclick="event.stopPropagation(); event.preventDefault(); joinEvent(${event.id})">
                        <i class="fas fa-ticket-alt"></i>
                        Ver Detalles
                    </a>
                </div>
            </div>
        </div>
    `).join('');

    renderPagination();
}

function renderPagination() {
    const totalPages = Math.ceil(filteredEvents.length / eventsPerPage);
    const pagination = document.getElementById('pagination');
    
    if (totalPages <= 1) {
        pagination.innerHTML = '';
        return;
    }

    let paginationHTML = '';
    
    if (currentPage > 1) {
        paginationHTML += `<a href="#" class="pagination-btn" onclick="changePage(${currentPage - 1})"><i class="fas fa-chevron-left"></i></a>`;
    }

    for (let i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
            paginationHTML += `<a href="#" class="pagination-btn active">${i}</a>`;
        } else {
            paginationHTML += `<a href="#" class="pagination-btn" onclick="changePage(${i})">${i}</a>`;
        }
    }

    if (currentPage < totalPages) {
        paginationHTML += `<a href="#" class="pagination-btn" onclick="changePage(${currentPage + 1})"><i class="fas fa-chevron-right"></i></a>`;
    }

    pagination.innerHTML = paginationHTML;
}

function changePage(page) {
    currentPage = page;
    renderEvents();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function applyFilters() {
    const category = document.getElementById('categoryFilter').value;
    const date = document.getElementById('dateFilter').value;
    const location = document.getElementById('locationFilter').value.toLowerCase();
    const search = searchInputEl ? (searchInputEl.value || '').toLowerCase() : '';

    filteredEvents = eventsData.filter(event => {
        const matchesCategory = !category || event.category === category;
        const matchesDate = !date || event.date === date;
        const matchesLocation = !location || event.location.toLowerCase().includes(location);
        const matchesSearch = !search || 
            event.title.toLowerCase().includes(search) || 
            event.description.toLowerCase().includes(search);

        return matchesCategory && matchesDate && matchesLocation && matchesSearch;
    });

    currentPage = 1;
    renderEvents();
}

function viewEvent(eventId) {
    const event = eventsData.find(e => e.id === eventId);
    alert(`Ver detalles del evento: ${event.title}\n\nEn una implementación real, esto abriría una página con todos los detalles del evento, mapa interactivo, opción de confirmar asistencia, etc.`);
}

function joinEvent(eventId) {
    const event = eventsData.find(e => e.id === eventId);
    alert(`¡Te has unido al evento: ${event.title}!\n\nEn una implementación real, esto registraría tu asistencia y te enviaría una confirmación.`);
}

function getCategoryName(category) {
    const categories = {
        'boda': 'Boda',
        'cumpleanos': 'Cumpleaños',
        'conferencia': 'Conferencia',
        'concierto': 'Concierto',
        'corporativo': 'Corporativo',
        'otro': 'Otros'
    };
    return categories[category] || 'Evento';
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('es-ES', options);
}

// Búsqueda en tiempo real (si existe input)
if (searchInputEl) {
    searchInputEl.addEventListener('input', function() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            applyFilters();
        }, 500);
    });
}

// Inicializar la página
document.addEventListener('DOMContentLoaded', function() {
    renderEvents();
});
