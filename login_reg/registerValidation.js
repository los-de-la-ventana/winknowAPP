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

    // Confirmación antes de enviar
    if (!confirm('¿Está seguro de que desea registrar este usuario?')) {
      e.preventDefault();
    }
  });
});