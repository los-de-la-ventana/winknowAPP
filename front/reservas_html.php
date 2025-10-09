<title>Reservas - WinKnow</title>

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

        <!-- ========== FORMULARIO DE RESERVA DE ESPACIO ========== -->
        <section class="filtros">
            <h2><i class="bi bi-calendar-plus"></i> <span data-lang="new_space_reservation">Nueva Reserva de Espacio</span></h2>
            <br>
            <form method="POST" action="docente_reservas.php" id="formReserva">
                <input type="hidden" name="crear_reserva" value="1">
                
                <div class="controles-filtro">
                    <select name="id_espacio" id="id_espacio" required>
                        <option value="" data-lang="select_space">Seleccione un espacio</option>
                        <?php 
                        if ($resultEspacios):
                            $resultEspacios->data_seek(0);
                            while ($espacio = $resultEspacios->fetch_assoc()): 
                        ?>
                            <option value="<?= $espacio['IdEspacio']; ?>">
                                <?= obtenerNombreEspacio($espacio['Tipo_salon'], $espacio['NumSalon']); ?> 
                                (<span data-lang="capacity">Capacidad</span>: <?= $espacio['capacidad']; ?>)
                            </option>
                        <?php 
                            endwhile;
                        endif;
                        ?>
                    </select>
                    
                    <input type="date" name="fecha" id="fecha" 
                           min="<?= date('Y-m-d'); ?>" 
                           required>
                    
                    <select name="hora_reserva" id="hora_reserva" required>
                        <option value="" data-lang="select_hour">Seleccione una hora</option>
                        <?php for ($i = 7; $i <= 22; $i++): ?>
                            <option value="<?= $i; ?>"><?= $i; ?>:00 hs</option>
                        <?php endfor; ?>
                    </select>
                    
                    <button type="submit" class="boton-primario">
                        <i class="bi bi-check-circle"></i> <span data-lang="confirm_reservation">Confirmar Reserva</span>
                    </button>
                </div>
            </form>
        </section>

        <br><br>

        <!-- ========== FORMULARIO DE RESERVA DE RECURSO ========== -->
        <section class="filtros">
            <h2><i class="bi bi-box-seam"></i> <span data-lang="new_resource_reservation">Nueva Reserva de Recurso</span></h2>
            <br>
            <form method="POST" action="docente_reservas.php" id="formReservaRecurso">
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
                    
                    <input type="date" name="fecha_recurso" id="fecha_recurso" 
                           min="<?= date('Y-m-d'); ?>" 
                           required>
                    
                    <select name="hora_reserva_recurso" id="hora_reserva_recurso" required>
                        <option value="" data-lang="select_hour">Seleccione una hora</option>
                        <?php for ($i = 7; $i <= 22; $i++): ?>
                            <option value="<?= $i; ?>"><?= $i; ?>:00 hs</option>
                        <?php endfor; ?>
                    </select>
                    
                    <button type="submit" class="boton-primario">
                        <i class="bi bi-check-circle"></i> <span data-lang="confirm_reservation">Confirmar Reserva</span>
                    </button>
                </div>
            </form>
        </section>

        <br><br>

        <!-- ========== ESPACIOS DISPONIBLES ========== -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-building"></i> <span data-lang="available_spaces">Espacios Disponibles</span></h2>
                <?php if ($resultEspacios): ?>
                    <?php $resultEspacios->data_seek(0); ?>
                    <p><strong><span data-lang="total">Total</span>: <?= $resultEspacios->num_rows; ?> <span data-lang="spaces">espacio(s)</span></strong></p>
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
                                    <i class="bi bi-people"></i> <span data-lang="capacity">Capacidad</span>: <strong><?= $espacio['capacidad']; ?></strong> <span data-lang="people">personas</span>
                                </div>
                                <div class="etiqueta">
                                    <?= htmlspecialchars($espacio['Tipo_salon']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p data-lang="no_spaces_available">No hay espacios disponibles.</p>
                <?php endif; ?>
            </div>
        </section>

        <br><br>

        <!-- ========== RECURSOS DISPONIBLES ========== -->
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

        <br><br><!-- ========== RESERVAS DE ESPACIOS APROBADAS ========== -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-check-circle-fill"></i> <span data-lang="approved_space_reservations">Reservas de Espacios Aprobadas</span></h2>
            </div>
            
            <?php if ($resultReservasAprobadas->num_rows > 0): ?>
                <div class="grilla">
                    <?php while ($reserva = $resultReservasAprobadas->fetch_assoc()): ?>
                        <div class="tarjeta-aula">
                            <div class="info-aula">
                                <h4>
                                    <i class="<?= obtenerIconoTipo($reserva['Tipo_salon']); ?>"></i>
                                    <?= obtenerNombreEspacio($reserva['Tipo_salon'], $reserva['NumSalon']); ?>
                                </h4>
                                <div class="detalles">
                                    <p><i class="bi bi-calendar"></i> <strong data-lang="date">Fecha</strong>: <?= formatearFecha($reserva['Fecha']); ?></p>
                                    <p><i class="bi bi-clock"></i> <strong data-lang="hour">Hora</strong>: <?= formatearHora($reserva['Hora_Reserva']); ?></p>
                                    <p><i class="bi bi-people"></i> <strong data-lang="capacity">Capacidad</strong>: <?= $reserva['capacidad']; ?> <span data-lang="people">personas</span></p>
                                </div>
                                <div class="etiqueta">
                                    <i class="bi bi-check-circle"></i> <span data-lang="approved">Aprobada</span>
                                </div>
                                <form method="POST" action="docente_reservas.php">
                                    <input type="hidden" name="eliminar_reserva" value="1">
                                    <input type="hidden" name="id_reserva" value="<?= $reserva['IdReserva']; ?>">
                                    <button type="submit" class="boton-secundario">
                                        <i class="bi bi-trash"></i> <span data-lang="cancel_reservation">Cancelar Reserva</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p data-lang="no_approved_space_reservations">No tienes reservas de espacios aprobadas en este momento.</p>
            <?php endif; ?>
        </section>

        <br><br>

        <!-- ========== RESERVAS DE RECURSOS APROBADAS ========== -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-check-circle-fill"></i> <span data-lang="approved_resource_reservations">Reservas de Recursos Aprobadas</span></h2>
            </div>
            
            <?php if ($resultReservasRecursosAprobadas->num_rows > 0): ?>
                <div class="grilla">
                    <?php while ($reserva = $resultReservasRecursosAprobadas->fetch_assoc()): ?>
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
                                <form method="POST" action="docente_reservas.php">
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
                <p data-lang="no_approved_resource_reservations">No tienes reservas de recursos aprobadas en este momento.</p>
            <?php endif; ?>
        </section>

        <br><br>

        <!-- ========== RESERVAS DE ESPACIOS PENDIENTES ========== -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-clock-history"></i> <span data-lang="pending_space_reservations">Reservas de Espacios Pendientes</span></h2>
            </div>
            
            <?php if ($resultReservasPendientes->num_rows > 0): ?>
                <div class="grilla">
                    <?php while ($reserva = $resultReservasPendientes->fetch_assoc()): ?>
                        <div class="tarjeta-aula">
                            <div class="info-aula">
                                <h4>
                                    <i class="<?= obtenerIconoTipo($reserva['Tipo_salon']); ?>"></i>
                                    <?= obtenerNombreEspacio($reserva['Tipo_salon'], $reserva['NumSalon']); ?>
                                </h4>
                                <div class="detalles">
                                    <p><i class="bi bi-calendar"></i> <strong data-lang="date">Fecha</strong>: <?= formatearFecha($reserva['Fecha']); ?></p>
                                    <p><i class="bi bi-clock"></i> <strong data-lang="hour">Hora</strong>: <?= formatearHora($reserva['Hora_Reserva']); ?></p>
                                    <p><i class="bi bi-people"></i> <strong data-lang="capacity">Capacidad</strong>: <?= $reserva['capacidad']; ?> <span data-lang="people">personas</span></p>
                                </div>
                                <div class="etiqueta">
                                    <i class="bi bi-clock"></i> <span data-lang="pending">Pendiente</span>
                                </div>
                                <form method="POST" action="docente_reservas.php">
                                    <input type="hidden" name="eliminar_reserva" value="1">
                                    <input type="hidden" name="id_reserva" value="<?= $reserva['IdReserva']; ?>">
                                    <button type="submit" class="boton-secundario">
                                        <i class="bi bi-trash"></i> <span data-lang="cancel_reservation">Cancelar Reserva</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p data-lang="no_pending_space_reservations">No tienes reservas de espacios pendientes en este momento.</p>
            <?php endif; ?>
        </section>

        <br><br>

        <!-- ========== RESERVAS DE RECURSOS PENDIENTES ========== -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-clock-history"></i> <span data-lang="pending_resource_reservations">Reservas de Recursos Pendientes</span></h2>
            </div>
            
            <?php if ($resultReservasRecursosPendientes->num_rows > 0): ?>
                <div class="grilla">
                    <?php while ($reserva = $resultReservasRecursosPendientes->fetch_assoc()): ?>
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
                                <form method="POST" action="docente_reservas.php">
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
                <p data-lang="no_pending_resource_reservations">No tienes reservas de recursos pendientes en este momento.</p>
            <?php endif; ?>
        </section>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../alertaLogout.js"></script>

</body>
</html>