<?php
// INICIO DE SESIÓN Y CONFIGURACIÓN
session_start();
require("../conexion.php");
$mysqli = conectarDB();

// SEGURIDAD: VERIFICAR PERMISOS DE DOCENTE
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['tipo'] !== 'docente') {
    header("Location: ../login_reg/login.php");
    exit;
}

// ============================================
// PROCESAR CREACIÓN DE RESERVA
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_reserva'])) {
    $idEspacio = intval($_POST['id_espacio']);
    $fecha = $_POST['fecha'];
    $horaReserva = intval($_POST['hora_reserva']);
    
    // Validar que la fecha no sea pasada
    $fechaActual = date('Y-m-d');
    if ($fecha < $fechaActual) {
        $_SESSION['mensaje'] = "No se puede reservar una fecha pasada.";
        $_SESSION['tipo_mensaje'] = "error";
    } else {
        // Verificar si ya existe una reserva para ese espacio, fecha y hora
        $sqlCheck = "SELECT COUNT(*) as total FROM Reserva 
                     WHERE IdEspacio = ? AND Fecha = ? AND Hora_Reserva = ?";
        $stmtCheck = $mysqli->prepare($sqlCheck);
        $stmtCheck->bind_param("isi", $idEspacio, $fecha, $horaReserva);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        $row = $resultCheck->fetch_assoc();
        
        if ($row['total'] > 0) {
            $_SESSION['mensaje'] = "Ya existe una reserva para ese espacio en esa fecha y hora.";
            $_SESSION['tipo_mensaje'] = "error";
        } else {
            // Insertar la reserva
            $sqlInsert = "INSERT INTO Reserva (IdEspacio, Fecha, Hora_Reserva) VALUES (?, ?, ?)";
            $stmtInsert = $mysqli->prepare($sqlInsert);
            $stmtInsert->bind_param("isi", $idEspacio, $fecha, $horaReserva);
            
            if ($stmtInsert->execute()) {
                $_SESSION['mensaje'] = "Reserva creada exitosamente.";
                $_SESSION['tipo_mensaje'] = "exito";
            } else {
                $_SESSION['mensaje'] = "Error al crear la reserva: " . $mysqli->error;
                $_SESSION['tipo_mensaje'] = "error";
            }
            $stmtInsert->close();
        }
        $stmtCheck->close();
    }
    
    header("Location: reservar_espacios.php");
    exit;
}

// ============================================
// PROCESAR ELIMINACIÓN DE RESERVA
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_reserva'])) {
    $idReserva = intval($_POST['id_reserva']);
    
    $sqlDelete = "DELETE FROM Reserva WHERE IdReserva = ?";
    $stmtDelete = $mysqli->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $idReserva);
    
    if ($stmtDelete->execute()) {
        $_SESSION['mensaje'] = "Reserva eliminada exitosamente.";
        $_SESSION['tipo_mensaje'] = "exito";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar la reserva: " . $mysqli->error;
        $_SESSION['tipo_mensaje'] = "error";
    }
    $stmtDelete->close();
    
    header("Location: reservar_espacios.php");
    exit;
}

// ============================================
// OBTENER DATOS PARA EL FORMULARIO
// ============================================
$idEspacioSeleccionado = isset($_GET['id_espacio']) ? intval($_GET['id_espacio']) : 0;

// Obtener todos los espacios
$queryEspacios = "SELECT * FROM Espacios ORDER BY NumSalon";
$resultEspacios = $mysqli->query($queryEspacios);

// Obtener información del espacio seleccionado
$espacioSeleccionado = null;
if ($idEspacioSeleccionado > 0) {
    $sqlEspacio = "SELECT * FROM Espacios WHERE IdEspacio = ?";
    $stmtEspacio = $mysqli->prepare($sqlEspacio);
    $stmtEspacio->bind_param("i", $idEspacioSeleccionado);
    $stmtEspacio->execute();
    $resultEspacio = $stmtEspacio->get_result();
    $espacioSeleccionado = $resultEspacio->fetch_assoc();
    $stmtEspacio->close();
}

// ============================================
// OBTENER RESERVAS ACTIVAS (FUTURAS Y ACTUALES)
// ============================================
$fechaActual = date('Y-m-d');
$queryReservas = "SELECT r.IdReserva, r.Fecha, r.Hora_Reserva, 
                         e.NumSalon, e.Tipo_salon, e.capacidad
                  FROM Reserva r
                  INNER JOIN Espacios e ON r.IdEspacio = e.IdEspacio
                  WHERE r.Fecha >= ?
                  ORDER BY r.Fecha ASC, r.Hora_Reserva ASC";
$stmtReservas = $mysqli->prepare($queryReservas);
$stmtReservas->bind_param("s", $fechaActual);
$stmtReservas->execute();
$resultReservas = $stmtReservas->get_result();

// ============================================
// FUNCIONES DE UTILIDAD
// ============================================
function obtenerNombreEspacio($tipo, $numSalon) {
    return match ($tipo) {
        'Taller' => 'Taller ' . $numSalon,
        'Salon'  => 'Salon ' . $numSalon,
        'Laboratorio' => 'Laboratorio ' . $numSalon,
        default  => 'Aula ' . $numSalon
    };
}

function formatearFecha($fecha) {
    $fechaObj = new DateTime($fecha);
    return $fechaObj->format('d/m/Y');
}

function formatearHora($hora) {
    return $hora . ':00 hs';
}

// ============================================
// INCLUIR HEADER
// ============================================
include '../front/header.html';
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

        <!-- BOTÓN VOLVER -->
        <section>
            <a href="docente_reservas.php" class="boton-secundario">
                <i class="bi bi-arrow-left"></i> Volver a Espacios
            </a>
        </section>
        <br><br>

        <!-- FORMULARIO DE RESERVA -->
        <section class="formulario-reserva">
            <h2><i class="bi bi-calendar-plus"></i> Nueva Reserva</h2>
            <br>
            <form method="POST" action="reservar_espacios.php">
                <input type="hidden" name="crear_reserva" value="1">
                
                <div class="campo-formulario">
                    <label for="id_espacio">Espacio:</label>
                    <select name="id_espacio" id="id_espacio" required onchange="this.form.submit()">
                        <option value="">Seleccione un espacio</option>
                        <?php 
                        $resultEspacios->data_seek(0);
                        while ($espacio = $resultEspacios->fetch_assoc()): 
                        ?>
                            <option value="<?= $espacio['IdEspacio']; ?>" 
                                    <?= $idEspacioSeleccionado == $espacio['IdEspacio'] ? 'selected' : ''; ?>>
                                <?= obtenerNombreEspacio($espacio['Tipo_salon'], $espacio['NumSalon']); ?> 
                                (Capacidad: <?= $espacio['capacidad']; ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <?php if ($espacioSeleccionado): ?>
                    <div class="info-espacio-seleccionado">
                        <p><strong>Tipo:</strong> <?= $espacioSeleccionado['Tipo_salon']; ?></p>
                        <p><strong>Capacidad:</strong> <?= $espacioSeleccionado['capacidad']; ?> personas</p>
                    </div>
                    <br>

                    <div class="campo-formulario">
                        <label for="fecha">Fecha de Reserva:</label>
                        <input type="date" name="fecha" id="fecha" 
                               min="<?= date('Y-m-d'); ?>" required>
                    </div>

                    <div class="campo-formulario">
                        <label for="hora_reserva">Hora de Reserva:</label>
                        <select name="hora_reserva" id="hora_reserva" required>
                            <option value="">Seleccione una hora</option>
                            <?php for ($i = 8; $i <= 22; $i++): ?>
                                <option value="<?= $i; ?>"><?= $i; ?>:00 hs</option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <br>
                    <button type="submit" class="boton-primario">
                        <i class="bi bi-check-circle"></i> Confirmar Reserva
                    </button>
                <?php endif; ?>
            </form>
        </section>

        <br><br>

        <!-- RESERVAS ACTIVAS -->
        <section class="reservas-activas">
            <h2><i class="bi bi-calendar-check"></i> Reservas Activas</h2>
            <br>
            
            <?php if ($resultReservas->num_rows > 0): ?>
                <div class="tabla-contenedor">
                    <table class="tabla-reservas">
                        <thead>
                            <tr>
                                <th>Espacio</th>
                                <th>Tipo</th>
                                <th>Capacidad</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($reserva = $resultReservas->fetch_assoc()): ?>
                                <tr>
                                    <td><?= obtenerNombreEspacio($reserva['Tipo_salon'], $reserva['NumSalon']); ?></td>
                                    <td><?= htmlspecialchars($reserva['Tipo_salon']); ?></td>
                                    <td><?= $reserva['capacidad']; ?> personas</td>
                                    <td><?= formatearFecha($reserva['Fecha']); ?></td>
                                    <td><?= formatearHora($reserva['Hora_Reserva']); ?></td>
                                    <td>
                                        <form method="POST" action="reservar_espacios.php" style="display:inline;">
                                            <input type="hidden" name="eliminar_reserva" value="1">
                                            <input type="hidden" name="id_reserva" value="<?= $reserva['IdReserva']; ?>">
                                            <button type="submit" class="boton-eliminar" 
                                                    onclick="return confirm('¿Está seguro de eliminar esta reserva?')">
                                                <i class="bi bi-trash"></i> Cancelar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No hay reservas activas en este momento.</p>
            <?php endif; ?>
        </section>

    </div>

</main>

<?php
include '../front/navDOC.php';

// ============================================
// CIERRE DE CONEXIÓN
// ============================================
if ($mysqli) {
    $mysqli->close();
}
?>

</body>
</html>