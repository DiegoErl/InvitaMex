// Variables globales
let allEvents = [];
let filteredEvents = [];
let currentPage = 1;
let eventsPerPage = 12;
let currentView = "grid";
let currentEventId = null;

// Inicialización
document.addEventListener("DOMContentLoaded", function () {
    loadEventsFromDatabase();
    setupEventListeners();
});

// Cargar eventos desde la base de datos
async function loadEventsFromDatabase() {
    try {
        const response = await fetch("/api/eventos");
        const data = await response.json();

        // Convertir las fechas de string a objeto Date
        allEvents = data.map((event) => ({
            ...event,
            date: new Date(event.date),
        }));

        filteredEvents = [...allEvents];
        renderEvents();
        updateResultsCount();

        console.log(
            `✅ ${allEvents.length} eventos cargados desde la base de datos`
        );
    } catch (error) {
        console.error("Error al cargar eventos:", error);
        // Mostrar mensaje de error al usuario
        document.getElementById("noResults").style.display = "block";
        document.getElementById("noResults").innerHTML = `
            <i class="fas fa-exclamation-triangle"></i>
            <h3>Error al cargar eventos</h3>
            <p>Por favor, intenta recargar la página</p>
        `;
    }
}

// Configurar event listeners
function setupEventListeners() {
    // Filtros en tiempo real
    document
        .getElementById("searchFilter")
        .addEventListener("input", debounce(applyFilters, 300));
    document
        .getElementById("typeFilter")
        .addEventListener("change", applyFilters);
    document
        .getElementById("dateFromFilter")
        .addEventListener("change", applyFilters);
    document
        .getElementById("dateToFilter")
        .addEventListener("change", applyFilters);
    document
        .getElementById("locationFilter")
        .addEventListener("input", debounce(applyFilters, 300));
    document
        .getElementById("organizerFilter")
        .addEventListener("input", debounce(applyFilters, 300));

    // Búsqueda global (si existe en el header)
    const globalSearch = document.getElementById("globalSearch");
    if (globalSearch) {
        globalSearch.addEventListener("input", function (e) {
            document.getElementById("searchFilter").value = e.target.value;
            applyFilters();
        });
    }
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
    const search = document.getElementById("searchFilter").value.toLowerCase();
    const type = document.getElementById("typeFilter").value;
    const dateFrom = document.getElementById("dateFromFilter").value;
    const dateTo = document.getElementById("dateToFilter").value;
    const location = document
        .getElementById("locationFilter")
        .value.toLowerCase();
    const organizer = document
        .getElementById("organizerFilter")
        .value.toLowerCase();

    filteredEvents = allEvents.filter((event) => {
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
            const eventDate = event.date.toISOString().split("T")[0];
            if (eventDate < dateFrom) {
                return false;
            }
        }

        // Filtro por fecha hasta
        if (dateTo) {
            const eventDate = event.date.toISOString().split("T")[0];
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
    document.getElementById("searchFilter").value = "";
    document.getElementById("typeFilter").value = "";
    document.getElementById("dateFromFilter").value = "";
    document.getElementById("dateToFilter").value = "";
    document.getElementById("locationFilter").value = "";
    document.getElementById("organizerFilter").value = "";

    const globalSearch = document.getElementById("globalSearch");
    if (globalSearch) {
        globalSearch.value = "";
    }

    filteredEvents = [...allEvents];
    currentPage = 1;
    renderEvents();
    updateResultsCount();
    updatePagination();
}

// Cambiar vista (grid/list)
function toggleView(view) {
    currentView = view;

    document
        .getElementById("gridViewBtn")
        .classList.toggle("active", view === "grid");
    document
        .getElementById("listViewBtn")
        .classList.toggle("active", view === "list");

    document
        .getElementById("eventsGrid")
        .classList.toggle("active", view === "grid");
    document
        .getElementById("eventsList")
        .classList.toggle("active", view === "list");

    renderEvents();
}

// Renderizar eventos
function renderEvents() {
    const startIndex = (currentPage - 1) * eventsPerPage;
    const endIndex = startIndex + eventsPerPage;
    const eventsToShow = filteredEvents.slice(startIndex, endIndex);

    if (eventsToShow.length === 0) {
        document.getElementById("noResults").style.display = "block";
        document.getElementById("eventsGrid").style.display = "none";
        document.getElementById("eventsList").style.display = "none";
        document.getElementById("pagination").style.display = "none";
        return;
    }

    document.getElementById("noResults").style.display = "none";
    document.getElementById("pagination").style.display = "flex";

    if (currentView === "grid") {
        renderGridView(eventsToShow);
    } else {
        renderListView(eventsToShow);
    }

    updatePagination();
}

// Renderizar vista de grid
function renderGridView(events) {
    const container = document.getElementById("eventsGrid");
    let html = "";

    events.forEach((event) => {
        const statusText = getStatusText(event.status);
        const statusClass = `status-${event.status}`;
        const eventIcon = event.icon || "fas fa-calendar-alt";
        const imageHtml = event.image
            ? `<img src="${event.image}" alt="${event.title}" style="width: 100%; height: 100%; object-fit: cover;">`
            : `<i class="${eventIcon}"></i>`;

        html += `
            <div class="event-card animate-on-scroll" onclick="showEventPreview(${
                event.id
            })">
                <div class="event-image" style="background: ${event.color}">
                    ${imageHtml}
                    <div class="event-type-badge">${getTypeText(
                        event.type
                    )}</div>
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
                        ${
                            event.payment_type === "pago"
                                ? `
                        <div class="event-detail">
                            <i class="fas fa-dollar-sign"></i>
                            <span>$${parseFloat(event.price).toFixed(2)}</span>
                        </div>
                        `
                                : ""
                        }
                    </div>
                    
                    <p class="event-description">${event.description.substring(
                        0,
                        100
                    )}${event.description.length > 100 ? "..." : ""}</p>
                    
                    <div class="event-stats">
                        <div class="stat-item">
                            <span class="stat-number">${event.attendees}</span>
                            <span class="stat-label">Asistentes</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">${getTypeText(
                                event.type
                            )}</span>
                            <span class="stat-label">Categoría</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">${getDaysUntil(
                                event.date
                            )}</span>
                            <span class="stat-label">Días</span>
                        </div>
                    </div>
                    
                    <div class="event-actions">
                        <button class="btn btn-primary btn-small" onclick="event.stopPropagation(); showEventPreview(${
                            event.id
                        })">
                            <i class="fas fa-eye"></i>
                            Ver Evento
                        </button>
                        <button class="btn btn-outline btn-small" onclick="event.stopPropagation(); quickShare(${
                            event.id
                        })">
                            <i class="fas fa-share"></i>
                            Compartir
                        </button>
                    </div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
    container.style.display = "grid";
    document.getElementById("eventsList").style.display = "none";
}

// Renderizar vista de lista
function renderListView(events) {
    const container = document.getElementById("eventsList");
    let html = "";

    events.forEach((event) => {
        const statusText = getStatusText(event.status);
        const statusClass = `status-${event.status}`;
        const eventIcon = event.icon || "fas fa-calendar-alt";
        const imageHtml = event.image
            ? `<img src="${event.image}" alt="${event.title}" style="width: 100%; height: 100%; object-fit: cover;">`
            : `<i class="${eventIcon}"></i>`;

        html += `
            <div class="event-card-list animate-on-scroll" onclick="showEventPreview(${
                event.id
            })">
                <div class="event-image-small" style="background: ${
                    event.color
                }">
                    ${imageHtml}
                    <div class="event-status ${statusClass}" style="position: absolute; top: 5px; left: 5px; font-size: 0.7rem; padding: 3px 8px;">
                        ${statusText}
                    </div>
                </div>
                
                <div class="event-info-list">
                    <div>
                        <h3 class="event-title" style="margin-bottom: 5px;">${
                            event.title
                        }</h3>
                        <p class="event-organizer" style="margin-bottom: 10px;">
                            <i class="fas fa-user"></i>
                            ${event.organizer}
                        </p>
                        <div class="event-details">
                            <div class="event-detail" style="margin-bottom: 5px;">
                                <i class="fas fa-calendar"></i>
                                <span>${formatDate(event.date)} - ${formatTime(
            event.date
        )}</span>
                            </div>
                            <div class="event-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>${event.location}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; gap: 20px; align-items: center; font-size: 0.9rem; color: var(--text-muted);">
                        <span><strong>${
                            event.attendees
                        }</strong> asistentes</span>
                        <span><strong>${getTypeText(event.type)}</strong></span>
                        <span><strong>${getDaysUntil(
                            event.date
                        )}</strong> días</span>
                        ${
                            event.payment_type === "pago"
                                ? `<span><strong>$${parseFloat(
                                      event.price
                                  ).toFixed(2)}</strong></span>`
                                : ""
                        }
                    </div>
                </div>
                
                <div class="event-actions-list">
                    <button class="btn btn-primary btn-small" onclick="event.stopPropagation(); showEventPreview(${
                        event.id
                    })">
                        <i class="fas fa-eye"></i>
                        Ver Evento
                    </button>
                    <button class="btn btn-outline btn-small" onclick="event.stopPropagation(); quickShare(${
                        event.id
                    })">
                        <i class="fas fa-share"></i>
                        Compartir
                    </button>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
    container.style.display = "flex";
    document.getElementById("eventsGrid").style.display = "none";
}

// Mostrar vista previa del evento - CORREGIDO
function showEventPreview(eventId) {
    const event = allEvents.find((e) => e.id === eventId);
    if (!event) return;

    currentEventId = eventId;

    // SIEMPRE mostrar primero el modal de detalles
    document.getElementById("modalHeader").style.background = event.color;
    document.getElementById("modalHeader").innerHTML = `<i class="${
        event.icon || "fas fa-calendar-alt"
    }"></i>`;
    document.getElementById("modalTitle").textContent = event.title;
    document.getElementById("modalOrganizer").textContent = event.organizer;
    document.getElementById("modalDate").textContent = formatFullDate(
        event.date
    );
    document.getElementById("modalLocation").textContent = event.location;
    document.getElementById("modalType").textContent = getTypeText(event.type);
    document.getElementById("modalAttendees").textContent = event.attendees;
    document.getElementById("modalDescription").textContent = event.description;

    // Mostrar modal de detalles
    document.getElementById("eventModal").classList.add("show");
    document.body.style.overflow = "hidden";
}

// Cerrar modal
function closeModal() {
    document.getElementById("eventModal").classList.remove("show");
    document.body.style.overflow = "auto";
    currentEventId = null;
}

// Confirmar asistencia - CORREGIDO
async function confirmAttendance() {
    if (!currentEventId) return;

    const event = allEvents.find((e) => e.id === currentEventId);
    if (!event) return;

    // Verificar si hay un usuario autenticado
    const isAuthenticated = document.querySelector(
        'meta[name="user-authenticated"]'
    );

    if (!isAuthenticated || isAuthenticated.content !== "true") {
        alert("Debes iniciar sesión para asistir a este evento");
        window.location.href = "/login";
        return;
    }

    // Verificar si el usuario es el organizador
    const userId = document.querySelector('meta[name="user-id"]');
    if (userId && userId.content === event.organizer_user_id.toString()) {
        alert("No puedes asistir a tu propio evento");
        return; // NO cerrar el modal, solo no hacer nada
    }

    // Si es evento de PAGO, guardar eventId antes de cerrar modal
    if (event.payment_type === "pago") {
        const eventId = currentEventId; // ⭐ GUARDAR EL ID AQUÍ
        const eventDetails = `${formatDate(event.date)} - ${formatTime(
            event.date
        )} | ${event.location}`;
        closeModal(); // Cerrar modal de detalles
        await openPaymentModal(eventId, event.title, event.price, eventDetails); // ⭐ USAR LA VARIABLE LOCAL
        return;
    }

    // Si es GRATIS, procesar confirmación normal
    try {
        const response = await fetch(
            `/eventos/${currentEventId}/solicitar-invitacion`,
            {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
            }
        );

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            await loadEventsFromDatabase();
            closeModal();
            window.location.href = "/mis-invitaciones";
        } else {
            alert(data.message || "Error al confirmar asistencia");
        }
    } catch (error) {
        console.error("Error:", error);
        alert("Hubo un error al procesar tu solicitud");
    }
}

// Compartir por WhatsApp
function shareWhatsApp() {
    if (!currentEventId) return;

    const event = allEvents.find((e) => e.id === currentEventId);
    if (!event) return;

    const mensaje =
        `🎉 ¡Te invito a ${event.title}! 🎉%0A%0A` +
        `📅 *Fecha:* ${formatFullDate(event.date)}%0A` +
        `📍 *Lugar:* ${event.location}%0A` +
        `👤 *Organizador:* ${event.organizer}%0A%0A` +
        `📝 *Descripción:* ${event.description}%0A%0A` +
        `Ver más: ${window.location.origin}/eventos/${event.id}%0A%0A` +
        `¡Espero verte ahí! 🥳`;

    const url = `https://wa.me/?text=${mensaje}`;
    window.open(url, "_blank");
}

// Compartir rápido
function quickShare(eventId) {
    const event = allEvents.find((e) => e.id === eventId);
    if (!event) return;

    const mensaje =
        `🎉 Mira este evento: ${event.title}%0A%0A` +
        `📅 ${formatFullDate(event.date)}%0A` +
        `📍 ${event.location}%0A` +
        `👤 Por: ${event.organizer}%0A%0A` +
        `Ver más: ${window.location.origin}/eventos/${event.id}`;

    const url = `https://wa.me/?text=${mensaje}`;
    window.open(url, "_blank");
}

// Ver ubicación
function viewLocation() {
    if (!currentEventId) return;

    const event = allEvents.find((e) => e.id === currentEventId);
    if (!event) return;

    const query = encodeURIComponent(event.location);
    const url = `https://www.google.com/maps/search/${query}`;
    window.open(url, "_blank");
}

// Actualizar contador de resultados
function updateResultsCount() {
    document.getElementById("resultsCount").textContent = filteredEvents.length;
}

// Paginación
function updatePagination() {
    const totalPages = Math.ceil(filteredEvents.length / eventsPerPage);
    const prevBtn = document.getElementById("prevPage");
    const nextBtn = document.getElementById("nextPage");
    const pageNumbers = document.getElementById("pageNumbers");

    prevBtn.disabled = currentPage <= 1;
    nextBtn.disabled = currentPage >= totalPages;

    let paginationHTML = "";
    const startPage = Math.max(1, currentPage - 2);
    const endPage = Math.min(totalPages, currentPage + 2);

    for (let i = startPage; i <= endPage; i++) {
        paginationHTML += `
            <button class="pagination-btn ${i === currentPage ? "active" : ""}" 
                    onclick="goToPage(${i})">
                ${i}
            </button>
        `;
    }

    pageNumbers.innerHTML = paginationHTML;

    // Ocultar paginación si hay pocas páginas
    if (totalPages <= 1) {
        document.getElementById("pagination").style.display = "none";
    } else {
        document.getElementById("pagination").style.display = "flex";
    }
}

function changePage(direction) {
    const totalPages = Math.ceil(filteredEvents.length / eventsPerPage);
    const newPage = currentPage + direction;

    if (newPage >= 1 && newPage <= totalPages) {
        currentPage = newPage;
        renderEvents();
        window.scrollTo({ top: 400, behavior: "smooth" });
    }
}

function goToPage(page) {
    currentPage = page;
    renderEvents();
    window.scrollTo({ top: 400, behavior: "smooth" });
}

// Funciones auxiliares
function getStatusText(status) {
    const statusMap = {
        upcoming: "Próximo",
        today: "Hoy",
        past: "Pasado",
    };
    return statusMap[status] || "Desconocido";
}

function getTypeText(type) {
    const typeMap = {
        boda: "Boda",
        cumpleanos: "Cumpleaños",
        graduacion: "Graduación",
        corporativo: "Corporativo",
        social: "Social",
        religioso: "Religioso",
        otro: "Otro",
    };
    return typeMap[type] || "Evento";
}

function formatDate(date) {
    return date.toLocaleDateString("es-ES", {
        weekday: "long",
        day: "numeric",
        month: "long",
    });
}

function formatTime(date) {
    return date.toLocaleTimeString("es-ES", {
        hour: "2-digit",
        minute: "2-digit",
    });
}

function formatFullDate(date) {
    return date.toLocaleDateString("es-ES", {
        weekday: "long",
        day: "numeric",
        month: "long",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
}

function getDaysUntil(date) {
    const today = new Date();
    const eventDate = new Date(date);
    const diffTime = eventDate - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays < 0) {
        return "Pasado";
    } else if (diffDays === 0) {
        return "Hoy";
    } else if (diffDays === 1) {
        return "1";
    } else {
        return diffDays.toString();
    }
}

// Close modal when clicking outside
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("eventModal");
    if (modal) {
        modal.addEventListener("click", function (e) {
            if (e.target === this) {
                closeModal();
            }
        });
    }
});

console.log("✅ Sistema de eventos dinámico cargado correctamente");

// ============================================
// FUNCIONES PARA MODAL DE PAGO CON STRIPE
// ============================================

let stripe, elements, cardElement;
let clientSecret = null;

// Abrir modal de pago - CORREGIDO
async function openPaymentModal(eventId, eventTitle, eventPrice, eventDetails) {
    currentEventId = eventId;

    // Verificar monto mínimo de Stripe (10 MXN)
    const price = parseFloat(eventPrice);
    if (price < 10) {
        alert(
            "Error: El precio mínimo para eventos de pago es de $10.00 MXN debido a las restricciones de Stripe."
        );
        return;
    }

    // Actualizar información del evento en el modal
    document.getElementById("modalEventTitle").textContent = eventTitle;
    document.getElementById("modalEventDetails").textContent = eventDetails;
    document.getElementById("modalEventPrice").textContent =
        "$" + price.toFixed(2) + " MXN";

    // Mostrar modal
    document.getElementById("paymentModal").style.display = "flex";
    document.body.style.overflow = "hidden";

    // Inicializar Stripe
    await initializeStripePayment(eventId);
}

// Cerrar modal de pago
function closePaymentModal() {
    document.getElementById("paymentModal").style.display = "none";
    document.body.style.overflow = "auto";

    // Limpiar Stripe
    if (cardElement) {
        cardElement.unmount();
        cardElement = null;
    }
}

// Inicializar Stripe para el pago - CON MEJOR MANEJO DE ERRORES
async function initializeStripePayment(eventId) {
    try {
        const response = await fetch(
            `/eventos/${eventId}/create-payment-intent`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    Accept: "application/json",
                },
            }
        );

        // Verificar si la respuesta es exitosa
        if (!response.ok) {
            const errorData = await response.json();
            console.error("Error del servidor:", errorData);
            alert(
                `Error: ${errorData.error || "Error desconocido del servidor"}`
            );
            closePaymentModal();
            return;
        }

        const data = await response.json();

        if (data.error) {
            console.error("Error en los datos:", data.error);
            alert(data.error);
            closePaymentModal();
            return;
        }

        // Verificar que tenemos los datos necesarios
        if (!data.clientSecret || !data.publishableKey) {
            console.error("Datos incompletos:", data);
            alert(
                "Error: El servidor no devolvió los datos necesarios para el pago"
            );
            closePaymentModal();
            return;
        }

        clientSecret = data.clientSecret;
        stripe = Stripe(data.publishableKey);

        // Crear Elements
        elements = stripe.elements();
        cardElement = elements.create("card", {
            style: {
                base: {
                    fontSize: "16px",
                    color: "#333",
                    fontFamily: '"Segoe UI", sans-serif',
                    "::placeholder": {
                        color: "#999",
                    },
                },
                invalid: {
                    color: "#dc3545",
                },
            },
        });

        cardElement.mount("#card-element");

        // Manejar errores en tiempo real
        cardElement.on("change", function (event) {
            const displayError = document.getElementById("card-errors");
            if (event.error) {
                displayError.textContent = event.error.message;
                displayError.classList.add("show");
            } else {
                displayError.textContent = "";
                displayError.classList.remove("show");
            }
        });

        console.log("✅ Stripe inicializado correctamente");
    } catch (error) {
        console.error("Error completo:", error);
        alert(`Error al inicializar el pago: ${error.message}`);
        closePaymentModal();
    }
}

// Procesar el pago cuando se envía el formulario
document.addEventListener("DOMContentLoaded", function () {
    const paymentForm = document.getElementById("payment-form");
    if (paymentForm) {
        paymentForm.addEventListener("submit", async function (event) {
            event.preventDefault();

            const submitButton = document.getElementById("submit-payment");
            const buttonText = document.getElementById("payment-btn-text");

            submitButton.disabled = true;
            buttonText.innerHTML = '<div class="spinner"></div>';

            try {
                const { error, paymentIntent } =
                    await stripe.confirmCardPayment(clientSecret, {
                        payment_method: {
                            card: cardElement,
                        },
                    });

                if (error) {
                    const errorElement = document.getElementById("card-errors");
                    errorElement.textContent = error.message;
                    errorElement.classList.add("show");

                    submitButton.disabled = false;
                    buttonText.textContent = "Pagar Ahora";
                } else if (paymentIntent.status === "succeeded") {
                    // Confirmar pago en el backend
                    const confirmResponse = await fetch(
                        `/eventos/${currentEventId}/confirm-payment`,
                        {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]'
                                ).content,
                                Accept: "application/json",
                            },
                            body: JSON.stringify({
                                payment_intent_id: paymentIntent.id,
                            }),
                        }
                    );

                    const confirmData = await confirmResponse.json();

                    if (confirmData.success) {
                        alert(
                            "¡Pago exitoso! Tu invitación ha sido generada. Redirigiendo..."
                        );
                        window.location.href = "/mis-invitaciones";
                    } else {
                        alert(confirmData.message);
                        submitButton.disabled = false;
                        buttonText.textContent = "Pagar Ahora";
                    }
                }
            } catch (error) {
                console.error("Error:", error);
                alert("Error al procesar el pago");
                submitButton.disabled = false;
                buttonText.textContent = "Pagar Ahora";
            }
        });
    }
});

// Cerrar modal con ESC
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        const paymentModal = document.getElementById("paymentModal");
        if (paymentModal && paymentModal.style.display === "flex") {
            closePaymentModal();
        }
    }
});
