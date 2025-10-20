<title>Gestión de Asignaturas - WinKnow</title>

<body>

<?php include '../front/navadm.php'; ?>

<!-- ==================== CONTENIDO PRINCIPAL ==================== -->
<main class="principal">
    
    <div class="contenido">

        <!-- MENSAJES -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div id="mensaje-data" 
                 data-mensaje="<?= htmlspecialchars($_SESSION['mensaje'], ENT_QUOTES, 'UTF-8'); ?>" 
                 data-tipo="<?= htmlspecialchars($_SESSION['tipo_mensaje'], ENT_QUOTES, 'UTF-8'); ?>" 
                 class="mensaje-oculto">
            </div>
            <?php 
                unset($_SESSION['mensaje']);
                unset($_SESSION['tipo_mensaje']);
            ?>
        <?php endif; ?>

        <!-- ESTADÍSTICAS -->
        <section class="estadisticas">
            <div class="tarjeta-estadistica">
                <div class="icono total"><i class="bi bi-book"></i></div>
                <div>
                    <h3 data-lang="total_subjects">Total Asignaturas</h3>
                    <div class="numero"><?= sprintf('%02d', $stats['total_asignaturas']); ?></div>
                </div>
            </div>
            
            <div class="tarjeta-estadistica">
                <div class="icono disponible"><i class="bi bi-journal-text"></i></div>
                <div>
                    <h3 data-lang="total_courses">Total Cursos</h3>
                    <div class="numero"><?= sprintf('%02d', $stats['total_cursos']); ?></div>
                </div>
            </div>
            
            <div class="tarjeta-estadistica">
                <div class="icono reservado"><i class="bi bi-person-workspace"></i></div>
                <div>
                    <h3 data-lang="active_teachers">Docentes Activos</h3>
                    <div class="numero"><?= sprintf('%02d', $stats['docentes_activos']); ?></div>
                </div>
            </div>
        </section>

        <br>

        <!-- FORMULARIO CREAR ASIGNATURA -->
        <section class="filtros">
            <h2><i class="bi bi-plus-circle"></i> <span data-lang="create_subject">Crear Nueva Asignatura</span></h2>
            <br>
            <form method="POST" action="asignaturas.php">
                <input type="hidden" name="crear_asignatura" value="1">
                
                <div class="controles-filtro">
                    <input type="text" name="nombre_asignatura" class="input-flex"
                           data-lang="subject_name" placeholder="Nombre de la Asignatura" 
                           required maxlength="50">
                    
                    <button type="submit" class="boton-primario">
                        <i class="bi bi-check-circle"></i> <span data-lang="create">Crear</span>
                    </button>
                </div>
            </form>
        </section>

        <br>

        <!-- FORMULARIO CREAR CURSO -->
        <section class="filtros">
            <h2><i class="bi bi-journal-plus"></i> <span data-lang="create_course">Crear Nuevo Curso</span></h2>
            <br>
            <form method="POST" action="asignaturas.php">
                <input type="hidden" name="crear_curso" value="1">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre_curso" data-lang="course_name">Nombre del Curso:</label>
                        <input type="text" id="nombre_curso" name="nombre_curso" 
                               data-lang="course_name" placeholder="Nombre del Curso" 
                               required maxlength="50">
                    </div>
                    
                    <div class="form-group">
                        <label for="cedula_docente" data-lang="assign_teacher">Asignar Docente:</label>
                        <select id="cedula_docente" name="cedula_docente" required>
                            <option value="" data-lang="select_teacher">-- Seleccione un docente --</option>
                            <?php 
                            if ($resultDocentes && $resultDocentes->num_rows > 0):
                                $resultDocentes->data_seek(0);
                                while ($docente = $resultDocentes->fetch_assoc()): 
                            ?>
                                <option value="<?= $docente['Cedula']; ?>">
                                    <?= htmlspecialchars($docente['Nombre_usr']); ?>
                                </option>
                            <?php 
                                endwhile;
                            endif;
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label data-lang="assign_subjects">Asignar Asignaturas (opcional):</label>
                    <div class="checkbox-grid">
                        <?php 
                        if ($resultAsignaturasSelector && $resultAsignaturasSelector->num_rows > 0):
                            $resultAsignaturasSelector->data_seek(0);
                            while ($asignatura = $resultAsignaturasSelector->fetch_assoc()): 
                        ?>
                            <label class="checkbox-label">
                                <input type="checkbox" name="asignaturas[]" 
                                       value="<?= $asignatura['IdAsignatura']; ?>">
                                <span><?= htmlspecialchars($asignatura['nombreAsignatura']); ?></span>
                            </label>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <p data-lang="no_subjects_available">No hay asignaturas disponibles. Crea una primero.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <br>
                <button type="submit" class="boton-primario">
                    <i class="bi bi-check-circle"></i> <span data-lang="create_course">Crear Curso</span>
                </button>
            </form>
        </section>

        <br><br>

        <!-- LISTA DE ASIGNATURAS -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-book"></i> <span data-lang="subjects_list">Lista de Asignaturas</span></h2>
                <p><strong><span data-lang="total">Total</span>: <?= $resultAsignaturas->num_rows; ?> <span data-lang="subjects">asignatura(s)</span></strong></p>
            </div>

            <?php if ($resultAsignaturas && $resultAsignaturas->num_rows > 0): ?>
                <div class="grilla">
                    <?php while ($asignatura = $resultAsignaturas->fetch_assoc()): ?>
                        <div class="tarjeta-aula">
                            <div class="info-aula">
                                <h4>
                                    <i class="bi bi-book-half"></i>
                                    <?= htmlspecialchars($asignatura['nombreAsignatura']); ?>
                                </h4>
                                <div class="detalles">
                                    <p><i class="bi bi-journal-text"></i> <span data-lang="courses">Cursos</span>: <strong><?= $asignatura['num_cursos']; ?></strong></p>
                                </div>
                                <div class="etiqueta">
                                    <span data-lang="subject">Asignatura</span>
                                </div>
                                
                                <form method="POST" action="asignaturas.php" class="form-eliminar" 
                                      data-nombre-aula="<?= htmlspecialchars($asignatura['nombreAsignatura']); ?>">
                                    <input type="hidden" name="eliminar_asignatura" value="1">
                                    <input type="hidden" name="id_asignatura" value="<?= $asignatura['IdAsignatura']; ?>">
                                    <button type="submit" class="boton-eliminar">
                                        <i class="bi bi-trash"></i> <span data-lang="delete">Eliminar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="mensaje-vacio">
                    <i class="bi bi-inbox"></i>
                    <p data-lang="no_subjects">No hay asignaturas registradas.</p>
                </div>
            <?php endif; ?>
        </section>

        <br><br>

        <!-- LISTA DE CURSOS -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-journal-text"></i> <span data-lang="courses_list">Lista de Cursos</span></h2>
                <p><strong><span data-lang="total">Total</span>: <?= $resultCursos->num_rows; ?> <span data-lang="courses">curso(s)</span></strong></p>
            </div>

            <?php if ($resultCursos && $resultCursos->num_rows > 0): ?>
                <div class="grilla">
                    <?php while ($curso = $resultCursos->fetch_assoc()): ?>
                        <div class="tarjeta-aula">
                            <div class="info-aula">
                                <h4>
                                    <i class="bi bi-journal-bookmark"></i>
                                    <?= htmlspecialchars($curso['nombre_curso']); ?>
                                </h4>
                                <div class="detalles">
                                    <p><i class="bi bi-person"></i> <strong data-lang="teacher">Docente</strong>: <?= htmlspecialchars($curso['nombre_docente']); ?></p>
                                    <?php if (!empty($curso['asignaturas'])): ?>
                                        <p><i class="bi bi-book"></i> <strong data-lang="subjects">Asignaturas</strong>: <?= htmlspecialchars($curso['asignaturas']); ?></p>
                                    <?php else: ?>
                                        <p><i class="bi bi-exclamation-circle"></i> <span data-lang="no_subjects_assigned">Sin asignaturas asignadas</span></p>
                                    <?php endif; ?>
                                </div>
                                <div class="etiqueta">
                                    <span data-lang="course">Curso</span>
                                </div>
                                
                                <form method="POST" action="asignaturas.php" class="form-eliminar" 
                                      data-nombre-aula="<?= htmlspecialchars($curso['nombre_curso']); ?>">
                                    <input type="hidden" name="eliminar_curso" value="1">
                                    <input type="hidden" name="id_curso" value="<?= $curso['IdCurso']; ?>">
                                    <button type="submit" class="boton-eliminar">
                                        <i class="bi bi-trash"></i> <span data-lang="delete">Eliminar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="mensaje-vacio">
                    <i class="bi bi-inbox"></i>
                    <p data-lang="no_courses">No hay cursos registrados.</p>
                </div>
            <?php endif; ?>
        </section>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../alertaLogout.js"></script>

</body>
</html>