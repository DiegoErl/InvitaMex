<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <title>Editar Perfil - InvitaCleth</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        .edit-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .edit-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            color: white;
            text-align: center;
        }

        .edit-header h1 {
            margin-bottom: 0.5rem;
        }

        .edit-body {
            padding: 2rem;
        }

        .photo-section {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .photo-preview-container {
            position: relative;
            display: inline-block;
        }

        .photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #667eea;
            margin-bottom: 1rem;
        }

        .photo-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            font-weight: bold;
            border: 5px solid #667eea;
            margin: 0 auto 1rem;
        }

        .photo-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-photo {
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-upload {
            background: #667eea;
            color: white;
        }

        .btn-upload:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .form-section h2 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.3rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            color: #555;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-input,
        .form-select {
            padding: 0.8rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .password-input-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            padding: 0.5rem;
        }

        .field-error {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: none;
        }

        .field-error.show {
            display: block;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #f0f0f0;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
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

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: none;
            align-items: center;
            gap: 0.5rem;
        }

        .success-message.show {
            display: flex;
        }

        #photoInput {
            display: none;
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .edit-body {
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="edit-container">
        <div class="edit-header">
            <h1><i class="fas fa-user-edit"></i> Editar Mi Perfil</h1>
            <p>Actualiza tu información personal</p>
        </div>

        <div class="edit-body">
            <div id="successMessage" class="success-message">
                <i class="fas fa-check-circle"></i>
                <span>¡Perfil actualizado exitosamente!</span>
            </div>

            <form id="editProfileForm" 
                  data-update-url="{{ route('perfil.update') }}"
                  data-delete-photo-url="{{ route('perfil.deletePhoto') }}"
                  data-perfil-url="{{ route('perfil') }}"
                  enctype="multipart/form-data">
                @csrf

                <!-- Sección de Foto -->
                <div class="photo-section">
                    <div class="photo-preview-container">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" 
                                 alt="Foto de perfil" 
                                 class="photo-preview" 
                                 id="photoPreview">
                        @else
                            <div class="photo-avatar" id="photoAvatar">
                                {{ strtoupper(substr(Auth::user()->firstName, 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastName, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="photo-buttons">
                        <input type="file" id="photoInput" name="profile_photo" accept="image/*">
                        <button type="button" class="btn-photo btn-upload" onclick="document.getElementById('photoInput').click()">
                            <i class="fas fa-camera"></i>
                            Cambiar Foto
                        </button>
                        @if(Auth::user()->profile_photo)
                        <button type="button" class="btn-photo btn-delete" id="deletePhotoBtn">
                            <i class="fas fa-trash"></i>
                            Eliminar Foto
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Información Personal -->
                <div class="form-section">
                    <h2>Información Personal</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName" class="form-label">Nombre *</label>
                            <input type="text" 
                                   id="firstName" 
                                   name="firstName" 
                                   class="form-input" 
                                   value="{{ Auth::user()->firstName }}" 
                                   required>
                            <div class="field-error" id="firstNameError"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="lastName" class="form-label">Apellido *</label>
                            <input type="text" 
                                   id="lastName" 
                                   name="lastName" 
                                   class="form-input" 
                                   value="{{ Auth::user()->lastName }}" 
                                   required>
                            <div class="field-error" id="lastNameError"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email" class="form-label">Correo Electrónico *</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-input" 
                                   value="{{ Auth::user()->email }}" 
                                   required>
                            <div class="field-error" id="emailError"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   class="form-input" 
                                   value="{{ Auth::user()->phone }}" 
                                   placeholder="+52 55 1234 5678">
                            <div class="field-error" id="phoneError"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="country" class="form-label">País</label>
                        <select id="country" name="country" class="form-select">
                            <option value="">Selecciona tu país</option>
                            <option value="MX" {{ Auth::user()->country == 'MX' ? 'selected' : '' }}>México</option>
                            <option value="US" {{ Auth::user()->country == 'US' ? 'selected' : '' }}>Estados Unidos</option>
                            <option value="CA" {{ Auth::user()->country == 'CA' ? 'selected' : '' }}>Canadá</option>
                            <option value="ES" {{ Auth::user()->country == 'ES' ? 'selected' : '' }}>España</option>
                            <option value="AR" {{ Auth::user()->country == 'AR' ? 'selected' : '' }}>Argentina</option>
                            <option value="CO" {{ Auth::user()->country == 'CO' ? 'selected' : '' }}>Colombia</option>
                            <option value="PE" {{ Auth::user()->country == 'PE' ? 'selected' : '' }}>Perú</option>
                            <option value="CL" {{ Auth::user()->country == 'CL' ? 'selected' : '' }}>Chile</option>
                            <option value="other" {{ Auth::user()->country == 'other' ? 'selected' : '' }}>Otro</option>
                        </select>
                        <div class="field-error" id="countryError"></div>
                    </div>
                </div>

                <!-- Cambiar Contraseña -->
                <div class="form-section">
                    <h2>Cambiar Contraseña (Opcional)</h2>
                    <p style="color: #666; font-size: 0.9rem; margin-bottom: 1rem;">
                        Deja estos campos en blanco si no deseas cambiar tu contraseña
                    </p>

                    <div class="form-group">
                        <label for="current_password" class="form-label">Contraseña Actual</label>
                        <div class="password-input-container">
                            <input type="password" 
                                   id="current_password" 
                                   name="current_password" 
                                   class="form-input" 
                                   placeholder="Tu contraseña actual">
                            <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                <i class="fas fa-eye" id="current_passwordIcon"></i>
                            </button>
                        </div>
                        <div class="field-error" id="current_passwordError"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="new_password" class="form-label">Nueva Contraseña</label>
                            <div class="password-input-container">
                                <input type="password" 
                                       id="new_password" 
                                       name="new_password" 
                                       class="form-input" 
                                       placeholder="Mínimo 8 caracteres">
                                <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                    <i class="fas fa-eye" id="new_passwordIcon"></i>
                                </button>
                            </div>
                            <div class="field-error" id="new_passwordError"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                            <div class="password-input-container">
                                <input type="password" 
                                       id="new_password_confirmation" 
                                       name="new_password_confirmation" 
                                       class="form-input" 
                                       placeholder="Repite tu nueva contraseña">
                                <button type="button" class="password-toggle" onclick="togglePassword('new_password_confirmation')">
                                    <i class="fas fa-eye" id="new_password_confirmationIcon"></i>
                                </button>
                            </div>
                            <div class="field-error" id="new_password_confirmationError"></div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="form-actions">
                    <a href="{{ route('perfil') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary" id="saveBtn">
                        <i class="fas fa-save"></i>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    @include('partials.footer')

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + 'Icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Preview de la foto antes de subir
        document.getElementById('photoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const photoPreview = document.getElementById('photoPreview');
                    const photoAvatar = document.getElementById('photoAvatar');
                    
                    if (photoPreview) {
                        photoPreview.src = e.target.result;
                    } else if (photoAvatar) {
                        // Reemplazar avatar por imagen
                        photoAvatar.outerHTML = `<img src="${e.target.result}" alt="Foto de perfil" class="photo-preview" id="photoPreview">`;
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // Eliminar foto de perfil
        const deletePhotoBtn = document.getElementById('deletePhotoBtn');
        if (deletePhotoBtn) {
            deletePhotoBtn.addEventListener('click', async function() {
                if (!confirm('¿Estás seguro de que quieres eliminar tu foto de perfil?')) {
                    return;
                }

                const form = document.getElementById('editProfileForm');
                const deletePhotoUrl = form.dataset.deletePhotoUrl;
                const csrfToken = form.querySelector('input[name="_token"]').value;

                try {
                    const response = await fetch(deletePhotoUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        alert('Foto de perfil eliminada exitosamente');
                        location.reload();
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Hubo un error al eliminar la foto');
                }
            });
        }

        // Enviar formulario
        document.getElementById('editProfileForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = this;
            const updateUrl = form.dataset.updateUrl;
            const perfilUrl = form.dataset.perfilUrl;
            const csrfToken = form.querySelector('input[name="_token"]').value;

            // Limpiar errores previos
            document.querySelectorAll('.field-error').forEach(el => {
                el.textContent = '';
                el.classList.remove('show');
            });

            const formData = new FormData(form);
            const button = document.getElementById('saveBtn');
            const originalText = button.innerHTML;

            // Deshabilitar botón
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

            try {
                const response = await fetch(updateUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    // Mostrar mensaje de éxito
                    const successMessage = document.getElementById('successMessage');
                    successMessage.classList.add('show');
                    
                    // Scroll hacia arriba
                    window.scrollTo({ top: 0, behavior: 'smooth' });

                    // Redirigir después de 2 segundos
                    setTimeout(() => {
                        window.location.href = perfilUrl;
                    }, 2000);
                } else {
                    // Mostrar errores
                    if (data.errors) {
                        for (let field in data.errors) {
                            const errorElement = document.getElementById(field + 'Error');
                            if (errorElement) {
                                errorElement.textContent = data.errors[field][0];
                                errorElement.classList.add('show');
                            }
                        }
                        
                        // Scroll al primer error
                        const firstError = document.querySelector('.field-error.show');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Hubo un error al actualizar tu perfil. Por favor, intenta de nuevo.');
            } finally {
                // Rehabilitar botón
                button.disabled = false;
                button.innerHTML = originalText;
            }
        });

        // Prevenir navegación hacia atrás después de cerrar sesión
        window.onpageshow = function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        };
    </script>
</body>
</html>