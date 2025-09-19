document.addEventListener('DOMContentLoaded', () => {
  const selectTipo   = document.getElementById('operacion');
  const adminBlock   = document.getElementById('admin-form');
  const docenteBlock = document.getElementById('docente-form');
  const estBlock     = document.getElementById('estudiante-form');
  const form         = document.getElementById('registroForm');

  // Función para establecer campos como requeridos
  function setRequired(block, enable) {
    const inputs = block.querySelectorAll('input');
    inputs.forEach(inp => {
      if (enable) {
        inp.setAttribute('required', 'required');
      } else {
        inp.removeAttribute('required');
      }
    });
  }

  // Función para mostrar/ocultar bloques según el tipo seleccionado
  function toggle() {
    const val = selectTipo.value;
    
    // Ocultar todos los bloques y remover required
    adminBlock.style.display = 'none';
    docenteBlock.style.display = 'none';
    estBlock.style.display = 'none';
    
    setRequired(adminBlock, false);
    setRequired(docenteBlock, false);
    setRequired(estBlock, false);
    
    // Mostrar y activar el bloque correspondiente
    if (val === 'admin') {
      adminBlock.style.display = 'block';
      setRequired(adminBlock, true);
    } else if (val === 'docente') {
      docenteBlock.style.display = 'block';
      setRequired(docenteBlock, true);
    } else if (val === 'estudiante') {
      estBlock.style.display = 'block';
      setRequired(estBlock, true);
    }

    // Aplicar validaciones después de mostrar los bloques
    applyFieldValidations();
  }

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

    function createPasswordToggle(passwordInput) {
    if (passwordInput.dataset.toggleAdded) return;

    // Crear contenedor flexible
    const wrapper = document.createElement('div');
    wrapper.style.display = 'flex';
    wrapper.style.alignItems = 'center';
    wrapper.style.position = 'relative';
    wrapper.style.width = '100%';

    // Ajustar estilos del input para que no se rompa el diseño
    passwordInput.style.flex = '1';

    // Insertar el input dentro del wrapper
    passwordInput.parentNode.insertBefore(wrapper, passwordInput);
    wrapper.appendChild(passwordInput);

    // Crear botón emoji
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

    // Estado de visibilidad
    let isVisible = false;
    toggleButton.addEventListener('click', () => {
      passwordInput.type = isVisible ? 'password' : 'text';
      toggleButton.textContent = isVisible ? '👁️' : '🙈';
      isVisible = !isVisible;
    });

    wrapper.appendChild(toggleButton);
    passwordInput.dataset.toggleAdded = "true";
  }

  // Ejecutar al cargar la página
  window.addEventListener('DOMContentLoaded', () => {
    const passwordField = document.querySelector('input[name="contra"]');
    if (passwordField) {
      createPasswordToggle(passwordField);
    }
  });

  // Función para aplicar todas las validaciones
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

  // Event listener para el cambio de tipo
  selectTipo.addEventListener('change', toggle);
  toggle(); // Ejecutar al cargar

  // Validación básica del formulario
  form.addEventListener('submit', (e) => {
    const tipo = selectTipo.value;
    
    if (!tipo) {
      e.preventDefault();
      alert('Por favor seleccione un tipo de usuario.');
      return;
    }

    // Validación adicional de cédula
    const activeBlock = document.querySelector('.input-field[style*="block"]');
    if (activeBlock) {
      const cedulaInput = activeBlock.querySelector('input[name="cedula"]');
      if (cedulaInput && cedulaInput.value.length < 7) {
        e.preventDefault();
        alert('La cédula debe tener al menos 7 dígitos.');
        return;
      }

      // Validación de teléfono
      const telefonoInput = activeBlock.querySelector('input[name="telefono"]');
      if (telefonoInput && telefonoInput.value.length < 8) {
        e.preventDefault();
        alert('El teléfono debe tener al menos 8 dígitos.');
        return;
      }
    }

    // Confirmación antes de enviar
    if (!confirm('¿Está seguro de que desea registrar este usuario?')) {
      e.preventDefault();
    }
  });

  // Aplicar validaciones iniciales
  setTimeout(() => {
    applyFieldValidations();
  }, 100);
});