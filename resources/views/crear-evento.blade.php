<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Evento - InvitaCleth</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/crearEvento.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    
</head>

<body>
    @include('partials.header')

    <div class="create-container">
        <div class="page-header">
            <h1><i class="fas fa-magic"></i> Crear Nuevo Evento</h1>
            <p>Diseña tu evento perfecto y genera invitaciones únicas con código QR</p>
        </div>

        <div class="create-layout">
            <!-- FORMULARIO -->
            <div class="form-section">
                <h2 class="section-title">
                    <i class="fas fa-edit"></i>
                    Información del Evento
                </h2>

                <form id="createEventForm"
                    data-store-url="{{ route('eventos.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <!-- PASO 1: Información Básica -->
                    <div class="form-step">
                        <div class="step-header">
                            <div class="step-number">1</div>
                            <h3 class="step-title">Información Básica</h3>
                        </div>

                        <div class="form-group">
                            <label for="title" class="form-label">
                                Título del Evento <span class="required">*</span>
                            </label>
                            <input type="text"
                                id="title"
                                name="title"
                                class="form-input"
                                placeholder="Ej: Boda de María y Juan, Fiesta de XV Años de Ana..."
                                required>
                            <div class="field-error" id="titleError"></div>
                        </div>

                        <div class="form-group">
                            <label for="host_name" class="form-label">
                                Nombre del Anfitrión/Organizador <span class="required">*</span>
                            </label>
                            <input type="text"
                                id="host_name"
                                name="host_name"
                                class="form-input"
                                placeholder="¿Quién organiza el evento?"
                                required>
                            <div class="field-error" id="host_nameError"></div>
                        </div>

                        <div class="form-group">
                            <label for="type" class="form-label">
                                Tipo de Evento <span class="required">*</span>
                            </label>
                            <select id="type" name="type" class="form-select" required>
                                <option value="">Selecciona un tipo</option>
                                <option value="boda">💒 Boda</option>
                                <option value="cumpleanos">🎂 Cumpleaños</option>
                                <option value="graduacion">🎓 Graduación</option>
                                <option value="corporativo">💼 Evento Corporativo</option>
                                <option value="social">🎉 Evento Social</option>
                                <option value="religioso">⛪ Evento Religioso</option>
                                <option value="otro">📌 Otro</option>
                            </select>
                            <div class="field-error" id="typeError"></div>
                        </div>
                    </div>

                    <!-- PASO 2: Ubicación y Fecha -->
                    <div class="form-step">
                        <div class="step-header">
                            <div class="step-number">2</div>
                            <h3 class="step-title">Ubicación y Fecha</h3>
                        </div>

                        <div class="form-group">
                            <label for="location" class="form-label">
                                Ubicación del Evento <span class="required">*</span>
                            </label>
                            <input type="text"
                                id="location"
                                name="location"
                                class="form-input"
                                placeholder="Dirección completa: Calle, número, colonia, ciudad..."
                                required>
                            <div class="field-error" id="locationError"></div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="event_date" class="form-label">
                                    Fecha del Evento <span class="required">*</span>
                                </label>
                                <input type="date"
                                    id="event_date"
                                    name="event_date"
                                    class="form-input"
                                    required>
                                <div class="field-error" id="event_dateError"></div>
                            </div>

                            <div class="form-group">
                                <label for="event_time" class="form-label">
                                    Hora del Evento <span class="required">*</span>
                                </label>
                                <input type="time"
                                    id="event_time"
                                    name="event_time"
                                    class="form-input"
                                    required>
                                <div class="field-error" id="event_timeError"></div>
                            </div>
                        </div>
                    </div>

                    <!-- PASO 3: Imagen del Evento -->
                    <div class="form-step">
                        <div class="step-header">
                            <div class="step-number">3</div>
                            <h3 class="step-title">Imagen del Evento</h3>
                        </div>

                        <div class="form-group">
                            <div class="image-upload-zone" id="imageUploadZone" onclick="document.getElementById('imageInput').click()">
                                <div class="image-preview-container" id="imagePreviewContainer">
                                    <img id="imagePreview" class="image-preview" alt="Vista previa">
                                    <button type="button" class="remove-image-btn" id="removeImageBtn" onclick="event.stopPropagation(); removeImage()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="upload-placeholder" id="uploadPlaceholder">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p style="font-size: 1.2rem; font-weight: 600; margin-top: 1rem;">Haz clic para subir una imagen</p>
                                    <p style="font-size: 0.9rem; color: #999; margin-top: 0.5rem;">PNG, JPG o GIF (máximo 5MB)</p>
                                    <p style="font-size: 0.85rem; color: #667eea; margin-top: 1rem; font-weight: 600;">
                                        <i class="fas fa-info-circle"></i> La imagen aparecerá en la tarjeta del evento
                                    </p>
                                </div>
                            </div>
                            <input type="file"
                                id="imageInput"
                                name="event_image"
                                accept="image/*">
                            <div class="field-error" id="event_imageError"></div>
                        </div>
                    </div>

                    <!-- PASO 4: Acceso y Capacidad -->
                    <div class="form-step">
                        <div class="step-header">
                            <div class="step-number">4</div>
                            <h3 class="step-title">Acceso y Capacidad</h3>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Tipo de Acceso <span class="required">*</span>
                            </label>
                            <div class="radio-group">
                                <label class="radio-option">
                                    <input type="radio"
                                        name="payment_type"
                                        value="gratis"
                                        id="paymentGratis"
                                        checked>
                                    <div>
                                        <strong style="display: block; margin-bottom: 0.25rem;">🎁 Evento Gratuito</strong>
                                        <small style="color: #666;">Sin costo de entrada</small>
                                    </div>
                                </label>
                                <label class="radio-option">
                                    <input type="radio"
                                        name="payment_type"
                                        value="pago"
                                        id="paymentPago">
                                    <div>
                                        <strong style="display: block; margin-bottom: 0.25rem;">💵 Evento de Pago</strong>
                                        <small style="color: #666;">Requiere boleto</small>
                                    </div>
                                </label>
                            </div>
                            <div class="field-error" id="payment_typeError"></div>
                        </div>

                        <div class="form-group" id="priceGroup" style="display: none;">
                            <label for="price" class="form-label">
                                Precio por Persona <span class="required">*</span>
                            </label>
                            <input type="number"
                                id="price"
                                name="price"
                                class="form-input"
                                placeholder="0.00"
                                step="0.01"
                                min="0">
                            <div class="field-error" id="priceError"></div>
                        </div>

                        <div class="form-group">
                            <label for="max_attendees" class="form-label">
                                Capacidad Máxima (Opcional)
                            </label>
                            <input type="number"
                                id="max_attendees"
                                name="max_attendees"
                                class="form-input"
                                placeholder="Ejemplo: 100, 200, 500..."
                                min="1">
                            <div class="info-box">
                                <i class="fas fa-info-circle"></i>
                                Deja este campo vacío si no hay límite de asistentes
                            </div>
                            <div class="field-error" id="max_attendeesError"></div>
                        </div>
                    </div>

                    <!-- PASO 5: Descripción -->
                    <div class="form-step">
                        <div class="step-header">
                            <div class="step-number">5</div>
                            <h3 class="step-title">Descripción del Evento</h3>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">
                                Descripción Completa <span class="required">*</span>
                            </label>
                            <textarea id="description"
                                name="description"
                                class="form-textarea"
                                placeholder="Describe tu evento: &#10;• ¿De qué trata?&#10;• ¿Qué pueden esperar los invitados?&#10;• Código de vestimenta&#10;• Información adicional importante..."
                                maxlength="2000"
                                required></textarea>
                            <div class="char-count">
                                <span id="charCount">0</span> / 2000 caracteres
                            </div>
                            <div class="field-error" id="descriptionError"></div>
                        </div>
                    </div>

                    <!-- PASO 6: Configuración de Visibilidad -->
                    <div class="form-step">
                        <div class="step-header">
                            <div class="step-number">6</div>
                            <h3 class="step-title">Visibilidad</h3>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-option" style="cursor: pointer; justify-content: flex-start;">
                                <input type="checkbox"
                                    id="is_public"
                                    name="is_public"
                                    value="1"
                                    checked>
                                <div>
                                    <strong style="display: block; margin-bottom: 0.25rem;">🌐 Evento Público</strong>
                                    <small style="color: #666;">El evento aparecerá en la página de eventos para que todos lo vean</small>
                                </div>
                            </label>
                            <div class="info-box" style="margin-top: 1rem;">
                                <i class="fas fa-lightbulb"></i>
                                Si desmarcas esta opción, solo las personas con el enlace directo podrán ver el evento
                            </div>
                        </div>
                    </div>

                    <!-- BOTONES DE ACCIÓN -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-draft" id="saveAsDraftBtn">
                            <i class="fas fa-save"></i>
                            Guardar Borrador
                        </button>
                        <button type="submit" class="btn btn-publish" id="publishBtn">
                            <i class="fas fa-rocket"></i>
                            Publicar Evento
                        </button>
                    </div>
                </form>
            </div>

            <!-- VISTA PREVIA -->
            <div class="preview-section">
                <div class="preview-tabs">
                    <button class="preview-tab active" data-tab="card" onclick="switchPreviewTab('card')">
                        <i class="fas fa-th-large"></i>
                        Vista Tarjeta
                    </button>
                    <button class="preview-tab" data-tab="list" onclick="switchPreviewTab('list')">
                        <i class="fas fa-list"></i>
                        Vista Lista
                    </button>
                </div>

                <div class="preview-content">
                    <!-- Vista de Tarjeta -->
                    <div class="preview-view active" id="cardView">
                        <div class="event-card-preview">
                            <div class="preview-card-image" id="previewCardImage">
                                <i class="fas fa-calendar-alt"></i>
                                <span class="preview-badge-overlay badge-gratis" id="previewBadge">
                                    <i class="fas fa-gift"></i> GRATIS
                                </span>
                            </div>

                            <div class="preview-card-content">
                                <h3 class="preview-card-title" id="previewTitle">Título del Evento</h3>
                                <div class="preview-card-host">
                                    <i class="fas fa-user-circle"></i>
                                    Organizado por <strong id="previewHost">Anfitrión</strong>
                                </div>

                                <div class="preview-card-details">
                                    <div class="preview-detail-item">
                                        <div class="preview-detail-icon">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                        <div>
                                            <strong style="display: block; font-size: 0.85rem; color: #888;">Tipo</strong>
                                            <span id="previewType">Tipo de evento</span>
                                        </div>
                                    </div>

                                    <div class="preview-detail-item">
                                        <div class="preview-detail-icon">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                        <div>
                                            <strong style="display: block; font-size: 0.85rem; color: #888;">Fecha</strong>
                                            <span id="previewDate">Fecha no especificada</span>
                                        </div>
                                    </div>

                                    <div class="preview-detail-item">
                                        <div class="preview-detail-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div>
                                            <strong style="display: block; font-size: 0.85rem; color: #888;">Hora</strong>
                                            <span id="previewTime">Hora no especificada</span>
                                        </div>
                                    </div>

                                    <div class="preview-detail-item">
                                        <div class="preview-detail-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div>
                                            <strong style="display: block; font-size: 0.85rem; color: #888;">Ubicación</strong>
                                            <span id="previewLocation">Ubicación no especificada</span>
                                        </div>
                                    </div>

                                    <div class="preview-detail-item" id="previewCapacityDiv" style="display: none;">
                                        <div class="preview-detail-icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div>
                                            <strong style="display: block; font-size: 0.85rem; color: #888;">Capacidad</strong>
                                            <span id="previewCapacity">0</span> personas
                                        </div>
                                    </div>
                                </div>

                                <div class="preview-card-description">
                                    <strong style="display: block; margin-bottom: 0.5rem; color: #333;">Descripción:</strong>
                                    <p id="previewDescription">La descripción del evento aparecerá aquí...</p>
                                </div>

                                <button class="preview-card-btn">
                                    <i class="fas fa-ticket-alt"></i>
                                    Confirmar Asistencia
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Vista de Lista -->
                    <div class="preview-view" id="listView">
                        <div class="event-list-preview">
                            <div class="list-preview-image" id="previewListImage">
                                <i class="fas fa-calendar-alt"></i>
                            </div>

                            <div class="list-preview-content">
                                <h3 class="preview-card-title" id="previewTitleList">Título del Evento</h3>
                                <div class="preview-card-host">
                                    <i class="fas fa-user-circle"></i>
                                    Organizado por <strong id="previewHostList">Anfitrión</strong>
                                </div>

                                <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin: 1rem 0;">
                                    <span style="background: #f0f0f0; padding: 0.4rem 0.8rem; border-radius: 8px; font-size: 0.85rem;">
                                        <i class="fas fa-tag"></i> <span id="previewTypeList">Tipo</span>
                                    </span>
                                    <span style="background: #f0f0f0; padding: 0.4rem 0.8rem; border-radius: 8px; font-size: 0.85rem;">
                                        <i class="fas fa-calendar"></i> <span id="previewDateList">Fecha</span>
                                    </span>
                                    <span style="background: #f0f0f0; padding: 0.4rem 0.8rem; border-radius: 8px; font-size: 0.85rem;">
                                        <i class="fas fa-clock"></i> <span id="previewTimeList">Hora</span>
                                    </span>
                                </div>

                                <p style="color: #666; line-height: 1.6; margin-bottom: 1rem;">
                                    <i class="fas fa-map-marker-alt" style="color: #667eea;"></i>
                                    <span id="previewLocationList">Ubicación</span>
                                </p>

                                <button class="preview-card-btn">
                                    <i class="fas fa-eye"></i>
                                    Ver Detalles
                                </button>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 2rem; padding: 1.5rem; background: #f8f9fa; border-radius: 12px; text-align: center;">
                        <p style="color: #666; font-size: 0.95rem; margin-bottom: 0.5rem;">
                            <i class="fas fa-eye" style="color: #667eea;"></i>
                            <strong>Vista Previa en Tiempo Real</strong>
                        </p>
                        <p style="color: #888; font-size: 0.85rem;">
                            Así es como se verá tu evento en la página de eventos
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <script src="{{ asset('js/crearEvento.js') }}" defer></script>

</body>

</html>