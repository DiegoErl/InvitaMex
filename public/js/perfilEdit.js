
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
   