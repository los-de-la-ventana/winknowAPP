<title data-lang="my_schedule_title">Calendario - WinKnow</title>



<body>

<?php include '../front/navEST.php'; ?>

<main class="principal">
    
    <div class="contenido">

        <!-- SELECTOR DE GRUPO -->
        <section class="selector-grupo">
            <h2><i class="bi bi-funnel"></i> <span data-lang="select_group">Seleccionar Grupo</span></h2>
            <form method="GET" action="calendarioEST.php" class="form-selector">
                <select name="grupo" onchange="this.form.submit()" required>
                    <option value="" data-lang="select_group_option">-- Seleccione un grupo --</option>
                    <?php 
                    if ($resultGrupos && $resultGrupos->num_rows > 0):
                        $resultGrupos->data_seek(0);
                        while ($grupo = $resultGrupos->fetch_assoc()): 
                    ?>
                        <option value="<?= $grupo['IdGrupo']; ?>" 
                                <?= ($grupoSeleccionado == $grupo['IdGrupo']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($grupo['nombre_curso']); ?> - 
                            <?= htmlspecialchars($grupo['nombreGrupo']); ?>
                        </option>
                    <?php 
                        endwhile;
                    endif;
                    ?>
                </select>
            </form>
        </section>

        <?php if ($infoGrupo): ?>
            <!-- INFORMACIÓN DEL GRUPO -->
            <div class="info-grupo">
                <div>
                    <h3><?= htmlspecialchars($infoGrupo['nombre_curso']); ?></h3>
                    <div class="detalles-grupo">
                        <span data-lang="group">Grupo</span>: <strong><?= htmlspecialchars($infoGrupo['nombreGrupo']); ?></strong>
                        <?php if ($infoGrupo['anio']): ?>
                            - <span data-lang="year">Año</span>: <strong><?= $infoGrupo['anio']; ?></strong>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- CALENDARIO SEMANAL -->
            <section class="calendario-semanal">
                <table class="tabla-horario">
                    <thead>
                        <tr>
                            <th class="hora-header" data-lang="hour">Hora</th>
                            <th data-lang="monday">Lunes</th>
                            <th data-lang="tuesday">Martes</th>
                            <th data-lang="wednesday">Miércoles</th>
                            <th data-lang="thursday">Jueves</th>
                            <th data-lang="friday">Viernes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $horasUsadas = [];
                        foreach ($diasSemana as $dia) {
                            foreach ($horariosSemana[$dia] as $hora => $clase) {
                                if ($clase !== null) {
                                    $horasUsadas[$hora] = true;
                                }
                            }
                        }
                        ksort($horasUsadas);
                        $horasUsadas = array_keys($horasUsadas);
                        
                        // Si no hay horarios, mostrar al menos de 7 a 16
                        if (empty($horasUsadas)) {
                            $horasUsadas = range(7, 16);
                        }
                        
                        // Asegurarse de que el rango incluya todas las horas de 7 a 16
                        $horaMin = !empty($horasUsadas) ? min($horasUsadas) : 7;
                        $horaMax = !empty($horasUsadas) ? max($horasUsadas) : 16;
                        if ($horaMax < 16) $horaMax = 16;
                        $horasUsadas = range($horaMin, $horaMax);
                        
                        foreach ($horasUsadas as $hora): 
                        ?>
                            <tr>
                                <td class="hora-label"><?= sprintf('%02d:00', $hora); ?></td>
                                <?php foreach ($diasSemana as $dia): ?>
                                    <?php 
                                        $clase = $horariosSemana[$dia][$hora];
                                        if ($clase !== null): 
                                    ?>
                                        <td>
                                            <div class="clase-bloque">
                                                <div class="clase-nombre">
                                                    <?= htmlspecialchars($clase['asignatura']); ?>
                                                </div>
                                            </div>
                                        </td>
                                    <?php 
                                        else: 
                                    ?>
                                        <td class="celda-vacia"></td>
                                    <?php 
                                        endif;
                                    ?>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

        <?php else: ?>
            <div class="mensaje-sin-horarios">
                <i class="bi bi-calendar-x"></i>
                <h3 data-lang="no_schedules_available">No hay horarios disponibles</h3>
                <p data-lang="select_group_to_view">Selecciona un grupo para ver su horario semanal.</p>
            </div>
        <?php endif; ?>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../alertaLogout.js"></script>

</body>
</html>