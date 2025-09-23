<?php
// funciones_usuarios.php
// Archivo con las funciones para gestión de usuarios

// Configuración de la base de datos
function conectarBD() {
    $servername = "localhost";
    $username = "root"; // Cambia según tu configuración
    $password = "";     // Cambia según tu configuración
    $dbname = "winknow"; // Cambia según el nombre de tu BD
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

// Función para agregar un nuevo usuario
function agregarUsuario($cedula, $contrasenia, $nombre, $tipo, $email = null, $telefono = null, $datos_adicionales = []) {
    try {
        $conn = conectarBD();
        $conn->beginTransaction();
        
        // Hash de la contraseña
        $hash_contrasenia = password_hash($contrasenia, PASSWORD_DEFAULT);
        
        // Insertar en tabla Usuarios
        $stmt = $conn->prepare("INSERT INTO Usuarios (Cedula, Contrasenia, Nombre_usr) VALUES (?, ?, ?)");
        $stmt->execute([$cedula, $hash_contrasenia, $nombre]);
        
        // Insertar email si se proporcionó
        if ($email) {
            $stmt = $conn->prepare("INSERT INTO Email (Cedula, numeroTelefono, email) VALUES (?, ?, ?)");
            $stmt->execute([$cedula, $telefono, $email]);
        }
        
        // Insertar en tabla específica según el tipo
        switch ($tipo) {
            case 'docente':
                $grado = $datos_adicionales['grado'] ?? 1;
                $estado = $datos_adicionales['estado'] ?? 'Activo';
                $stmt = $conn->prepare("INSERT INTO Docente (Cedula, grado, contrasenia, AnioInsercion, Estado) VALUES (?, ?, ?, CURDATE(), ?)");
                $stmt->execute([$cedula, $grado, $hash_contrasenia, $estado]);
                break;
                
            case 'admin':
                $rolAdmin = $datos_adicionales['rolAdmin'] ?? 'Administrador General';
                $stmt = $conn->prepare("INSERT INTO Administrador (Cedula, EsAdmin, rolAdmin) VALUES (?, 1, ?)");
                $stmt->execute([$cedula, $rolAdmin]);
                break;
                
            case 'estudiante':
                $fechaNac = $datos_adicionales['fechaNac'] ?? null;
                $stmt = $conn->prepare("INSERT INTO Estudiante (Cedula, FechaNac) VALUES (?, ?)");
                $stmt->execute([$cedula, $fechaNac]);
                break;
        }
        
        $conn->commit();
        return ['success' => true, 'message' => 'Usuario agregado exitosamente'];
        
    } catch(PDOException $e) {
        $conn->rollback();
        return ['success' => false, 'message' => 'Error al agregar usuario: ' . $e->getMessage()];
    }
}

// Función para listar todos los usuarios
function listarUsuarios() {
    try {
        $conn = conectarBD();
        
        $query = "
            SELECT 
                u.Cedula,
                u.Nombre_usr,
                e.email,
                e.numeroTelefono,
                CASE 
                    WHEN d.Cedula IS NOT NULL THEN 'Docente'
                    WHEN a.Cedula IS NOT NULL THEN 'Administrador'
                    WHEN est.Cedula IS NOT NULL THEN 'Estudiante'
                    ELSE 'Sin rol'
                END as tipo_usuario,
                d.Estado as estado_docente,
                a.rolAdmin,
                est.FechaNac
            FROM Usuarios u
            LEFT JOIN Email e ON u.Cedula = e.Cedula
            LEFT JOIN Docente d ON u.Cedula = d.Cedula
            LEFT JOIN Administrador a ON u.Cedula = a.Cedula
            LEFT JOIN Estudiante est ON u.Cedula = est.Cedula
            ORDER BY u.Nombre_usr
        ";
        
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ['success' => true, 'data' => $usuarios];
        
    } catch(PDOException $e) {
        return ['success' => false, 'message' => 'Error al listar usuarios: ' . $e->getMessage()];
    }
}

// Función para obtener un usuario específico por cédula
function obtenerUsuario($cedula) {
    try {
        $conn = conectarBD();
        
        $query = "
            SELECT 
                u.Cedula,
                u.Nombre_usr,
                e.email,
                e.numeroTelefono,
                CASE 
                    WHEN d.Cedula IS NOT NULL THEN 'Docente'
                    WHEN a.Cedula IS NOT NULL THEN 'Administrador'
                    WHEN est.Cedula IS NOT NULL THEN 'Estudiante'
                    ELSE 'Sin rol'
                END as tipo_usuario,
                d.grado,
                d.Estado as estado_docente,
                d.AnioInsercion,
                a.rolAdmin,
                est.FechaNac
            FROM Usuarios u
            LEFT JOIN Email e ON u.Cedula = e.Cedula
            LEFT JOIN Docente d ON u.Cedula = d.Cedula
            LEFT JOIN Administrador a ON u.Cedula = a.Cedula
            LEFT JOIN Estudiante est ON u.Cedula = est.Cedula
            WHERE u.Cedula = ?
        ";
        
        $stmt = $conn->prepare($query);
        $stmt->execute([$cedula]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario) {
            return ['success' => true, 'data' => $usuario];
        } else {
            return ['success' => false, 'message' => 'Usuario no encontrado'];
        }
        
    } catch(PDOException $e) {
        return ['success' => false, 'message' => 'Error al obtener usuario: ' . $e->getMessage()];
    }
}

// Función para modificar un usuario
function modificarUsuario($cedula, $datos) {
    try {
        $conn = conectarBD();
        $conn->beginTransaction();
        
        // Actualizar datos básicos del usuario
        if (isset($datos['nombre'])) {
            $stmt = $conn->prepare("UPDATE Usuarios SET Nombre_usr = ? WHERE Cedula = ?");
            $stmt->execute([$datos['nombre'], $cedula]);
        }
        
        // Actualizar contraseña si se proporcionó
        if (isset($datos['nueva_contrasenia']) && !empty($datos['nueva_contrasenia'])) {
            $hash_contrasenia = password_hash($datos['nueva_contrasenia'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE Usuarios SET Contrasenia = ? WHERE Cedula = ?");
            $stmt->execute([$hash_contrasenia, $cedula]);
            
            // También actualizar en tabla Docente si es docente
            $stmt = $conn->prepare("UPDATE Docente SET contrasenia = ? WHERE Cedula = ?");
            $stmt->execute([$hash_contrasenia, $cedula]);
        }
        
        // Actualizar email
        if (isset($datos['email'])) {
            $stmt = $conn->prepare("
                INSERT INTO Email (Cedula, email, numeroTelefono) VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE email = VALUES(email), numeroTelefono = VALUES(numeroTelefono)
            ");
            $stmt->execute([$cedula, $datos['email'], $datos['telefono'] ?? null]);
        }
        
        // Actualizar datos específicos según el tipo
        if (isset($datos['tipo_usuario'])) {
            switch ($datos['tipo_usuario']) {
                case 'Docente':
                    if (isset($datos['estado_docente'])) {
                        $stmt = $conn->prepare("UPDATE Docente SET Estado = ? WHERE Cedula = ?");
                        $stmt->execute([$datos['estado_docente'], $cedula]);
                    }
                    if (isset($datos['grado'])) {
                        $stmt = $conn->prepare("UPDATE Docente SET grado = ? WHERE Cedula = ?");
                        $stmt->execute([$datos['grado'], $cedula]);
                    }
                    break;
                    
                case 'Administrador':
                    if (isset($datos['rolAdmin'])) {
                        $stmt = $conn->prepare("UPDATE Administrador SET rolAdmin = ? WHERE Cedula = ?");
                        $stmt->execute([$datos['rolAdmin'], $cedula]);
                    }
                    break;
                    
                case 'Estudiante':
                    if (isset($datos['fechaNac'])) {
                        $stmt = $conn->prepare("UPDATE Estudiante SET FechaNac = ? WHERE Cedula = ?");
                        $stmt->execute([$datos['fechaNac'], $cedula]);
                    }
                    break;
            }
        }
        
        $conn->commit();
        return ['success' => true, 'message' => 'Usuario modificado exitosamente'];
        
    } catch(PDOException $e) {
        $conn->rollback();
        return ['success' => false, 'message' => 'Error al modificar usuario: ' . $e->getMessage()];
    }
}

// Función para eliminar un usuario
function eliminarUsuario($cedula) {
    try {
        $conn = conectarBD();
        
        // Verificar que el usuario existe
        $stmt = $conn->prepare("SELECT Cedula FROM Usuarios WHERE Cedula = ?");
        $stmt->execute([$cedula]);
        
        if (!$stmt->fetch()) {
            return ['success' => false, 'message' => 'Usuario no encontrado'];
        }
        
        // Eliminar usuario (las claves foráneas con CASCADE se encargan del resto)
        $stmt = $conn->prepare("DELETE FROM Usuarios WHERE Cedula = ?");
        $stmt->execute([$cedula]);
        
        return ['success' => true, 'message' => 'Usuario eliminado exitosamente'];
        
    } catch(PDOException $e) {
        return ['success' => false, 'message' => 'Error al eliminar usuario: ' . $e->getMessage()];
    }
}

// Función para cambiar estado de un docente
function cambiarEstadoDocente($cedula, $nuevo_estado) {
    try {
        $conn = conectarBD();
        
        $stmt = $conn->prepare("UPDATE Docente SET Estado = ? WHERE Cedula = ?");
        $stmt->execute([$nuevo_estado, $cedula]);
        
        if ($stmt->rowCount() > 0) {
            return ['success' => true, 'message' => 'Estado actualizado exitosamente'];
        } else {
            return ['success' => false, 'message' => 'No se pudo actualizar el estado'];
        }
        
    } catch(PDOException $e) {
        return ['success' => false, 'message' => 'Error al cambiar estado: ' . $e->getMessage()];
    }
}

// Función para obtener estadísticas de usuarios
function obtenerEstadisticasUsuarios() {
    try {
        $conn = conectarBD();
        
        $query = "
            SELECT 
                (SELECT COUNT(*) FROM Usuarios) as total_usuarios,
                (SELECT COUNT(*) FROM Docente) as total_docentes,
                (SELECT COUNT(*) FROM Administrador) as total_administradores,
                (SELECT COUNT(*) FROM Estudiante) as total_estudiantes,
                (SELECT COUNT(*) FROM Docente WHERE Estado = 'Activo') as docentes_activos,
                (SELECT COUNT(*) FROM Docente WHERE Estado = 'Inactivo') as docentes_inactivos
        ";
        
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $estadisticas = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return ['success' => true, 'data' => $estadisticas];
        
    } catch(PDOException $e) {
        return ['success' => false, 'message' => 'Error al obtener estadísticas: ' . $e->getMessage()];
    }
}
?>