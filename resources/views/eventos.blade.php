<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos Públicos - InvitaMex</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/eventos.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <!-- HEADER -->
    @include('partials.header')

    <!-- PAGE HERO -->
    <section class="page-hero">
        <div class="page-hero-content">
            <h1>Descubre Eventos Increíbles</h1>
            <p>
                Explora una gran variedad de eventos públicos creados por nuestra comunidad. 
                Desde bodas elegantes hasta cumpleaños divertidos, encuentra la inspiración perfecta.
            </p>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="container">
            <!-- FILTERS SECTION -->
            <section class="filters-section animate-on-scroll">
                <div class="filters-header">
                    <h2 class="filters-title">
                        <i class="fas fa-filter"></i>
                        Filtrar Eventos
                    </h2>
                    <div class="results-info">
                        <span id="resultsCount">0</span> eventos encontrados
                    </div>
                </div>
                
                <div class="filters-grid">
                    <div class="filter-group">
                        <label class="filter-label">Buscar por nombre</label>
                        <input type="text" class="filter-input" id="searchFilter" 
                               placeholder="Nombre del evento...">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label" for="typeFilter">Tipo de evento</label>
                        <select class="filter-select" id="typeFilter" name="typeFilter">
                            <option value="">Todos los tipos</option>
                            <option value="boda">Bodas</option>
                            <option value="cumpleanos">Cumpleaños</option>
                            <option value="graduacion">Graduaciones</option>
                            <option value="corporativo">Corporativos</option>
                            <option value="social">Eventos Sociales</option>
                            <option value="religioso">Religiosos</option>
                            <option value="otro">Otros</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Fecha desde</label>
                        <input type="date" class="filter-input" id="dateFromFilter" title="Selecciona la fecha de inicio" placeholder="Fecha desde">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Fecha hasta</label>
                        <input type="date" class="filter-input" id="dateToFilter" title="Selecciona la fecha de fin" placeholder="Fecha hasta">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Ubicación</label>
                        <input type="text" class="filter-input" id="locationFilter" 
                               placeholder="Ciudad o lugar...">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Organizador</label>
                        <input type="text" class="filter-input" id="organizerFilter" 
                               placeholder="Nombre del organizador...">
                    </div>
                </div>
                
                <div class="filter-actions">
                    <div class="filter-buttons">
                        <button class="btn btn-primary" onclick="applyFilters()">
                            <i class="fas fa-search"></i>
                            Buscar Eventos
                        </button>
                        <button class="btn btn-secondary" onclick="clearFilters()">
                            <i class="fas fa-times"></i>
                            Limpiar Filtros
                        </button>
                    </div>
                    
                    <div class="view-toggle">
                        <button class="view-btn active" id="gridViewBtn" onclick="toggleView('grid')" title="Vista de cuadrícula">
                            <i class="fas fa-th"></i>
                        </button>
                        <button class="view-btn" id="listViewBtn" onclick="toggleView('list')" title="Vista de lista">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </section>

            <!-- EVENTS SECTION -->
            <section class="events-section">
                <!-- GRID VIEW -->
                <div class="events-grid active" id="eventsGrid">
                    <!-- Los eventos se cargarán aquí dinámicamente -->
                </div>
                
                <!-- LIST VIEW -->
                <div class="events-list" id="eventsList">
                    <!-- Los eventos se cargarán aquí dinámicamente -->
                </div>
                
                <!-- NO RESULTS -->
                <div class="no-results" id="noResults" style="display: none;">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No se encontraron eventos</h3>
                    <p>Prueba ajustando los filtros de búsqueda</p>
                </div>
                
                <!-- PAGINATION -->
                <div class="pagination" id="pagination">
                    <button class="pagination-btn" id="prevPage" onclick="changePage(-1)" disabled title="Página anterior">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span id="pageNumbers"></span>
                    <button class="pagination-btn" id="nextPage" onclick="changePage(1)" title="Página siguiente">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </section>
        </div>
    </main>

    <!-- EVENT PREVIEW MODAL -->
    <div class="modal" id="eventModal">
        <div class="modal-content">
            <div class="modal-close" onclick="closeModal()">&times;</div>
            
            <div class="modal-header" id="modalHeader">
                <i class="fas fa-calendar-alt"></i>
            </div>
            
            <div class="modal-body">
                <h2 class="modal-title" id="modalTitle">Título del Evento</h2>
                <p class="modal-organizer">
                    <i class="fas fa-user"></i>
                    Organizado por <span id="modalOrganizer">Organizador</span>
                </p>
                
                <div class="modal-details-grid">
                    <div class="modal-detail">
                        <div class="modal-detail-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                        <div>
                            <strong>Fecha y Hora</strong>
                            <br><span id="modalDate">Fecha</span>
                        </div>
                    </div>
                    
                    <div class="modal-detail">
                        <div class="modal-detail-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <strong>Ubicación</strong>
                            <br><span id="modalLocation">Ubicación</span>
                        </div>
                    </div>
                    
                    <div class="modal-detail">
                        <div class="modal-detail-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div>
                            <strong>Tipo de Evento</strong>
                            <br><span id="modalType">Tipo</span>
                        </div>
                    </div>
                    
                    <div class="modal-detail">
                        <div class="modal-detail-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <strong>Asistentes</strong>
                            <br><span id="modalAttendees">0</span> confirmados
                        </div>
                    </div>
                </div>
                
                <div class="modal-description">
                    <h3 style="margin-bottom: 15px;">Descripción del Evento</h3>
                    <p id="modalDescription">Descripción completa del evento...</p>
                </div>
                
                <div class="modal-actions">
                    <button class="btn btn-primary" onclick="confirmAttendance()">
                        <i class="fas fa-check"></i>
                        Confirmar Asistencia
                    </button>
                    <button class="btn btn-secondary" onclick="shareWhatsApp()">
                        <i class="fab fa-whatsapp"></i>
                        Compartir por WhatsApp
                    </button>
                    <button class="btn btn-secondary" onclick="viewLocation()">
                        <i class="fas fa-map"></i>
                        Ver Ubicación
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    @include('partials.footer')

    <!-- BACK TO TOP BUTTON -->
    <button class="back-to-top" id="backToTop" aria-label="Volver arriba">
        <i class="fas fa-chevron-up"></i>
    </button>

    <script src="{{ asset('js/eventos.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>

    
</body>
</html>
