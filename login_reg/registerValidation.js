document.addEventListener('DOMContentLoaded', function () {

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

  function inicializarFormularioRegistro() {
    var domMenuSeleccion = document.getElementById('operacion');
    var domDivDeInputs = document.getElementById('divDeInputs');

    if (!domMenuSeleccion || !domDivDeInputs) {
      console.error('No se encontraron elementos necesarios');
      return;
    }

    function updateInputs() {
      var opcionSeleccionada = domMenuSeleccion.value;
      domDivDeInputs.innerHTML = '';

      console.log('Opción seleccionada:', opcionSeleccionada);

      if (opcionSeleccionada === 'docente') {
        var docenteTemplate = document.getElementById('template-docente');
        console.log('Template docente encontrado:', docenteTemplate);
        
        if (docenteTemplate) {
          var content = docenteTemplate.content || docenteTemplate;
          var clone = content.cloneNode(true);
          domDivDeInputs.appendChild(clone);
          console.log('Template docente insertado');
        } else {
          console.error('No se encontró el template de docente');
        }
      }
      else if (opcionSeleccionada === 'estudiante') {
        var estudianteTemplate = document.getElementById('template-estudiante');
        console.log('Template estudiante encontrado:', estudianteTemplate);
        
        if (estudianteTemplate) {
          var content = estudianteTemplate.content || estudianteTemplate;
          var clone = content.cloneNode(true);
          domDivDeInputs.appendChild(clone);
          console.log('Template estudiante insertado');
        } else {
          console.error('No se encontró el template de estudiante');
        }
      }

      applyFieldValidations();
    }

    domMenuSeleccion.addEventListener('change', updateInputs);
    updateInputs();
  }

  function createPasswordToggle(passwordInput) {
    if (passwordInput.dataset.toggleAdded) {
      return;
    }

    var wrapper = document.createElement('div');
    wrapper.className = 'password-wrapper';
    wrapper.style.cssText = 'display: flex; align-items: center; position: relative; width: 100%;';

    passwordInput.parentNode.insertBefore(wrapper, passwordInput);
    wrapper.appendChild(passwordInput);

    var toggleButton = document.createElement('button');
    toggleButton.type = 'button';
    toggleButton.className = 'password-toggle-btn';
    toggleButton.textContent = '👁️';
    toggleButton.style.cssText = 'margin-left: 8px; font-size: 20px; background: transparent; border: none; cursor: pointer; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);';

    var isVisible = false;

    toggleButton.addEventListener('click', function () {
      passwordInput.type = isVisible ? 'password' : 'text';
      toggleButton.textContent = isVisible ? '👁️' : '🙈';
      isVisible = !isVisible;
    });

    wrapper.appendChild(toggleButton);
    passwordInput.dataset.toggleAdded = "true";
  }

  function applyFieldValidations() {
    console.log('Aplicando validaciones...');
    
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
  }

  inicializarFormularioRegistro();
});