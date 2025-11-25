<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pago - {{ $event->title }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://js.stripe.com/v3/"></script>
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
            padding-top: 100px;
            padding-bottom: 3rem;
        }

        .checkout-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
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

        .checkout-layout {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 2rem;
        }

        .payment-form-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .order-summary {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            height: fit-content;
            position: sticky;
            top: 120px;
        }

        .section-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .event-info {
            margin-bottom: 1.5rem;
        }

        .event-info h3 {
            color: #333;
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }

        .event-info p {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .price-breakdown {
            margin: 1.5rem 0;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            color: #666;
        }

        .price-row:last-child {
            margin-bottom: 0;
            padding-top: 1rem;
            border-top: 2px solid #e0e0e0;
            font-weight: 700;
            font-size: 1.2rem;
            color: #333;
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

        #card-errors {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: none;
        }

        #card-errors.show {
            display: block;
        }

        .btn {
            width: 100%;
            padding: 1.2rem;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-pay {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-top: 2rem;
        }

        .btn-pay:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.5);
        }

        .btn-pay:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            margin-top: 1rem;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .security-note {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #e3f2fd;
            border-radius: 8px;
            font-size: 0.85rem;
            color: #1976d2;
            text-align: center;
        }

        .security-note i {
            margin-right: 0.5rem;
        }

        .test-cards {
            margin-top: 1rem;
            padding: 1rem;
            background: #fff3cd;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        .test-cards h4 {
            color: #856404;
            margin-bottom: 0.5rem;
        }

        .test-cards ul {
            margin-left: 1.5rem;
            color: #856404;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 1024px) {
            .checkout-layout {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: relative;
                top: 0;
            }
        }

        @media (max-width: 768px) {
            body {
                padding-top: 80px;
            }

            .checkout-container {
                padding: 1rem;
            }

            .payment-form-section,
            .order-summary {
                padding: 1.5rem;
            }

            .page-header h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="checkout-container">
        <div class="page-header">
            <h1><i class="fas fa-credit-card"></i> Pago Seguro</h1>
            <p>Completa tu pago para obtener tu invitación</p>
        </div>

        <div class="checkout-layout">
            <!-- Formulario de Pago -->
            <div class="payment-form-section">
                <h2 class="section-title">
                    <i class="fas fa-lock"></i>
                    Información de Pago
                </h2>

                <form id="payment-form">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-credit-card"></i>
                            Tarjeta de Crédito o Débito
                        </label>
                        <div id="card-element"></div>
                        <div id="card-errors"></div>
                    </div>

                    <button type="submit" class="btn btn-pay" id="submit-button">
                        <i class="fas fa-lock"></i>
                        Pagar ${{ number_format($event->price, 2) }} MXN
                    </button>

                    <a href="{{ route('eventos.show', $event->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Cancelar
                    </a>
                </form>

                <div class="security-note">
                    <i class="fas fa-shield-alt"></i>
                    <strong>Pago 100% seguro.</strong> Procesado por Stripe. Tu información está encriptada.
                </div>

                <!-- Tarjetas de prueba (solo en desarrollo) -->
                <div class="test-cards">
                    <h4><i class="fas fa-flask"></i> Modo de Prueba - Tarjetas de Prueba</h4>
                    <ul>
                        <li><strong>Éxito:</strong> 4242 4242 4242 4242</li>
                        <li><strong>Rechazada:</strong> 4000 0000 0000 0002</li>
                        <li><strong>Requiere 3D Secure:</strong> 4000 0027 6000 3184</li>
                        <li>Fecha: Cualquier fecha futura (ej: 12/25)</li>
                        <li>CVC: Cualquier 3 dígitos (ej: 123)</li>
                        <li>ZIP: Cualquier código (ej: 12345)</li>
                    </ul>
                </div>
            </div>

            <!-- Resumen del Pedido -->
            <div class="order-summary">
                <h2 class="section-title">
                    <i class="fas fa-receipt"></i>
                    Resumen
                </h2>

                <div class="event-info">
                    <h3>{{ $event->title }}</h3>
                    <p>
                        <i class="fas fa-calendar"></i>
                        {{ $event->event_date->format('d/m/Y') }} - {{ $event->event_time->format('H:i') }}
                    </p>
                    <p>
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $event->location }}
                    </p>
                    <p>
                        <i class="fas fa-user"></i>
                        Organizado por {{ $event->host_name }}
                    </p>
                </div>

                <div class="price-breakdown">
                    <div class="price-row">
                        <span>Precio de entrada</span>
                        <span>${{ number_format($event->price, 2) }}</span>
                    </div>
                    <div class="price-row">
                        <span>Comisión de servicio (10%)</span>
                        <span>${{ number_format($event->price * 0.10, 2) }}</span>
                    </div>
                    <div class="price-row">
                        <span>Total a pagar</span>
                        <span>${{ number_format($event->price, 2) }} MXN</span>
                    </div>
                </div>

                <div style="background: #e3f2fd; padding: 1rem; border-radius: 8px; font-size: 0.9rem; color: #1976d2;">
                    <i class="fas fa-info-circle"></i>
                    Recibirás tu invitación con código QR inmediatamente después del pago.
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configurar Stripe
        let stripe, elements, cardElement;
        let clientSecret;

        async function initializeStripe() {
            try {
                const response = await fetch(`/eventos/{{ $event->id }}/create-payment-intent`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (data.error) {
                    alert(data.error);
                    return;
                }

                clientSecret = data.clientSecret;
                stripe = Stripe(data.publishableKey);

                // Crear Elements
                elements = stripe.elements();
                cardElement = elements.create('card', {
                    style: {
                        base: {
                            fontSize: '16px',
                            color: '#333',
                            fontFamily: '"Segoe UI", Tahoma, Geneva, Verdana, sans-serif',
                            '::placeholder': {
                                color: '#999',
                            },
                        },
                        invalid: {
                            color: '#dc3545',
                        },
                    },
                });

                cardElement.mount('#card-element');

                // Manejar errores en tiempo real
                cardElement.on('change', function(event) {
                    const displayError = document.getElementById('card-errors');
                    if (event.error) {
                        displayError.textContent = event.error.message;
                        displayError.classList.add('show');
                    } else {
                        displayError.textContent = '';
                        displayError.classList.remove('show');
                    }
                });

            } catch (error) {
                console.error('Error:', error);
                alert('Error al inicializar el pago');
            }
        }

        // Procesar el pago
        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            const submitButton = document.getElementById('submit-button');
            submitButton.disabled = true;
            submitButton.innerHTML = '<div class="spinner"></div> Procesando pago...';

            try {
                const {error, paymentIntent} = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: '{{ Auth::user()->firstName }} {{ Auth::user()->lastName }}',
                            email: '{{ Auth::user()->email }}',
                        },
                    },
                });

                if (error) {
                    // Mostrar error
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = error.message;
                    errorElement.classList.add('show');
                    
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="fas fa-lock"></i> Pagar ${{ number_format($event->price, 2) }} MXN';
                } else if (paymentIntent.status === 'succeeded') {
                    // Pago exitoso - confirmar en el backend
                    const confirmResponse = await fetch(`/eventos/{{ $event->id }}/confirm-payment`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            payment_intent_id: paymentIntent.id
                        })
                    });

                    const confirmData = await confirmResponse.json();

                    if (confirmData.success) {
                        alert('¡Pago exitoso! Redirigiendo a tus invitaciones...');
                        window.location.href = confirmData.redirect;
                    } else {
                        alert(confirmData.message);
                        submitButton.disabled = false;
                        submitButton.innerHTML = '<i class="fas fa-lock"></i> Pagar ${{ number_format($event->price, 2) }} MXN';
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al procesar el pago');
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-lock"></i> Pagar ${{ number_format($event->price, 2) }} MXN';
            }
        });

        // Inicializar cuando cargue la página
        document.addEventListener('DOMContentLoaded', initializeStripe);
    </script>
</body>
</html>