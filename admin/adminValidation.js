import Swal from 'sweetalert2'


// ================== MOSTRAR MENSAJES DE PHP ==================
document.addEventListener("DOMContentLoaded", () => {
    const overlay = document.querySelector(".form-overlay");
    if (overlay) {
        const mensaje = overlay.dataset.mensaje;
        const tipo = overlay.dataset.tipo;

        if (mensaje) {
            Swal.fire({
                icon: tipo === "success" ? "success" : "error",
                title: mensaje,
                timer: 3000,
                showConfirmButton: false
            });
        }
    }
});

// ================== CONFIRMACIÓN AL GUARDAR ==================
var form = document.querySelector('#editarUsuarioForm');
if (form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
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

// ================== FUNCIONES CON SWEETALERT ==================
function eliminarUsuario(cedula) {
    Swal.fire({
        title: '¿Está seguro?',
        text: "Esta acción eliminará el usuario.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            postForm({
                accion: 'eliminar',
                cedula: cedula
            });
        }
    });
}

function editarUsuario(cedula) {
    window.location.href = 'adm_usr/editar_usr.php?cedula=' + cedula;
}
