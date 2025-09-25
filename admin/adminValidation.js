/**
 * ARCHIVO: adminValidation.js
 * PROPOSITO: Validaciones del lado del cliente para WinKnow ITSP
 */

const CONFIG = {
    tiposEspacios: ['Salon', 'Aula', 'Taller'],
    pisos: ['Planta Baja', 'Primer Piso', 'Segundo Piso'],
    capacidades: [20, 25, 30, 35, 40, 60, 80],
    mensajes: {
        tipo: 'Debe seleccionar un tipo de espacio válido',
        piso: 'Debe seleccionar un piso válido',
        capacidad: 'La capacidad debe ser un número válido',
        filtros: 'Debe seleccionar al menos un filtro',
        espacios: '⚠️ No hay espacios cargados en el sistema'
    }
};

/* ================= VALIDACIONES ================= */

function validarTipo(val) {
    return !val || CONFIG.tiposEspacios.includes(val);
}
function validarPiso(val) {
    return !val || CONFIG.pisos.includes(val);
}
function validarCapacidad(val) {
    return !val || CONFIG.capacidades.includes(parseInt(val));
}

function validarFormulario(form) {
    const { tipo_salon, piso, capacidad } = form;

    if (!tipo_salon.value && !piso.value && !capacidad.value) {
        notificar(CONFIG.mensajes.filtros, 'error');
        return false;
    }
    if (tipo_salon.value && !validarTipo(tipo_salon.value)) {
        notificar(CONFIG.mensajes.tipo, 'error');
        return false;
    }
    if (piso.value && !validarPiso(piso.value)) {
        notificar(CONFIG.mensajes.piso, 'error');
        return false;
    }
    if (capacidad.value && !validarCapacidad(capacidad.value)) {
        notificar(CONFIG.mensajes.capacidad, 'error');
        return false;
    }

    notificar('Filtros aplicados correctamente', 'success');
    return true;
}

/* ================= ESTILOS Y NOTIFICACIONES ================= */

function aplicarEstilo(el, valido) {
    el.classList.toggle('valido', valido);
    el.classList.toggle('invalido', !valido);
}

function notificar(msg, tipo = 'info') {
    document.querySelector('.notificacion-admin')?.remove();
    const n = document.createElement('div');
    n.className = `notificacion-admin ${tipo}`;
    n.textContent = msg;
    document.body.appendChild(n);
    setTimeout(() => n.remove(), 5000);
    n.addEventListener('click', () => n.remove());
}

/* ================= FUNCIONES DE APOYO ================= */

function sanitizar(input) {
    return typeof input === 'string'
        ? input.trim().replace(/[<>'"&]/g, '').slice(0, 100)
        : '';
}
function rangoValido(num, min = 1, max = 100) {
    return !isNaN(num) && num >= min && num <= max;
}
function formatearCapacidad(cap) {
    const n = parseInt(cap);
    return isNaN(n) ? 'No especificada' : `${n} personas`;
}
function getURLParams() {
    const p = new URLSearchParams(location.search);
    return {
        tipo_salon: p.get('tipo_salon') || '',
        piso: p.get('piso') || '',
        capacidad: p.get('capacidad') || ''
    };
}

/* ================= EVENTOS PRINCIPALES ================= */

function bindEventos() {
    // Validar formulario filtros
    const form = document.querySelector('form[action="aulas.php"]');
    if (form) {
        form.addEventListener('submit', e => {
            if (!validarFormulario(form)) e.preventDefault();
        });
    }

    // Validación en tiempo real
    const reglas = {
        tipo_salon: validarTipo,
        piso: validarPiso,
        capacidad: validarCapacidad
    };

    Object.entries(reglas).forEach(([name, fn]) => {
        const select = document.querySelector(`select[name="${name}"]`);
        if (select) {
            select.addEventListener('change', () =>
                aplicarEstilo(select, fn(select.value))
            );
        }
    });

    // Marcar filtros activos (desde PHP o URL)
    document.querySelectorAll('select[name]').forEach(s => {
        if (s.value) aplicarEstilo(s, true);
    });

    // Verificar espacios cargados
    const grilla = document.querySelector('.grilla');
    if (grilla && !grilla.querySelector('.tarjeta-aula')) {
        const advertencia = document.createElement('div');
        advertencia.className = 'alert-warning';
        advertencia.innerHTML = `
            <h4>${CONFIG.mensajes.espacios}</h4>
            <p>Ejecute <strong>precargar_espacios.php</strong> para crear los 6 espacios reales del ITSP.</p>
        `;
        grilla.appendChild(advertencia);
    }
}

/* ================= INICIO ================= */

document.addEventListener('DOMContentLoaded', () => {
    console.log('Sistema WinKnow listo', CONFIG);
    console.log('Filtros URL:', getURLParams());
    bindEventos();
});

/* EXPORT (para Node o tests) */
if (typeof module !== 'undefined') {
    module.exports = {
        CONFIG,
        validarTipo,
        validarPiso,
        validarCapacidad,
        validarFormulario,
        aplicarEstilo,
        notificar,
        sanitizar,
        rangoValido,
        formatearCapacidad,
        getURLParams
    };
}
