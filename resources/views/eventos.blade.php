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

                <div class="test-cards-info">
                    <p><strong>🧪 Tarjetas de Prueba:</strong></p>
                    <ul>
                        <li>Éxito: <code>4242 4242 4242 4242</code></li>
                        <li>Fecha: Cualquier futura (ej: 12/25)</li>
                        <li>CVC: Cualquier 3 dígitos (ej: 123)</li>
                    </ul>
                </div>

                <div class="security-badge">
                    <i class="fas fa-shield-alt"></i>
                    Pago 100% seguro procesado por Stripe
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Estilos del Modal de Pago */
        .payment-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .payment-modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
        }

        .payment-modal-content {
            position: relative;
            background: white;
            border-radius: 20px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .payment-modal-header {
            padding: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .payment-modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .modal-close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .modal-close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        .payment-modal-body {
            padding: 2rem;
        }

        .event-summary {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .event-summary h3 {
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }

        .event-summary p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .price-display {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 2px solid #e0e0e0;
            font-size: 1.1rem;
        }

        .price-display strong {
            color: #667eea;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            color: #555;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        #card-element {
            padding: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            background: white;
        }

        #card-element.StripeElement--focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .card-errors {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: none;
        }

        .card-errors.show {
            display: block;
        }

        .btn-pay {
            width: 100%;
            padding: 1.2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-pay:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-pay:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .test-cards-info {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #fff3cd;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        .test-cards-info code {
            background: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }

        .security-badge {
            text-align: center;
            margin-top: 1rem;
            padding: 0.75rem;
            background: #e3f2fd;
            border-radius: 8px;
            color: #1976d2;
            font-size: 0.9rem;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>


    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('js/eventos.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>


</body>

</html>