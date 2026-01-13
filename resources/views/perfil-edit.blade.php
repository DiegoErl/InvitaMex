<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <title>Editar Perfil - InvitaCleth</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/perfilEdit.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
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
                                   readonly>
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

    <script src="{{ asset('js/perfilEdit.js') }}"></script>
    
</body>
</html>