document.addEventListener('DOMContentLoaded', () => {
  // Función para validar solo números
  function validateNumericInput(input) {
    input.addEventListener('input', (e) => {
      e.target.value = e.target.value.replace(/[^0-9]/g, '');
    });

    input.addEventListener('keypress', (e) => {
      if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
        e.preventDefault();
      }
    });

    input.addEventListener('paste', (e) => {
      e.preventDefault();
      const pastedText = (e.clipboardData || window.clipboardData).getData('text');
      const numericText = pastedText.replace(/[^0-9]/g, '');
      input.value = numericText;
    });
  }

  // Función para inicializar el formulario de registro
  function inicializarFormularioRegistro() {
    const domMenuSeleccion = document.getElementById('operacion');
    const domDivDeInputs = document.getElementById('divDeInputs');

    function updateInputs() {
      const opcionSeleccionada = domMenuSeleccion.value;
      domDivDeInputs.innerHTML = '';

      if (opcionSeleccionada === 'admin') {
        const adminTemplate = document.getElementById('template-admin');
        if (adminTemplate && adminTemplate.content) {
          domDivDeInputs.appendChild(adminTemplate.content.cloneNode(true));
        }
      }
      else if (opcionSeleccionada === 'docente') {
        const docenteTemplate = document.getElementById('template-docente');
        if (docenteTemplate && docenteTemplate.content) {
          domDivDeInputs.appendChild(docenteTemplate.content.cloneNode(true));
        }
      }
      else if (opcionSeleccionada === 'estudiante') {
        const estudianteTemplate = document.getElementById('template-estudiante');
        if (estudianteTemplate && estudianteTemplate.content) {
          domDivDeInputs.appendChild(estudianteTemplate.content.cloneNode(true));
        }
      }

      // Aplicar validaciones a los nuevos inputs
      applyFieldValidations();
    }

    if (domMenuSeleccion && domDivDeInputs) {
      domMenuSeleccion.addEventListener('change', updateInputs);
      updateInputs();
    }
  }

  function createPasswordToggle(passwordInput) {
    if (passwordInput.dataset.toggleAdded) return;

    const wrapper = document.createElement('div');
    wrapper.style.display = 'flex';
    wrapper.style.alignItems = 'center';
    wrapper.style.position = 'relative';
    wrapper.style.width = '100%';

    passwordInput.style.flex = '1';

    passwordInput.parentNode.insertBefore(wrapper, passwordInput);
    wrapper.appendChild(passwordInput);

    const toggleButton = document.createElement('button');
    toggleButton.type = 'button';
    toggleButton.textContent = '👁️';
    toggleButton.style.marginLeft = '8px';
    toggleButton.style.fontSize = '20px';
    toggleButton.style.background = 'transparent';
    toggleButton.style.border = 'none';
    toggleButton.style.cursor = 'pointer';
    toggleButton.style.position = 'absolute';
    toggleButton.style.right = '10px';
    toggleButton.style.top = '50%';
    toggleButton.style.transform = 'translateY(-50%)';

    let isVisible = false;
    toggleButton.addEventListener('click', () => {
      passwordInput.type = isVisible ? 'password' : 'text';
      toggleButton.textContent = isVisible ? '👁️' : '🙈';
      isVisible = !isVisible;
    });

    wrapper.appendChild(toggleButton);
    passwordInput.dataset.toggleAdded = "true";
  }

  function applyFieldValidations() {
    // Validar campos de cédula (solo números)
    const cedulaInputs = document.querySelectorAll('input[name="cedula"]');
    cedulaInputs.forEach(input => {
      if (input.offsetParent !== null) {
        validateNumericInput(input);
      }
    });

    // Validar campos de teléfono (solo números)
    const telefonoInputs = document.querySelectorAll('input[name="telefono"]');
    telefonoInputs.forEach(input => {
      if (input.offsetParent !== null) {
        validateNumericInput(input);
      }
    });

    // Agregar toggle a campos de contraseña
    const passwordInputs = document.querySelectorAll('input[name="contra"]');
    passwordInputs.forEach(input => {
      if (input.offsetParent !== null) {
        createPasswordToggle(input);
      }
    });
  }

  // Inicializar formulario
  inicializarFormularioRegistro();
});