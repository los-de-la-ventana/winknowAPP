
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

// ================== CONFIRMACIÓN PARA CANCELAR RESERVAS ==================
document.addEventListener('DOMContentLoaded', function() {
    const formsEliminar = document.querySelectorAll('form[action="docente_reservas.php"]');
    formsEliminar.forEach(form => {
        if (form.querySelector('input[name="eliminar_reserva"]')) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Cancelar reserva?',
                    text: "Esta acción no se puede deshacer",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#4b5563',
                    confirmButtonText: 'Sí, cancelar',
                    cancelButtonText: 'No, mantener'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });
}); 
// ================== MOSTRAR MENSAJES DE RESERVAS CON SWEETALERT ==================
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.mensajeReserva !== 'undefined') {
        const icon = window.mensajeReserva.tipo === 'exito' ? 'success' : 'error';
        const title = window.mensajeReserva.tipo === 'exito' ? '¡Éxito!' : 'Error';
        
        Swal.fire({
            icon: icon,
            title: title,
            text: window.mensajeReserva.mensaje,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#4f7df3',
            timer: 3000,
            timerProgressBar: true
        });
        
        delete window.mensajeReserva;
    }
});

// ================== MOSTRAR MENSAJES DE RESERVAS CON SWEETALERT ==================
document.addEventListener('DOMContentLoaded', function() {
    const mensajeDiv = document.getElementById('mensaje-data');
    if (mensajeDiv) {
        const mensaje = mensajeDiv.getAttribute('data-mensaje');
        const tipo = mensajeDiv.getAttribute('data-tipo');
        const icon = tipo === 'exito' ? 'success' : 'error';
        const title = tipo === 'exito' ? '¡Éxito!' : 'Error';
        
        Swal.fire({
            icon: icon,
            title: title,
            text: mensaje,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#4f7df3',
            timer: 3000,
            timerProgressBar: true
        });
        
        mensajeDiv.remove();
    }
});
// ================== MOSTRAR MENSAJES DE LOGIN ==================
document.addEventListener('DOMContentLoaded', function() {
    const mensajeDiv = document.getElementById('mensaje-data');
    if (mensajeDiv) {
        const mensaje = mensajeDiv.getAttribute('data-mensaje');
        const tipo = mensajeDiv.getAttribute('data-tipo');
        const icon = tipo === 'exito' ? 'success' : 'error';
        const title = tipo === 'exito' ? '¡Éxito!' : 'Error';
        
        Swal.fire({
            icon: icon,
            title: title,
            text: mensaje,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#4f7df3',
            timer: 3000,
            timerProgressBar: true
        });
        
        mensajeDiv.remove();
    }
});
// ================== CONFIRMACIÓN PARA ELIMINAR AULAS ==================
document.addEventListener('DOMContentLoaded', function() {
    const formsEliminarAula = document.querySelectorAll('.form-eliminar');
    
    formsEliminarAula.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nombreAula = form.getAttribute('data-nombre-aula');
            
            Swal.fire({
                title: '¿Desea eliminar?',
                text: `Esta acción eliminará ${nombreAula} permanentemente`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#4b5563',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});