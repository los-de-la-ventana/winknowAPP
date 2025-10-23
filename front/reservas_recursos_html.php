<title>Reservas de Recursos - WinKnow</title>

<body>

<!-- ==================== CONTENIDO PRINCIPAL ==================== -->
<main class="principal">
    
    <div class="contenido">
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

        <!-- FORMULARIO DE RESERVA DE RECURSO -->
        <section class="filtros">
            <h2><i class="bi bi-box-seam"></i> <span data-lang="new_resource_reservation">Nueva Reserva de Recurso</span></h2>
            <br>
            <form method="POST" action="docente_reservas_recursos.php" id="formReservaRecurso">
                <input type="hidden" name="crear_reserva_recurso" value="1">
                
                <div class="controles-filtro">
                    <select name="id_recurso" id="id_recurso" required>
                        <option value="" data-lang="select_resource">Seleccione un recurso</option>
                        <?php 
                        if ($resultRecursos):
                            $resultRecursos->data_seek(0);
                            while ($recurso = $resultRecursos->fetch_assoc()): 
                        ?>
                            <option value="<?= $recurso['IdRecurso']; ?>">
                                <?= htmlspecialchars($recurso['nombre_Recurso']); ?> 
                                - <?= obtenerNombreEspacio($recurso['Tipo_salon'], $recurso['NumSalon']); ?>
                            </option>
                        <?php 
                            endwhile;
                        endif;
                        ?>
                    </select>
                    
                    <input type="date" name="fecha" id="fecha" 
                           min="<?= date('Y-m-d'); ?>" 
                           required>
                    
                                        <?php
                        $horarios = [
                            '-- Seleccione --',
                            '7:00 - 7:45 (Primera)',
                            '7:50  8:35 (Segunda)', 
                            '8:40  9:25 (Tercera)',
                            '9:30  10:15 (Cuarta)',
                            '10:20  11:55 (Quinta)',
                            '12:00  12:45 (Sexta)',
                            '12:50  13:35 (Septima)',
                            '13:40  14:25 (Octava)', 
                            '14:30  15:15 (Novena)',
                            '15:20  16:05 (Decima)'
                        ];
                        ?>

                        <select name="hora_reserva" id="hora_reserva" required>
                            <option value=""><?= $horarios[0] ?></option>
                            <?php for($i = 1; $i < count($horarios); $i++): ?>
                                <option value="<?= $horarios[$i] ?>"><?= $horarios[$i] ?></option>
                            <?php endfor; ?>
                        </select>
                    
                    <button type="submit" class="boton-primario">
                        <i class="bi bi-check-circle"></i> <span data-lang="confirm_reservation">Confirmar Reserva</span>
                    </button>
                </div>
            </form>
        </section>

        <br><br>

        <!-- RECURSOS DISPONIBLES -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-box-seam"></i> <span data-lang="available_resources">Recursos Disponibles</span></h2>
                <?php if ($resultRecursos): ?>
                    <?php $resultRecursos->data_seek(0); ?>
                    <p><strong><span data-lang="total">Total</span>: <?= $resultRecursos->num_rows; ?> <span data-lang="resources">recurso(s)</span></strong></p>
                <?php endif; ?>
            </div>

            <div class="grilla">
                <?php if ($resultRecursos && $resultRecursos->num_rows > 0): ?>
                    <?php while ($recurso = $resultRecursos->fetch_assoc()): ?>
                        <div class="tarjeta-aula">
                            <div class="info-aula">
                                <h4>
                                    <i class="<?= obtenerIconoRecurso($recurso['nombre_Recurso']); ?>"></i>
                                    <?= htmlspecialchars($recurso['nombre_Recurso']); ?>
                                </h4>
                                <div class="detalles">
                                    <i class="bi bi-geo-alt"></i> <span data-lang="location">Ubicación</span>: <strong><?= obtenerNombreEspacio($recurso['Tipo_salon'], $recurso['NumSalon']); ?></strong>
                                </div>
                                <div class="etiqueta">
                                    <span data-lang="resource">Recurso</span>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p data-lang="no_resources_available">No hay recursos disponibles.</p>
                <?php endif; ?>
            </div>
        </section>

        <br><br>

        <!-- RESERVAS DE RECURSOS APROBADAS -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-check-circle-fill"></i> <span data-lang="approved_reservations">Reservas Aprobadas</span></h2>
            </div>
            
            <?php if ($resultReservasAprobadas->num_rows > 0): ?>
                <div class="grilla">
                    <?php while ($reserva = $resultReservasAprobadas->fetch_assoc()): ?>
                        <div class="tarjeta-aula">
                            <div class="info-aula">
                                <h4>
                                    <i class="<?= obtenerIconoRecurso($reserva['nombre_Recurso']); ?>"></i>
                                    <?= htmlspecialchars($reserva['nombre_Recurso']); ?>
                                </h4>
                                <div class="detalles">
                                    <p><i class="bi bi-calendar"></i> <strong data-lang="date">Fecha</strong>: <?= formatearFecha($reserva['Fecha']); ?></p>
                                    <p><i class="bi bi-clock"></i> <strong data-lang="hour">Hora</strong>: <?= formatearHora($reserva['Hora_Reserva']); ?></p>
                                    <p><i class="bi bi-geo-alt"></i> <strong data-lang="location">Ubicación</strong>: <?= obtenerNombreEspacio($reserva['Tipo_salon'], $reserva['NumSalon']); ?></p>
                                </div>
                                <div class="etiqueta">
                                    <i class="bi bi-check-circle"></i> <span data-lang="approved">Aprobada</span>
                                </div>
                                <form method="POST" action="docente_reservas_recursos.php">
                                    <input type="hidden" name="eliminar_reserva_recurso" value="1">
                                    <input type="hidden" name="id_reserva_recurso" value="<?= $reserva['IdReservaRecurso']; ?>">
                                    <button type="submit" class="boton-secundario">
                                        <i class="bi bi-trash"></i> <span data-lang="cancel_reservation">Cancelar Reserva</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p data-lang="no_approved_reservations">No tienes reservas aprobadas en este momento.</p>
            <?php endif; ?>
        </section>

        <br><br>

        <!-- RESERVAS DE RECURSOS PENDIENTES -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-clock-history"></i> <span data-lang="pending_reservations">Reservas Pendientes</span></h2>
            </div>
            
            <?php if ($resultReservasPendientes->num_rows > 0): ?>
                <div class="grilla">
                    <?php while ($reserva = $resultReservasPendientes->fetch_assoc()): ?>
                        <div class="tarjeta-aula">
                            <div class="info-aula">
                                <h4>
                                    <i class="<?= obtenerIconoRecurso($reserva['nombre_Recurso']); ?>"></i>
                                    <?= htmlspecialchars($reserva['nombre_Recurso']); ?>
                                </h4>
                                <div class="detalles">
                                    <p><i class="bi bi-calendar"></i> <strong data-lang="date">Fecha</strong>: <?= formatearFecha($reserva['Fecha']); ?></p>
                                    <p><i class="bi bi-clock"></i> <strong data-lang="hour">Hora</strong>: <?= formatearHora($reserva['Hora_Reserva']); ?></p>
                                    <p><i class="bi bi-geo-alt"></i> <strong data-lang="location">Ubicación</strong>: <?= obtenerNombreEspacio($reserva['Tipo_salon'], $reserva['NumSalon']); ?></p>
                                </div>
                                <div class="etiqueta">
                                    <i class="bi bi-clock"></i> <span data-lang="pending">Pendiente</span>
                                </div>
                                <form method="POST" action="docente_reservas_recursos.php">
                                    <input type="hidden" name="eliminar_reserva_recurso" value="1">
                                    <input type="hidden" name="id_reserva_recurso" value="<?= $reserva['IdReservaRecurso']; ?>">
                                    <button type="submit" class="boton-secundario">
                                        <i class="bi bi-trash"></i> <span data-lang="cancel_reservation">Cancelar Reserva</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p data-lang="no_pending_reservations_teacher">No tienes reservas pendientes en este momento.</p>
            <?php endif; ?>
        </section>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../alertaLogout.js"></script>

</body>
</html>