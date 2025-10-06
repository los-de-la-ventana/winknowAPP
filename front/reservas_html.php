<title>Reservas - WinKnow</title>

<body>


<!-- ==================== CONTENIDO PRINCIPAL ==================== -->
<main class="principal">
    
    <div class="contenido">
<?php if (isset($_SESSION['mensaje'])): ?>
    <div id="mensaje-data" 
         data-mensaje="<?= htmlspecialchars($_SESSION['mensaje'], ENT_QUOTES, 'UTF-8'); ?>" 
         data-tipo="<?= htmlspecialchars($_SESSION['tipo_mensaje'], ENT_QUOTES, 'UTF-8'); ?>" 
         style="display: none;">
    </div>
    <?php 
        unset($_SESSION['mensaje']);
        unset($_SESSION['tipo_mensaje']);
    ?>
<?php endif; ?>

        <!-- FORMULARIO DE RESERVA -->
        <section class="filtros">
            <h2><i class="bi bi-calendar-plus"></i> Nueva Reserva</h2>
            <br>
            <form method="POST" action="docente_reservas.php">
                <input type="hidden" name="crear_reserva" value="1">
                
                <div class="controles-filtro">
                    <select name="id_espacio" id="id_espacio" required>
                        <option value="">Seleccione un espacio</option>
                        <?php 
                        if ($resultEspacios):
                            $resultEspacios->data_seek(0);
                            while ($espacio = $resultEspacios->fetch_assoc()): 
                        ?>
                            <option value="<?= $espacio['IdEspacio']; ?>">
                                <?= obtenerNombreEspacio($espacio['Tipo_salon'], $espacio['NumSalon']); ?> 
                                (Capacidad: <?= $espacio['capacidad']; ?>)
                            </option>
                        <?php 
                            endwhile;
                        endif;
                        ?>
                    </select>
                    
                    <input type="date" name="fecha" id="fecha" 
                           min="<?= date('Y-m-d'); ?>" 
                           required
                        >
                    
                    <select name="hora_reserva" id="hora_reserva" required>
                        <option value="">Seleccione una hora</option>
                        <?php for ($i = 7; $i <= 22; $i++): ?>
                            <option value="<?= $i; ?>"><?= $i; ?>:00 hs</option>
                        <?php endfor; ?>
                    </select>
                    
                    <button type="submit" class="boton-primario">
                        <i class="bi bi-check-circle"></i> Confirmar Reserva
                    </button>
                </div>
            </form>
        </section>

        <br><br>

        <!-- ESPACIOS DISPONIBLES -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-building"></i> Espacios Disponibles</h2>
                <?php if ($resultEspacios): ?>
                    <?php $resultEspacios->data_seek(0); ?>
                    <p><strong>Total: <?= $resultEspacios->num_rows; ?> espacio(s)</strong></p>
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
                                    <i class="bi bi-people"></i> Capacidad: <strong><?= $espacio['capacidad']; ?></strong> personas
                                </div>
                                <div class="etiqueta">
                                    <?= htmlspecialchars($espacio['Tipo_salon']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No hay espacios disponibles.</p>
                <?php endif; ?>
            </div>
        </section>

        <br><br>

        <!-- RESERVAS ACTIVAS -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-calendar-check"></i> Mis Reservas Activas</h2>
            </div>
            
            <?php if ($resultReservas->num_rows > 0): ?>
                <div class="grilla">
                    <?php while ($reserva = $resultReservas->fetch_assoc()): ?>
                        <div class="tarjeta-aula">
                            <div class="info-aula">
                                <h4>
                                    <i class="<?= obtenerIconoTipo($reserva['Tipo_salon']); ?>"></i>
                                    <?= obtenerNombreEspacio($reserva['Tipo_salon'], $reserva['NumSalon']); ?>
                                </h4>
                                <div class="detalles">
                                    <p><i class="bi bi-calendar"></i> <strong>Fecha:</strong> <?= formatearFecha($reserva['Fecha']); ?></p>
                                    <p><i class="bi bi-clock"></i> <strong>Hora:</strong> <?= formatearHora($reserva['Hora_Reserva']); ?></p>
                                    <p><i class="bi bi-people"></i> <strong>Capacidad:</strong> <?= $reserva['capacidad']; ?> personas</p>
                                </div>
                                <form method="POST" action="docente_reservas.php">
                                    <input type="hidden" name="eliminar_reserva" value="1">
                                    <input type="hidden" name="id_reserva" value="<?= $reserva['IdReserva']; ?>">
                                  <button type="submit" class="boton-secundario">
                                        <i class="bi bi-trash"></i> Cancelar Reserva
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No tienes reservas activas en este momento.</p>
            <?php endif; ?>
        </section>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>
</html>