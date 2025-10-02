    <?php
    session_start();
    require("../conexion.php");
    $mysqli = conectarDB();

    // Verificar que sea un administrador
    if (!isset($_SESSION['logged_in']) || $_SESSION['tipo'] !== 'admin') {
        header("Location: ../login_reg/login.php");
        exit;
    }

    // Variables para mensajes
    $mensaje = '';
    $tipo_mensaje = '';

    // PROCESAR REGISTRO DE NUEVO ESPACIO
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
        
        if ($_POST['accion'] === 'registrar') {
            $num_salon = $_POST['num_salon'];
            $capacidad = $_POST['capacidad'];
            $tipo_salon = $_POST['tipo_salon'];
            
            // Validar que no exista el número de salón
            $checkQuery = "SELECT NumSalon FROM Espacios WHERE NumSalon = ?";
            $checkStmt = $mysqli->prepare($checkQuery);
            $checkStmt->bind_param("i", $num_salon);
            $checkStmt->execute();
            $checkStmt->store_result();
            
            if ($checkStmt->num_rows > 0) {
                $mensaje = "El espacio con número $num_salon ya existe";
                $tipo_mensaje = 'error';
            } else {
                // Insertar nuevo espacio
                $insertQuery = "INSERT INTO Espacios (NumSalon, capacidad, Tipo_salon) VALUES (?, ?, ?)";
                $insertStmt = $mysqli->prepare($insertQuery);
                $insertStmt->bind_param("iis", $num_salon, $capacidad, $tipo_salon);
                
                if ($insertStmt->execute()) {
                    $mensaje = "Espacio $tipo_salon $num_salon registrado exitosamente";
                    $tipo_mensaje = 'success';
                } else {
                    $mensaje = "Error al registrar el espacio: " . $mysqli->error;
                    $tipo_mensaje = 'error';
                }
                $insertStmt->close();
            }
            $checkStmt->close();
        }
        
        // PROCESAR ELIMINACIÓN DE ESPACIO
        if ($_POST['accion'] === 'eliminar') {
            $id_espacio = $_POST['id_espacio'];
            
            // Primero eliminar recursos asociados
            $deleteRecursos = "DELETE FROM Recursos WHERE IDEspacio = ?";
            $stmtRecursos = $mysqli->prepare($deleteRecursos);
            $stmtRecursos->bind_param("i", $id_espacio);
            $stmtRecursos->execute();
            $stmtRecursos->close();
            
            // Eliminar reservas asociadas
            $deleteReservas = "DELETE FROM Reserva WHERE IDEspacio = ?";
            $stmtReservas = $mysqli->prepare($deleteReservas);
            $stmtReservas->bind_param("i", $id_espacio);
            $stmtReservas->execute();
            $stmtReservas->close();
            
            // Finalmente eliminar el espacio
            $deleteEspacio = "DELETE FROM Espacios WHERE IDEspacio = ?";
            $stmtEspacio = $mysqli->prepare($deleteEspacio);
            $stmtEspacio->bind_param("i", $id_espacio);
            
            if ($stmtEspacio->execute()) {
                $mensaje = "Espacio eliminado exitosamente";
                $tipo_mensaje = 'success';
            } else {
                $mensaje = "Error al eliminar el espacio: " . $mysqli->error;
                $tipo_mensaje = 'error';
            }
            $stmtEspacio->close();
        }
    }

    // OBTENER TODOS LOS ESPACIOS PARA MOSTRAR
    $espaciosQuery = "SELECT IDEspacio, NumSalon, capacidad, Tipo_salon FROM Espacios ORDER BY NumSalon";
    $resultEspacios = $mysqli->query($espaciosQuery);

    $mysqli->close();

    include '../front/header.html';
    ?>


<body>
    <div class="container">
        <div class="card">
            <h1>Gestión de Espacios</h1>
            <p class="subtitle">Sistema de registro y administración de espacios del ITSP</p>
            
            <?php if ($mensaje): ?>
                <div class="mensaje <?php echo $tipo_mensaje; ?>">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>
            
            <!-- ESTADÍSTICAS -->
            <div class="stats">
                <?php
                $mysqli = conectarDB();
                $statsQuery = "SELECT 
                    COUNT(*) as total,
                    SUM(capacidad) as capacidad_total,
                    COUNT(CASE WHEN Tipo_salon = 'Aula' THEN 1 END) as aulas,
                    COUNT(CASE WHEN Tipo_salon = 'Taller' THEN 1 END) as talleres,
                    COUNT(CASE WHEN Tipo_salon = 'Laboratorio' THEN 1 END) as laboratorios,
                    COUNT(CASE WHEN Tipo_salon = 'Salon' THEN 1 END) as salones
                FROM Espacios";
                $statsResult = $mysqli->query($statsQuery);
                $stats = $statsResult->fetch_assoc();
                ?>
             
            </div>
            
            <!-- FORMULARIO DE REGISTRO -->
            <h2> Registrar Nuevo Espacio</h2>
            <form method="POST" action="">
                <input type="hidden" name="accion" value="registrar">
                
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
                
                <div class="form-group">
                    <label for="tipo_salon">Tipo de Espacio:</label>
                    <select id="tipo_salon" name="tipo_salon" required>
                        <option value="">-- Seleccione un tipo --</option>
                        <option value="Aula">📚 Aula</option>
                        <option value="Taller">🔧 Taller</option>
                        <option value="Laboratorio">🧪 Laboratorio</option>
                        <option value="Salon">🏫 Salón</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary"> Registrar Espacio</button>
            </form>
        </div>
        
       
</body>
</html>