/**
 * ARCHIVO: adminValidation.js
 * PROPOSITO: Validaciones del lado del cliente para el sistema WinKnow
 * EXTRAIDO DE: aulas.php - Validaciones de filtros y formularios
 * PARA: Administradores del sistema WinKnow ITSP
 */

// CONFIGURACION GLOBAL DE VALIDACIONES
const CONFIG_VALIDACION = {
    // Tipos de espacios permitidos segun ITSP
    tiposEspaciosValidos: ['Salon', 'Aula', 'Taller'],
    
    // Pisos disponibles en el edificio del ITSP
    pisosValidos: ['Planta Baja', 'Primer Piso', 'Segundo Piso'],
    
    // Capacidades tipicas segun entrevistas
    capacidadesValidas: [20, 25, 30, 35, 40, 60, 80],
    
    // Mensajes de error personalizados
    mensajes: {
        tipoInvalido: 'Debe seleccionar un tipo de espacio valido',
        pisoInvalido: 'Debe seleccionar un piso valido',
        capacidadInvalida: 'La capacidad debe ser un numero valido',
        formularioIncompleto: 'Complete todos los campos requeridos',
        filtroVacio: 'Debe seleccionar al menos un filtro para aplicar'
    }
};

// CLASE PRINCIPAL DE VALIDACIONES
class AdminValidator {
    
    constructor() {
        this.inicializarValidaciones();
        this.configurarEventos();
    }
    
    /**
     * Inicializar sistema de validaciones al cargar la pagina
     */
    inicializarValidaciones() {
        console.log('Inicializando validaciones WinKnow...');
        this.validarFormularioAlCargar();
        this.configurarValidacionEnTiempoReal();
    }
    
    /**
     * Configurar eventos de validacion para formularios
     */
    configurarEventos() {
        // Validar formulario de filtros antes de enviar
        const formFiltros = document.querySelector('form[action="aulas.php"]');
        if (formFiltros) {
            formFiltros.addEventListener('submit', (e) => {
                if (!this.validarFormularioFiltros(e)) {
                    e.preventDefault();
                }
            });
        }
        
        // Validacion en tiempo real para selects
        this.configurarValidacionSelects();
        
        // Validar botones de accion
        this.configurarValidacionBotones();
    }
    
    /**
     * Validar formulario de filtros antes de envio
     * @param {Event} evento - Evento de envio del formulario
     * @return {boolean} - true si es valido, false si no
     */
    validarFormularioFiltros(evento) {
        const form = evento.target;
        const tipoSalon = form.querySelector('select[name="tipo_salon"]')?.value || '';
        const piso = form.querySelector('select[name="piso"]')?.value || '';
        const capacidad = form.querySelector('select[name="capacidad"]')?.value || '';
        
        // Verificar que al menos un filtro este seleccionado
        if (!tipoSalon && !piso && !capacidad) {
            this.mostrarError(CONFIG_VALIDACION.mensajes.filtroVacio);
            return false;
        }
        
        // Validar tipo de salon si esta seleccionado
        if (tipoSalon && !this.validarTipoEspacio(tipoSalon)) {
            this.mostrarError(CONFIG_VALIDACION.mensajes.tipoInvalido);
            return false;
        }
        
        // Validar piso si esta seleccionado
        if (piso && !this.validarPiso(piso)) {
            this.mostrarError(CONFIG_VALIDACION.mensajes.pisoInvalido);
            return false;
        }
        
        // Validar capacidad si esta seleccionada
        if (capacidad && !this.validarCapacidad(capacidad)) {
            this.mostrarError(CONFIG_VALIDACION.mensajes.capacidadInvalida);
            return false;
        }
        
        // Todo valido
        this.mostrarExito('Filtros aplicados correctamente');
        return true;
    }
    
    /**
     * Validar que el tipo de espacio sea valido segun ITSP
     * @param {string} tipo - Tipo de espacio seleccionado
     * @return {boolean} - true si es valido
     */
    validarTipoEspacio(tipo) {
        if (!tipo || tipo.trim() === '') return true; // Vacio es valido (todos)
        return CONFIG_VALIDACION.tiposEspaciosValidos.includes(tipo);
    }
    
    /**
     * Validar que el piso sea valido segun estructura del ITSP
     * @param {string} piso - Piso seleccionado
     * @return {boolean} - true si es valido
     */
    validarPiso(piso) {
        if (!piso || piso.trim() === '') return true; // Vacio es valido (todos)
        return CONFIG_VALIDACION.pisosValidos.includes(piso);
    }
    
    /**
     * Validar que la capacidad sea un numero valido
     * @param {string|number} capacidad - Capacidad seleccionada
     * @return {boolean} - true si es valido
     */
    validarCapacidad(capacidad) {
        if (!capacidad || capacidad === '') return true; // Vacio es valido (cualquiera)
        
        const cap = parseInt(capacidad);
        if (isNaN(cap) || cap <= 0) return false;
        
        return CONFIG_VALIDACION.capacidadesValidas.includes(cap);
    }
    
    /**
     * Configurar validacion en tiempo real para elementos select
     */
    configurarValidacionSelects() {
        // Select de tipo de salon
        const selectTipo = document.querySelector('select[name="tipo_salon"]');
        if (selectTipo) {
            selectTipo.addEventListener('change', (e) => {
                this.validarSelectEnTiempoReal(e.target, this.validarTipoEspacio.bind(this));
            });
        }
        
        // Select de piso
        const selectPiso = document.querySelector('select[name="piso"]');
        if (selectPiso) {
            selectPiso.addEventListener('change', (e) => {
                this.validarSelectEnTiempoReal(e.target, this.validarPiso.bind(this));
            });
        }
        
        // Select de capacidad
        const selectCapacidad = document.querySelector('select[name="capacidad"]');
        if (selectCapacidad) {
            selectCapacidad.addEventListener('change', (e) => {
                this.validarSelectEnTiempoReal(e.target, this.validarCapacidad.bind(this));
            });
        }
    }
    
    /**
     * Validar un select en tiempo real
     * @param {HTMLSelectElement} select - Elemento select
     * @param {Function} validador - Funcion de validacion
     */
    validarSelectEnTiempoReal(select, validador) {
        const valor = select.value;
        const esValido = validador(valor);
        
        // Cambiar estilo visual segun validacion
        if (esValido) {
            this.marcarElementoValido(select);
        } else {
            this.marcarElementoInvalido(select);
        }
    }
    
    /**
     * Configurar validaciones para botones de accion
     */
    configurarValidacionBotones() {
        // Boton de aplicar filtros
        const btnAplicar = document.querySelector('button[type="submit"]');
        if (btnAplicar) {
            btnAplicar.addEventListener('click', () => {
                this.animarBoton(btnAplicar);
            });
        }
        
        // Validar que existan espacios antes de permitir filtrado
        this.validarExistenciaEspacios();
    }
    
    /**
     * Validar que existan espacios en la base de datos
     */
    validarExistenciaEspacios() {
        const grilla = document.querySelector('.grilla');
        const tarjetasAulas = grilla?.querySelectorAll('.tarjeta-aula');
        
        if (!tarjetasAulas || tarjetasAulas.length === 0) {
            this.mostrarAdvertenciaEspaciosVacios();
        }
    }
    
    /**
     * Mostrar advertencia cuando no hay espacios cargados
     */
    mostrarAdvertenciaEspaciosVacios() {
        const grilla = document.querySelector('.grilla');
        if (grilla && !grilla.querySelector('.advertencia-espacios')) {
            const advertencia = document.createElement('div');
            advertencia.className = 'advertencia-espacios';
            advertencia.style.cssText = `
                background-color: #fff3cd;
                border: 1px solid #ffeaa7;
                color: #856404;
                padding: 20px;
                border-radius: 8px;
                margin: 20px 0;
                text-align: center;
            `;
            advertencia.innerHTML = `
                <h4>⚠️ No hay espacios cargados en el sistema</h4>
                <p>Para comenzar a usar el sistema, ejecute el archivo <strong>precargar_espacios.php</strong></p>
                <p>Este archivo creara los 6 espacios reales del ITSP segun las entrevistas realizadas.</p>
            `;
            grilla.appendChild(advertencia);
        }
    }
    
    /**
     * Validar formulario al cargar la pagina
     */
    validarFormularioAlCargar() {
        // Verificar si hay filtros activos aplicados desde PHP
        const filtrosActivos = document.querySelector('.filtros-activos');
        if (filtrosActivos) {
            console.log('Filtros detectados al cargar la pagina');
            this.validarFiltrosActivos();
        }
    }
    
    /**
     * Validar filtros que vienen activos desde PHP
     */
    validarFiltrosActivos() {
        const selects = document.querySelectorAll('select[name^="tipo"], select[name^="piso"], select[name^="capacidad"]');
        selects.forEach(select => {
            if (select.value && select.value !== '') {
                this.marcarElementoValido(select);
            }
        });
    }
    
    /**
     * Marcar elemento como valido visualmente
     * @param {HTMLElement} elemento - Elemento a marcar
     */
    marcarElementoValido(elemento) {
        elemento.style.borderColor = '#28a745';
        elemento.style.backgroundColor = '#f8fff8';
        elemento.classList.remove('invalido');
        elemento.classList.add('valido');
    }
    
    /**
     * Marcar elemento como invalido visualmente
     * @param {HTMLElement} elemento - Elemento a marcar
     */
    marcarElementoInvalido(elemento) {
        elemento.style.borderColor = '#dc3545';
        elemento.style.backgroundColor = '#fff8f8';
        elemento.classList.remove('valido');
        elemento.classList.add('invalido');
    }
    
    /**
     * Mostrar mensaje de error al usuario
     * @param {string} mensaje - Mensaje de error
     */
    mostrarError(mensaje) {
        this.mostrarNotificacion(mensaje, 'error');
    }
    
    /**
     * Mostrar mensaje de exito al usuario
     * @param {string} mensaje - Mensaje de exito
     */
    mostrarExito(mensaje) {
        this.mostrarNotificacion(mensaje, 'exito');
    }
    
    /**
     * Sistema de notificaciones unificado
     * @param {string} mensaje - Mensaje a mostrar
     * @param {string} tipo - Tipo de notificacion (error, exito, advertencia)
     */
    mostrarNotificacion(mensaje, tipo = 'info') {
        // Remover notificacion anterior si existe
        const notificacionAnterior = document.querySelector('.notificacion-admin');
        if (notificacionAnterior) {
            notificacionAnterior.remove();
        }
        
        // Crear nueva notificacion
        const notificacion = document.createElement('div');
        notificacion.className = 'notificacion-admin';
        
        // Estilos segun tipo
        let colores = {
            'error': { bg: '#f8d7da', border: '#f5c6cb', text: '#721c24' },
            'exito': { bg: '#d4edda', border: '#c3e6cb', text: '#155724' },
            'advertencia': { bg: '#fff3cd', border: '#ffeaa7', text: '#856404' },
            'info': { bg: '#d1ecf1', border: '#bee5eb', text: '#0c5460' }
        };
        
        const color = colores[tipo] || colores['info'];
        
        notificacion.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: ${color.bg};
            border: 1px solid ${color.border};
            color: ${color.text};
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            z-index: 9999;
            max-width: 400px;
            font-weight: 500;
        `;
        
        notificacion.textContent = mensaje;
        document.body.appendChild(notificacion);
        
        // Auto-remover despues de 5 segundos
        setTimeout(() => {
            if (notificacion.parentNode) {
                notificacion.remove();
            }
        }, 5000);
        
        // Permitir cerrar haciendo clic
        notificacion.addEventListener('click', () => {
            notificacion.remove();
        });
        
        notificacion.style.cursor = 'pointer';
        notificacion.title = 'Clic para cerrar';
    }
    
    /**
     * Animacion para botones al hacer clic
     * @param {HTMLButtonElement} boton - Boton a animar
     */
    animarBoton(boton) {
        boton.style.transform = 'scale(0.95)';
        setTimeout(() => {
            boton.style.transform = 'scale(1)';
        }, 100);
    }
}

// FUNCIONES DE UTILIDAD GLOBALES

/**
 * Sanitizar input del usuario para prevenir XSS basico
 * @param {string} input - Texto a sanitizar
 * @return {string} - Texto sanitizado
 */
function sanitizarInput(input) {
    if (typeof input !== 'string') return '';
    return input
        .trim()
        .replace(/[<>'"&]/g, '') // Remover caracteres peligrosos basicos
        .substring(0, 100); // Limitar longitud
}

/**
 * Validar que un numero este en un rango valido
 * @param {number} numero - Numero a validar
 * @param {number} min - Minimo permitido
 * @param {number} max - Maximo permitido
 * @return {boolean} - true si esta en rango
 */
function validarRangoNumero(numero, min = 1, max = 100) {
    return !isNaN(numero) && numero >= min && numero <= max;
}

/**
 * Formatear capacidad para mostrar consistentemente
 * @param {number} capacidad - Numero de capacidad
 * @return {string} - Capacidad formateada
 */
function formatearCapacidad(capacidad) {
    const cap = parseInt(capacidad);
    if (isNaN(cap)) return 'No especificada';
    return cap + ' personas';
}

/**
 * Obtener parametros URL actuales para mantener estado de filtros
 * @return {Object} - Objeto con parametros actuales
 */
function obtenerParametrosURL() {
    const params = new URLSearchParams(window.location.search);
    return {
        tipo_salon: params.get('tipo_salon') || '',
        piso: params.get('piso') || '',
        capacidad: params.get('capacidad') || ''
    };
}

// INICIALIZAR SISTEMA AL CARGAR LA PAGINA
document.addEventListener('DOMContentLoaded', () => {
    console.log('Iniciando sistema de validaciones WinKnow ITSP...');
    
    // Crear instancia del validador
    window.adminValidator = new AdminValidator();
    
    // Mostrar informacion de sistema en consola
    console.log('Sistema WinKnow cargado correctamente');
    console.log('Espacios permitidos:', CONFIG_VALIDACION.tiposEspaciosValidos);
    console.log('Pisos disponibles:', CONFIG_VALIDACION.pisosValidos);
    console.log('Capacidades validas:', CONFIG_VALIDACION.capacidadesValidas);
    
    // Verificar parametros URL actuales
    const params = obtenerParametrosURL();
    if (params.tipo_salon || params.piso || params.capacidad) {
        console.log('Filtros detectados en URL:', params);
    }
});

// EXPORTAR PARA USO EN OTROS ARCHIVOS (si se necesita)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        AdminValidator,
        CONFIG_VALIDACION,
        sanitizarInput,
        validarRangoNumero,
        formatearCapacidad,
        obtenerParametrosURL
    };
}