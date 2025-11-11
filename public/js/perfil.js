
        // Prevenir que el navegador guarde en caché esta página
        window.onpageshow = function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        };

        // Limpiar el historial al cerrar sesión
        document.getElementById('logoutForm').addEventListener('submit', function() {
            // Limpiar el storage del navegador
            if (window.localStorage) {
                localStorage.clear();
            }
            if (window.sessionStorage) {
                sessionStorage.clear();
            }
        });
    