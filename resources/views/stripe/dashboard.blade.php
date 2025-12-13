<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Stripe - InvitaMex</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding-top: 100px;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .dashboard-header {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .test-mode-badge {
            display: inline-block;
            background: #ffc107;
            color: #333;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .info-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .info-card h3 {
            color: #667eea;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #666;
            font-weight: 600;
        }

        .info-value {
            color: #333;
            font-weight: 700;
        }

        .status-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: white;
            color: #667eea;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .alert-info {
            background: #d1ecf1;
            border: 2px solid #17a2b8;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            color: #0c5460;
        }

        .alert-info strong {
            display: block;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="dashboard-container">
        <div class="dashboard-header">
            <span class="test-mode-badge">
                <i class="fas fa-flask"></i> MODO DE PRUEBA
            </span>
            <h1 style="margin: 1rem 0;">
                <i class="fab fa-stripe"></i> Dashboard de Stripe
            </h1>
            <p style="color: #666;">Información de tu cuenta conectada</p>
        </div>

        @if($testMode)
        <div class="alert-info">
            <strong><i class="fas fa-info-circle"></i> Estás en modo de prueba</strong>
            Esta es una cuenta de Stripe de prueba. En producción, este botón te llevará a tu dashboard real de Stripe donde podrás ver tus pagos, transferencias y más.
        </div>
        @endif

        <div class="dashboard-grid">
            <!-- Información de la cuenta -->
            <div class="info-card">
                <h3>
                    <i class="fas fa-user-circle"></i>
                    Información de la Cuenta
                </h3>
                <div class="info-row">
                    <span class="info-label">ID de Cuenta</span>
                    <span class="info-value">{{ substr($account->id, 0, 20) }}...</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $account->email ?? $user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tipo de Cuenta</span>
                    <span class="info-value">{{ ucfirst($account->type) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Estado</span>
                    <span class="info-value">
                        @if($account->charges_enabled)
                            <span class="status-badge status-active">
                                <i class="fas fa-check-circle"></i> Activa
                            </span>
                        @else
                            <span class="status-badge status-pending">
                                <i class="fas fa-clock"></i> Pendiente
                            </span>
                        @endif
                    </span>
                </div>
            </div>

            <!-- Capacidades -->
            <div class="info-card">
                <h3>
                    <i class="fas fa-shield-alt"></i>
                    Capacidades
                </h3>
                <div class="info-row">
                    <span class="info-label">Puede recibir pagos</span>
                    <span class="info-value">
                        @if($account->charges_enabled)
                            <i class="fas fa-check-circle" style="color: #28a745;"></i> Sí
                        @else
                            <i class="fas fa-times-circle" style="color: #dc3545;"></i> No
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Puede hacer retiros</span>
                    <span class="info-value">
                        @if($account->payouts_enabled)
                            <i class="fas fa-check-circle" style="color: #28a745;"></i> Sí
                        @else
                            <i class="fas fa-times-circle" style="color: #dc3545;"></i> No
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Detalles completados</span>
                    <span class="info-value">
                        @if($account->details_submitted)
                            <i class="fas fa-check-circle" style="color: #28a745;"></i> Sí
                        @else
                            <i class="fas fa-times-circle" style="color: #dc3545;"></i> No
                        @endif
                    </span>
                </div>
            </div>

            <!-- Comisiones -->
            <div class="info-card">
                <h3>
                    <i class="fas fa-percentage"></i>
                    Comisiones
                </h3>
                <div class="info-row">
                    <span class="info-label">Recibes por venta</span>
                    <span class="info-value" style="color: #28a745; font-size: 1.5rem;">90%</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Comisión plataforma</span>
                    <span class="info-value">10%</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ejemplo de $100 MXN</span>
                    <span class="info-value">
                        <div style="font-size: 0.9rem;">
                            Recibes: <strong style="color: #28a745;">$90.00</strong><br>
                            Plataforma: $10.00
                        </div>
                    </span>
                </div>
            </div>

            <!-- Modo de prueba -->
            @if($testMode)
            <div class="info-card" style="background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);">
                <h3 style="color: #856404;">
                    <i class="fas fa-flask"></i>
                    Modo de Prueba
                </h3>
                <p style="color: #856404; line-height: 1.6;">
                    Actualmente estás en modo de prueba. Los pagos no son reales y puedes usar tarjetas de prueba para simular transacciones.
                </p>
                <div style="margin-top: 1rem; padding: 1rem; background: white; border-radius: 10px;">
                    <strong style="color: #333;">Tarjeta de prueba:</strong><br>
                    <code style="background: #f0f0f0; padding: 0.5rem; display: block; margin-top: 0.5rem; border-radius: 5px;">
                        4242 4242 4242 4242
                    </code>
                    <small style="color: #666;">Fecha: Cualquier futura | CVC: Cualquier 3 dígitos</small>
                </div>
            </div>
            @endif
        </div>

        <div style="margin-top: 2rem; text-align: center;">
            <a href="{{ route('perfil') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Volver a Mi Perfil
            </a>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>