// ============================================
// QR SCANNER CON ESTADÍSTICAS REALES
// ============================================

let isScanning = false;

// Sonidos
const successSound = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYHG2m98OScTgwOUKbj8LVjGwU5k9jyz3osBS');
const errorSound = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBgoOEhYaHiImKi4yNjo+QkZKTlJWWl5iZmpucnZ6foKGio6SlpqeoqaqrrK2ur7CxsrO0tba3uLm6u7y9vr/AwcLDxMXGx8jJysvMzc7P0NHS09TV1tfY2drb3N3e3+Dh4uPk5ebn6Onq6+zt7u/w8fLz9PX29/j5+vv8/f7/');

// Inicializar escáner
const html5QrCode = new Html5Qrcode("reader");

const config = {
    fps: 10,
    qrbox: { width: 250, height: 250 },
    aspectRatio: 1.0
};

// ============================================
// CARGAR ESTADÍSTICAS DESDE LA BASE DE DATOS
// ============================================
async function loadStats() {
    try {
        const response = await fetch(`/eventos/${EVENT_ID}/stats`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            const stats = data.stats;
            
            // Actualizar estadísticas en la UI
            document.getElementById('totalScanned').textContent = stats.total;
            document.getElementById('usedScans').textContent = stats.usado;
            document.getElementById('confirmedScans').textContent = stats.confirmado;
            document.getElementById('pendingScans').textContent = stats.pendiente;
            document.getElementById('rejectedScans').textContent = stats.rechazado;
        }
    } catch (error) {
        console.error('Error cargando estadísticas:', error);
        // Mostrar 0 en caso de error
        document.getElementById('totalScanned').textContent = '0';
        document.getElementById('usedScans').textContent = '0';
        document.getElementById('confirmedScans').textContent = '0';
        document.getElementById('pendingScans').textContent = '0';
        document.getElementById('rejectedScans').textContent = '0';
    }
}

// ⭐ CARGAR ESTADÍSTICAS AL INICIAR LA PÁGINA
window.addEventListener('DOMContentLoaded', () => {
    loadStats();
});

// ============================================
// FUNCIONES DEL ESCÁNER
// ============================================

function onScanSuccess(decodedText, decodedResult) {
    if (isScanning) return; // Evitar múltiples escaneos
    
    isScanning = true;
    validateQRCode(decodedText);
    
    // Permitir otro escaneo después de 2 segundos
    setTimeout(() => {
        isScanning = false;
    }, 2000);
}

function onScanFailure(error) {
    // Ignorar errores de escaneo (son muy frecuentes)
}

// Iniciar escáner
html5QrCode.start(
    { facingMode: "environment" },
    config,
    onScanSuccess,
    onScanFailure
).catch(err => {
    console.error("Error al iniciar el escáner:", err);
    alert("No se pudo acceder a la cámara. Usa la entrada manual.");
});

// ============================================
// VALIDAR CÓDIGO QR
// ============================================
async function validateQRCode(qrCode) {
    try {
        const response = await fetch('/api/validate-qr', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ qr_code: qrCode })
        });

        const data = await response.json();
        
        const resultCard = document.getElementById('resultCard');
        const resultIcon = document.getElementById('resultIcon');
        const resultTitle = document.getElementById('resultTitle');
        const resultMessage = document.getElementById('resultMessage');
        const resultDetails = document.getElementById('resultDetails');

        resultCard.classList.remove('result-success', 'result-error', 'result-warning', 'result-pending');

        // ⭐ MANEJAR DIFERENTES ESTADOS
        if (data.success && data.status === 'valid') {
            // ✅ INVITACIÓN VÁLIDA (Confirmada → Usado)
            resultCard.classList.add('result-success');
            resultIcon.innerHTML = '<i class="fas fa-check-circle"></i>';
            resultTitle.textContent = '✅ Acceso Concedido';
            resultMessage.textContent = data.message;
            
            if (data.invitation) {
                resultDetails.innerHTML = `
                    <div class="detail-row">
                        <span class="detail-label">Invitado:</span>
                        <span class="detail-value">${data.invitation.user_name}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">${data.invitation.user_email}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Evento:</span>
                        <span class="detail-value">${data.invitation.event_name}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Confirmado:</span>
                        <span class="detail-value">${data.invitation.confirmed_at}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Validado:</span>
                        <span class="detail-value">${data.invitation.used_at}</span>
                    </div>
                `;
            }
            
            playSound(successSound);
            
        } else if (data.status === 'pending') {
            // ⏳ INVITACIÓN PENDIENTE (No confirmada aún)
            resultCard.classList.add('result-pending');
            resultIcon.innerHTML = '<i class="fas fa-hourglass-half"></i>';
            resultTitle.textContent = '⏳ Invitación Pendiente';
            resultMessage.textContent = data.message;
            
            if (data.invitation) {
                resultDetails.innerHTML = `
                    <div class="detail-row">
                        <span class="detail-label">Invitado:</span>
                        <span class="detail-value">${data.invitation.user_name}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">${data.invitation.user_email}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Estado:</span>
                        <span class="detail-value" style="color: #ffc107; font-weight: bold;">Sin confirmar</span>
                    </div>
                    <div style="margin-top: 1rem; padding: 0.75rem; background: #fff3cd; border-radius: 8px; text-align: center;">
                        <p style="margin: 0; color: #856404; font-size: 0.9rem;">
                            El usuario debe confirmar su asistencia desde "Mis Invitaciones"
                        </p>
                    </div>
                `;
            }
            
            playSound(errorSound);
            
        } else if (data.status === 'rejected') {
            // ❌ INVITACIÓN RECHAZADA
            resultCard.classList.add('result-error');
            resultIcon.innerHTML = '<i class="fas fa-times-circle"></i>';
            resultTitle.textContent = '❌ Invitación Rechazada';
            resultMessage.textContent = data.message;
            
            if (data.invitation) {
                resultDetails.innerHTML = `
                    <div class="detail-row">
                        <span class="detail-label">Invitado:</span>
                        <span class="detail-value">${data.invitation.user_name}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">${data.invitation.user_email}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Estado:</span>
                        <span class="detail-value" style="color: #dc3545; font-weight: bold;">Rechazada</span>
                    </div>
                `;
            }
            
            playSound(errorSound);
            
        } else if (data.status === 'already_used') {
            // ⚠️ YA FUE USADA
            resultCard.classList.add('result-warning');
            resultIcon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
            resultTitle.textContent = '⚠️ Ya Utilizada';
            resultMessage.textContent = data.message;
            
            if (data.invitation) {
                resultDetails.innerHTML = `
                    <div class="detail-row">
                        <span class="detail-label">Invitado:</span>
                        <span class="detail-value">${data.invitation.user_name}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Usado el:</span>
                        <span class="detail-value">${data.invitation.used_at}</span>
                    </div>
                `;
            }
            
            playSound(errorSound);
            
        } else {
            // ❌ QR INVÁLIDO O CANCELADO
            resultCard.classList.add('result-error');
            resultIcon.innerHTML = '<i class="fas fa-times-circle"></i>';
            resultTitle.textContent = '❌ Acceso Denegado';
            resultMessage.textContent = data.message;
            resultDetails.innerHTML = '';
            
            playSound(errorSound);
        }

        resultCard.classList.add('show');
        
        // ⭐ RECARGAR ESTADÍSTICAS DESPUÉS DE ESCANEAR
        setTimeout(() => {
            loadStats();
        }, 500);
        
        // Ocultar resultado después de 5 segundos
        setTimeout(() => {
            resultCard.classList.remove('show');
        }, 5000);

    } catch (error) {
        console.error('Error:', error);
        alert('Error al validar el código QR');
    }
}

// ============================================
// VALIDAR CÓDIGO MANUAL
// ============================================
function validateManualCode() {
    const code = document.getElementById('manualCode').value.trim();
    if (code) {
        validateQRCode(code);
        document.getElementById('manualCode').value = '';
    }
}

// Permitir Enter en input manual
document.getElementById('manualCode').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        validateManualCode();
    }
});

// ============================================
// REPRODUCIR SONIDO
// ============================================
function playSound(sound) {
    if (document.getElementById('soundEnabled').checked) {
        sound.play().catch(err => console.log('No se pudo reproducir el sonido'));
    }
}