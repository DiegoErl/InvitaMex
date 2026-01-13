// Cambiar entre pestañas
function switchTab(tab) {
    const slider = document.getElementById("tabSlider");
    const tabs = document.querySelectorAll(".tab-button");
    const contents = document.querySelectorAll(".tab-content");

    // Actualizar botones
    tabs.forEach((t) => t.classList.remove("active"));
    if (tab === "invitations") {
        tabs[0].classList.add("active");
        slider.classList.remove("slide-right");
    } else {
        tabs[1].classList.add("active");
        slider.classList.add("slide-right");
    }

    // Actualizar contenido
    contents.forEach((c) => c.classList.remove("active"));
    document.getElementById(tab + "-content").classList.add("active");
}

// Imprimir invitación individual
function printInvitation(button) {
    const card = button.closest(".invitation-card");
    const allCards = document.querySelectorAll(".invitation-card");

    allCards.forEach((c) => {
        if (c !== card) c.style.display = "none";
    });

    window.print();

    setTimeout(() => {
        allCards.forEach((c) => (c.style.display = "block"));
    }, 100);
}

// Descargar QR individual
function downloadQR(button) {
    const card = button.closest(".invitation-card");
    const qrImage = card.querySelector(".qr-code img");

    if (qrImage && qrImage.src) {
        fetch(qrImage.src)
            .then((response) => response.blob())
            .then((blob) => {
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement("a");
                link.href = url;
                link.download = `invitacion-qr-${Date.now()}.png`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
            })
            .catch((err) => {
                console.error("Error:", err);
                alert("Error al descargar el código QR");
            });
    } else {
        alert("No se encontró el código QR");
    }
}

// Compartir invitación
function shareInvitation(eventId) {
    const url = `${window.location.origin}/eventos/${eventId}`;
    const message = `¡Mira este evento! ${url}`;

    if (navigator.share) {
        navigator
            .share({
                title: "Invitación a Evento",
                text: message,
                url: url,
            })
            .catch((err) => console.log("Error al compartir:", err));
    } else {
        navigator.clipboard
            .writeText(url)
            .then(() => {
                alert("Enlace copiado al portapapeles");
            })
            .catch(() => {
                const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(
                    message
                )}`;
                window.open(whatsappUrl, "_blank");
            });
    }
}

// Restaurar vista después de imprimir
window.addEventListener("afterprint", function () {
    document.querySelectorAll(".invitation-card").forEach((card) => {
        card.style.display = "block";
    });
});

// Confirmar y eliminar evento
function confirmDeleteEvent(eventId, eventTitle) {
    if (
        confirm(
            `¿Estás seguro de que deseas eliminar el evento "${eventTitle}"?\n\nEsta acción no se puede deshacer y se eliminarán todas las invitaciones asociadas.`
        )
    ) {
        deleteEvent(eventId);
    }
}

async function deleteEvent(eventId) {
    try {
        const response = await fetch(`/eventos/${eventId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
                Accept: "application/json",
            },
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.message || "Error al eliminar el evento");
        }
    } catch (error) {
        console.error("Error:", error);
        alert("Hubo un error al eliminar el evento");
    }
}

// ============================================
// SISTEMA RSVP - AGREGAR AL FINAL DEL ARCHIVO
// ============================================



// ============================================
// MODAL DE CONFIRMACIÓN RSVP
// ============================================
function openRsvpModal(invitationId, eventTitle) {
    currentInvitationId = invitationId;
    document.getElementById("rsvpEventTitle").textContent = eventTitle;
    document.getElementById("rsvpModal").style.display = "flex";
}

function closeRsvpModal() {
    document.getElementById("rsvpModal").style.display = "none";
    currentInvitationId = null;
}

// Confirmar asistencia (SÍ ASISTIRÉ)
async function confirmAttendance() {
    if (!currentInvitationId) return;

    try {
        const response = await fetch(
            `/invitations/${currentInvitationId}/confirm-rsvp`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
            }
        );

        const data = await response.json();

        if (data.success) {
            showNotification("success", data.message);
            closeRsvpModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification("error", data.message);
        }
    } catch (error) {
        console.error("Error:", error);
        showNotification(
            "error",
            "Error al confirmar asistencia. Intenta de nuevo."
        );
    }
}

// Rechazar invitación (NO ASISTIRÉ)
async function rejectInvitation() {
    if (!currentInvitationId) return;

    if (
        !confirm(
            "¿Estás seguro de rechazar esta invitación? Esta acción no se puede deshacer."
        )
    ) {
        return;
    }

    try {
        const response = await fetch(
            `/invitations/${currentInvitationId}/reject-rsvp`,
            {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
            }
        );

        const data = await response.json();

        if (data.success) {
            showNotification("success", data.message);
            closeRsvpModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification("error", data.message);
        }
    } catch (error) {
        console.error("Error:", error);
        showNotification(
            "error",
            "Error al rechazar invitación. Intenta de nuevo."
        );
    }
}

// ============================================
// MODAL DE LISTA DE ASISTENTES
// ============================================
async function showAttendees(eventId, eventTitle) {
    currentEventId = eventId;
    document.getElementById("attendeesModal").style.display = "flex";
    document.getElementById("attendeesLoading").style.display = "block";
    document.getElementById("attendeesList").innerHTML = "";
    document.getElementById("attendeesEmpty").style.display = "none";

    try {
        const response = await fetch(`/eventos/${eventId}/attendees`);
        const data = await response.json();

        if (data.success) {
            attendeesData = data;
            renderEventInfo(data.event);
            renderStats(data.counts);
            renderAttendeesList("all");
            document.getElementById("attendeesLoading").style.display = "none";
        } else {
            showNotification("error", data.message);
            closeAttendeesModal();
        }
    } catch (error) {
        console.error("Error:", error);
        showNotification("error", "Error al cargar la lista de asistentes");
        closeAttendeesModal();
    }
}

function closeAttendeesModal() {
    document.getElementById("attendeesModal").style.display = "none";
    currentEventId = null;
    attendeesData = null;
}

function renderEventInfo(event) {
    const eventInfo = `
        <div style="text-align: center;">
            <h4 style="margin: 0 0 0.5rem 0; color: #333;">${event.title}</h4>
            <p style="margin: 0; color: #666;">
                <i class="fas fa-calendar"></i> ${event.date} - ${event.time}
            </p>
            ${
                event.rsvp_deadline
                    ? `
                <p style="margin: 0.5rem 0 0 0; color: #ff9800; font-weight: 600;">
                    <i class="fas fa-hourglass-half"></i> Límite: ${event.rsvp_deadline}
                </p>
            `
                    : ""
            }
        </div>
    `;
    document.getElementById("attendeesEventInfo").innerHTML = eventInfo;
}

function renderStats(counts) {
    const stats = `
        <div style="background: #d4edda; padding: 1rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #155724;">${counts.confirmados}</div>
            <div style="font-size: 0.9rem; color: #155724;">Confirmados</div>
        </div>
        <div style="background: #fff3cd; padding: 1rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #856404;">${counts.pendientes}</div>
            <div style="font-size: 0.9rem; color: #856404;">Pendientes</div>
        </div>
        <div style="background: #f8d7da; padding: 1rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #721c24;">${counts.rechazados}</div>
            <div style="font-size: 0.9rem; color: #721c24;">Rechazados</div>
        </div>
        <div style="background: #e7f3ff; padding: 1rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #1976D2;">${counts.total}</div>
            <div style="font-size: 0.9rem; color: #1976D2;">Total</div>
        </div>
    `;
    document.getElementById("attendeesStats").innerHTML = stats;
}

function filterAttendees(status) {
    // Actualizar tabs activos
    document.querySelectorAll(".attendees-tab").forEach((tab) => {
        tab.classList.remove("active");
    });
    event.target.classList.add("active");

    renderAttendeesList(status);
}

function renderAttendeesList(status) {
    if (!attendeesData) return;

    let attendees = [];

    if (status === "all") {
        attendees = [
            ...attendeesData.attendees.confirmados,
            ...attendeesData.attendees.pendientes,
            ...attendeesData.attendees.rechazados,
        ];
    } else if (status === "confirmado") {
        attendees = attendeesData.attendees.confirmados;
    } else if (status === "pendiente") {
        attendees = attendeesData.attendees.pendientes;
    } else if (status === "rechazado") {
        attendees = attendeesData.attendees.rechazados;
    }

    const listContainer = document.getElementById("attendeesList");
    const emptyContainer = document.getElementById("attendeesEmpty");

    if (attendees.length === 0) {
        listContainer.innerHTML = "";
        emptyContainer.style.display = "block";
        return;
    }

    emptyContainer.style.display = "none";

    const html = attendees
        .map((attendee) => {
            const statusConfig = {
                confirmado: {
                    color: "#d4edda",
                    textColor: "#155724",
                    icon: "fa-check-circle",
                    label: "Confirmado",
                },
                pendiente: {
                    color: "#fff3cd",
                    textColor: "#856404",
                    icon: "fa-clock",
                    label: "Pendiente",
                },
                rechazado: {
                    color: "#f8d7da",
                    textColor: "#721c24",
                    icon: "fa-times-circle",
                    label: "Rechazado",
                },
            };

            const config = statusConfig[attendee.status];

            return `
            <div style="background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 1rem; margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center;">
                <div style="flex: 1;">
                    <div style="font-weight: 600; color: #333; margin-bottom: 0.25rem;">
                        <i class="fas fa-user" style="color: #667eea; margin-right: 0.5rem;"></i>
                        ${attendee.name}
                    </div>
                    <div style="font-size: 0.85rem; color: #666;">
                        <i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>
                        ${attendee.email}
                    </div>
                    ${
                        attendee.confirmed_at
                            ? `
                        <div style="font-size: 0.8rem; color: #999; margin-top: 0.25rem;">
                            <i class="fas fa-clock"></i> Confirmado: ${attendee.confirmed_at}
                        </div>
                    `
                            : ""
                    }
                </div>
                <div style="background: ${config.color}; color: ${
                config.textColor
            }; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; white-space: nowrap;">
                    <i class="fas ${config.icon}"></i>
                    ${config.label}
                </div>
            </div>
        `;
        })
        .join("");

    listContainer.innerHTML = html;
}

// ============================================
// SISTEMA DE NOTIFICACIONES
// ============================================
function showNotification(type, message) {
    // Eliminar notificaciones anteriores
    const existingNotification = document.querySelector(".notification");
    if (existingNotification) {
        existingNotification.remove();
    }

    // Crear nueva notificación
    const notification = document.createElement("div");
    notification.className = `notification notification-${type}`;

    const icons = {
        success: "fa-check-circle",
        error: "fa-exclamation-circle",
        warning: "fa-exclamation-triangle",
        info: "fa-info-circle",
    };

    const colors = {
        success: "#10b981",
        error: "#ef4444",
        warning: "#f59e0b",
        info: "#3b82f6",
    };

    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 10000;
        animation: slideIn 0.3s ease;
        border-left: 4px solid ${colors[type]};
        max-width: 400px;
    `;

    notification.innerHTML = `
        <i class="fas ${icons[type]}" style="color: ${colors[type]}; font-size: 1.5rem;"></i>
        <span style="color: #333; font-weight: 500;">${message}</span>
        <button onclick="this.parentElement.remove()" style="
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0;
            margin-left: auto;
        ">
            <i class="fas fa-times"></i>
        </button>
    `;

    document.body.appendChild(notification);

    // Auto-cerrar después de 5 segundos
    setTimeout(() => {
        notification.style.animation = "slideOut 0.3s ease";
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// ============================================
// ESTILOS DE ANIMACIÓN (agregar una sola vez)
// ============================================
if (!document.getElementById("rsvpStyles")) {
    const style = document.createElement("style");
    style.id = "rsvpStyles";
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        @keyframes modalIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
}


// ============================================
// SISTEMA RSVP - AGREGAR AL FINAL DEL ARCHIVO
// ============================================

let currentInvitationId = null;
let currentEventId = null;
let attendeesData = null;

// ============================================
// MODAL DE CONFIRMACIÓN RSVP
// ============================================
function openRsvpModal(invitationId, eventTitle) {
    currentInvitationId = invitationId;
    document.getElementById('rsvpEventTitle').textContent = eventTitle;
    document.getElementById('rsvpModal').style.display = 'flex';
}

function closeRsvpModal() {
    document.getElementById('rsvpModal').style.display = 'none';
    currentInvitationId = null;
}

// Confirmar asistencia (SÍ ASISTIRÉ)
async function confirmAttendance() {
    if (!currentInvitationId) return;

    try {
        const response = await fetch(`/invitations/${currentInvitationId}/confirm-rsvp`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            showNotification('success', data.message);
            closeRsvpModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('error', data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('error', 'Error al confirmar asistencia. Intenta de nuevo.');
    }
}

// Rechazar invitación (NO ASISTIRÉ)
async function rejectInvitation() {
    if (!currentInvitationId) return;

    if (!confirm('¿Estás seguro de rechazar esta invitación? Esta acción no se puede deshacer.')) {
        return;
    }

    try {
        const response = await fetch(`/invitations/${currentInvitationId}/reject-rsvp`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            showNotification('success', data.message);
            closeRsvpModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('error', data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('error', 'Error al rechazar invitación. Intenta de nuevo.');
    }
}

// ============================================
// MODAL DE LISTA DE ASISTENTES
// ============================================
async function showAttendees(eventId, eventTitle) {
    currentEventId = eventId;
    document.getElementById('attendeesModal').style.display = 'flex';
    document.getElementById('attendeesLoading').style.display = 'block';
    document.getElementById('attendeesList').innerHTML = '';
    document.getElementById('attendeesEmpty').style.display = 'none';

    try {
        const response = await fetch(`/eventos/${eventId}/attendees`);
        const data = await response.json();

        if (data.success) {
            attendeesData = data;
            renderEventInfo(data.event);
            renderStats(data.counts);
            renderAttendeesList('all');
            document.getElementById('attendeesLoading').style.display = 'none';
        } else {
            showNotification('error', data.message);
            closeAttendeesModal();
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('error', 'Error al cargar la lista de asistentes');
        closeAttendeesModal();
    }
}

function closeAttendeesModal() {
    document.getElementById('attendeesModal').style.display = 'none';
    currentEventId = null;
    attendeesData = null;
}

function renderEventInfo(event) {
    const eventInfo = `
        <div style="text-align: center;">
            <h4 style="margin: 0 0 0.5rem 0; color: #333;">${event.title}</h4>
            <p style="margin: 0; color: #666;">
                <i class="fas fa-calendar"></i> ${event.date} - ${event.time}
            </p>
            ${event.rsvp_deadline ? `
                <p style="margin: 0.5rem 0 0 0; color: #ff9800; font-weight: 600;">
                    <i class="fas fa-hourglass-half"></i> Límite: ${event.rsvp_deadline}
                </p>
            ` : ''}
        </div>
    `;
    document.getElementById('attendeesEventInfo').innerHTML = eventInfo;
}

function renderStats(counts) {
    const stats = `
        <div style="background: #d1ecf1; padding: 1rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #0c5460;">${counts.usados}</div>
            <div style="font-size: 0.9rem; color: #0c5460;">Asistieron</div>
        </div>
        <div style="background: #d4edda; padding: 1rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #155724;">${counts.confirmados}</div>
            <div style="font-size: 0.9rem; color: #155724;">Confirmados</div>
        </div>
        <div style="background: #fff3cd; padding: 1rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #856404;">${counts.pendientes}</div>
            <div style="font-size: 0.9rem; color: #856404;">Pendientes</div>
        </div>
        <div style="background: #f8d7da; padding: 1rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #721c24;">${counts.rechazados}</div>
            <div style="font-size: 0.9rem; color: #721c24;">Rechazados</div>
        </div>
        <div style="background: #e7f3ff; padding: 1rem; border-radius: 8px; text-align: center;">
            <div style="font-size: 2rem; font-weight: bold; color: #1976D2;">${counts.total}</div>
            <div style="font-size: 0.9rem; color: #1976D2;">Total</div>
        </div>
    `;
    document.getElementById('attendeesStats').innerHTML = stats;
}

function filterAttendees(status) {
    // Actualizar tabs activos
    document.querySelectorAll('.attendees-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    event.target.classList.add('active');

    renderAttendeesList(status);
}

function renderAttendeesList(status) {
    if (!attendeesData) return;

    let attendees = [];

    if (status === 'all') {
        attendees = [
            ...attendeesData.attendees.usados,
            ...attendeesData.attendees.confirmados,
            ...attendeesData.attendees.pendientes,
            ...attendeesData.attendees.rechazados
        ];
    } else if (status === 'usado') {
        attendees = attendeesData.attendees.usados;
    } else if (status === 'confirmado') {
        attendees = attendeesData.attendees.confirmados;
    } else if (status === 'pendiente') {
        attendees = attendeesData.attendees.pendientes;
    } else if (status === 'rechazado') {
        attendees = attendeesData.attendees.rechazados;
    }

    const listContainer = document.getElementById('attendeesList');
    const emptyContainer = document.getElementById('attendeesEmpty');

    if (attendees.length === 0) {
        listContainer.innerHTML = '';
        emptyContainer.style.display = 'block';
        return;
    }

    emptyContainer.style.display = 'none';

    const statusConfig = {
        usado: {
            color: '#d1ecf1',
            textColor: '#0c5460',
            icon: 'fa-check-double',
            label: 'Asistió'
        },
        confirmado: {
            color: '#d4edda',
            textColor: '#155724',
            icon: 'fa-check-circle',
            label: 'Confirmado'
        },
        pendiente: {
            color: '#fff3cd',
            textColor: '#856404',
            icon: 'fa-clock',
            label: 'Pendiente'
        },
        rechazado: {
            color: '#f8d7da',
            textColor: '#721c24',
            icon: 'fa-times-circle',
            label: 'Rechazado'
        }
    };

    const html = attendees.map(attendee => {
        const config = statusConfig[attendee.status];

        return `
            <div style="background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 1rem; margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center;">
                <div style="flex: 1;">
                    <div style="font-weight: 600; color: #333; margin-bottom: 0.25rem;">
                        <i class="fas fa-user" style="color: #667eea; margin-right: 0.5rem;"></i>
                        ${attendee.name}
                    </div>
                    <div style="font-size: 0.85rem; color: #666;">
                        <i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>
                        ${attendee.email}
                    </div>
                    ${attendee.confirmed_at ? `
                        <div style="font-size: 0.8rem; color: #999; margin-top: 0.25rem;">
                            <i class="fas fa-check"></i> Confirmó: ${attendee.confirmed_at}
                        </div>
                    ` : ''}
                    ${attendee.used_at ? `
                        <div style="font-size: 0.8rem; color: #0c5460; margin-top: 0.25rem; font-weight: 600;">
                            <i class="fas fa-door-open"></i> Asistió: ${attendee.used_at}
                        </div>
                    ` : ''}
                </div>
                <div style="background: ${config.color}; color: ${config.textColor}; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600; white-space: nowrap;">
                    <i class="fas ${config.icon}"></i>
                    ${config.label}
                </div>
            </div>
        `;
    }).join('');

    listContainer.innerHTML = html;
}

// ============================================
// SISTEMA DE NOTIFICACIONES
// ============================================
function showNotification(type, message) {
    // Eliminar notificaciones anteriores
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Crear nueva notificación
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };

    const colors = {
        success: '#10b981',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6'
    };

    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 10000;
        animation: slideIn 0.3s ease;
        border-left: 4px solid ${colors[type]};
        max-width: 400px;
    `;

    notification.innerHTML = `
        <i class="fas ${icons[type]}" style="color: ${colors[type]}; font-size: 1.5rem;"></i>
        <span style="color: #333; font-weight: 500;">${message}</span>
        <button onclick="this.parentElement.remove()" style="
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 0;
            margin-left: auto;
        ">
            <i class="fas fa-times"></i>
        </button>
    `;

    document.body.appendChild(notification);

    // Auto-cerrar después de 5 segundos
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// ============================================
// ESTILOS DE ANIMACIÓN (agregar una sola vez)
// ============================================
if (!document.getElementById('rsvpStyles')) {
    const style = document.createElement('style');
    style.id = 'rsvpStyles';
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        @keyframes modalIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);
}