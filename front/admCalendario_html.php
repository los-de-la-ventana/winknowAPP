<title>Calendario de Cursos - WinKnow</title>

<style>
/* Estilos específicos para el calendario */
.tabs-container {
    display: flex;
    gap: 16px;
    margin-bottom: 24px;
    border-bottom: 2px solid var(--color-borde);
}

.tab-button {
    padding: 12px 24px;
    background: transparent;
    border: none;
    border-bottom: 3px solid transparent;
    color: var(--texto-secundario);
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: all var(--transition-rapida);
}

.tab-button.active {
    color: var(--azul);
    border-bottom-color: var(--azul);
}

.tab-button:hover {
    color: var(--texto-primario);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Calendario semanal */
.calendario-semanal {
    overflow-x: auto;
    background: var(--fondo-tarjeta);
    border-radius: var(--border-radius);
    padding: 20px;
}

.calendario-grid {
    display: grid;
    grid-template-columns: 80px repeat(5, 1fr);
    gap: 1px;
    background: var(--color-borde);
    border: 1px solid var(--color-borde);
    min-width: 800px;
}

.calendario-header {
    background: var(--fondo-primario);
    padding: 16px 8px;
    text-align: center;
    font-weight: 600;
    font-size: 14px;
    color: var(--texto-primario);
}

.hora-label {
    background: var(--fondo-primario);
    padding: 12px 8px;
    text-align: center;
    font-size: 13px;
    font-weight: 500;
    color: var(--texto-secundario);
    display: flex;
    align-items: center;
    justify-content: center;
}

.calendario-celda {
    background: var(--fondo-tarjeta);
    padding: 8px;
    min-height: 60px;
    position: relative;
}

.calendario-celda:hover {
    background: var(--fondo-primario);
}

.clase-item {
    background: var(--azul);
    color: white;
    padding: 8px;
    border-radius: 6px;
    margin-bottom: 6px;
    font-size: 12px;
    line-height: 1.4;
    box-shadow: 0 2px 4px var(--color-shadow);
}

.clase-item:last-child {
    margin-bottom: 0;
}

.clase-nombre {
    font-weight: 600;
    margin-bottom: 4px;
}

.clase-docente {
    opacity: 0.9;
    font-size: 11px;
}

.clase-asignaturas {
    opacity: 0.85;
    font-size: 11px;
    margin-top: 4px;
    font-style: italic;
}

/* Vista de cursos */
.cursos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 24px;
}

.curso-card {
    background: var(--fondo-primario);
    border-radius: var(--border-radius);
    padding: 20px;
    box-shadow: 0 2px 8px var(--color-shadow);
    transition: transform var(--transition-rapida);
}

.curso-card:hover {
    transform: translateY(-4px);
}

.curso-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 2px solid var(--color-borde);
}

.curso-icono {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.curso-titulo h3 {
    margin: 0 0 4px 0;
    font-size: 18px;
    color: var(--texto-primario);
}

.curso-docente {
    margin: 0;
    font-size: 14px;
    color: var(--texto-secundario);
}

.curso-asignaturas {
    margin-bottom: 16px;
}

.curso-asignaturas h4 {
    font-size: 14px;
    color: var(--texto-secundario);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.asignaturas-list {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.asignatura-tag {
    display: inline-block;
    padding: 4px 10px;
    background: var(--fondo-tarjeta);
    border-radius: 12px;
    font-size: 12px;
    color: var(--texto-primario);
}

.mensaje-sin-datos {
    text-align: center;
    padding: 60px 20px;
    color: var(--texto-secundario);
}

.mensaje-sin-datos i {
    font-size: 64px;
    margin-bottom: 16px;
    display: block;
    opacity: 0.5;
}

/* Responsive */
@media screen and (max-width: 1023px) {
    .calendario-grid {
        grid-template-columns: 60px repeat(5, 1fr);
        font-size: 12px;
    }
    
    .cursos-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }
}

@media screen and (max-width: 767px) {
    .calendario-semanal {
        padding: 12px;
    }
    
    .tabs-container {
        overflow-x: auto;
    }
    
    .tab-button {
        padding: 10px 16px;
        font-size: 14px;
        white-space: nowrap;
    }
    
    .cursos-grid {
        grid-template-columns: 1fr;
    }
    
    .calendario-header {
        padding: 12px 4px;
        font-size: 12px;
    }
    
    .hora-label {
        padding: 8px 4px;
        font-size: 11px;
    }
}
</style>

<body>

<main class="principal">
    
    <div class="contenido">

        <!-- ESTADÍSTICAS -->
        <section class="estadisticas">
            <div class="tarjeta-estadistica">
                <div class="icono total"><i class="bi bi-journal-text"></i></div>
                <div>
                    <h3 data-lang="total_courses">Total Cursos</h3>
                    <div class="numero"><?= sprintf('%02d', $stats['total_cursos']); ?></div>
                </div>
            </div>
            
            <div class="tarjeta-estadistica">
                <div class="icono disponible"><i class="bi bi-book"></i></div>
                <div>
                    <h3 data-lang="total_subjects">Total Asignaturas</h3>
                    <div class="numero"><?= sprintf('%02d', $stats['total_asignaturas']); ?></div>
                </div>
            </div>
            
            <div class="tarjeta-estadistica">
                <div class="icono reservado"><i class="bi bi-calendar-check"></i></div>
                <div>
                    <h3 data-lang="total_schedules">Horarios Programados</h3>
                    <div class="numero"><?= sprintf('%02d', $stats['total_horarios']); ?></div>
                </div>
            </div>
        </section>

        <br>

        <!-- PESTAÑAS -->
        <div class="tabs-container">
            <button class="tab-button active" onclick="switchTab('calendario')" data-lang="weekly_calendar">
                <i class="bi bi-calendar-week"></i> Calendario Semanal
            </button>
            <button class="tab-button" onclick="switchTab('cursos')" data-lang="courses_list">
                <i class="bi bi-list-ul"></i> Lista de Cursos
            </button>
        </div>

        <!-- CONTENIDO PESTAÑA: CALENDARIO SEMANAL -->
        <div id="tab-calendario" class="tab-content active">
            <section class="calendario-semanal">
                <?php if ($stats['total_horarios'] > 0): ?>
                    <div class="calendario-grid">
                        <!-- Encabezado con hora vacía y días -->
                        <div class="calendario-header"></div>
                        <?php foreach ($diasSemana as $dia): ?>
                            <div class="calendario-header"><?= $dia; ?></div>
                        <?php endforeach; ?>
                        
                        <!-- Filas de horas -->
                        <?php for ($hora = 7; $hora <= 22; $hora++): ?>
                            <div class="hora-label"><?= sprintf('%02d:00', $hora); ?></div>
                            
                            <?php foreach ($diasSemana as $dia): ?>
                                <div class="calendario-celda">
                                    <?php 
                                    if (isset($horariosSemana[$dia][$hora]) && !empty($horariosSemana[$dia][$hora])):
                                        foreach ($horariosSemana[$dia][$hora] as $index => $clase):
                                            $color = obtenerColorCurso($index);
                                    ?>
                                        <div class="clase-item" style="background: <?= $color; ?>">
                                            <div class="clase-nombre"><?= htmlspecialchars($clase['nombre_curso']); ?></div>
                                            <div class="clase-docente">
                                                <i class="bi bi-person"></i> <?= htmlspecialchars($clase['docente']); ?>
                                            </div>
                                            <?php if (!empty($clase['asignaturas'])): ?>
                                                <div class="clase-asignaturas">
                                                    <?= htmlspecialchars($clase['asignaturas']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php 
                                        endforeach;
                                    endif;
                                    ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endfor; ?>
                    </div>
                <?php else: ?>
                    <div class="mensaje-sin-datos">
                        <i class="bi bi-calendar-x"></i>
                        <h3 data-lang="no_schedules">No hay horarios programados</h3>
                        <p data-lang="add_schedules_msg">Agrega cursos y horarios para visualizarlos en el calendario.</p>
                    </div>
                <?php endif; ?>
            </section>
        </div>

        <!-- CONTENIDO PESTAÑA: LISTA DE CURSOS -->
        <div id="tab-cursos" class="tab-content">
            <section class="aulas">
                <?php if ($resultCursos && $resultCursos->num_rows > 0): ?>
                    <div class="cursos-grid">
                        <?php 
                        $resultCursos->data_seek(0);
                        $colorIndex = 0;
                        while ($curso = $resultCursos->fetch_assoc()): 
                            $color = obtenerColorCurso($colorIndex++);
                        ?>
                            <div class="curso-card">
                                <div class="curso-header">
                                    <div class="curso-icono" style="background: <?= $color; ?>">
                                        <i class="bi bi-mortarboard"></i>
                                    </div>
                                    <div class="curso-titulo">
                                        <h3><?= htmlspecialchars($curso['nombre_curso']); ?></h3>
                                        <p class="curso-docente">
                                            <i class="bi bi-person-badge"></i> 
                                            <?= htmlspecialchars($curso['nombre_docente']); ?>
                                        </p>
                                    </div>
                                </div>

                                <?php if (!empty($curso['asignaturas'])): ?>
                                    <div class="curso-asignaturas">
                                        <h4 data-lang="subjects">Asignaturas:</h4>
                                        <div class="asignaturas-list">
                                            <?php 
                                            $asignaturas = explode('|', $curso['asignaturas']);
                                            foreach ($asignaturas as $asignatura): 
                                                if (!empty(trim($asignatura))):
                                            ?>
                                                <span class="asignatura-tag">
                                                    <?= htmlspecialchars(trim($asignatura)); ?>
                                                </span>
                                            <?php 
                                                endif;
                                            endforeach; 
                                            ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="curso-asignaturas">
                                        <p class="curso-docente" data-lang="no_subjects_assigned">
                                            <i class="bi bi-info-circle"></i> Sin asignaturas asignadas
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <div class="detalles" style="margin-top: 12px;">
                                    <p style="font-size: 13px; color: var(--texto-secundario);">
                                        <i class="bi bi-hash"></i> 
                                        <span data-lang="course_id">ID del Curso:</span> <?= $curso['IdCurso']; ?>
                                    </p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="mensaje-sin-datos">
                        <i class="bi bi-inbox"></i>
                        <h3 data-lang="no_courses">No hay cursos registrados</h3>
                        <p data-lang="add_courses_msg">Ve a la sección de Asignaturas para crear nuevos cursos.</p>
                    </div>
                <?php endif; ?>
            </section>
        </div>

    </div>

</main>

<script>
// Función para cambiar entre pestañas
function switchTab(tabName) {
    // Ocultar todos los contenidos de pestañas
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.remove('active');
    });
    
    // Desactivar todos los botones
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('active');
    });
    
    // Activar la pestaña seleccionada
    document.getElementById('tab-' + tabName).classList.add('active');
    event.target.classList.add('active');
}
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../alertaLogout.js"></script>

</body>
</html>