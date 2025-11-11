<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - EventosPro | Comunícate con Nosotros</title>

    <!-- Estilos externos -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/contacto.css') }}" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
</head>
<body>
    
    @include('partials.header')

    <!-- PAGE HERO -->
    <section class="page-hero">
        <div class="page-hero-content">
            <h1>Contáctanos</h1>
            <p>
                Estamos aquí para ayudarte. Comunícate con nosotros a través de WhatsApp, 
                email o nuestro formulario de contacto. ¡Responderemos lo más pronto posible!
            </p>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="container">
            <!-- CONTACT METHODS SECTION -->
            <section class="contact-methods">
                <div class="contact-intro">
                    <h2>¿Cómo prefieres contactarnos?</h2>
                    <p>
                        Ofrecemos múltiples canales de comunicación para que elijas el que más te convenga. 
                        Nuestro equipo está listo para ayudarte con cualquier consulta sobre EventosPro.
                    </p>
                </div>
                
                <div class="methods-grid">
                    <!-- WHATSAPP - MÉTODO PRINCIPAL -->
                    <div class="method-card whatsapp primary animate-on-scroll">
                        <div class="method-icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <h3 class="method-title">WhatsApp Business</h3>
                        <p class="method-description">
                            ¡Nuestro método preferido! Respuesta inmediata de lunes a viernes de 9:00 AM a 8:00 PM. 
                            Perfecto para consultas rápidas, soporte técnico y demostraciones en vivo.
                        </p>
                        <div>
                            <a href="#" class="method-action primary-btn" onclick="contactWhatsApp('general')">
                                <i class="fab fa-whatsapp"></i>
                                Chatear Ahora
                            </a>
                            <a href="#" class="method-action primary-btn" onclick="contactWhatsApp('demo')">
                                <i class="fas fa-play"></i>
                                Solicitar Demo
                            </a>
                        </div>
                    </div>
                    
                    <!-- EMAIL -->
                    <div class="method-card email animate-on-scroll">
                        <div class="method-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3 class="method-title">Correo Electrónico</h3>
                        <p class="method-description">
                            Para consultas detalladas, propuestas comerciales o documentación técnica. 
                            Tiempo de respuesta promedio: 24 horas en días hábiles.
                        </p>
                        <div>
                            <a href="mailto:hola@eventospro.com" class="method-action email-btn">
                                <i class="fas fa-envelope"></i>
                                Enviar Email
                            </a>
                        </div>
                    </div>
                </div>

                <!-- DEPARTAMENTOS ESPECÍFICOS -->
                <!-- <div class="quick-contact">
                    <h3>Contacto por Departamento</h3>
                    <div class="quick-links">
                        <a href="#" class="quick-link" onclick="contactWhatsApp('soporte')">
                            <i class="fas fa-headset"></i>
                            <span>Soporte Técnico</span>
                        </a>
                        <a href="#" class="quick-link" onclick="contactWhatsApp('ventas')">
                            <i class="fas fa-handshake"></i>
                            <span>Ventas</span>
                        </a>
                        <a href="#" class="quick-link" onclick="contactWhatsApp('bugs')">
                            <i class="fas fa-bug"></i>
                            <span>Reportar Error</span>
                        </a>
                        <a href="#" class="quick-link" onclick="contactWhatsApp('sugerencias')">
                            <i class="fas fa-lightbulb"></i>
                            <span>Sugerencias</span>
                        </a>
                    </div>
                </div> -->
            </section>

            <!-- CONTACT FORM SECTION -->
            <section class="contact-form-section">
                <div class="form-container">
                    <div class="form-header">
                        <h2>Formulario de Contacto</h2>
                        <p>
                            ¿Prefieres escribirnos? Completa este formulario y nos pondremos en contacto contigo 
                            lo antes posible. También puedes adjuntar archivos si es necesario.
                        </p>
                    </div>
                    
                    <form class="contact-form" id="contactForm">
                        <div class="success-message" id="successMessage">
                            <i class="fas fa-check-circle"></i>
                            <span>¡Mensaje enviado exitosamente! Te contactaremos pronto.</span>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="firstName" class="form-label">Nombre *</label>
                                <input type="text" id="firstName" name="firstName" class="form-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="lastName" class="form-label">Apellido *</label>
                                <input type="text" id="lastName" name="lastName" class="form-input" required>
                            </div>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="email" class="form-label">Correo Electrónico *</label>
                                <input type="email" id="email" name="email" class="form-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone" class="form-label">Teléfono (WhatsApp)</label>
                                <input type="tel" id="phone" name="phone" class="form-input" placeholder="+52 55 1234 5678">
                            </div>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="subject" class="form-label">Asunto *</label>
                            <select id="subject" name="subject" class="form-select" required>
                                <option value="">Selecciona un tema...</option>
                                <option value="general">Consulta General</option>
                                <option value="demo">Solicitar Demo</option>
                                <option value="soporte">Soporte Técnico</option>
                                <option value="ventas">Información Comercial</option>
                                <option value="bug">Reportar Error</option>
                                <option value="feature">Solicitud de Función</option>
                                <option value="partnership">Alianzas Estratégicas</option>
                                <option value="otros">Otros</option>
                            </select>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="message" class="form-label">Mensaje *</label>
                            <textarea id="message" name="message" class="form-textarea" 
                                placeholder="Cuéntanos en qué podemos ayudarte..." required></textarea>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="company" class="form-label">Empresa (Opcional)</label>
                            <input type="text" id="company" name="company" class="form-input" 
                                placeholder="Nombre de tu empresa u organización">
                        </div>
                        
                        <button type="submit" class="form-submit" id="submitBtn">
                            <i class="fas fa-paper-plane"></i>
                            Enviar Mensaje
                        </button>
                    </form>
                </div>
            </section>

            <!-- FAQ SECTION -->
            <section class="faq-section">
                <div class="faq-header">
                    <h2>Preguntas Frecuentes</h2>
                    <p>Las respuestas a las consultas más comunes de nuestros usuarios</p>
                </div>
                
                <div class="faq-list">
                    <div class="faq-item animate-on-scroll">
                        <button class="faq-question" onclick="toggleFAQ(this)">
                            <span>¿Cómo puedo crear mi primera invitación?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Es muy sencillo. Solo regístrate en nuestra plataforma, haz clic en "Crear Evento", completa la información básica y personaliza tu invitación con colores, fuentes e imágenes. En menos de 5 minutos tendrás tu invitación lista para compartir.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item animate-on-scroll">
                        <button class="faq-question" onclick="toggleFAQ(this)">
                            <span>¿InvitaMex es gratuito?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Ofrecemos un plan gratuito con características básicas que incluye hasta 3 eventos por mes y 50 invitados por evento. También tenemos planes premium con funciones avanzadas, plantillas exclusivas y soporte prioritario.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item animate-on-scroll">
                        <button class="faq-question" onclick="toggleFAQ(this)">
                            <span>¿Puedo usar mis propias imágenes y logos?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>¡Absolutamente! Puedes subir tus propias imágenes, logos y elementos gráficos. Soportamos formatos JPG, PNG y SVG. También ofrecemos una biblioteca de imágenes profesionales gratuitas.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item animate-on-scroll">
                        <button class="faq-question" onclick="toggleFAQ(this)">
                            <span>¿Cómo funcionan los códigos QR?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Cada invitación genera automáticamente un código QR único. Los invitados pueden escanearlo con cualquier teléfono para acceder directamente al evento, ver detalles, confirmar asistencia y agregar el evento a su calendario.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item animate-on-scroll">
                        <button class="faq-question" onclick="toggleFAQ(this)">
                            <span>¿Ofrecen soporte en español?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>Sí, todo nuestro equipo habla español nativo. Ofrecemos soporte completo en español por WhatsApp, email y chat en vivo. Nuestros horarios de atención son de lunes a viernes de 9:00 AM a 8:00 PM (horario de México).</p>
                        </div>
                    </div>
                    
                    <div class="faq-item animate-on-scroll">
                        <button class="faq-question" onclick="toggleFAQ(this)">
                            <span>¿Puedo exportar la lista de asistentes?</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="faq-answer">
                            <p>¡Por supuesto! Puedes exportar la lista completa de asistentes en formato Excel (CSV) con todos los detalles: nombres, emails, estados de confirmación, fechas de respuesta y códigos QR únicos.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- FLOATING WHATSAPP -->
    <a href="#" class="floating-whatsapp" onclick="contactWhatsApp('flotante')" 
       title="¡Chatea con nosotros por WhatsApp!">
        <i class="fab fa-whatsapp"></i>
    </a>

    @include('partials.footer')

    <!-- BACK TO TOP BUTTON -->
    <button class="back-to-top" id="backToTop" aria-label="Volver arriba">
        <i class="fas fa-chevron-up"></i>
    </button>

    <script src="{{ asset('js/contacto.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
    

    
</body>
</html>