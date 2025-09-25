
// Selecciona el botón de cambio de tema y el elemento raíz del documento
const btnToggleTheme = document.getElementById('toggle-theme');
const root = document.documentElement;

// INICIALIZACIÓN DEL TEMA

// Si no hay tema guardado en localStorage, usar preferencia del sistema
if (!localStorage.getItem('theme')) {
    const prefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;
    root.setAttribute('data-theme', prefersLight ? 'light' : 'dark');
} else {
    // Si hay un tema guardado, aplicarlo
    root.setAttribute('data-theme', localStorage.getItem('theme'));
}

// EVENTO DE CAMBIO DE TEMA

btnToggleTheme.addEventListener('click', () => {
    const current = root.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';

    root.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
});