    <?php
    session_start();
    require("../conexion.php");

    // Función para limpiar cédula
    function clean_ci($ci) {
        return preg_replace('/\D/', '', $ci);
    }

    // Función para calcular dígito verificador
    function validation_digit($ci) {
        $ci = clean_ci($ci);
        // Rellenar a 7 dígitos con ceros a la izquierda
        $ci = str_pad($ci, 7, '0', STR_PAD_LEFT);
        
        $sum = 0;
        $baseNumber = "2987634";
        
        for ($i = 0; $i < 7; $i++) {
            $baseDigit = (int)$baseNumber[$i];
            $ciDigit = (int)$ci[$i];
            $sum += ($baseDigit * $ciDigit) % 10;
        }
        
        $remainder = $sum % 10;
        return $remainder === 0 ? 0 : 10 - $remainder;
    }

    // Función para validar cédula uruguaya
    function validate_ci($ci) {
        if (empty(trim($ci))) {
            return false;
        }
        
        $ci = clean_ci($ci);
        
        if (strlen($ci) < 7 || strlen($ci) > 8) {
            return false;
        }
        
        // Obtener el último dígito (dígito verificador)
        $validationDigit = (int)substr($ci, -1);
        
        // Obtener los primeros 6 o 7 dígitos (sin el dígito verificador)
        $ciWithoutCheck = substr($ci, 0, -1);
        
        // Calcular el dígito verificador esperado
        $expectedDigit = validation_digit($ciWithoutCheck);
        
        return $validationDigit === $expectedDigit;
    }

    $mysqli = conectarDB();

    // Variables para mensajes
    $mensaje = '';
    $tipo_mensaje = '';
    $redirigir = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $tipo = $_POST['operacion'] ?? '';
            $cedula_raw = ($_POST['cedula'] ?? '');
            $nombre = ($_POST['nombre'] ?? '');
            $telefono = ($_POST['telefono'] ?? '');
            $contra = $_POST['contra'] ?? '';

            // Validaciones básicas
            if (empty($tipo) || empty($cedula_raw) || empty($nombre) || empty($telefono) || empty($contra)) {
                throw new Exception("Todos los campos son obligatorios");
            }
            
            // Validar cédula uruguaya
            if (!validate_ci($cedula_raw)) {
                throw new Exception("La cédula ingresada no es válida");
            }
            
            // Limpiar y convertir a entero
            $cedula = intval(clean_ci($cedula_raw));
            $pass = password_hash($contra, PASSWORD_BCRYPT);
            
            // Verificar si el usuario ya existe
            $checkUsuario = $mysqli->prepare("SELECT Cedula FROM Usuarios WHERE Cedula = ?");
            if (!$checkUsuario) {
                throw new Exception("Error en la consulta: " . $mysqli->error);
            }
            
            $checkUsuario->bind_param("i", $cedula);
            $checkUsuario->execute();
            $result = $checkUsuario->get_result();
            $usuarioExiste = $result->num_rows > 0;
            $checkUsuario->close();
            
            if ($usuarioExiste) {
                throw new Exception("Ya existe un usuario con esa cédula");
            }
            
            // Comenzar transacción
            $mysqli->autocommit(FALSE);
            
            // Insertar en tabla Usuarios
            $stmtUsuario = $mysqli->prepare("INSERT INTO Usuarios (Cedula, Contrasenia, Nombre_usr) VALUES (?, ?, ?)");
            if (!$stmtUsuario) {
                throw new Exception("Error preparando consulta de usuario: " . $mysqli->error);
            }
            
            $stmtUsuario->bind_param("iss", $cedula, $pass, $nombre);
            if (!$stmtUsuario->execute()) {
                throw new Exception("Error al crear usuario: " . $stmtUsuario->error);
            }
            $stmtUsuario->close();
            
            // Insertar en tabla Email
            $stmtEmail = $mysqli->prepare("INSERT INTO Email (Cedula, numeroTelefono, email) VALUES (?, ?, ?)");
            if (!$stmtEmail) {
                throw new Exception("Error preparando consulta de email: " . $mysqli->error);
            }
            
            $email_empty = '';
            $stmtEmail->bind_param("iss", $cedula, $telefono, $email_empty);
            if (!$stmtEmail->execute()) {
                throw new Exception("Error al crear email: " . $stmtEmail->error);
            }
            $stmtEmail->close();
            
            // Establecer variables de sesión básicas
            $_SESSION['cedula'] = $cedula;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['telefono'] = $telefono;
            $_SESSION['tipo'] = $tipo;
            $_SESSION['logged_in'] = true;
            
            // Manejar tipos específicos de usuario
            if ($tipo === 'admin') {
                $rolAdm = trim($_POST['rolAdm'] ?? '');
                if (empty($rolAdm)) {
                    throw new Exception("El rol de administrador es obligatorio");
                }
                
                $_SESSION['rolAdmin'] = $rolAdm;
                
                $stmtAdmin = $mysqli->prepare("INSERT INTO Administrador (Cedula, EsAdmin, rolAdmin) VALUES (?, TRUE, ?)");
                if (!$stmtAdmin) {
                    throw new Exception("Error preparando consulta de administrador: " . $mysqli->error);
                }
                
                $stmtAdmin->bind_param("is", $cedula, $rolAdm);
                if (!$stmtAdmin->execute()) {
                    throw new Exception("Error al registrar administrador: " . $stmtAdmin->error);
                }
                $stmtAdmin->close();
                
                $mysqli->commit();
                $mensaje = "Registro exitoso como Administrador";
                $tipo_mensaje = 'success';
                $redirigir = '../admin/inicio.php';
                
            } elseif ($tipo === 'docente') {
                $anioIns = $_POST['anioIns'] ?? '';
                $estado = trim($_POST['estado'] ?? '');
                
                if (empty($anioIns) || empty($estado)) {
                    throw new Exception("Año de inserción y estado son obligatorios para docentes");
                }
                
                if (!is_numeric($anioIns) || $anioIns < 1900 || $anioIns > 2025) {
                    throw new Exception("Año de inserción debe ser válido (1900-2025)");
                }
                
                $fechaIns = "$anioIns-01-01";
                $_SESSION['anioIns'] = $anioIns;
                $_SESSION['estado'] = $estado;
                
                $stmtDocente = $mysqli->prepare("INSERT INTO Docente (Cedula, contrasenia, AnioInsercion, Estado) VALUES (?, ?, ?, ?)");
                if (!$stmtDocente) {
                    throw new Exception("Error preparando consulta de docente: " . $mysqli->error);
                }
                
                $stmtDocente->bind_param("isss", $cedula, $pass, $fechaIns, $estado);
                if (!$stmtDocente->execute()) {
                    throw new Exception("Error al registrar docente: " . $stmtDocente->error);
                }
                $stmtDocente->close();
                
                $mysqli->commit();
                $mensaje = "Registro exitoso como Docente";
                $tipo_mensaje = 'success';
                $redirigir = '../docente/inicioDoc.php';
                
            } elseif ($tipo === 'estudiante') {
                $fnac = $_POST['fnac'] ?? '';
                if (empty($fnac)) {
                    throw new Exception("La fecha de nacimiento es obligatoria para estudiantes");
                }
                
                // Validar que la fecha no sea futura
                if (strtotime($fnac) > time()) {
                    throw new Exception("La fecha de nacimiento no puede ser futura");
                }
                
                $_SESSION['fnac'] = $fnac;
                
                $stmtEstudiante = $mysqli->prepare("INSERT INTO Estudiante (Cedula, FechaNac) VALUES (?, ?)");
                if (!$stmtEstudiante) {
                    throw new Exception("Error preparando consulta de estudiante: " . $mysqli->error);
                }
                
                $stmtEstudiante->bind_param("is", $cedula, $fnac);
                if (!$stmtEstudiante->execute()) {
                    throw new Exception("Error al registrar estudiante: " . $stmtEstudiante->error);
                }
                $stmtEstudiante->close();
                
                $mysqli->commit();
                $mensaje = "Registro exitoso como Estudiante";
                $tipo_mensaje = 'success';
                $redirigir = '../estudiante/inicioEst.php';
                
            } else {
                throw new Exception("Tipo de usuario no válido");
            }
            
        } catch (Exception $e) {
            // Rollback en caso de error
            $mysqli->rollback();
            $mensaje = $e->getMessage();
            $tipo_mensaje = 'error';
        } finally {
            $mysqli->autocommit(TRUE);
        }
    }

    $mysqli->close();

    // Si hay redirección exitosa, hacerla con header
    if ($tipo_mensaje === 'success' && !empty($redirigir)) {
        header("Location: $redirigir");
        exit;
    }


    include '../front/usrREG_form.php';
    ?>
