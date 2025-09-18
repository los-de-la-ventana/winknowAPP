document.addEventListener('DOMContentLoaded', () => {
  const selectTipo   = document.getElementById('operacion');
  const adminBlock   = document.getElementById('admin-form');
  const docenteBlock = document.getElementById('docente-form');
  const estBlock     = document.getElementById('estudiante-form');
  const form         = document.getElementById('registroForm');

  function setRequired(block, enable) {
    const inputs = block.querySelectorAll('input');
    inputs.forEach(inp => {
      if (enable) inp.setAttribute('required', 'true');
      else inp.removeAttribute('required');
    });
  }

  function toggle() {
    const val = selectTipo.value;

    adminBlock.style.display   = val === 'admin'      ? 'block' : 'none';
    docenteBlock.style.display = val === 'docente'    ? 'block' : 'none';
    estBlock.style.display     = val === 'estudiante' ? 'block' : 'none';

    setRequired(adminBlock,   val === 'admin');
    setRequired(docenteBlock, val === 'docente');
    setRequired(estBlock,     val === 'estudiante');
  }

  selectTipo.addEventListener('change', toggle);
  toggle();

  function validarCedula(cedula) {
    cedula = cedula.replace(/\D/g, '');
    if (cedula.length !== 8) return false;

    const coef = [2,9,8,7,6,3,4];
    let suma = 0;
    for (let i = 0; i < 7; i++) {
      suma += parseInt(cedula[i]) * coef[i];
    }
    const resto = suma % 10;
    const digitoVerificador = resto === 0 ? 0 : 10 - resto;

    return digitoVerificador === parseInt(cedula[7]);
  }

  function validarContrasenia(contra) {
  return /^(?=.*\d).{8,}$/.test(contra);
}
  function validarLongitud(valor, min, max) {
    return valor.length >= min && valor.length <= max;
  }

  form.addEventListener('submit', (e) => {
    const tipo = selectTipo.value;
    const bloque = tipo === 'admin' ? adminBlock :
                   tipo === 'docente' ? docenteBlock : estBlock;

    const nombre     = bloque.querySelector('input[name="nombre"]')?.value.trim();
    const contra     = bloque.querySelector('input[name="contra"]')?.value;
    const cedula     = bloque.querySelector('input[name="cedula"]')?.value;
    const telefono   = bloque.querySelector('input[name="telefono"]')?.value;
    const rolAdm     = bloque.querySelector('input[name="rolAdm"]')?.value;
    const estado     = bloque.querySelector('input[name="estado"]')?.value;

    let errores = [];
 

    if (!validarLongitud(nombre, 3, 50)) {
      errores.push("El nombre debe tener entre 3 y 50 caracteres.");
    }

    if (!validarLongitud(telefono, 7, 15)) {
      errores.push("El teléfono debe tener entre 7 y 15 caracteres.");
    }

    if (!validarContrasenia(contra)) {
      errores.push("La contraseña debe tener al menos 8 caracteres y contener al menos un número.");
    }

    if (tipo === 'admin' && !validarLongitud(rolAdm || '', 3, 30)) {
      errores.push("El rol de administrador debe tener entre 3 y 30 caracteres.");
    }

    if (tipo === 'docente' && !validarLongitud(estado || '', 3, 20)) {
      errores.push("El estado debe tener entre 3 y 20 caracteres.");
    }

    if (errores.length > 0) {
      e.preventDefault();
      alert(errores.join('\n'));
    }
  });
});