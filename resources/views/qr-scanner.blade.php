<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Escáner QR - InvitaCleth</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .scanner-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .scanner-card {
            background: white;
            border-radius: 25px;
            padding: 2rem;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.3);
            margin-bottom: 2rem;
        }

        #reader {
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .manual-input {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #f0f0f0;
        }

        .manual-input h3 {
            margin-bottom: 1rem;
            color: #333;
        }

        .input-group {
            display: flex;
            gap: 1rem;
        }

        .form-input {
            flex: 1;
            padding: 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1rem;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        .result-card {
            margin-top: 2rem;
            padding: 2rem;
            border-radius: 15px;
            display: none;
        }

        .result-card.show {
            display: block;
            animation: slideIn 0.3s ease;
        }

        .result-success {
            background: #d4edda;
            border: 2px solid #28a745;
        }

        .result-error {
            background: #f8d7da;
            border: 2px solid #dc3545;
        }

        .result-warning {
            background: #fff3cd;
            border: 2px solid #ffc107;
        }

        .result-icon {
            font-size: 4rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        .result-success .result-icon {
            color: #28a745;
        }

        .result-error .result-icon {
            color: #dc3545;
        }

        .result-warning .result-icon {
            color: #ffc107;
        }

        .result-title {
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .result-details {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            margin-top: 1rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
        }

        .detail-value {
            color: #333;
            text-align: right;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #667eea;
            display: block;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .input-group {
                flex-direction: column;
            }
        }

        .sound-toggle {
            text-align: center;
            margin-top: 1rem;
        }

        .sound-toggle label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            color: #666;
        }

        .sound-toggle input {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="scanner-container">
        <div class="header">
            <h1><i class="fas fa-qrcode"></i> Escáner de Invitaciones</h1>
            <p>Valida los códigos QR de los asistentes</p>
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

        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-number" id="totalScanned">0</span>
                <span class="stat-label">Total Escaneados</span>
            </div>
            <div class="stat-card">
                <span class="stat-number" id="validScans" style="color: #28a745;">0</span>
                <span class="stat-label">Válidos</span>
            </div>
            <div class="stat-card">
                <span class="stat-number" id="invalidScans" style="color: #dc3545;">0</span>
                <span class="stat-label">Rechazados</span>
            </div>
        </div>
    </div>

    <script>
        let totalScanned = 0;
        let validScans = 0;
        let invalidScans = 0;
        let isScanning = false;

        // Sonidos
        const successSound = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYHG2m98OScTgwOUKbj8LVjGwU5k9jyz3osBS'); // Sonido de éxito corto
        const errorSound = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBgoOEhYaHiImKi4yNjo+QkZKTlJWWl5iZmpucnZ6foKGio6SlpqeoqaqrrK2ur7CxsrO0tba3uLm6u7y9vr/AwcLDxMXGx8jJysvMzc7P0NHS09TV1tfY2drb3N3e3+Dh4uPk5ebn6Onq6+zt7u/w8fLz9PX29/j5+vv8/f7/'); // Sonido de error

        // Inicializar escáner
        const html5QrCode = new Html5Qrcode("reader");

        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };

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

        // Validar código QR
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
                
                totalScanned++;
                document.getElementById('totalScanned').textContent = totalScanned;

                const resultCard = document.getElementById('resultCard');
                const resultIcon = document.getElementById('resultIcon');
                const resultTitle = document.getElementById('resultTitle');
                const resultMessage = document.getElementById('resultMessage');
                const resultDetails = document.getElementById('resultDetails');

                resultCard.classList.remove('result-success', 'result-error', 'result-warning');

                if (data.success) {
                    validScans++;
                    document.getElementById('validScans').textContent = validScans;
                    
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
                } else {
                    invalidScans++;
                    document.getElementById('invalidScans').textContent = invalidScans;
                    
                    if (data.status === 'already_used') {
                        resultCard.classList.add('result-warning');
                        resultIcon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
                    } else {
                        resultCard.classList.add('result-error');
                        resultIcon.innerHTML = '<i class="fas fa-times-circle"></i>';
                    }
                    
                    resultTitle.textContent = '❌ Acceso Denegado';
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
                    } else {
                        resultDetails.innerHTML = '';
                    }
                    
                    playSound(errorSound);
                }

                resultCard.classList.add('show');
                
                // Ocultar resultado después de 5 segundos
                setTimeout(() => {
                    resultCard.classList.remove('show');
                }, 5000);

            } catch (error) {
                console.error('Error:', error);
                alert('Error al validar el código QR');
            }
        }

        // Validar código manual
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

        // Reproducir sonido
        function playSound(sound) {
            if (document.getElementById('soundEnabled').checked) {
                sound.play().catch(err => console.log('No se pudo reproducir el sonido'));
            }
        }
    </script>
</body>
</html>