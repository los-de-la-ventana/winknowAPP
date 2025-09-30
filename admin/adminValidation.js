

// Mostrar campos específicos según tipo de usuario
function mostrarCamposEspecificos(tipo) {
    var tipos = ['docente', 'admin', 'estudiante'];
    var i;
    
    for (i = 0; i < tipos.length; i++) {
        var elemento = document.getElementById('campos-' + tipos[i]);
        if (elemento) {
            elemento.style.display = 'none';
        }
    }
    
    if (tipo) {
        var campoActivo = document.getElementById('campos-' + tipo);
        if (campoActivo) {
            campoActivo.style.display = 'block';
        }
    }
}

// Crear y enviar formulario POST
function postForm(data) {
    var form = document.createElement('form');
    form.method = 'POST';
    form.style.display = 'none';
    
    var keys = Object.keys(data);
    var i;
    
    for (i = 0; i < keys.length; i++) {
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

// Eliminar usuario
function eliminarUsuario(cedula) {
    if (confirm('¿Está seguro de eliminar este usuario?')) {
        postForm({
            accion: 'eliminar',
            cedula: cedula
        });
    }
}

// Cambiar estado del docente
function cambiarEstado(cedula, estado) {
    if (confirm('¿Cambiar estado a ' + estado + '?')) {
        postForm({
            accion: 'cambiar_estado',
            cedula: cedula,
            nuevo_estado: estado
        });
    }
}

// Editar usuario
function editarUsuario(cedula) {
    window.location.href = 'adm_usr/editar_usr.php?cedula=' + cedula;
}

// Inicializar página
function inicializarPagina() {
    console.log('Sistema de usuarios cargado');
}

// Ejecutar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', inicializarPagina);
} else {
    inicializarPagina();
}
