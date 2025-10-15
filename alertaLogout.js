// ================== VERIFICAR SWEETALERT2 ==================
if (typeof Swal === 'undefined') {
    console.error('❌ SweetAlert2 no está cargado. Verifica que el CDN esté incluido en header.html');
}

// ================== MOSTRAR MENSAJES DE PHP ==================
document.addEventListener("DOMContentLoaded", () => {
    const overlay = document.querySelector(".form-overlay");
    if (overlay) {
        const mensaje = overlay.dataset.mensaje;
        const tipo = overlay.dataset.tipo;

        if (mensaje && typeof Swal !== 'undefined') {
            Swal.fire({
                icon: tipo === "success" ? "success" : "error",
                title: mensaje,
                timer: 3000,
                showConfirmButton: false
            });
        }
    }

    // ================== CONFIRMACIÓN PARA ELIMINAR USUARIOS ==================
    const formsEliminarUsuario = document.querySelectorAll('.form-eliminar-usuario');
    
    console.log(`✅ Se encontraron ${formsEliminarUsuario.length} formularios de eliminación`);
    
    formsEliminarUsuario.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Verificar que SweetAlert2 esté disponible
            if (typeof Swal === 'undefined') {
                console.error('SweetAlert2 no disponible');
                return;
            }
            
            // Obtener la cédula del usuario a eliminar
            const cedulaInput = form.querySelector('input[name="cedula"]');
            const cedula = cedulaInput ? cedulaInput.value : 'este usuario';
            
            console.log(`🗑️ Intentando eliminar usuario con cédula: ${cedula}`);
            
            Swal.fire({
                title: '¿Está seguro?',
                html: `Esta acción eliminará permanentemente al usuario<br><strong>Cédula: ${cedula}</strong>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#4b5563',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('✅ Usuario confirmó eliminación, enviando formulario...');
                    form.submit();
                } else {
                    console.log('❌ Usuario canceló la eliminación');
                }
            });
        });
    });
});

// ================== CONFIRMACIÓN AL GUARDAR ==================
document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('#editarUsuarioForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (typeof Swal === 'undefined') {
                form.submit();
                return;
            }
            
            Swal.fire({
                title: '¿Guardar cambios?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }
});

// ================== FUNCIONES AUXILIARES ==================
function postForm(data) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.style.display = 'none';
    
    var keys = Object.keys(data);
    for (var i = 0; i < keys.length; i++) {
        var key = keys[i];
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = data[key];
        form.appendChild(input);
    }
    document.body.appendChild(form);
    form.submit();
}

// ================== FUNCIONES CON SWEETALERT (COMPATIBILIDAD) ==================
function eliminarUsuario(cedula) {
    if (typeof Swal === 'undefined') {
        if (confirm('¿Está seguro de eliminar este usuario?')) {
            postForm({
                accion: 'eliminar',
                cedula: cedula
            });
        }
        return;
    }
    
    Swal.fire({
        title: '¿Está seguro?',
        html: `Esta acción eliminará permanentemente al usuario<br><strong>Cédula: ${cedula}</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#4b5563',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            postForm({
                accion: 'eliminar',
                cedula: cedula
            });
        }
    });
}

function cambiarEstado(cedula, estado) {
    if (typeof Swal === 'undefined') {
        if (confirm('¿Cambiar el estado del usuario?')) {
            postForm({
                accion: 'cambiar_estado',
                cedula: cedula,
                nuevo_estado: estado
            });
        }
        return;
    }
    
    Swal.fire({
        title: '¿Cambiar estado?',
        text: "El nuevo estado será: " + estado,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4f7df3',
        cancelButtonColor: '#4b5563',
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            postForm({
                accion: 'cambiar_estado',
                cedula: cedula,
                nuevo_estado: estado
            });
        }
    });
}

function editarUsuario(cedula) {
    window.location.href = 'adm_usr/editar_usr.php?cedula=' + cedula;
}

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
    const formsEliminar = document.querySelectorAll('form[action="docente_reservas.php"], form[action="docente_reservas_recursos.php"]');
    formsEliminar.forEach(form => {
        if (form.querySelector('input[name="eliminar_reserva"]') || form.querySelector('input[name="eliminar_reserva_recurso"]')) {
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

// ================== MOSTRAR MENSAJES CON SWEETALERT (INCLUYE DUPLICADOS) ==================
document.addEventListener('DOMContentLoaded', function() {
    const mensajeDiv = document.getElementById('mensaje-data');
    if (mensajeDiv) {
        const mensaje = mensajeDiv.getAttribute('data-mensaje');
        const tipo = mensajeDiv.getAttribute('data-tipo');
        
        // Determinar el ícono y título según el tipo
        let icon = 'info';
        let title = 'Información';
        let timerDuration = 3000;
        
        if (tipo === 'exito') {
            icon = 'success';
            title = '¡Éxito!';
        } else if (tipo === 'error_duplicado') {
            icon = 'warning';
            title = '⚠️ Reserva Duplicada';
            timerDuration = 4000; // Más tiempo para leer el mensaje
        } else if (tipo === 'error') {
            icon = 'error';
            title = 'Error';
        }
        
        Swal.fire({
            icon: icon,
            title: title,
            text: mensaje,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#4f7df3',
            timer: timerDuration,
            timerProgressBar: true,
            customClass: {
                popup: tipo === 'error_duplicado' ? 'swal-duplicado' : ''
            }
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