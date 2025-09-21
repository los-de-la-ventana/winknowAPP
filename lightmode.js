// Selecciona el botón de cambio de tema y el elemento raíz del documento
const btn = document.getElementById('toggle-theme');
const root = document.documentElement;

// Si no hay tema guardado, usa la preferencia del sistema (claro u oscuro)
if (!localStorage.getItem('theme')) {
    if (window.matchMedia('(prefers-color-scheme: light)').matches) {
        root.setAttribute('data-theme', 'light');
    } else {
        root.setAttribute('data-theme', 'dark');
    }
} else {
    // Si hay tema guardado, lo aplica
    root.setAttribute('data-theme', localStorage.getItem('theme'));
}

// Al hacer clic en el botón, alterna entre modo claro y oscuro y guarda la preferencia
btn.onclick = function() {
    const current = root.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    root.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
};