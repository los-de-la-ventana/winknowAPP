<title>Administrar Reservas - WinKnow</title>

<body>


<main class="principal">
    
    <div class="contenido">

        <!-- MENSAJES -->
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

        <!-- ESTADÍSTICAS -->
        <section class="estadisticas">
            <div class="tarjeta-estadistica">
                <div class="icono total"><i class="bi bi-calendar-check"></i></div>
                <div>
                    <h3 data-lang="active_reservations">Reservas Activas</h3>
                    <div class="numero"><?= sprintf('%02d', $totalReservas); ?></div>
                </div>
            </div>
        </section>

        <br>

        <!-- LISTA DE RESERVAS -->
        <section class="aulas">
            <div class="aulas-header">
                <h2><i class="bi bi-list-check"></i> <span data-lang="reservation_management">Gestión de Reservas</span></h2>
                <p><strong><span data-lang="total">Total</span>: <?= $resultReservas->num_rows; ?> <span data-lang="pending_reservations_count">reserva(s) pendiente(s)</span></strong></p>
            </div>

            <?php if ($resultReservas->num_rows > 0): ?>
                <div class="tabla-reservas-admin">
                    <?php while ($reserva = $resultReservas->fetch_assoc()): ?>
                        <div class="tarjeta-reserva-admin">
                            <div class="reserva-info">
                                <div class="reserva-header">
                                    <h4>
                                        <i class="<?= obtenerIconoTipo($reserva['Tipo_salon']); ?>"></i>
                                        <?= obtenerNombreEspacio($reserva['Tipo_salon'], $reserva['NumSalon']); ?>
                                    </h4>
                                    <span class="badge-pendiente" data-lang="pending">Pendiente</span>
                                </div>
                                
                                <div class="reserva-detalles">
                                    <div class="detalle-item">
                                        <i class="bi bi-calendar"></i>
                                        <span><strong data-lang="date">Fecha</strong>: <?= formatearFecha($reserva['Fecha']); ?></span>
                                    </div>
                                    <div class="detalle-item">
                                        <i class="bi bi-clock"></i>
                                        <span><strong data-lang="hour">Hora</strong>: <?= formatearHora($reserva['Hora_Reserva']); ?></span>
                                    </div>
                                    <div class="detalle-item">
                                        <i class="bi bi-people"></i>
                                        <span><strong data-lang="capacity">Capacidad</strong>: <?= $reserva['capacidad']; ?> <span data-lang="people">personas</span></span>
                                    </div>
                                    <div class="detalle-item">
                                        <i class="bi bi-person-badge"></i>
                                        <span><strong data-lang="requester">Solicitante</strong>: <span data-lang="not_specified">No especificado</span></span>
                                    </div>
                                </div>
                            </div>

                            <div class="reserva-acciones">
                                <form method="POST" action="administrar_reservas_espacios.php" class="form-aprobar">
                                    <input type="hidden" name="aprobar_reserva" value="1">
                                    <input type="hidden" name="id_reserva" value="<?= $reserva['IdReserva']; ?>">
                                    <button type="submit" class="btn-aprobar">
                                        <i class="bi bi-check-circle"></i> <span data-lang="approve">Aprobar</span>
                                    </button>
                                </form>

                                <form method="POST" action="administrar_reservas_espacios.php" class="form-rechazar">
                                    <input type="hidden" name="rechazar_reserva" value="1">
                                    <input type="hidden" name="id_reserva" value="<?= $reserva['IdReserva']; ?>">
                                    <button type="submit" class="btn-rechazar">
                                        <i class="bi bi-x-circle"></i> <span data-lang="reject">Rechazar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="mensaje-vacio">
                    <i class="bi bi-inbox"></i>
                    <p data-lang="no_pending_reservations">No hay reservas pendientes en este momento.</p>
                </div>
            <?php endif; ?>
        </section>

    </div>

</main>

</body>
</html>