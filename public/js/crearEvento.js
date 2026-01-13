// Cambiar entre vistas de preview
function switchPreviewTab(tab) {
    document
        .querySelectorAll(".preview-tab")
        .forEach((t) => t.classList.remove("active"));
    document
        .querySelectorAll(".preview-view")
        .forEach((v) => v.classList.remove("active"));

    document.querySelector(`[data-tab="${tab}"]`).classList.add("active");
    document
        .getElementById(tab === "card" ? "cardView" : "listView")
        .classList.add("active");
}

// Actualizar vista previa en tiempo real
document.getElementById("title").addEventListener("input", function (e) {
    const value = e.target.value || "Título del Evento";
    document.getElementById("previewTitle").textContent = value;
    document.getElementById("previewTitleList").textContent = value;
});

document.getElementById("host_name").addEventListener("input", function (e) {
    const value = e.target.value || "Anfitrión";
    document.getElementById("previewHost").textContent = value;
    document.getElementById("previewHostList").textContent = value;
});

document.getElementById("location").addEventListener("input", function (e) {
    const value = e.target.value || "Ubicación no especificada";
    document.getElementById("previewLocation").textContent = value;
    document.getElementById("previewLocationList").textContent = value;
});

document.getElementById("event_date").addEventListener("change", function (e) {
    if (e.target.value) {
        const date = new Date(e.target.value + "T00:00:00");
        const options = {
            year: "numeric",
            month: "long",
            day: "numeric",
        };
        const formatted = date.toLocaleDateString("es-MX", options);
        document.getElementById("previewDate").textContent = formatted;
        document.getElementById("previewDateList").textContent = formatted;
    } else {
        document.getElementById("previewDate").textContent =
            "Fecha no especificada";
        document.getElementById("previewDateList").textContent = "Fecha";
    }
});

document.getElementById("event_time").addEventListener("change", function (e) {
    const value = e.target.value || "Hora no especificada";
    document.getElementById("previewTime").textContent = value;
    document.getElementById("previewTimeList").textContent = value;
});

document.getElementById("type").addEventListener("change", function (e) {
    const typeLabels = {
        boda: "Boda",
        cumpleanos: "Cumpleaños",
        graduacion: "Graduación",
        corporativo: "Evento Corporativo",
        social: "Evento Social",
        religioso: "Evento Religioso",
        otro: "Otro",
    };
    const value = typeLabels[e.target.value] || "Tipo de evento";
    document.getElementById("previewType").textContent = value;
    document.getElementById("previewTypeList").textContent = value;
});

document.getElementById("description").addEventListener("input", function (e) {
    document.getElementById("previewDescription").textContent =
        e.target.value || "La descripción del evento aparecerá aquí...";
    document.getElementById("charCount").textContent = e.target.value.length;
});

document
    .getElementById("max_attendees")
    .addEventListener("input", function (e) {
        const capacityDiv = document.getElementById("previewCapacityDiv");
        if (e.target.value) {
            document.getElementById("previewCapacity").textContent =
                e.target.value;
            capacityDiv.style.display = "flex";
        } else {
            capacityDiv.style.display = "none";
        }
    });

// Manejo de tipo de pago
document.querySelectorAll('input[name="payment_type"]').forEach((radio) => {
    radio.addEventListener("change", function () {
        const priceGroup = document.getElementById("priceGroup");
        const priceInput = document.getElementById("price");
        const badge = document.getElementById("previewBadge");

        if (this.value === "pago") {
            priceGroup.style.display = "block";
            priceInput.required = true;
            badge.innerHTML = '<i class="fas fa-dollar-sign"></i> DE PAGO';
            badge.className = "preview-badge-overlay badge-pago";
        } else {
            priceGroup.style.display = "none";
            priceInput.required = false;
            priceInput.value = "";
            badge.innerHTML = '<i class="fas fa-gift"></i> GRATIS';
            badge.className = "preview-badge-overlay badge-gratis";
        }
    });
});

// Actualizar precio en vista previa
document.getElementById("price").addEventListener("input", function (e) {
    const badge = document.getElementById("previewBadge");
    if (e.target.value) {
        badge.innerHTML = `<i class="fas fa-dollar-sign"></i> ${parseFloat(
            e.target.value
        ).toFixed(2)}`;
    } else {
        badge.innerHTML = '<i class="fas fa-dollar-sign"></i> DE PAGO';
    }
});

// Preview de imagen
document.getElementById("imageInput").addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const imageUrl = e.target.result;

            // Actualizar preview del input
            const previewContainer = document.getElementById(
                "imagePreviewContainer"
            );
            const preview = document.getElementById("imagePreview");
            const placeholder = document.getElementById("uploadPlaceholder");
            const uploadZone = document.getElementById("imageUploadZone");

            preview.src = imageUrl;
            previewContainer.classList.add("show");
            placeholder.style.display = "none";
            uploadZone.classList.add("has-image");

            // Actualizar preview de tarjeta
            document.getElementById("previewCardImage").innerHTML = `
                        <img src="${imageUrl}" alt="Evento" style="width: 100%; height: 100%; object-fit: cover;">
                        <span class="preview-badge-overlay badge-gratis" id="previewBadge">
                            <i class="fas fa-gift"></i> GRATIS
                        </span>
                    `;

            // Actualizar preview de lista
            document.getElementById("previewListImage").innerHTML = `
                        <img src="${imageUrl}" alt="Evento" style="width: 100%; height: 100%; object-fit: cover;">
                    `;

            // Re-aplicar el badge correcto según el tipo de pago
            const paymentType = document.querySelector(
                'input[name="payment_type"]:checked'
            ).value;
            const price = document.getElementById("price").value;
            const newBadge = document.getElementById("previewBadge");

            if (paymentType === "pago") {
                if (price) {
                    newBadge.innerHTML = `<i class="fas fa-dollar-sign"></i> ${parseFloat(
                        price
                    ).toFixed(2)}`;
                } else {
                    newBadge.innerHTML =
                        '<i class="fas fa-dollar-sign"></i> DE PAGO';
                }
                newBadge.className = "preview-badge-overlay badge-pago";
            }
        };
        reader.readAsDataURL(file);
    }
});

// Remover imagen
function removeImage() {
    document.getElementById("imageInput").value = "";
    document.getElementById("imagePreviewContainer").classList.remove("show");
    document.getElementById("uploadPlaceholder").style.display = "block";
    document.getElementById("imageUploadZone").classList.remove("has-image");

    // Restaurar preview original
    const paymentType = document.querySelector(
        'input[name="payment_type"]:checked'
    ).value;
    const price = document.getElementById("price").value;
    let badgeHTML = '<i class="fas fa-gift"></i> GRATIS';
    let badgeClass = "preview-badge-overlay badge-gratis";

    if (paymentType === "pago") {
        badgeHTML = price
            ? `<i class="fas fa-dollar-sign"></i> ${parseFloat(price).toFixed(
                  2
              )}`
            : '<i class="fas fa-dollar-sign"></i> DE PAGO';
        badgeClass = "preview-badge-overlay badge-pago";
    }

    document.getElementById("previewCardImage").innerHTML = `
                <i class="fas fa-calendar-alt"></i>
                <span class="${badgeClass}" id="previewBadge">${badgeHTML}</span>
            `;

    document.getElementById("previewListImage").innerHTML =
        '<i class="fas fa-calendar-alt"></i>';
}

// ============================================
// MANEJO DE GALERÍA ADICIONAL (MÚLTIPLES IMÁGENES)
// ============================================

let galleryFiles = [];

document
    .getElementById("galleryInput")
    .addEventListener("change", function (e) {
        const newFiles = Array.from(e.target.files);

        // Limitar a 5 imágenes máximo
        if (galleryFiles.length + newFiles.length > 5) {
            alert("Puedes seleccionar máximo 5 imágenes para la galería");
            const remaining = 5 - galleryFiles.length;
            galleryFiles = galleryFiles.concat(newFiles.slice(0, remaining));
        } else {
            galleryFiles = galleryFiles.concat(newFiles);
        }

        updateGalleryPreview();
        e.target.value = ""; // Reset input para poder seleccionar las mismas imágenes después
    });

function updateGalleryPreview() {
    const grid = document.getElementById("galleryPreviewGrid");
    const placeholder = document.getElementById("galleryPlaceholder");
    const count = document.getElementById("galleryCount");

    if (galleryFiles.length === 0) {
        grid.style.display = "none";
        placeholder.style.display = "block";
        count.textContent = "0";
        return;
    }

    grid.style.display = "grid";
    placeholder.style.display = "none";
    count.textContent = galleryFiles.length;

    grid.innerHTML = "";

    galleryFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function (e) {
            const itemDiv = document.createElement("div");
            itemDiv.className = "gallery-preview-item";
            itemDiv.innerHTML = `
                <img src="${e.target.result}" alt="Imagen ${index + 1}">
                <button type="button" class="remove-btn" onclick="removeGalleryImage(${index})">
                    <i class="fas fa-times"></i>
                </button>
                <div class="image-number">${index + 1}</div>
            `;
            grid.appendChild(itemDiv);
        };
        reader.readAsDataURL(file);
    });
}

function removeGalleryImage(index) {
    galleryFiles.splice(index, 1);
    updateGalleryPreview();
    // updateGalleryInput();
}

// function updateGalleryInput() {
//     // Actualizar el input file con los archivos actuales
//     const input = document.getElementById("galleryInput");
//     const dataTransfer = new DataTransfer();

//     galleryFiles.forEach((file) => {
//         dataTransfer.items.add(file);
//     });

//     input.files = dataTransfer.files;
// }

// Prevenir que se abra el selector al hacer click en las imágenes de preview
document.addEventListener("click", function (e) {
    if (e.target.closest(".gallery-preview-item")) {
        e.stopPropagation();
    }
});

// Guardar como borrador
document
    .getElementById("saveAsDraftBtn")
    .addEventListener("click", function () {
        submitForm("borrador");
    });

// Publicar evento
document
    .getElementById("createEventForm")
    .addEventListener("submit", function (e) {
        e.preventDefault();
        submitForm("publicado");
    });

async function submitForm(status) {
    const form = document.getElementById("createEventForm");
    const storeUrl = form.dataset.storeUrl;
    const csrfToken = form.querySelector('input[name="_token"]').value;

    // Limpiar errores previos
    document.querySelectorAll(".field-error").forEach((el) => {
        el.textContent = "";
        el.classList.remove("show");
    });

    const formData = new FormData(form);
    formData.set("status", status);

    // ⭐ IMPORTANTE: Agregar manualmente los archivos de la galería
    // Eliminar el campo vacío primero
    formData.delete("additional_images[]");

    // Agregar cada archivo de la galería manualmente
    galleryFiles.forEach((file, index) => {
        formData.append("additional_images[]", file);
    });

    const button =
        status === "borrador"
            ? document.getElementById("saveAsDraftBtn")
            : document.getElementById("publishBtn");
    const originalText = button.innerHTML;

    // Deshabilitar botones
    document.getElementById("saveAsDraftBtn").disabled = true;
    document.getElementById("publishBtn").disabled = true;
    button.innerHTML =
        '<i class="fas fa-spinner fa-spin"></i> ' +
        (status === "borrador" ? "Guardando..." : "Publicando...");

    try {
        const response = await fetch(storeUrl, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
            },
            body: formData,
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            window.location.href = data.redirect;
        } else {
            // ⭐ MANEJAR ERROR DE STRIPE NO CONECTADO
            if (data.message && data.redirect) {
                const goToStripe = confirm(
                    data.message +
                        "\n\n¿Deseas conectar tu cuenta de Stripe ahora?"
                );
                if (goToStripe) {
                    window.location.href = data.redirect;
                }
            } else if (data.errors) {
                // Mostrar errores de validación
                for (let field in data.errors) {
                    const errorElement = document.getElementById(
                        field + "Error"
                    );
                    if (errorElement) {
                        errorElement.textContent = data.errors[field][0];
                        errorElement.classList.add("show");
                    }
                }

                // Scroll al primer error
                const firstError = document.querySelector(".field-error.show");
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
                }
            }
        }
        
    } catch (error) {
        console.error("Error:", error);
        alert(
            "Hubo un error al procesar tu solicitud. Por favor, intenta de nuevo."
        );
    } finally {
        // Rehabilitar botones
        document.getElementById("saveAsDraftBtn").disabled = false;
        document.getElementById("publishBtn").disabled = false;
        button.innerHTML = originalText;
    }
}


// ============================================
// SISTEMA DE PLANTILLAS Y PERSONALIZACIÓN
// Agregar al final de crearEvento.js
// ============================================

// Definición de plantillas con sus colores y fuentes predefinidos
const templatePresets = {
    // Bodas
    'boda-elegante': {
        primary: '#ffd700',
        secondary: '#ffed4e',
        background: '#fffdf0',
        font: 'Playfair Display'
    },
    'boda-romantica': {
        primary: '#ff6b9d',
        secondary: '#ffc3d7',
        background: '#fff5f8',
        font: 'Dancing Script'
    },
    'boda-moderna': {
        primary: '#2c3e50',
        secondary: '#3498db',
        background: '#ecf0f1',
        font: 'Montserrat'
    },
    'boda-vintage': {
        primary: '#d4a574',
        secondary: '#e6c896',
        background: '#faf8f3',
        font: 'Lora'
    },
    
    // Cumpleaños
    'cumple-festivo': {
        primary: '#ff6b6b',
        secondary: '#feca57',
        background: '#fff9f0',
        font: 'Inter'
    },
    'cumple-infantil': {
        primary: '#74b9ff',
        secondary: '#a29bfe',
        background: '#f5f7ff',
        font: 'Montserrat'
    },
    'cumple-elegante': {
        primary: '#6c5ce7',
        secondary: '#a29bfe',
        background: '#f8f7ff',
        font: 'Playfair Display'
    },
    
    // XV Años
    'xv-princesa': {
        primary: '#ff9ff3',
        secondary: '#feca57',
        background: '#fff9fd',
        font: 'Dancing Script'
    },
    'xv-moderna': {
        primary: '#48dbfb',
        secondary: '#0abde3',
        background: '#f0fcff',
        font: 'Raleway'
    },
    'xv-elegante': {
        primary: '#341f97',
        secondary: '#ee5a6f',
        background: '#fdf5f7',
        font: 'Playfair Display'
    },
    
    // Generales
    'basica': {
        primary: '#667eea',
        secondary: '#764ba2',
        background: '#ffffff',
        font: 'Inter'
    },
    'colorida': {
        primary: '#f093fb',
        secondary: '#4facfe',
        background: '#fdfbff',
        font: 'Inter'
    },
    'minimalista': {
        primary: '#2c3e50',
        secondary: '#95a5a6',
        background: '#ecf0f1',
        font: 'Raleway'
    }
};

// Mostrar plantillas según el tipo de evento seleccionado
document.getElementById('type').addEventListener('change', function(e) {
    const selectedType = e.target.value;
    
    // Ocultar todas las categorías
    document.querySelectorAll('.template-category').forEach(cat => {
        cat.style.display = 'none';
    });
    
    // Mostrar categoría correspondiente
    const categoryMap = {
        'boda': 'boda',
        'cumpleanos': 'cumpleanos',
        'graduacion': 'xv', // Reutilizar plantillas elegantes
        'corporativo': 'general',
        'social': 'general',
        'religioso': 'general',
        'otro': 'general'
    };
    
    const categoryToShow = categoryMap[selectedType] || 'general';
    const categoryElement = document.querySelector(`[data-type="${categoryToShow}"]`);
    
    if (categoryElement) {
        categoryElement.style.display = 'block';
    }
    
    // Siempre mostrar plantillas generales
    document.querySelector('[data-type="general"]').style.display = 'block';
});

// Seleccionar plantilla
function selectTemplate(templateId) {
    // Remover clase active de todas las plantillas
    document.querySelectorAll('.template-card').forEach(card => {
        card.classList.remove('active');
    });
    
    // Agregar clase active a la seleccionada
    const selectedCard = document.querySelector(`[data-template="${templateId}"]`);
    if (selectedCard) {
        selectedCard.classList.add('active');
    }
    
    // Guardar en input hidden
    document.getElementById('template_id').value = templateId;
    
    // Aplicar preset de colores y fuente
    if (templatePresets[templateId]) {
        const preset = templatePresets[templateId];
        applyColorPreset(preset.primary, preset.secondary, preset.background);
        selectFont(preset.font);
    }
}

// Aplicar preset de colores
function applyColorPreset(primary, secondary, background) {
    // Actualizar color pickers
    document.getElementById('primary_color').value = primary;
    document.getElementById('primary_hex').value = primary;
    
    document.getElementById('secondary_color').value = secondary;
    document.getElementById('secondary_hex').value = secondary;
    
    document.getElementById('background_color').value = background;
    document.getElementById('background_hex').value = background;
    
    // Actualizar preview
    updatePreviewColors();
}

// Sincronizar color picker con input de texto
function syncColorPicker(type) {
    const hexInput = document.getElementById(`${type}_hex`);
    const colorInput = document.getElementById(`${type}_color`);
    
    let hexValue = hexInput.value.trim();
    
    // Agregar # si no lo tiene
    if (!hexValue.startsWith('#')) {
        hexValue = '#' + hexValue;
    }
    
    // Validar formato hexadecimal
    if (/^#[0-9A-F]{6}$/i.test(hexValue)) {
        colorInput.value = hexValue;
        hexInput.value = hexValue;
        updatePreviewColors();
    } else {
        // Restaurar valor anterior si es inválido
        hexInput.value = colorInput.value;
    }
}

// Actualizar colores en el preview
function updatePreviewColors() {
    const primary = document.getElementById('primary_color').value;
    const secondary = document.getElementById('secondary_color').value;
    const background = document.getElementById('background_color').value;
    
    // Actualizar inputs de texto
    document.getElementById('primary_hex').value = primary;
    document.getElementById('secondary_hex').value = secondary;
    document.getElementById('background_hex').value = background;
    
    // Aplicar al preview de tarjeta
    const cardPreview = document.querySelector('.event-card-preview');
    if (cardPreview) {
        cardPreview.style.background = background;
        
        // Actualizar gradiente de imagen si no hay imagen cargada
        const cardImage = document.getElementById('previewCardImage');
        if (cardImage && !cardImage.querySelector('img')) {
            cardImage.style.background = `linear-gradient(135deg, ${primary} 0%, ${secondary} 100%)`;
        }
        
        // Actualizar iconos y detalles
        document.querySelectorAll('.preview-detail-icon').forEach(icon => {
            icon.style.background = `rgba(${hexToRgb(primary)}, 0.1)`;
            icon.style.color = primary;
        });
        
        // Actualizar botón
        const cardBtn = document.querySelector('.preview-card-btn');
        if (cardBtn) {
            cardBtn.style.background = `linear-gradient(135deg, ${primary} 0%, ${secondary} 100%)`;
        }
    }
    
    // Aplicar al preview de lista
    const listImage = document.getElementById('previewListImage');
    if (listImage && !listImage.querySelector('img')) {
        listImage.style.background = `linear-gradient(135deg, ${primary} 0%, ${secondary} 100%)`;
    }
}

// Convertir hex a RGB
function hexToRgb(hex) {
    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? 
        `${parseInt(result[1], 16)}, ${parseInt(result[2], 16)}, ${parseInt(result[3], 16)}` 
        : '102, 126, 234';
}

// Seleccionar fuente
function selectFont(fontName) {
    // Remover clase active de todas las fuentes
    document.querySelectorAll('.font-option').forEach(option => {
        option.classList.remove('active');
    });
    
    // Agregar clase active a la seleccionada
    const selectedOption = Array.from(document.querySelectorAll('.font-option'))
        .find(option => option.querySelector('.font-name').textContent === fontName 
                     || option.querySelector('.font-preview').style.fontFamily.includes(fontName));
    
    if (selectedOption) {
        selectedOption.classList.add('active');
    }
    
    // Guardar en input hidden
    document.getElementById('font_family').value = fontName;
    
    // Aplicar al preview
    updatePreviewFont();
}

// Actualizar fuente en el preview
function updatePreviewFont() {
    const fontFamily = document.getElementById('font_family').value;
    
    // Aplicar fuente al título
    document.querySelectorAll('.preview-card-title').forEach(title => {
        title.style.fontFamily = `'${fontFamily}', sans-serif`;
    });
}

// Actualizar tamaño en el preview
function updatePreviewSize() {
    const selectedSize = document.querySelector('input[name="font_size"]:checked').value;
    
    const cardPreview = document.querySelector('.event-card-preview');
    if (cardPreview) {
        // Remover clases anteriores
        cardPreview.classList.remove('font-small', 'font-medium', 'font-large');
        // Agregar nueva clase
        cardPreview.classList.add(`font-${selectedSize}`);
    }
    
    // Aplicar tamaños específicos
    const title = document.getElementById('previewTitle');
    const content = document.querySelector('.preview-card-content');
    
    if (title) {
        const sizes = {
            'small': '1.5rem',
            'medium': '1.8rem',
            'large': '2.2rem'
        };
        title.style.fontSize = sizes[selectedSize];
    }
    
    if (content) {
        const contentSizes = {
            'small': '0.85rem',
            'medium': '0.95rem',
            'large': '1.05rem'
        };
        content.style.fontSize = contentSizes[selectedSize];
    }
}

// ============================================
// ACTUALIZAR FUNCIÓN submitForm PARA INCLUIR DISEÑO
// Reemplazar la función existente
// ============================================

async function submitForm(status) {
    const form = document.getElementById("createEventForm");
    const storeUrl = form.dataset.storeUrl;
    const csrfToken = form.querySelector('input[name="_token"]').value;

    // Limpiar errores previos
    document.querySelectorAll(".field-error").forEach((el) => {
        el.textContent = "";
        el.classList.remove("show");
    });

    const formData = new FormData(form);
    formData.set("status", status);

    // ⭐ Agregar datos de diseño (ya están en el form pero aseguramos que se envíen)
    formData.set("template_id", document.getElementById("template_id").value);
    formData.set("primary_color", document.getElementById("primary_color").value);
    formData.set("secondary_color", document.getElementById("secondary_color").value);
    formData.set("background_color", document.getElementById("background_color").value);
    formData.set("font_family", document.getElementById("font_family").value);
    
    const selectedFontSize = document.querySelector('input[name="font_size"]:checked');
    if (selectedFontSize) {
        formData.set("font_size", selectedFontSize.value);
    }

    // Agregar archivos de la galería
    formData.delete("additional_images[]");
    galleryFiles.forEach((file, index) => {
        formData.append("additional_images[]", file);
    });

    const button =
        status === "borrador"
            ? document.getElementById("saveAsDraftBtn")
            : document.getElementById("publishBtn");
    const originalText = button.innerHTML;

    // Deshabilitar botones
    document.getElementById("saveAsDraftBtn").disabled = true;
    document.getElementById("publishBtn").disabled = true;
    button.innerHTML =
        '<i class="fas fa-spinner fa-spin"></i> ' +
        (status === "borrador" ? "Guardando..." : "Publicando...");

    try {
        const response = await fetch(storeUrl, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                Accept: "application/json",
            },
            body: formData,
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            window.location.href = data.redirect;
        } else {
            if (data.message && data.redirect) {
                const goToStripe = confirm(
                    data.message +
                        "\n\n¿Deseas conectar tu cuenta de Stripe ahora?"
                );
                if (goToStripe) {
                    window.location.href = data.redirect;
                }
            } else if (data.errors) {
                for (let field in data.errors) {
                    const errorElement = document.getElementById(field + "Error");
                    if (errorElement) {
                        errorElement.textContent = data.errors[field][0];
                        errorElement.classList.add("show");
                    }
                }

                const firstError = document.querySelector(".field-error.show");
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
                }
            }
        }
    } catch (error) {
        console.error("Error:", error);
        alert("Hubo un error al procesar tu solicitud. Por favor, intenta de nuevo.");
    } finally {
        document.getElementById("saveAsDraftBtn").disabled = false;
        document.getElementById("publishBtn").disabled = false;
        button.innerHTML = originalText;
    }
}

// ============================================
// INICIALIZACIÓN AL CARGAR LA PÁGINA
// ============================================

// Seleccionar plantilla básica por defecto
document.addEventListener('DOMContentLoaded', function() {
    selectTemplate('basica');
    selectFont('Inter');
    updatePreviewColors();
});