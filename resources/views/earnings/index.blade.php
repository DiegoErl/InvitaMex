<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Ingresos - InvitaMex</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding-top: 100px;
        }

        .earnings-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .page-header {
            text-align: center;
            color: white;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .section-title {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .section-title h2 {
            margin: 0;
            color: #333;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .content-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            margin-bottom: 2rem;
        }

        .payments-table {
            width: 100%;
            border-collapse: collapse;
        }

        .payments-table th {
            background: #f8f9fa;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #666;
            border-bottom: 2px solid #e0e0e0;
        }

        .payments-table td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .payments-table tr:hover {
            background: #f8f9fa;
        }

        .event-name {
            font-weight: 600;
            color: #333;
        }

        .buyer-name {
            color: #666;
            font-size: 0.9rem;
        }

        .amount {
            font-weight: 700;
            font-size: 1.2rem;
            color: #28a745;
        }

        .date {
            color: #999;
            font-size: 0.85rem;
        }

        .no-data {
            text-align: center;
            padding: 3rem;
            color: #999;
        }

        .no-data i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .top-events-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .top-event-item {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-event-item:last-child {
            border-bottom: none;
        }

        .event-info {
            flex: 1;
        }

        .event-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .event-sales {
            color: #666;
            font-size: 0.85rem;
        }

        .event-earnings {
            font-weight: 700;
            font-size: 1.3rem;
            color: #28a745;
        }

        .chart-container {
            margin-top: 1.5rem;
        }

        .month-bar {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .month-label {
            width: 100px;
            font-weight: 600;
            color: #666;
        }

        .bar-wrapper {
            flex: 1;
            background: #f0f0f0;
            border-radius: 10px;
            height: 30px;
            position: relative;
            overflow: hidden;
        }

        .bar-fill {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            height: 100%;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 10px;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            transition: width 0.5s ease;
        }

        .btn-stripe {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #635bff;
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .btn-stripe:hover {
            background: #5145cd;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(99, 91, 255, 0.3);
        }

        @media (max-width: 768px) {
            body {
                padding-top: 80px;
            }

            .earnings-container {
                padding: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .payments-table {
                font-size: 0.85rem;
            }

            .payments-table th,
            .payments-table td {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="earnings-container">
        <div class="page-header">
            <h1><i class="fas fa-dollar-sign"></i> Mis Ingresos</h1>
            <p>Administra y visualiza tus ganancias</p>
        </div>

        <!-- Estadísticas principales -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="stat-value">${{ number_format($totalEarnings, 2) }}</div>
                <div class="stat-label">Ingresos Totales (MXN)</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-value">{{ $totalTransactions }}</div>
                <div class="stat-label">Ventas Totales</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-value">
                    @if($totalTransactions > 0)
                        ${{ number_format($totalEarnings / $totalTransactions, 2) }}
                    @else
                        $0.00
                    @endif
                </div>
                <div class="stat-label">Promedio por Venta</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-value">${{ number_format($totalPlatformFees, 2) }}</div>
                <div class="stat-label">Comisión Plataforma (10%)</div>
            </div>
        </div>

        @if(Auth::user()->hasStripeAccount())
        <div style="text-align: center; margin-bottom: 2rem;">
            <a href="{{ route('stripe.dashboard') }}" class="btn-stripe">
                <i class="fab fa-stripe"></i>
                Ver Dashboard de Stripe
            </a>
        </div>
        @endif

        <!-- Eventos con más ingresos -->
        @if($topEvents->count() > 0)
        <div class="section-title">
            <h2><i class="fas fa-trophy"></i> Eventos con Más Ingresos</h2>
        </div>

        <div class="content-card">
            <ul class="top-events-list">
                @foreach($topEvents as $topEvent)
                <li class="top-event-item">
                    <div class="event-info">
                        <div class="event-title">{{ $topEvent->event->title }}</div>
                        <div class="event-sales">{{ $topEvent->sales_count }} ventas</div>
                    </div>
                    <div class="event-earnings">${{ number_format($topEvent->total_earnings, 2) }}</div>
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Gráfico de ingresos mensuales -->
        @if($monthlyEarnings->count() > 0)
        <div class="section-title">
            <h2><i class="fas fa-chart-bar"></i> Ingresos por Mes</h2>
        </div>

        <div class="content-card">
            <div class="chart-container">
                @php
                    $maxEarning = $monthlyEarnings->max('total');
                    $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                @endphp

                @foreach($monthlyEarnings as $earning)
                <div class="month-bar">
                    <div class="month-label">{{ $months[$earning->month - 1] }} {{ $earning->year }}</div>
                    <div class="bar-wrapper">
                        <div class="bar-fill" style="width: {{ $maxEarning > 0 ? ($earning->total / $maxEarning * 100) : 0 }}%">
                            ${{ number_format($earning->total, 2) }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Historial de pagos -->
        <div class="section-title">
            <h2><i class="fas fa-history"></i> Historial de Pagos</h2>
        </div>

        <div class="content-card">
            @if($payments->count() > 0)
            <div style="overflow-x: auto;">
                <table class="payments-table">
                    <thead>
                        <tr>
                            <th>Evento</th>
                            <th>Comprador</th>
                            <th>Monto Total</th>
                            <th>Recibes</th>
                            <th>Comisión</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td>
                                <div class="event-name">{{ $payment->event->title }}</div>
                            </td>
                            <td>
                                <div class="buyer-name">{{ $payment->user->firstName }} {{ $payment->user->lastName }}</div>
                            </td>
                            <td>${{ number_format($payment->amount, 2) }}</td>
                            <td>
                                <div class="amount">${{ number_format($payment->organizer_amount, 2) }}</div>
                            </td>
                            <td>${{ number_format($payment->platform_fee, 2) }}</td>
                            <td>
                                <div class="date">{{ $payment->paid_at->format('d/m/Y H:i') }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="no-data">
                <i class="fas fa-inbox"></i>
                <h3>Aún no has recibido pagos</h3>
                <p>Tus ingresos aparecerán aquí cuando alguien compre un boleto para tus eventos.</p>
            </div>
            @endif
        </div>
    </div>

    @include('partials.footer')
</body>
</html>