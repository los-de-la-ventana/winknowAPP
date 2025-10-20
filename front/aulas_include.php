<title>Gestión de Aulas - WinKnow</title>

<body>

<?php
    include '../front/navadm.php';
?>

<!-- ==================== CONTENIDO PRINCIPAL ==================== -->
<main class="principal">
    
    <div class="contenido">

        <!-- MENSAJES DE NOTIFICACIÓN -->
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

        <!-- TARJETAS DE ESTADÍSTICAS -->
        <section class="estadisticas">
            <div class="tarjeta-estadistica">
                <div class="icono total"><i class="bi bi-person-gear"></i></div>
                <div>
                    <h3 data-lang="administrators">Administradores</h3>
                    <div class="numero"><?= sprintf('%02d', $conteoAdmins); ?></div>
                </div>
            </div>
            <div class="tarjeta-estadistica">
                <div class="icono total"><i class="bi bi-person-workspace"></i></div>
                <div>
                    <h3 data-lang="active_teachers">Docentes Activos</h3>
                    <div class="numero"><?= sprintf('%02d', $conteoDocentes); ?></div>
                </div>
            </div>
            <div class="tarjeta-estadistica">
                <div class="icono total"><i class="bi bi-people"></i></div>
                <div>
                    <h3 data-lang="students">Estudiantes</h3>
                    <div class="numero"><?= sprintf('%02d', $conteoEstudiantes); ?></div>
                </div>
            </div>
        </section>

  <!-- BOTONES DE GESTIÓN -->
<section class="acciones-gestion">
    <a href="register_espacios.php" class="boton-primario">
        <i class="bi bi-plus-circle"></i> <span data-lang="register_spaces">Registrar Espacios</span>
    </a>
    
    <a href="register_recursos.php" class="boton-primario">
        <i class="bi bi-box-seam"></i> <span data-lang="register_resources">Registrar Recursos</span>
    </a>
    
    <a href="administrar_reservas_espacios.php" class="boton-primario">
        <i class="bi bi-gear"></i> <span data-lang="manage_reservations_spaces">Reservas de Espacios</span>
    </a>
    <a href="administrar_reservas_recursos.php" class="boton-primario">
        <i class="bi bi-gear"></i> <span data-lang="manage_reservations_resources">Reservas de Recursos</span>
    </a>
</section>



        <!-- FILTROS DE BÚSQUEDA -->
        <section class="filtros">
            <h2 data-lang="filter_spaces">Filtrar Espacios</h2><br>
            <form method="GET" action="aulas.php" class="controles-filtro">
                <select name="tipo_salon">
                    <option value=""><span data-lang="space_type">Tipo de Espacio</span> - <span data-lang="all">Todos</span></option>
                    <option value="Salon"  <?= $filtroTipo == 'Salon' ? 'selected' : ''; ?> data-lang="hall">Salon</option>
                    <option value="Aula"   <?= $filtroTipo == 'Aula' ? 'selected' : ''; ?> data-lang="classroom">Aula</option>
                    <option value="Taller" <?= $filtroTipo == 'Taller' ? 'selected' : ''; ?> data-lang="workshop">Taller</option>
                    <option value="Laboratorio" <?= $filtroTipo == 'Laboratorio' ? 'selected' : ''; ?> data-lang="laboratory">Laboratorio</option>
                </select>
                <select name="capacidad">
                    <option value=""><span data-lang="capacity">Capacidad</span> - <span data-lang="any">Cualquiera</span></option>
                    <option value="30" <?= $filtroCapacidad == '30' ? 'selected' : ''; ?>>30 <span data-lang="people">Personas</span></option>
                    <option value="40" <?= $filtroCapacidad == '40' ? 'selected' : ''; ?>>40 <span data-lang="people">Personas</span></option>
                    <option value="60" <?= $filtroCapacidad == '60' ? 'selected' : ''; ?>>60 <span data-lang="people">Personas</span></option>
                    <option value="80" <?= $filtroCapacidad == '80' ? 'selected' : ''; ?>>80 <span data-lang="people">Personas</span></option>
                </select>
                <br><br>
                <button type="submit" class="boton-primario">
                    <i class="bi bi-funnel"></i> <span data-lang="apply_filters">Aplicar Filtros</span>
                </button>
            </form>
        </section>

        <!-- RESULTADOS DE AULAS -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-building"></i> <span data-lang="available_spaces">Espacios Disponibles</span></h2>
                <?php if ($resultEspacios): ?>
                    <p><strong><span data-lang="showing_results">Mostrando</span> <?= $resultEspacios->num_rows; ?> <span data-lang="results">resultado(s)</span></strong></p>
                <?php endif; ?>
            </div>

            <div class="grilla">
                <?php if ($resultEspacios && $resultEspacios->num_rows > 0): ?>
                    <?php while ($espacio = $resultEspacios->fetch_assoc()): ?>
                       <div class="tarjeta-aula">
                            <div class="info-aula">
                                <h4>
                                    <i class="<?= obtenerIconoTipo($espacio['Tipo_salon']); ?>"></i>
                                      <?= obtenerNombreAula($espacio); ?>
                                </h4>
                                <div class="detalles">
                                    <i class="bi bi-people"></i> <span data-lang="capacity">Capacidad</span>: <strong><?= $espacio['capacidad']; ?></strong> <span data-lang="people">Personas</span>
                                </div>
                                <div class="tipo-salon <?= strtolower($espacio['Tipo_salon']); ?>">
                                    <i class="bi bi-tag"></i> <?= htmlspecialchars($espacio['Tipo_salon']); ?>
                                </div>
                                <form method="POST" action="aulas.php" class="form-eliminar" data-nombre-aula="<?= htmlspecialchars(obtenerNombreAula($espacio)); ?>">
                                    <input type="hidden" name="eliminar_aula" value="1">
                                    <input type="hidden" name="num_salon" value="<?= $espacio['NumSalon']; ?>">
                                    <button type="submit" class="boton-eliminar">
                                        <i class="bi bi-trash"></i> <span data-lang="delete">Eliminar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p data-lang="no_spaces_found">No se encontraron espacios con los filtros seleccionados.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="adminValidation.js"></script>
<script src="../alertaLogout.js"></script>


<?php
// ============================================
// CIERRE DE CONEXIÓN
// ============================================
if ($mysqli) {
    $mysqli->close();
}
?>

</body>
</html>