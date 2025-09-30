<?php

function conectarBD() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "winknow";
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

function agregarUsuario($cedula, $contrasenia, $nombre, $tipo_usuario, $email = null, $telefono = null, $datos_adicionales = []) {
    try {
        $conn = conectarBD();
        $conn->beginTransaction();
        
        // Validar que la cédula no exista
        $stmt = $conn->prepare("SELECT Cedula FROM usuarios WHERE Cedula = ?");
        $stmt->execute([$cedula]);
        if ($stmt->fetch()) {
            throw new Exception("La cédula ya está registrada");
        }
        
        // Hash de la contraseña
        $hash_contrasenia = password_hash($contrasenia, PASSWORD_DEFAULT);
        
        // Insertar en tabla usuarios
        $stmt = $conn->prepare("INSERT INTO usuarios (Cedula, Contrasenia, Nombre_usr) VALUES (?, ?, ?)");
        $stmt->execute([$cedula, $hash_contrasenia, $nombre]);
        
        // Insertar email y teléfono (campo email es parte de la clave primaria, usar string vacío si no hay email)
        $email_valor = $email ? $email : '';
        $stmt = $conn->prepare("INSERT INTO email (Cedula, numeroTelefono, email) VALUES (?, ?, ?)");
        $stmt->execute([$cedula, $telefono, $email_valor]);
        
        // Insertar según tipo de usuario
        switch (strtolower($tipo_usuario)) {
            case 'docente':
                $anioInsercion = $datos_adicionales['anioIns'] ?? date('Y-m-d');
                $estado = $datos_adicionales['estado'] ?? 'Activo';
                
                $stmt = $conn->prepare("INSERT INTO docente (Cedula, contrasenia, AnioInsercion, Estado) VALUES (?, ?, ?, ?)");
                $stmt->execute([$cedula, $hash_contrasenia, $anioInsercion, $estado]);
                break;
                
            case 'admin':
                $rolAdmin = $datos_adicionales['rolAdmin'] ?? 'ADMIN';
                
                $stmt = $conn->prepare("INSERT INTO administrador (Cedula, EsAdmin, rolAdmin) VALUES (?, 1, ?)");
                $stmt->execute([$cedula, $rolAdmin]);
                break;
                
            case 'estudiante':
                $fechaNac = $datos_adicionales['fechaNac'] ?? null;
                
                $stmt = $conn->prepare("INSERT INTO estudiante (Cedula, FechaNac) VALUES (?, ?)");
                $stmt->execute([$cedula, $fechaNac]);
                break;
                
            default:
                throw new Exception("Tipo de usuario no válido");
        }
        
        $conn->commit();
        return ['success' => true, 'message' => 'Usuario agregado exitosamente'];
        
    } catch(Exception $e) {
        if (isset($conn)) {
            $conn->rollback();
        }
        return ['success' => false, 'message' => 'Error al agregar usuario: ' . $e->getMessage()];
    }
}

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
            FROM usuarios u
            LEFT JOIN email e ON u.Cedula = e.Cedula
            LEFT JOIN docente d ON u.Cedula = d.Cedula
            LEFT JOIN administrador a ON u.Cedula = a.Cedula
            LEFT JOIN estudiante est ON u.Cedula = est.Cedula
            WHERE u.Cedula != '0'
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
                d.Estado as estado_docente,
                d.AnioInsercion,
                a.rolAdmin,
                est.FechaNac
            FROM usuarios u
            LEFT JOIN email e ON u.Cedula = e.Cedula
            LEFT JOIN docente d ON u.Cedula = d.Cedula
            LEFT JOIN administrador a ON u.Cedula = a.Cedula
            LEFT JOIN estudiante est ON u.Cedula = est.Cedula
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

function modificarUsuario($cedula, $datos) {
    try {
        $conn = conectarBD();
        $conn->beginTransaction();
        
        if (isset($datos['nombre'])) {
            $stmt = $conn->prepare("UPDATE usuarios SET Nombre_usr = ? WHERE Cedula = ?");
            $stmt->execute([$datos['nombre'], $cedula]);
        }
        
        if (isset($datos['nueva_contrasenia']) && !empty($datos['nueva_contrasenia'])) {
            $hash_contrasenia = password_hash($datos['nueva_contrasenia'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usuarios SET Contrasenia = ? WHERE Cedula = ?");
            $stmt->execute([$hash_contrasenia, $cedula]);
            
            $stmt = $conn->prepare("UPDATE docente SET contrasenia = ? WHERE Cedula = ?");
            $stmt->execute([$hash_contrasenia, $cedula]);
        }
        
        if (isset($datos['email']) || isset($datos['telefono'])) {
            $email_valor = isset($datos['email']) ? $datos['email'] : '';
            $telefono_valor = isset($datos['telefono']) ? $datos['telefono'] : '';
            
            $stmt = $conn->prepare("UPDATE email SET email = ?, numeroTelefono = ? WHERE Cedula = ?");
            $stmt->execute([$email_valor, $telefono_valor, $cedula]);
        }
        
        if (isset($datos['tipo_usuario'])) {
            switch ($datos['tipo_usuario']) {
                case 'Docente':
                    if (isset($datos['estado_docente'])) {
                        $stmt = $conn->prepare("UPDATE docente SET Estado = ? WHERE Cedula = ?");
                        $stmt->execute([$datos['estado_docente'], $cedula]);
                    }
                    break;
                    
                case 'Administrador':
                    if (isset($datos['rolAdmin'])) {
                        $stmt = $conn->prepare("UPDATE administrador SET rolAdmin = ? WHERE Cedula = ?");
                        $stmt->execute([$datos['rolAdmin'], $cedula]);
                    }
                    break;
                    
                case 'Estudiante':
                    if (isset($datos['fechaNac'])) {
                        $stmt = $conn->prepare("UPDATE estudiante SET FechaNac = ? WHERE Cedula = ?");
                        $stmt->execute([$datos['fechaNac'], $cedula]);
                    }
                    break;
            }
        }
        
        $conn->commit();
        return ['success' => true, 'message' => 'Usuario modificado exitosamente'];
        
    } catch(PDOException $e) {
        if (isset($conn)) {
            $conn->rollback();
        }
        return ['success' => false, 'message' => 'Error al modificar usuario: ' . $e->getMessage()];
    }
}

function eliminarUsuario($cedula) {
    try {
        $conn = conectarBD();
        
        $stmt = $conn->prepare("SELECT Cedula FROM usuarios WHERE Cedula = ?");
        $stmt->execute([$cedula]);
        
        if (!$stmt->fetch()) {
            return ['success' => false, 'message' => 'Usuario no encontrado'];
        }
        
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE Cedula = ?");
        $stmt->execute([$cedula]);
        
        return ['success' => true, 'message' => 'Usuario eliminado exitosamente'];
        
    } catch(PDOException $e) {
        return ['success' => false, 'message' => 'Error al eliminar usuario: ' . $e->getMessage()];
    }
}

function cambiarEstadoDocente($cedula, $nuevo_estado) {
    try {
        $conn = conectarBD();
        
        $stmt = $conn->prepare("UPDATE docente SET Estado = ? WHERE Cedula = ?");
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

function obtenerEstadisticasUsuarios() {
    try {
        $conn = conectarBD();
        
        $query = "
            SELECT 
                (SELECT COUNT(*) FROM usuarios WHERE Cedula != '0') as total_usuarios,
                (SELECT COUNT(*) FROM docente WHERE Cedula != 0) as total_docentes,
                (SELECT COUNT(*) FROM administrador WHERE Cedula != '0') as total_administradores,
                (SELECT COUNT(*) FROM estudiante) as total_estudiantes,
                (SELECT COUNT(*) FROM docente WHERE Estado = 'Activo' AND Cedula != 0) as docentes_activos,
                (SELECT COUNT(*) FROM docente WHERE Estado = 'Inactivo' AND Cedula != 0) as docentes_inactivos
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