<?php
function conectarDB() {
    try {
        return ['success' => true, 'connection' => new PDO(
            "mysql:host=127.0.0.1;dbname=db_WinKnow;charset=utf8mb4",
            "root", "",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        )];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()];
    }
}

function listarUsuarios() {
    $conn = conectarDB();
    if (!$conn['success']) return ['success' => false, 'message' => $conn['message'], 'data' => []];
    
    try {
        $db = $conn['connection'];
        
        // Primero obtener todos los usuarios básicos
        $stmt = $db->prepare("SELECT Cedula, Nombre_usr FROM usuarios WHERE Cedula != '0' ORDER BY Nombre_usr");
        $stmt->execute();
        $usuarios_base = $stmt->fetchAll();
        
        $usuarios = [];
        
        // Enriquecer cada usuario con su información adicional
        foreach ($usuarios_base as $user) {
            $cedula = $user['Cedula'];
            
            // Obtener email y teléfono
            $stmt_email = $db->prepare("SELECT email, numeroTelefono FROM email WHERE Cedula = ?");
            $stmt_email->execute([$cedula]);
            $email_data = $stmt_email->fetch() ?: ['email' => '', 'numeroTelefono' => ''];
            
            // Determinar tipo de usuario
            $tipo_usuario = 'Sin tipo';
            $rolAdmin = null;
            
            $stmt_admin = $db->prepare("SELECT rolAdmin FROM administrador WHERE Cedula = ?");
            $stmt_admin->execute([$cedula]);
            if ($admin = $stmt_admin->fetch()) {
                $tipo_usuario = 'Administrador';
                $rolAdmin = $admin['rolAdmin'];
            } else {
                $stmt_doc = $db->prepare("SELECT Cedula FROM docente WHERE Cedula = ?");
                $stmt_doc->execute([(int)$cedula]);
                if ($stmt_doc->fetch()) {
                    $tipo_usuario = 'Docente';
                } else {
                    $stmt_est = $db->prepare("SELECT Cedula FROM estudiante WHERE Cedula = ?");
                    $stmt_est->execute([(int)$cedula]);
                    if ($stmt_est->fetch()) {
                        $tipo_usuario = 'Estudiante';
                    }
                }
            }
            
            $usuarios[] = [
                'Cedula' => $cedula,
                'Nombre_usr' => $user['Nombre_usr'],
                'email' => $email_data['email'],
                'numeroTelefono' => $email_data['numeroTelefono'],
                'tipo_usuario' => $tipo_usuario,
                'rolAdmin' => $rolAdmin
            ];
        }
        
        return ['success' => true, 'message' => 'Usuarios obtenidos correctamente', 'data' => $usuarios];
        
    } catch (PDOException $e) {
        error_log("Error en listarUsuarios: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage(), 'data' => []];
    }
}

function agregarUsuario($cedula, $contrasenia, $nombre, $tipo_usuario, $email = '', $telefono = '', $datos_adicionales = []) {
    $conn = conectarDB();
    if (!$conn['success']) return ['success' => false, 'message' => $conn['message']];
    
    try {
        $db = $conn['connection'];
        $db->beginTransaction();
        
        $stmt = $db->prepare("SELECT Cedula FROM usuarios WHERE Cedula = ?");
        $stmt->execute([$cedula]);
        if ($stmt->fetch()) {
            $db->rollBack();
            return ['success' => false, 'message' => 'La cédula ya está registrada'];
        }
        
        $hash_pass = password_hash($contrasenia, PASSWORD_DEFAULT);
        
        $db->prepare("INSERT INTO usuarios (Cedula, Contrasenia, Nombre_usr) VALUES (?, ?, ?)")->execute([$cedula, $hash_pass, $nombre]);
        $db->prepare("INSERT INTO email (Cedula, numeroTelefono, email) VALUES (?, ?, ?)")->execute([$cedula, $telefono, $email]);
        
        switch ($tipo_usuario) {
            case 'docente':
                $db->prepare("INSERT INTO docente (Cedula, contrasenia) VALUES (?, ?)")->execute([$cedula, $hash_pass]);
                break;
            case 'admin':
                $db->prepare("INSERT INTO administrador (Cedula, rolAdmin) VALUES (?, ?)")->execute([$cedula, $datos_adicionales['rolAdmin'] ?? 'ADMIN']);
                break;
            case 'estudiante':
                $db->prepare("INSERT INTO estudiante (Cedula) VALUES (?)")->execute([$cedula]);
                break;
            default:
                $db->rollBack();
                return ['success' => false, 'message' => 'Tipo de usuario no válido'];
        }
        
        $db->commit();
        return ['success' => true, 'message' => 'Usuario agregado correctamente'];
    } catch (PDOException $e) {
        if (isset($db)) $db->rollBack();
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}

function modificarUsuario($cedula, $datos) {
    $conn = conectarDB();
    if (!$conn['success']) return ['success' => false, 'message' => $conn['message']];
    
    try {
        $db = $conn['connection'];
        $db->beginTransaction();
        
        $stmt = $db->prepare("SELECT Cedula FROM usuarios WHERE Cedula = ?");
        $stmt->execute([$cedula]);
        if (!$stmt->fetch()) {
            $db->rollBack();
            return ['success' => false, 'message' => 'El usuario no existe'];
        }
        
        if (!empty($datos['nombre'])) 
            $db->prepare("UPDATE usuarios SET Nombre_usr = ? WHERE Cedula = ?")->execute([$datos['nombre'], $cedula]);
        
        if (!empty($datos['nueva_contrasenia'])) {
            $hash_pass = password_hash($datos['nueva_contrasenia'], PASSWORD_DEFAULT);
            $db->prepare("UPDATE usuarios SET Contrasenia = ? WHERE Cedula = ?")->execute([$hash_pass, $cedula]);
            if ($datos['tipo_usuario'] === 'Docente')
                $db->prepare("UPDATE docente SET contrasenia = ? WHERE Cedula = ?")->execute([$hash_pass, $cedula]);
        }
        
        $db->prepare("UPDATE email SET email = ?, numeroTelefono = ? WHERE Cedula = ?")->execute([$datos['email'] ?? '', $datos['telefono'] ?? '', $cedula]);
        
        switch ($datos['tipo_usuario']) {
            case 'Docente':
                if (isset($datos['estado_docente']))
                    $db->prepare("UPDATE docente SET estado_docente = ? WHERE Cedula = ?")->execute([$datos['estado_docente'], $cedula]);
                break;
            case 'Administrador':
                if (isset($datos['rolAdmin']))
                    $db->prepare("UPDATE administrador SET rolAdmin = ? WHERE Cedula = ?")->execute([$datos['rolAdmin'], $cedula]);
                break;
        }
        
        $db->commit();
        return ['success' => true, 'message' => 'Usuario actualizado correctamente'];
    } catch (PDOException $e) {
        if (isset($db)) $db->rollBack();
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}

function eliminarUsuario($cedula) {
    $conn = conectarDB();
    if (!$conn['success']) return ['success' => false, 'message' => $conn['message']];
    
    try {
        $db = $conn['connection'];
        $db->beginTransaction();
        
        $stmt = $db->prepare("SELECT Cedula FROM usuarios WHERE Cedula = ?");
        $stmt->execute([$cedula]);
        if (!$stmt->fetch()) {
            $db->rollBack();
            return ['success' => false, 'message' => 'El usuario no existe'];
        }
        
        foreach (['administrador', 'docente', 'estudiante', 'email'] as $tabla)
            $db->prepare("DELETE FROM $tabla WHERE Cedula = ?")->execute([$cedula]);
        
        $db->prepare("DELETE FROM usuarios WHERE Cedula = ?")->execute([$cedula]);
        $db->commit();
        return ['success' => true, 'message' => 'Usuario eliminado correctamente'];
    } catch (PDOException $e) {
        if (isset($db)) $db->rollBack();
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}

function obtenerEstadisticasUsuarios() {
    $conn = conectarDB();
    if (!$conn['success']) return ['success' => false, 'message' => $conn['message'], 'data' => []];
    
    try {
        $db = $conn['connection'];
        $estadisticas = ['total' => 0, 'docentes' => 0, 'estudiantes' => 0, 'administradores' => 0];
        $queries = [
            'total' => "SELECT COUNT(*) as total FROM usuarios WHERE Cedula != '0'",
            'docentes' => "SELECT COUNT(*) as total FROM docente WHERE Cedula != 0",
            'estudiantes' => "SELECT COUNT(*) as total FROM estudiante",
            'administradores' => "SELECT COUNT(*) as total FROM administrador WHERE Cedula != '0'"
        ];
        foreach ($queries as $key => $query)
            $estadisticas[$key] = $db->query($query)->fetch()['total'];
        return ['success' => true, 'message' => 'Estadísticas obtenidas', 'data' => $estadisticas];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage(), 'data' => []];
    }
}

function obtenerUsuario($cedula) {
    $conn = conectarDB();
    if (!$conn['success']) return ['success' => false, 'message' => $conn['message'], 'data' => null];
    
    try {
        $stmt = $conn['connection']->prepare("SELECT u.Cedula, u.Nombre_usr,
            COALESCE(e.numeroTelefono, '') as numeroTelefono, COALESCE(e.email, '') as email,
            CASE WHEN a.Cedula IS NOT NULL THEN 'Administrador'
                 WHEN d.Cedula IS NOT NULL THEN 'Docente'
                 WHEN est.Cedula IS NOT NULL THEN 'Estudiante' ELSE 'Sin tipo' END as tipo_usuario,
            a.rolAdmin, d.estado_docente
            FROM usuarios u
            LEFT JOIN email e ON u.Cedula = e.Cedula
            LEFT JOIN administrador a ON u.Cedula = a.Cedula
            LEFT JOIN docente d ON u.Cedula = CAST(d.Cedula AS CHAR)
            LEFT JOIN estudiante est ON u.Cedula = CAST(est.Cedula AS CHAR)
            WHERE u.Cedula = ?");
        $stmt->execute([$cedula]);
        $usuario = $stmt->fetch();
        
        return $usuario 
            ? ['success' => true, 'message' => 'Usuario obtenido', 'data' => $usuario]
            : ['success' => false, 'message' => 'Usuario no encontrado', 'data' => null];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage(), 'data' => null];
    }
}
?>