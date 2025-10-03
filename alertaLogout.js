
// ================== EVENTO CERRAR SESIÓN muestra MENSAJE CON SWEET ALERT PARA CONFIRMAR ==================
document.addEventListener('DOMContentLoaded', function() {
    const logoutLink = document.getElementById('logout-link');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: '¿Seguro que deseas cerrar la sesión?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cerrar sesión',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = logoutLink.href;
                }
            });
        });
    }
});