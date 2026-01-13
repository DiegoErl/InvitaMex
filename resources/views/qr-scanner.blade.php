<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Escáner QR - InvitaCleth</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('css/qrScanner.css') }}" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    
    <!-- ⭐ PASAR EL EVENT ID AL JAVASCRIPT -->
    <script>
        const EVENT_ID = {{ $eventId }};
    </script>
</head>
<body>
    <div class="scanner-container">
        <div class="header">
            <h1><i class="fas fa-qrcode"></i> Escáner de Invitaciones</h1>
            <p>Valida los códigos QR de los asistentes - {{ $event->title }}</p>
        </div>

        <div class="scanner-card">
            <h2 style="margin-bottom: 1rem; color: #333;">
                <i class="fas fa-camera"></i> Escanear Código QR
            </h2>
            <div id="reader"></div>

            <div class="sound-toggle">
                <label>
                    <input type="checkbox" id="soundEnabled" checked>
                    <i class="fas fa-volume-up"></i>
                    Activar sonidos
                </label>
            </div>

            <div class="manual-input">
                <h3><i class="fas fa-keyboard"></i> O ingresa el código manualmente</h3>
                <div class="input-group">
                    <input type="text" 
                           id="manualCode" 
                           class="form-input" 
                           placeholder="Pega el código UUID aquí..."
                           autocomplete="off">
                    <button class="btn btn-primary" onclick="validateManualCode()">
                        <i class="fas fa-check"></i>
                        Validar
                    </button>
                </div>
            </div>
        </div>

        <div id="resultCard" class="result-card">
            <div class="result-icon" id="resultIcon"></div>
            <h2 class="result-title" id="resultTitle"></h2>
            <p style="text-align: center; font-size: 1.1rem;" id="resultMessage"></p>
            <div id="resultDetails" class="result-details"></div>
        </div>

        <!-- ⭐ ESTADÍSTICAS ACTUALIZADAS -->
        <div class="stats-grid">
            <div class="stat-card stat-total">
                <span class="stat-number" id="totalScanned">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
                <span class="stat-label">Total Invitaciones</span>
            </div>
            <div class="stat-card stat-used">
                <span class="stat-number" id="usedScans" style="color: #28a745;">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
                <span class="stat-label">✅ Escaneados (Válidos)</span>
            </div>
            <div class="stat-card stat-confirmed">
                <span class="stat-number" id="confirmedScans" style="color: #17a2b8;">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
                <span class="stat-label">🎫 Confirmados (Sin Escanear)</span>
            </div>
            <div class="stat-card stat-pending">
                <span class="stat-number" id="pendingScans" style="color: #ffc107;">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
                <span class="stat-label">⏳ Pendientes</span>
            </div>
            <div class="stat-card stat-rejected">
                <span class="stat-number" id="rejectedScans" style="color: #dc3545;">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
                <span class="stat-label">❌ Rechazados</span>
            </div>
        </div>

        <!-- ⭐ BOTÓN PARA REFRESCAR ESTADÍSTICAS -->
        <div style="text-align: center; margin-top: 1rem;">
            <button class="btn btn-secondary" onclick="loadStats()" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-sync-alt"></i>
                Actualizar Estadísticas
            </button>
        </div>
    </div>

    <script src="{{ asset('js/qrScanner.js') }}"></script>
    
</body>
</html>