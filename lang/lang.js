/**
 * Sistema de Cambio de Idioma - WinKnow
 * Gestiona las traducciones y el cambio de idioma en toda la aplicación
 */

(function() {
    'use strict';

    // Objeto para almacenar las traducciones
    let translations = {};
    let currentLanguage = 'es'; // Idioma por defecto

    /**
     * Inicializa el sistema de idiomas
     */
    function initLanguageSystem() {
        // Cargar el idioma guardado en localStorage o usar el predeterminado
        const savedLanguage = localStorage.getItem('language') || 'es';
        currentLanguage = savedLanguage;

        // Cargar las traducciones desde el archivo JSON
        loadTranslations().then(() => {
            // Aplicar el idioma actual
            applyLanguage(currentLanguage);
            
            // Actualizar el indicador de idioma
            updateLanguageIndicator();
            
            // Configurar el botón de cambio de idioma
            setupLanguageToggle();
        });
    }

    /**
     * Carga las traducciones desde el archivo JSON
     */
    async function loadTranslations() {
        try {
            const response = await fetch('../lang/lang.json');
            if (!response.ok) {
                throw new Error('Error al cargar las traducciones');
            }
            translations = await response.json();
        } catch (error) {
            console.error('Error cargando traducciones:', error);
            // Traducciones de respaldo básicas
            translations = {
                es: {
                    nav_inicio: "Inicio",
                    nav_logout: "Cerrar Sesión",
                    default_user: "Usuario"
                },
                en: {
                    nav_inicio: "Home",
                    nav_logout: "Logout",
                    default_user: "User"
                }
            };
        }
    }

    /**
     * Aplica el idioma seleccionado a todos los elementos con data-lang
     */
    function applyLanguage(lang) {
        currentLanguage = lang;
        
        // Obtener todos los elementos con el atributo data-lang
        const elements = document.querySelectorAll('[data-lang]');
        
        elements.forEach(element => {
            const key = element.getAttribute('data-lang');
            const translation = translations[lang]?.[key];
            
            if (translation) {
                // Si el elemento es un input, actualizar el placeholder
                if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                    element.placeholder = translation;
                } else {
                    // Para otros elementos, actualizar el texto
                    element.textContent = translation;
                }
            }
        });

        // Guardar el idioma seleccionado
        localStorage.setItem('language', lang);
        
        // Actualizar el atributo lang del HTML
        document.documentElement.lang = lang;
    }

    /**
     * Configura el botón de cambio de idioma
     */
    function setupLanguageToggle() {
        const btnToggleLanguage = document.getElementById('toggle-language');
        
        if (btnToggleLanguage) {
            btnToggleLanguage.addEventListener('click', () => {
                // Alternar entre español e inglés
                const newLanguage = currentLanguage === 'es' ? 'en' : 'es';
                applyLanguage(newLanguage);
                updateLanguageIndicator();
                
                // Mostrar notificación opcional
                showLanguageChangeNotification(newLanguage);
            });
        }
    }

    /**
     * Actualiza el indicador visual del idioma actual
     */
    function updateLanguageIndicator() {
        const currentLangSpan = document.getElementById('current-lang');
        
        if (currentLangSpan) {
            currentLangSpan.textContent = currentLanguage.toUpperCase();
        }
    }

    /**
     * Muestra una notificación cuando se cambia el idioma (opcional)
     */
    function showLanguageChangeNotification(lang) {
        const messages = {
            es: 'Idioma cambiado a Español',
            en: 'Language changed to English'
        };

        // Si existe SweetAlert2, usarlo para la notificación
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: messages[lang],
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-start'
            });
        }
    }

    /**
     * Función pública para cambiar el idioma programáticamente
     */
    window.changeLanguage = function(lang) {
        if (translations[lang]) {
            applyLanguage(lang);
            updateLanguageIndicator();
        } else {
            console.error(`Idioma no soportado: ${lang}`);
        }
    };

    /**
     * Función pública para obtener una traducción específica
     */
    window.getTranslation = function(key, lang = null) {
        const targetLang = lang || currentLanguage;
        return translations[targetLang]?.[key] || key;
    };

    /**
     * Función pública para obtener el idioma actual
     */
    window.getCurrentLanguage = function() {
        return currentLanguage;
    };

    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLanguageSystem);
    } else {
        initLanguageSystem();
    }

})();