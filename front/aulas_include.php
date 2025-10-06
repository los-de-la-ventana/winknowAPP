<title>Gestión de Aulas - WinKnow</title>

<body>

<?php
    include '../front/navADM.php';
?>

<!-- ==================== CONTENIDO PRINCIPAL ==================== -->
<main class="principal">
    
    <div class="contenido">

        <!-- MENSAJES DE NOTIFICACIÓN -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="mensaje-notificacion <?= $_SESSION['tipo_mensaje']; ?>">
                <i class="bi <?= $_SESSION['tipo_mensaje'] === 'exito' ? 'bi-check-circle' : 'bi-exclamation-triangle'; ?>"></i>
                <?= htmlspecialchars($_SESSION['mensaje']); ?>
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
                    <h3>Administradores</h3>
                    <div class="numero"><?= sprintf('%02d', $conteoAdmins); ?></div>
                </div>
            </div>
            <div class="tarjeta-estadistica">
                <div class="icono total"><i class="bi bi-person-workspace"></i></div>
                <div>
                    <h3>Docentes Activos</h3>
                    <div class="numero"><?= sprintf('%02d', $conteoDocentes); ?></div>
                </div>
            </div>
            <div class="tarjeta-estadistica">
                <div class="icono total"><i class="bi bi-people"></i></div>
                <div>
                    <h3>Estudiantes</h3>
                    <div class="numero"><?= sprintf('%02d', $conteoEstudiantes); ?></div>
                </div>
            </div>
        </section>

        <!-- BOTÓN REGISTRAR ESPACIOS -->
        <section >
            <a href="register_espacios.php" class="boton-primario">
                <i class="bi bi-plus-circle"></i> Registrar Espacios
            </a>
        </section>  
                <br>
                <br>

        <!-- BOTÓN RESERVAS-->
        <section >
            <a href="administrar_reservas_espacios.php" class="boton-primario">
                            <i class="bi bi-gear"></i> Administrar Reservas
            </a>
        </section>  
                <br>
                <br>


        <!-- FILTROS DE BÚSQUEDA -->
        <section class="filtros">
            <h2>Filtrar Espacios</h2><br>
            <form method="GET" action="aulas.php" class="controles-filtro">
                <select name="tipo_salon">
                    <option value="">Tipo de Espacio - Todos</option>
                    <option value="Salon"  <?= $filtroTipo == 'Salon' ? 'selected' : ''; ?>>Salon</option>
                    <option value="Aula"   <?= $filtroTipo == 'Aula' ? 'selected' : ''; ?>>Aula</option>
                    <option value="Taller" <?= $filtroTipo == 'Taller' ? 'selected' : ''; ?>>Taller</option>
                    <option value="Laboratorio" <?= $filtroTipo == 'Laboratorio' ? 'selected' : ''; ?>>Laboratorio</option>
                </select>
                <select name="capacidad">
                    <option value="">Capacidad - Cualquiera</option>
                    <option value="30" <?= $filtroCapacidad == '30' ? 'selected' : ''; ?>>30 Personas</option>
                    <option value="40" <?= $filtroCapacidad == '40' ? 'selected' : ''; ?>>40 Personas</option>
                    <option value="60" <?= $filtroCapacidad == '60' ? 'selected' : ''; ?>>60 Personas</option>
                    <option value="80" <?= $filtroCapacidad == '80' ? 'selected' : ''; ?>>80 Personas</option>
                </select>
                <br><br>
                <button type="submit" class="boton-primario">
                    <i class="bi bi-funnel"></i> Aplicar Filtros
                </button>
            </form>
        </section>

        <!-- RESULTADOS DE AULAS -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-building"></i> Espacios Disponibles</h2>
                <?php if ($resultEspacios): ?>
                    <p><strong>Mostrando <?= $resultEspacios->num_rows; ?> resultado(s)</strong></p>
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
                                    <i class="bi bi-people"></i> Capacidad: <strong><?= $espacio['capacidad']; ?></strong> Personas
                                </div>
                                <div class="tipo-salon <?= strtolower($espacio['Tipo_salon']); ?>">
                                    <i class="bi bi-tag"></i> <?= htmlspecialchars($espacio['Tipo_salon']); ?>
                                </div>
                                <form method="POST" action="aulas.php" class="form-eliminar" data-nombre-aula="<?= htmlspecialchars(obtenerNombreAula($espacio)); ?>">
                                    <input type="hidden" name="eliminar_aula" value="1">
                                    <input type="hidden" name="num_salon" value="<?= $espacio['NumSalon']; ?>">
                                    <button type="submit" class="boton-eliminar">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No se encontraron espacios con los filtros seleccionados.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="adminValidation.js"></script>

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