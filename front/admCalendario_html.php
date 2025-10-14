<title>Horarios Semanales - WinKnow</title>

<style>
/* Estilos específicos para el calendario de horarios */
.selector-grupo {
    padding: var(--padding-seccion);
    background: var(--fondo-tarjeta);
    border-radius: var(--border-radius);
    margin-bottom: var(--padding-seccion);
    box-shadow: 0 2px 8px var(--color-shadow);
}

.selector-grupo h2 {
    margin-bottom: 16px;
    font-size: 18px;
}

.form-selector {
    display: flex;
    align-items: center;
    gap: 16px;
}

.form-selector select {
    flex: 1;
    max-width: 400px;
    padding: 12px 16px;
    background: var(--fondo-primario);
    border: 1px solid var(--color-borde);
    border-radius: var(--border-radius);
    color: var(--texto-primario);
    font-family: var(--fuente-principal);
    font-size: 15px;
    cursor: pointer;
}

.info-grupo {
    padding: 20px;
    background: var(--fondo-primario);
    border-radius: var(--border-radius);
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.info-grupo h3 {
    margin: 0;
    font-size: 24px;
    color: var(--texto-primario);
}

.info-grupo .detalles-grupo {
    font-size: 14px;
    color: var(--texto-secundario);
}

.calendario-semanal {
    overflow-x: auto;
    background: var(--fondo-tarjeta);
    border-radius: var(--border-radius);
    padding: 20px;
    box-shadow: 0 2px 8px var(--color-shadow);
}

.tabla-horario {
    width: 100%;
    min-width: 800px;
    border-collapse: separate;
    border-spacing: 2px;
    background: var(--color-borde);
}

.tabla-horario th {
    background: var(--azul);
    color: white;
    padding: 16px 8px;
    text-align: center;
    font-weight: 600;
    font-size: 14px;
    border-radius: 4px;
}

.tabla-horario th.hora-header {
    background: var(--fondo-primario);
    color: var(--texto-primario);
    width: 80px;
}

.tabla-horario td {
    background: var(--fondo-tarjeta);
    padding: 8px;
    min-height: 60px;
    vertical-align: top;
    text-align: center;
}

.tabla-horario td.hora-label {
    background: var(--fondo-primario);
    font-size: 13px;
    font-weight: 500;
    color: var(--texto-secundario);
    text-align: center;
}

.clase-bloque {
    background: var(--azul);
    color: white;
    padding: 10px 8px;
    border-radius: 6px;
    height: 100%;
    min-height: 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 4px;
    box-shadow: 0 2px 4px var(--color-shadow);
    transition: transform 0.2s;
}

.clase-bloque:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px var(--color-shadow);
}

.clase-nombre {
    font-weight: 600;
    font-size: 13px;
    line-height: 1.3;
}

.celda-vacia {
    background: var(--fondo-primario);
    opacity: 0.5;
}

.celda-recreo {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.mensaje-sin-horarios {
    text-align: center;
    padding: 60px 20px;
    color: var(--texto-secundario);
}

.mensaje-sin-horarios i {
    font-size: 64px;
    margin-bottom: 16px;
    display: block;
    opacity: 0.5;
}

/* Responsive */
@media screen and (max-width: 1023px) {
    .form-selector {
        flex-direction: column;
        align-items: stretch;
    }
    
    .form-selector select {
        max-width: none;
    }
    
    .info-grupo {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }
    
    .tabla-horario {
        font-size: 12px;
    }
    
    .tabla-horario th,
    .tabla-horario td {
        padding: 8px 4px;
    }
}

@media screen and (max-width: 767px) {
    .calendario-semanal {
        padding: 12px;
    }
    
    .tabla-horario {
        min-width: 600px;
    }
    
    .clase-nombre {
        font-size: 11px;
    }
    
    .clase-docente {
        font-size: 10px;
    }
}
</style>

<body>

<main class="principal">
    
    <div class="contenido">

        <!-- ESTADÍSTICAS -->
        <section class="estadisticas">
            <div class="tarjeta-estadistica">
                <div class="icono total"><i class="bi bi-collection"></i></div>
                <div>
                    <h3>Total Cursos</h3>
                    <div class="numero"><?= sprintf('%02d', $stats['total_cursos']); ?></div>
                </div>
            </div>
            
            <div class="tarjeta-estadistica">
                <div class="icono disponible"><i class="bi bi-people-fill"></i></div>
                <div>
                    <h3>Total Grupos</h3>
                    <div class="numero"><?= sprintf('%02d', $stats['total_grupos']); ?></div>
                </div>
            </div>
            
            <div class="tarjeta-estadistica">
                <div class="icono reservado"><i class="bi bi-person-workspace"></i></div>
                <div>
                    <h3>Docentes Activos</h3>
                    <div class="numero"><?= sprintf('%02d', $stats['docentes_activos']); ?></div>
                </div>
            </div>
        </section>

        <br>

        <!-- SELECTOR DE GRUPO -->
        <section class="selector-grupo">
            <h2><i class="bi bi-funnel"></i> Seleccionar Grupo</h2>
            <form method="GET" action="ver_horarios.php" class="form-selector">
                <select name="grupo" onchange="this.form.submit()" required>
                    <option value="">-- Seleccione un grupo --</option>
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
                        Grupo: <strong><?= htmlspecialchars($infoGrupo['nombreGrupo']); ?></strong>
                        <?php if ($infoGrupo['anio']): ?>
                            - Año: <strong><?= $infoGrupo['anio']; ?></strong>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- CALENDARIO SEMANAL -->
            <section class="calendario-semanal">
                <table class="tabla-horario">
                    <thead>
                        <tr>
                            <th class="hora-header">Hora</th>
                            <?php foreach ($diasSemana as $dia): ?>
                                <th><?= $dia; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $horasUsadas = [];
                        foreach ($diasSemana as $dia) {
                            foreach ($horariosSemana[$dia] as $hora => $clase) {
                                if ($clase !== null || $hora == 13) { // Incluir hora 13 (recreo)
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
                        
                        $colorIndex = 0;
                        $coloresAsignatura = array();
                        
                        foreach ($horasUsadas as $hora): 
                        ?>
                            <tr>
                                <td class="hora-label"><?= sprintf('%02d:00', $hora); ?></td>
                                <?php foreach ($diasSemana as $dia): ?>
                                    <?php 
                                    // Mostrar recreo a las 13:00
                                    if ($hora == 13): 
                                    ?>
                                    <?php 
                                    else:
                                        $clase = $horariosSemana[$dia][$hora];
                                        if ($clase !== null): 
                                            // Asignar color único por asignatura
                                            if (!isset($coloresAsignatura[$clase['asignatura']])) {
                                                $coloresAsignatura[$clase['asignatura']] = obtenerColorAsignatura($colorIndex++);
                                            }
                                            $color = $coloresAsignatura[$clase['asignatura']];
                                    ?>
                                        <td>
                                            <div class="clase-bloque" style="background: <?= $color; ?>">
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
                <h3>No hay horarios disponibles</h3>
                <p>Selecciona un grupo para ver su horario semanal.</p>
            </div>
        <?php endif; ?>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../alertaLogout.js"></script>

</body>
</html>