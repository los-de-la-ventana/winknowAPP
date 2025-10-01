<?php
/**
 * ARCHIVO: precargar_espacios.php
 * PROPOSITO: Borrar todos los espacios existentes e insertar datos actualizados del ITSP
 * ESPACIOS ACTUALIZADOS: 3 Aulas + 3 Talleres + 1 Laboratorio + 5 Salones
 */

require("../conexion.php");
$mysqli = conectarDB();

// Verificar conexion a la base de datos
if (!$mysqli) {
    die("Error: No se pudo conectar a la base de datos");
}

// FUNCION PARA BORRAR TODOS LOS ESPACIOS EXISTENTES
function borrarEspaciosExistentes($mysqli) {
    echo "Borrando todos los espacios existentes...\n";
    
    // Primero borrar recursos asociados para evitar problemas de clave foranea
    $deleteRecursos = "DELETE FROM Recursos";
    if ($mysqli->query($deleteRecursos)) {
        echo "Recursos eliminados correctamente.\n";
    } else {
        echo "Error eliminando recursos: " . $mysqli->error . "\n";
    }
    
    // Luego borrar reservas asociadas
    $deleteReservas = "DELETE FROM Reserva";
    if ($mysqli->query($deleteReservas)) {
        echo "Reservas eliminadas correctamente.\n";
    } else {
        echo "Error eliminando reservas: " . $mysqli->error . "\n";
    }
    
    // Finalmente borrar espacios
    $deleteEspacios = "DELETE FROM Espacios";
    if ($mysqli->query($deleteEspacios)) {
        echo "Todos los espacios eliminados correctamente.\n";
        
        // Reiniciar el AUTO_INCREMENT para que empiece desde 1
        $resetAutoIncrement = "ALTER TABLE Espacios AUTO_INCREMENT = 1";
        $mysqli->query($resetAutoIncrement);
        echo "Contador de ID reiniciado.\n\n";
        return true;
    } else {
        echo "Error eliminando espacios: " . $mysqli->error . "\n";
        return false;
    }
}

// FUNCION PARA INSERTAR ESPACIOS ACTUALIZADOS DEL ITSP
function insertarEspaciosActualizados($mysqli) {
    // Array con la nueva estructura de espacios
    // 3 AULAS + 3 TALLERES + 1 LABORATORIO + 5 SALONES = 12 ESPACIOS TOTALES
    $espacios = [
        // === AULAS (3 espacios) ===
        [
            'num_salon' => 101,
            'capacidad' => 35,
            'tipo_salon' => 'Aula'
        ],
        [
            'num_salon' => 102,
            'capacidad' => 40,
            'tipo_salon' => 'Aula'
        ],
        [
            'num_salon' => 103,
            'capacidad' => 30,
            'tipo_salon' => 'Aula'
        ],
        
        // === TALLERES (3 espacios) ===
        [
            'num_salon' => 201,
            'capacidad' => 25,
            'tipo_salon' => 'Taller'
        ],
        [
            'num_salon' => 202,
            'capacidad' => 28,
            'tipo_salon' => 'Taller'
        ],
        [
            'num_salon' => 203,
            'capacidad' => 22,
            'tipo_salon' => 'Taller'
        ],
        
        // === LABORATORIO (1 espacio) ===
        [
            'num_salon' => 301,
            'capacidad' => 30,
            'tipo_salon' => 'Laboratorio'
        ],
        
        // === SALONES (5 espacios) ===
        [
            'num_salon' => 401,
            'capacidad' => 45,
            'tipo_salon' => 'Salon'
        ],
        [
            'num_salon' => 402,
            'capacidad' => 50,
            'tipo_salon' => 'Salon'
        ],
        [
            'num_salon' => 403,
            'capacidad' => 40,
            'tipo_salon' => 'Salon'
        ],
        [
            'num_salon' => 404,
            'capacidad' => 38,
            'tipo_salon' => 'Salon'
        ],
        [
            'num_salon' => 405,
            'capacidad' => 42,
            'tipo_salon' => 'Salon'
        ]
    ];
    
    $espaciosInsertados = 0;
    
    echo "Insertando espacios actualizados del ITSP...\n\n";
    
    // Insertar cada espacio
    foreach ($espacios as $espacio) {
        $insertQuery = "INSERT INTO Espacios (NumSalon, capacidad, Tipo_salon) 
                        VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($insertQuery);
        
        if (!$stmt) {
            echo "Error preparando consulta: " . $mysqli->error . "\n";
            continue;
        }
        
        $stmt->bind_param("iis", 
            $espacio['num_salon'], 
            $espacio['capacidad'],
            $espacio['tipo_salon']
        );
        
        if ($stmt->execute()) {
            $espaciosInsertados++;
            echo "✓ Insertado: {$espacio['tipo_salon']} {$espacio['num_salon']} (Capacidad: {$espacio['capacidad']} personas)\n";
        } else {
            echo "✗ Error insertando {$espacio['tipo_salon']} {$espacio['num_salon']}: " . $stmt->error . "\n";
        }
        
        $stmt->close();
    }
    
    return $espaciosInsertados;
}

// EJECUTAR LA RECARGA COMPLETA DE ESPACIOS
echo "============================================\n";
echo "SISTEMA DE RECARGA DE ESPACIOS ITSP\n";
echo "============================================\n";
echo "Fecha: " . date('Y-m-d H:i:s') . "\n";
echo "Nueva estructura de espacios\n\n";

echo "ATENCION: Este proceso borrara TODOS los espacios existentes\n";
echo "y los reemplazara con la nueva estructura.\n\n";

// En un entorno web, verificar parametro de confirmacion
if (!isset($_GET['confirmar']) || $_GET['confirmar'] !== 'si') {
    echo "Para ejecutar este proceso, agregue ?confirmar=si a la URL\n";
    echo "Ejemplo: precargar_espacios.php?confirmar=si\n\n";
    echo "ESPACIOS QUE SE CREARAN:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📚 AULAS (3):\n";
    echo "   • Aula 101: 35 personas\n";
    echo "   • Aula 102: 40 personas\n";
    echo "   • Aula 103: 30 personas\n\n";
    echo "🔧 TALLERES (3):\n";
    echo "   • Taller 201: 25 personas\n";
    echo "   • Taller 202: 28 personas\n";
    echo "   • Taller 203: 22 personas\n\n";
    echo "🧪 LABORATORIO (1):\n";
    echo "   • Laboratorio 301: 30 personas\n\n";
    echo "🏫 SALONES (5):\n";
    echo "   • Salon 401: 45 personas\n";
    echo "   • Salon 402: 50 personas\n";
    echo "   • Salon 403: 40 personas\n";
    echo "   • Salon 404: 38 personas\n";
    echo "   • Salon 405: 42 personas\n\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "TOTAL: 12 espacios\n";
    echo "Capacidad total: 425 personas\n";
    exit;
}

// Paso 1: Borrar espacios existentes
if (!borrarEspaciosExistentes($mysqli)) {
    die("Error: No se pudieron borrar los espacios existentes.\n");
}

// Paso 2: Insertar espacios actualizados
$insertados = insertarEspaciosActualizados($mysqli);

// Paso 3: Mostrar resumen
echo "\n============================================\n";
echo "PROCESO COMPLETADO EXITOSAMENTE\n";
echo "============================================\n";
echo "Espacios insertados: $insertados/12\n\n";

echo "DISTRIBUCION FINAL:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📚 AULAS (3 espacios):\n";
echo "   Capacidad: 105 personas\n\n";
echo "🔧 TALLERES (3 espacios):\n";
echo "   Capacidad: 75 personas\n\n";
echo "🧪 LABORATORIO (1 espacio):\n";
echo "   Capacidad: 30 personas\n\n";
echo "🏫 SALONES (5 espacios):\n";
echo "   Capacidad: 215 personas\n\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "CAPACIDAD TOTAL DEL INSTITUTO: 425 personas\n";
echo "ESPACIOS TOTALES: 12\n\n";

echo "✅ Datos listos para usar en WinKnow!\n";

// Cerrar la conexion
if ($mysqli) {
    $mysqli->close();
}
?>