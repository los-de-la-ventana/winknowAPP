function initThemeToggle() {
    const btnToggleTheme = document.getElementById('toggle-theme');
    const root = document.documentElement;

    // Inicialización del tema
    if (!localStorage.getItem('theme')) {
        const prefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;
        root.setAttribute('data-theme', prefersLight ? 'light' : 'dark');
    } else {
        root.setAttribute('data-theme', localStorage.getItem('theme'));
    }

    // Evento de cambio de tema
    btnToggleTheme.addEventListener('click', () => {
        const current = root.getAttribute('data-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        root.setAttribute('data-theme', next);
        localStorage.setItem('theme', next);
    });
}

// Llama a la función al cargar la página
document.addEventListener('DOMContentLoaded', initThemeToggle); 