<?php
// Función para conectar a la base de datos
function conectarDB() {
    try {
        $conn = new PDO(
            "mysql:host=127.0.0.1;dbname=winknow;charset=utf8mb4",
            "root",
            "",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        return ['success' => true, 'connection' => $conn];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()];
    }
}

// Función para listar todos los usuarios
function listarUsuarios() {
    $conn = conectarDB();
    if (!$conn['success']) {
        return ['success' => false, 'message' => $conn['message'], 'data' => []];
    }
    
    try {
        $db = $conn['connection'];
        
        // Consulta para obtener todos los usuarios con su información completa
        $query = "SELECT DISTINCT
                    u.Cedula,
                    u.Nombre_usr,
                    COALESCE(e.numeroTelefono, '') as numeroTelefono,
                    COALESCE(e.email, '') as email,
                    CASE 
                        WHEN a.Cedula IS NOT NULL THEN 'Administrador'
                        WHEN d.Cedula IS NOT NULL THEN 'Docente'
                        WHEN est.Cedula IS NOT NULL THEN 'Estudiante'
                        ELSE 'Sin tipo'
                    END as tipo_usuario,
                    a.rolAdmin,
                    CASE 
                        WHEN a.Cedula IS NOT NULL THEN a.codigo_adm
                        WHEN d.Cedula IS NOT NULL THEN d.codigo_doc
                        ELSE NULL
                    END as codigo
                FROM usuarios u
                LEFT JOIN email e ON u.Cedula = e.Cedula
                LEFT JOIN administrador a ON u.Cedula = a.Cedula
                LEFT JOIN docente d ON u.Cedula = CAST(d.Cedula AS CHAR)
                LEFT JOIN estudiante est ON u.Cedula = CAST(est.Cedula AS CHAR)
                WHERE u.Cedula != '0'
                ORDER BY u.Nombre_usr";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $usuarios = $stmt->fetchAll();
        
        return [
            'success' => true,
            'message' => 'Usuarios obtenidos correctamente',
            'data' => $usuarios
        ];
        
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Error al listar usuarios: ' . $e->getMessage(),
            'data' => []
        ];
    }
}

// Función para agregar un nuevo usuario
function agregarUsuario($cedula, $contrasenia, $nombre, $tipo_usuario, $email = '', $telefono = '', $datos_adicionales = []) {
    $conn = conectarDB();
    if (!$conn['success']) {
        return ['success' => false, 'message' => $conn['message']];
    }
    
    try {
        $db = $conn['connection'];
        $db->beginTransaction();
        
        // Verificar si el usuario ya existe
        $stmt = $db->prepare("SELECT Cedula FROM usuarios WHERE Cedula = ?");
        $stmt->execute([$cedula]);
        if ($stmt->fetch()) {
            $db->rollBack();
            return ['success' => false, 'message' => 'La cédula ya está registrada'];
        }
        
        // Hash de la contraseña
        $hash_pass = password_hash($contrasenia, PASSWORD_DEFAULT);
        
        // 1. Insertar en tabla usuarios
        $stmt = $db->prepare("INSERT INTO usuarios (Cedula, Contrasenia, Nombre_usr) VALUES (?, ?, ?)");
        $stmt->execute([$cedula, $hash_pass, $nombre]);
        
        // 2. Insertar en tabla email (siempre, aunque estén vacíos)
        $stmt = $db->prepare("INSERT INTO email (Cedula, numeroTelefono, email) VALUES (?, ?, ?)");
        $stmt->execute([$cedula, $telefono, $email]);
        
        // 3. Insertar en tabla específica según tipo
        switch ($tipo_usuario) {
            case 'docente':
                // Insertar en tabla docente
                $stmt = $db->prepare("INSERT INTO docente (Cedula, contrasenia) VALUES (?, ?)");
                $stmt->execute([$cedula, $hash_pass]);
                break;
                
            case 'admin':
                // Insertar en tabla administrador
                $rolAdmin = $datos_adicionales['rolAdmin'] ?? 'ADMIN';
                $stmt = $db->prepare("INSERT INTO administrador (Cedula, rolAdmin) VALUES (?, ?)");
                $stmt->execute([$cedula, $rolAdmin]);
                break;
                
            case 'estudiante':
                // Insertar en tabla estudiante
                $stmt = $db->prepare("INSERT INTO estudiante (Cedula) VALUES (?)");
                $stmt->execute([$cedula]);
                break;
                
            default:
                $db->rollBack();
                return ['success' => false, 'message' => 'Tipo de usuario no válido'];
        }
        
        $db->commit();
        return ['success' => true, 'message' => 'Usuario agregado correctamente'];
        
    } catch (PDOException $e) {
        if (isset($db)) {
            $db->rollBack();
        }
        return ['success' => false, 'message' => 'Error al agregar usuario: ' . $e->getMessage()];
    }
}

// Función para eliminar un usuario
function eliminarUsuario($cedula) {
    $conn = conectarDB();
    if (!$conn['success']) {
        return ['success' => false, 'message' => $conn['message']];
    }
    
    try {
        $db = $conn['connection'];
        $db->beginTransaction();
        
        // Verificar que el usuario existe
        $stmt = $db->prepare("SELECT Cedula FROM usuarios WHERE Cedula = ?");
        $stmt->execute([$cedula]);
        if (!$stmt->fetch()) {
            $db->rollBack();
            return ['success' => false, 'message' => 'El usuario no existe'];
        }
        
        // Eliminar de tablas específicas (gracias a CASCADE se eliminará automáticamente)
        // Pero lo hacemos manualmente para tener control
        $stmt = $db->prepare("DELETE FROM administrador WHERE Cedula = ?");
        $stmt->execute([$cedula]);
        
        $stmt = $db->prepare("DELETE FROM docente WHERE Cedula = ?");
        $stmt->execute([$cedula]);
        
        $stmt = $db->prepare("DELETE FROM estudiante WHERE Cedula = ?");
        $stmt->execute([$cedula]);
        
        // Eliminar de email
        $stmt = $db->prepare("DELETE FROM email WHERE Cedula = ?");
        $stmt->execute([$cedula]);
        
        // Eliminar de usuarios
        $stmt = $db->prepare("DELETE FROM usuarios WHERE Cedula = ?");
        $stmt->execute([$cedula]);
        
        $db->commit();
        return ['success' => true, 'message' => 'Usuario eliminado correctamente'];
        
    } catch (PDOException $e) {
        if (isset($db)) {
            $db->rollBack();
        }
        return ['success' => false, 'message' => 'Error al eliminar usuario: ' . $e->getMessage()];
    }
}

// Función para obtener estadísticas de usuarios
function obtenerEstadisticasUsuarios() {
    $conn = conectarDB();
    if (!$conn['success']) {
        return ['success' => false, 'message' => $conn['message'], 'data' => []];
    }
    
    try {
        $db = $conn['connection'];
        
        $estadisticas = [
            'total' => 0,
            'docentes' => 0,
            'estudiantes' => 0,
            'administradores' => 0
        ];
        
        // Contar total de usuarios
        $stmt = $db->query("SELECT COUNT(*) as total FROM usuarios WHERE Cedula != '0'");
        $estadisticas['total'] = $stmt->fetch()['total'];
        
        // Contar docentes
        $stmt = $db->query("SELECT COUNT(*) as total FROM docente WHERE Cedula != 0");
        $estadisticas['docentes'] = $stmt->fetch()['total'];
        
        // Contar estudiantes
        $stmt = $db->query("SELECT COUNT(*) as total FROM estudiante");
        $estadisticas['estudiantes'] = $stmt->fetch()['total'];
        
        // Contar administradores
        $stmt = $db->query("SELECT COUNT(*) as total FROM administrador WHERE Cedula != '0'");
        $estadisticas['administradores'] = $stmt->fetch()['total'];
        
        return [
            'success' => true,
            'message' => 'Estadísticas obtenidas',
            'data' => $estadisticas
        ];
        
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Error al obtener estadísticas: ' . $e->getMessage(),
            'data' => []
        ];
    }
}

// Función para obtener un usuario específico
function obtenerUsuario($cedula) {
    $conn = conectarDB();
    if (!$conn['success']) {
        return ['success' => false, 'message' => $conn['message'], 'data' => null];
    }
    
    try {
        $db = $conn['connection'];
        
        $query = "SELECT 
                    u.Cedula,
                    u.Nombre_usr,
                    COALESCE(e.numeroTelefono, '') as numeroTelefono,
                    COALESCE(e.email, '') as email,
                    CASE 
                        WHEN a.Cedula IS NOT NULL THEN 'Administrador'
                        WHEN d.Cedula IS NOT NULL THEN 'Docente'
                        WHEN est.Cedula IS NOT NULL THEN 'Estudiante'
                        ELSE 'Sin tipo'
                    END as tipo_usuario,
                    a.rolAdmin
                FROM usuarios u
                LEFT JOIN email e ON u.Cedula = e.Cedula
                LEFT JOIN administrador a ON u.Cedula = a.Cedula
                LEFT JOIN docente d ON u.Cedula = CAST(d.Cedula AS CHAR)
                LEFT JOIN estudiante est ON u.Cedula = CAST(est.Cedula AS CHAR)
                WHERE u.Cedula = ?";
        
        $stmt = $db->prepare($query);
        $stmt->execute([$cedula]);
        $usuario = $stmt->fetch();
        
        if (!$usuario) {
            return ['success' => false, 'message' => 'Usuario no encontrado', 'data' => null];
        }
        
        return [
            'success' => true,
            'message' => 'Usuario obtenido',
            'data' => $usuario
        ];
        
    } catch (PDOException $e) {
        return [
            'success' => false,
            'message' => 'Error al obtener usuario: ' . $e->getMessage(),
            'data' => null
        ];
    }
}
?>