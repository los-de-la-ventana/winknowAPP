<title>Registrar Espacios - WinKnow</title>

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
                <i class="bi bi-arrow-left"></i> Volver a Aulas
            </a>
        </section>
        <!-- ESTADÍSTICAS -->
        <section class="estadisticas">
            <div class="tarjeta-estadistica">
                <div class="icono total"><i class="bi bi-building"></i></div>
                <div>
                    <h3>Total Espacios</h3>
                    <div class="numero"><?= sprintf('%02d', $stats['total']); ?></div>
                </div>
            </div>
            
            <div class="tarjeta-estadistica">
                <div class="icono disponible"><i class="bi bi-door-open"></i></div>
                <div>
                    <h3>Aulas</h3>
                    <div class="numero"><?= sprintf('%02d', $stats['aulas']); ?></div>
                </div>
            </div>
            
            <div class="tarjeta-estadistica">
                <div class="icono reservado"><i class="bi bi-tools"></i></div>
                <div>
                    <h3>Talleres</h3>
                    <div class="numero"><?= sprintf('%02d', $stats['talleres']); ?></div>
                </div>
            </div>
        </section>

        <br>

 

        <br>

        <!-- FORMULARIO DE REGISTRO -->
        <section class="filtros">
            <h2><i class="bi bi-plus-circle"></i> Registrar Nuevo Espacio</h2>
            <br>
            
            <form method="POST" action="register_espacios.php">
                <input type="hidden" name="accion" value="registrar">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="num_salon">Número de Salón:</label>
                        <input type="number" id="num_salon" name="num_salon" required 
                               placeholder="Ej: 101, 201, 301..." min="1">
                    </div>
                    
                    <div class="form-group">
                        <label for="capacidad">Capacidad (personas):</label>
                        <input type="number" id="capacidad" name="capacidad" required 
                               placeholder="Ej: 30" min="1" max="200">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="tipo_salon">Tipo de Espacio:</label>
                    <select id="tipo_salon" name="tipo_salon" required>
                        <option value="">-- Seleccione un tipo --</option>
                        <option value="Aula">Aula</option>
                        <option value="Taller">Taller</option>
                        <option value="Laboratorio">Laboratorio</option>
                        <option value="Salon">Salón</option>
                    </select>
                </div>
                
                <br>
                <button type="submit" class="boton-primario">
                    <i class="bi bi-check-circle"></i> Registrar Espacio
                </button>
            </form>
        </section>

    </div>

</main>

</body>
</html>