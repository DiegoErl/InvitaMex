<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Eventos Públicos - InvitaMex</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/eventos.css') }}" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
    <meta name="user-authenticated" content="true">
    <meta name="user-id" content="{{ Auth::id() }}">
    @endauth

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



            <!-- IMAGEN DE PORTADA PRINCIPAL -->
            <div class="modal-header-image" id="modalHeaderImage" onclick="openImageViewer(0)">
                <img id="modalMainImage" src="" alt="Evento" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;">
                <div class="image-zoom-hint">
                    <i class="fas fa-search-plus"></i>
                    Click para ampliar
                </div>
            </div>

            <div class="modal-body">
                <h2 class="modal-title" id="modalTitle">Título del Evento</h2>
                <p class="modal-organizer">
                    <i class="fas fa-user"></i>
                    Organizado por <span id="modalOrganizer">Organizador</span>
                </p>

                <!-- CARRUSEL DE IMÁGENES ADICIONALES -->
                <div class="modal-gallery" id="modalGallery" style="display: none;">
                    <h3 style="margin-bottom: 15px; color: #667eea;">
                        <i class="fas fa-images"></i> Más imágenes del evento
                    </h3>
                    <div class="gallery-thumbnails" id="galleryThumbnails">
                        <!-- Se llenarán dinámicamente con JavaScript -->
                    </div>
                </div>

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
                    <button class="btn btn-primary" onclick="viewFullInvitation()">
                        <i class="fas fa-eye"></i>
                        Ver Invitación Completa
                    </button>
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

    <!-- MODAL DE VISTA AMPLIADA DE IMÁGENES -->
    <div class="image-viewer-modal" id="imageViewerModal" onclick="closeImageViewer()">
        <div class="image-viewer-content" onclick="event.stopPropagation()">
            <button class="viewer-close" onclick="closeImageViewer()">&times;</button>

            <button class="viewer-control prev" onclick="event.stopPropagation(); changeViewerImage(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>

            <div class="viewer-image-container">
                <img id="viewerImage" src="" alt="Vista ampliada">
            </div>

            <button class="viewer-control next" onclick="event.stopPropagation(); changeViewerImage(1)">
                <i class="fas fa-chevron-right"></i>
            </button>

            <div class="viewer-counter">
                <span id="viewerCounter">1 / 1</span>
            </div>
        </div>
    </div>

    

    <!-- FOOTER -->
    @include('partials.footer')

    <!-- BACK TO TOP BUTTON -->
    <button class="back-to-top" id="backToTop" aria-label="Volver arriba">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- MODAL DE PAGO -->
    <div class="payment-modal" id="paymentModal" style="display: none;">
        <div class="payment-modal-overlay" onclick="closePaymentModal()"></div>
        <div class="payment-modal-content">
            <div class="payment-modal-header">
                <h2><i class="fas fa-credit-card"></i> Pago Seguro</h2>
                <button class="modal-close-btn" onclick="closePaymentModal()">&times;</button>
            </div>

            <div class="payment-modal-body">
                <div class="event-summary" id="eventSummary">
                    <h3 id="modalEventTitle">Cargando...</h3>
                    <p id="modalEventDetails"></p>
                    <div class="price-display">
                        <span>Total a pagar:</span>
                        <strong id="modalEventPrice">$0.00 MXN</strong>
                    </div>
                </div>

                <form id="payment-form">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-credit-card"></i> Información de Tarjeta
                        </label>
                        <div id="card-element"></div>
                        <div id="card-errors" class="card-errors"></div>
                    </div>

                    <button type="submit" class="btn-pay" id="submit-payment">
                        <i class="fas fa-lock"></i>
                        <span id="payment-btn-text">Pagar Ahora</span>
                    </button>
                </form>

                <!-- <div class="test-cards-info">
                    <p><strong>🧪 Tarjetas de Prueba:</strong></p>
                    <ul>
                        <li>Éxito: <code>4242 4242 4242 4242</code></li>
                        <li>Fecha: Cualquier futura (ej: 12/25)</li>
                        <li>CVC: Cualquier 3 dígitos (ej: 123)</li>
                    </ul>
                </div> -->

                <div class="security-badge">
                    <i class="fas fa-shield-alt"></i>
                    Pago 100% seguro procesado por Stripe
                </div>
            </div>
        </div>
    </div>

    


    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('js/eventos.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>


</body>

</html>