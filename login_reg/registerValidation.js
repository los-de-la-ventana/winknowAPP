// Esperar a que cargue todo el DOM
document.addEventListener('DOMContentLoaded', function () {

  /**
   * Función para validar que un input solo acepte números
   * Elimina caracteres no numéricos en input, bloquea teclas no válidas y controla el pegado de texto
   */
  function validateNumericInput(input) {
    input.addEventListener('input', function (e) {
      e.target.value = e.target.value.replace(/[^0-9]/g, '');
    });

    input.addEventListener('keypress', function (e) {
      var allowedKeys = ['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight'];
      if (!/[0-9]/.test(e.key) && allowedKeys.indexOf(e.key) === -1) {
        e.preventDefault();
      }
    });

    input.addEventListener('paste', function (e) {
      e.preventDefault();
      var pastedText = (e.clipboardData || window.clipboardData).getData('text');
      var numericText = pastedText.replace(/[^0-9]/g, '');
      input.value = numericText;
    });
  }

  /**
   * Función que inicializa el formulario y carga los inputs dinámicos
   * dependiendo de la opción seleccionada en el menú
   */
  function inicializarFormularioRegistro() {
    var domMenuSeleccion = document.getElementById('operacion');
    var domDivDeInputs = document.getElementById('divDeInputs');

    function updateInputs() {
      var opcionSeleccionada = domMenuSeleccion.value;
      domDivDeInputs.innerHTML = '';

      // Clonar el contenido del template según la opción seleccionada
      if (opcionSeleccionada === 'docente') {
        var docenteTemplate = document.getElementById('template-docente');
        if (docenteTemplate && docenteTemplate.content) {
          domDivDeInputs.appendChild(docenteTemplate.content.cloneNode(true));
        }
      }
      else if (opcionSeleccionada === 'estudiante') {
        var estudianteTemplate = document.getElementById('template-estudiante');
        if (estudianteTemplate && estudianteTemplate.content) {
          domDivDeInputs.appendChild(estudianteTemplate.content.cloneNode(true));
        }
      }

      // Aplicar validaciones a los nuevos inputs insertados
      applyFieldValidations();
    }

    if (domMenuSeleccion && domDivDeInputs) {
      domMenuSeleccion.addEventListener('change', updateInputs);
      updateInputs(); // Ejecutar al inicio
    }
  }

  /**
   * Función que crea el botón para mostrar/ocultar contraseña
   */
  function createPasswordToggle(passwordInput) {
    if (passwordInput.dataset.toggleAdded) {
      return;
    }

    // Crear contenedor flexible
    var wrapper = document.createElement('div');
    wrapper.className = 'password-wrapper';

    // Insertar el wrapper y meter el input dentro
    passwordInput.parentNode.insertBefore(wrapper, passwordInput);
    wrapper.appendChild(passwordInput);

    // Crear botón de alternar visibilidad
    var toggleButton = document.createElement('button');
    toggleButton.type = 'button';
    toggleButton.className = 'password-toggle-btn';
    toggleButton.textContent = '👁️';

    var isVisible = false;

    toggleButton.addEventListener('click', function () {
      passwordInput.type = isVisible ? 'password' : 'text';
      toggleButton.textContent = isVisible ? '👁️' : '🙈';
      isVisible = !isVisible;
    });

    wrapper.appendChild(toggleButton);
    passwordInput.dataset.toggleAdded = "true";
  }

  /**
   * Función que aplica validaciones específicas a campos según su nombre
   */
  function applyFieldValidations() {
    // Validación de cédula: solo números
    var cedulaInputs = document.querySelectorAll('input[name="cedula"]');
    cedulaInputs.forEach(function (input) {
      if (input.offsetParent !== null) {
        validateNumericInput(input);
      }
    });

    // Validación de teléfono: solo números
    var telefonoInputs = document.querySelectorAll('input[name="telefono"]');
    telefonoInputs.forEach(function (input) {
      if (input.offsetParent !== null) {
        validateNumericInput(input);
      }
    });

    // Agregar toggle de contraseña
    var passwordInputs = document.querySelectorAll('input[name="contra"]');
    passwordInputs.forEach(function (input) {
      if (input.offsetParent !== null) {
        createPasswordToggle(input);
      }
    });
  }

  // Inicializar formulario al cargar la página
  inicializarFormularioRegistro();
});