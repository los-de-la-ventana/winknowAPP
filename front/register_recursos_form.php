<title>Registrar Recursos - WinKnow</title>

<body>

<?php include 'navADM.php'; ?>

<main class="principal">
    
    <div class="contenido">

        <!-- MENSAJES -->
        <?php if ($mensaje): ?>
            <div id="mensaje-data" 
                 data-mensaje="<?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>" 
                 data-tipo="<?= htmlspecialchars($tipo_mensaje, ENT_QUOTES, 'UTF-8'); ?>">
            </div>
        <?php endif; ?>

        <!-- BOTÓN VOLVER -->
        <section class="acciones">
            <a href="aulas.php" class="boton-secundario">
                <i class="bi bi-arrow-left"></i> <span data-lang="back_to_classrooms">Volver a Aulas</span>
            </a>
        </section>

        <br>

        <!-- FORMULARIO DE REGISTRO -->
        <section class="filtros">
            <h2>
                <i class="bi bi-plus-circle"></i> 
                <span data-lang="register_new_resource">Registrar Nuevo Recurso</span>
            </h2>
            <br>
            
            <form method="POST" action="register_recursos.php">
                <input type="hidden" name="accion" value="registrar">
                
                <div class="form-group">
                    <label for="nombre_recurso" data-lang="resource_name">Nombre del Recurso:</label>
                    <input type="text" id="nombre_recurso" name="nombre_recurso" required 
                           placeholder="Ej: Proyector, Computadora, Pizarra..." maxlength="120">
                </div>
                
                <div class="form-group">
                    <label for="id_espacio" data-lang="assign_to_space">Asignar a Espacio:</label>
                    <select id="id_espacio" name="id_espacio" required>
                        <option value="" data-lang="select_space">-- Seleccione un espacio --</option>
                        <?php if ($resultEspacios && $resultEspacios->num_rows > 0): ?>
                            <?php while ($espacio = $resultEspacios->fetch_assoc()): ?>
                                <option value="<?= $espacio['IdEspacio']; ?>">
                                    <?php
                                    $tipo = $espacio['Tipo_salon'];
                                    $num = $espacio['NumSalon'];
                                    $nombre = match ($tipo) {
                                        'Taller' => "Taller $num",
                                        'Salon' => "Salón $num",
                                        'Laboratorio' => "Laboratorio $num",
                                        default => "Aula $num"
                                    };
                                    echo htmlspecialchars($nombre);
                                    echo " (Cap: " . $espacio['capacidad'] . ")";
                                    ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <br>
                <button type="submit" class="boton-primario">
                    <i class="bi bi-check-circle"></i> <span data-lang="register_resource">Registrar Recurso</span>
                </button>
            </form>
        </section>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../alertaLogout.js"></script>

</body>
</html>