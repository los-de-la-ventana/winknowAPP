// Esperar a que cargue todo el DOM
document.addEventListener('DOMContentLoaded', function () {

  /**
   * Función para validar que un input solo acepte números
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
   */
  function inicializarFormularioRegistro() {
    var domMenuSeleccion = document.getElementById('operacion');
    var domDivDeInputs = document.getElementById('divDeInputs');

    function updateInputs() {
      var opcionSeleccionada = domMenuSeleccion.value;
      domDivDeInputs.innerHTML = '';

      console.log('Opción seleccionada:', opcionSeleccionada);

      // Clonar el contenido del template según la opción seleccionada
      if (opcionSeleccionada === 'docente') {
        var docenteTemplate = document.getElementById('template-docente');
        
        if (docenteTemplate) {
          var templateContent = null;
          
          // Método 1: Usar .content (estándar HTML5)
          if (docenteTemplate.content) {
            templateContent = docenteTemplate.content.cloneNode(true);
            console.log('Método 1: Usando template.content');
          }
          // Método 2: Buscar el div interno directamente
          else if (docenteTemplate.querySelector) {
            var innerDiv = docenteTemplate.querySelector('#docente-form');
            if (innerDiv) {
              templateContent = innerDiv.cloneNode(true);
              console.log('Método 2: Usando querySelector interno');
            }
          }
          // Método 3: Clonar usando innerHTML
          else {
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = docenteTemplate.innerHTML;
            templateContent = tempDiv.firstElementChild;
            console.log('Método 3: Usando innerHTML');
          }

          if (templateContent) {
            domDivDeInputs.appendChild(templateContent);
            console.log('✅ Template docente insertado');
          } else {
            console.error('❌ No se pudo clonar el template de docente');
          }
        } else {
          console.error('❌ No se encontró el template de docente');
        }
      }
      else if (opcionSeleccionada === 'estudiante') {
        var estudianteTemplate = document.getElementById('template-estudiante');
        
        if (estudianteTemplate) {
          var templateContent = null;
          
          // Método 1: Usar .content (estándar HTML5)
          if (estudianteTemplate.content) {
            templateContent = estudianteTemplate.content.cloneNode(true);
            console.log('Método 1: Usando template.content');
          }
          // Método 2: Buscar el div interno directamente
          else if (estudianteTemplate.querySelector) {
            var innerDiv = estudianteTemplate.querySelector('#estudiante-form');
            if (innerDiv) {
              templateContent = innerDiv.cloneNode(true);
              console.log('Método 2: Usando querySelector interno');
            }
          }
          // Método 3: Clonar usando innerHTML
          else {
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = estudianteTemplate.innerHTML;
            templateContent = tempDiv.firstElementChild;
            console.log('Método 3: Usando innerHTML');
          }

          if (templateContent) {
            domDivDeInputs.appendChild(templateContent);
            console.log('✅ Template estudiante insertado');
          } else {
            console.error('❌ No se pudo clonar el template de estudiante');
          }
        } else {
          console.error('❌ No se encontró el template de estudiante');
        }
      }

      // Aplicar validaciones a los nuevos inputs insertados
      applyFieldValidations();
    }

    if (domMenuSeleccion && domDivDeInputs) {
      domMenuSeleccion.addEventListener('change', updateInputs);
      updateInputs();
    } else {
      console.error('❌ No se encontraron elementos del formulario');
      console.log('domMenuSeleccion:', domMenuSeleccion);
      console.log('domDivDeInputs:', domDivDeInputs);
    }
  }

  /**
   * Función que crea el botón para mostrar/ocultar contraseña
   */
  function createPasswordToggle(passwordInput) {
    if (passwordInput.dataset.toggleAdded) {
      return;
    }

    var wrapper = document.createElement('div');
    wrapper.className = 'password-wrapper';

    passwordInput.parentNode.insertBefore(wrapper, passwordInput);
    wrapper.appendChild(passwordInput);

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
    console.log('🔧 Aplicando validaciones...');
    
    var cedulaInputs = document.querySelectorAll('input[name="cedula"]');
    console.log('Inputs de cédula encontrados:', cedulaInputs.length);
    cedulaInputs.forEach(function (input) {
      if (input.offsetParent !== null) {
        validateNumericInput(input);
      }
    });

    var telefonoInputs = document.querySelectorAll('input[name="telefono"]');
    console.log('Inputs de teléfono encontrados:', telefonoInputs.length);
    telefonoInputs.forEach(function (input) {
      if (input.offsetParent !== null) {
        validateNumericInput(input);
      }
    });

    var passwordInputs = document.querySelectorAll('input[name="contra"]');
    console.log('Inputs de contraseña encontrados:', passwordInputs.length);
    passwordInputs.forEach(function (input) {
      if (input.offsetParent !== null) {
        createPasswordToggle(input);
      }
    });
    
    console.log('✅ Validaciones aplicadas');
  }

  console.log('🚀 Iniciando registerValidation.js');
  inicializarFormularioRegistro();
  console.log('✅ registerValidation.js cargado');
});