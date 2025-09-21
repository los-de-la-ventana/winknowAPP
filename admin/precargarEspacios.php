<?php
/**
 * ARCHIVO: precargar_espacios.php
 * PROPOSITO: Borrar todos los espacios existentes e insertar datos reales del ITSP
 * BASADO EN: Entrevistas realizadas al personal del Instituto Tecnologico Superior de Paysandu
 * ESPACIOS REALES: 3 Aulas + 3 Laboratorios segun documentacion oficial
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

// FUNCION PARA INSERTAR ESPACIOS REALES DEL ITSP
function insertarEspaciosReales($mysqli) {
    // Array con espacios reales basados en entrevistas del ITSP
    // SEGUN DOCUMENTO: "Aulas hay tres" + "laboratorio de soporte, laboratorio de fisica y el de biologia/quimica"
    $espaciosReales = [
        // === AULAS (3 espacios para 9 grupos en turno matutino) ===
        [
            'num_edificio' => 1,
            'num_salon' => 1,
            'capacidad' => 35,
            'tipo_salon' => 'Aula'
        ],
        [
            'num_edificio' => 1,
            'num_salon' => 2,
            'capacidad' => 40,
            'tipo_salon' => 'Aula'
        ],
        [
            'num_edificio' => 1,
            'num_salon' => 3,
            'capacidad' => 30,
            'tipo_salon' => 'Aula'
        ],
        
        // === LABORATORIOS/TALLERES (3 espacios especializados) ===
        [
            'num_edificio' => 0,  // Planta baja segun entrevista "el de aca abajo"
            'num_salon' => 10,
            'capacidad' => 25,
            'tipo_salon' => 'Taller'  // Laboratorio de Soporte
        ],
        [
            'num_edificio' => 2,  // Segundo piso
            'num_salon' => 20,
            'capacidad' => 30,
            'tipo_salon' => 'Taller'  // Laboratorio de Fisica
        ],
        [
            'num_edificio' => 2,  // Segundo piso
            'num_salon' => 21,
            'capacidad' => 25,
            'tipo_salon' => 'Taller'  // Laboratorio de Biologia/Quimica
        ]
    ];
    
    $espaciosInsertados = 0;
    
    echo "Insertando espacios reales del ITSP...\n\n";
    
    // Insertar cada espacio real
    foreach ($espaciosReales as $espacio) {
        $insertQuery = "INSERT INTO Espacios (NumEdificio, NumSalon, capacidad, Tipo_salon) 
                        VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($insertQuery);
        
        if (!$stmt) {
            echo "Error preparando consulta: " . $mysqli->error . "\n";
            continue;
        }
        
        $stmt->bind_param("iiis", 
            $espacio['num_edificio'], 
            $espacio['num_salon'], 
            $espacio['capacidad'],
            $espacio['tipo_salon']
        );
        
        if ($stmt->execute()) {
            $espaciosInsertados++;
            $nombreEspacio = $espacio['tipo_salon'] . " " . $espacio['num_salon'];
            $piso = obtenerNombrePisoTexto($espacio['num_edificio']);
            echo "✓ Insertado: $nombreEspacio ($piso, Capacidad: {$espacio['capacidad']} personas)\n";
        } else {
            echo "✗ Error insertando " . $espacio['tipo_salon'] . " " . $espacio['num_salon'] . 
                 ": " . $stmt->error . "\n";
        }
        
        $stmt->close();
    }
    
    return $espaciosInsertados;
}

// FUNCION AUXILIAR PARA NOMBRES DE PISOS
function obtenerNombrePisoTexto($numEdificio) {
    switch($numEdificio) {
        case 0:
            return 'Planta Baja';
        case 1:
            return 'Primer Piso';
        case 2:
            return 'Segundo Piso';
        default:
            return 'Piso ' . $numEdificio;
    }
}

// EJECUTAR LA RECARGA COMPLETA DE ESPACIOS
echo "============================================\n";
echo "SISTEMA DE RECARGA DE ESPACIOS ITSP\n";
echo "============================================\n";
echo "Fecha: " . date('Y-m-d H:i:s') . "\n";
echo "Basado en: Entrevistas oficiales del ITSP\n\n";

echo "ATENCION: Este proceso borrara TODOS los espacios existentes\n";
echo "y los reemplazara con los espacios reales del instituto.\n\n";

// En un entorno web, verificar parametro de confirmacion
if (!isset($_GET['confirmar']) || $_GET['confirmar'] !== 'si') {
    echo "Para ejecutar este proceso, agregue ?confirmar=si a la URL\n";
    echo "Ejemplo: precargar_espacios.php?confirmar=si\n\n";
    echo "ESPACIOS QUE SE CREARAN:\n";
    echo "- 3 Aulas (Primer Piso): Capacidades 35, 40, 30 personas\n";
    echo "- 3 Talleres/Laboratorios:\n";
    echo "  * Lab. Soporte (Planta Baja): 25 personas\n";
    echo "  * Lab. Fisica (Segundo Piso): 30 personas\n";
    echo "  * Lab. Biologia/Quimica (Segundo Piso): 25 personas\n";
    exit;
}

// Paso 1: Borrar espacios existentes
if (!borrarEspaciosExistentes($mysqli)) {
    die("Error: No se pudieron borrar los espacios existentes.\n");
}

// Paso 2: Insertar espacios reales
$insertados = insertarEspaciosReales($mysqli);

// Paso 3: Mostrar resumen
echo "\n============================================\n";
echo "PROCESO COMPLETADO EXITOSAMENTE\n";
echo "============================================\n";
echo "Espacios insertados: $insertados/6\n";
echo "Estructura creada segun entrevistas del ITSP:\n\n";

echo "DISTRIBUCION FINAL:\n";
echo "📍 PLANTA BAJA (Edificio 0):\n";
echo "   • Taller 10 - Lab. Soporte (25 personas)\n\n";
echo "📍 PRIMER PISO (Edificio 1):\n";
echo "   • Aula 1 (35 personas)\n";
echo "   • Aula 2 (40 personas)\n";
echo "   • Aula 3 (30 personas)\n\n";
echo "📍 SEGUNDO PISO (Edificio 2):\n";
echo "   • Taller 20 - Lab. Fisica (30 personas)\n";
echo "   • Taller 21 - Lab. Biologia/Quimica (25 personas)\n\n";

echo "CAPACIDAD TOTAL: " . (35+40+30+25+30+25) . " personas\n";
echo "PROBLEMA IDENTIFICADO: 3 aulas para 9 grupos (turno matutino)\n";
echo "SOLUCION: Uso rotativo como 'rompecabezas' segun entrevistas\n\n";

echo "Datos listos para usar en WinKnow!\n";

// Cerrar la conexion
if ($mysqli) {
    $mysqli->close();
}
?>