<?php
session_start();

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['tipo'] !== 'admin') {
    header("Location: ../login_reg/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
      <link rel="icon" type="image/x-icon" href="/img/image-removebg-preview (2).png">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../inicio.css">
   <link rel="stylesheet" href="aulas.css">
    <title>WinKnow - Gestión de Aulas</title>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Barra Lateral -->
    <aside class="barra-lateral">
        <div class="logo">
            <div class="icono-logo">WK</div>
            <span>WinKnow</span>
        </div>
        
    <nav class="navegacion">
            <ul>
                <a href="inicio.php"> <li><i class="bi bi-house"></i> Inicio</li></a> 
                <a href="aulas.php"> <li class="activo"><i class="bi bi-building"></i> Aulas</li></a>
               <a href="calendario.php">  <li><i class="bi bi-calendar3"></i> Calendario</li> </a>
                  <a href="reportes.php">  <li><i class="bi bi-bar-chart"></i> Reportes</li></a>
              <a href="reportes.php">  <li><i class="bi bi-bar-chart"></i> Administrar usuario</li></a>

            </ul>
        </nav>
        
        <div class="usuario">
           <p>nombreUsuario</p>
    </aside>

    <!-- Contenido Principal -->
    <main class="principal">
        <header class="encabezado">
            <h1>Gestión de Aulas y Espacios</h1>
        </header>

        <div class="contenido">
            <!-- Botones de Acción -->
            <div class="acciones">
                <button class="boton-primario"><i class="bi bi-plus"></i> Nueva reserva</button>
            </div>

            <!-- Tarjetas de Estadísticas -->
            <section class="estadisticas">
                <div class="tarjeta-estadistica">
                    <div class="icono total"><i class="bi bi-building"></i></div>
                    <div>
                        <h3>Total de Aulas</h3>
                        <div class="numero">12</div>
                    </div>
                </div>
                
                <div class="tarjeta-estadistica">
                    <div class="icono disponible"><i class="bi bi-check-circle"></i></div>
                    <div>
                        <h3>Aulas Disponibles</h3>
                        <div class="numero">08</div>
                    </div>
                </div>
                
                <div class="tarjeta-estadistica">
                    <div class="icono reservado"><i class="bi bi-clock"></i></div>
                    <div>
                        <h3>Reservadas hoy</h3>
                        <div class="numero">04</div>
                    </div>
                </div>
            </section>

            <!-- Filtros -->
            <section class="filtros">
                <div class="pestanas">
                    <div class="pestana activa">Aulas</div>
                    <div class="pestana">Calendario</div>
                </div>  
                
                <div class="controles-filtro">
                    <select><option>Tipo de Aula - Todos</option></select>
                    <select><option>Piso - Todos</option></select>
                    <select><option>Capacidad - Cualquiera</option></select>
                    <select><option>Estado - Todos</option></select>
                    <button class="boton-secundario">Limpiar</button>
                    <button class="boton-primario">Aplicar Filtro</button>
                </div>
            </section>

            <!-- Grilla de Aulas -->
            <section class="aulas">
                <div class="aulas-header">
                    <h2>Aulas y Espacios</h2>
                    <button class="boton-primario"><i class="bi bi-plus-circle"></i> Agregar Aula</button>
                </div>
                
                <div class="grilla">
                    <div class="tarjeta-aula">
                        <div class="estado disponible">Disponible</div>
                        <div class="acciones-aula">
                            <button class="boton-accion"><i class="bi bi-pencil"></i></button>
                            <button class="boton-accion"><i class="bi bi-trash"></i></button>
                        </div>
                        <div class="info-aula">
                            <h4>Aula 1</h4>
                            <div class="detalles">
                                Planta Baja<br>
                                Capacidad: 30 Estudiantes<br>
                                Proyector, A/C
                            </div>
                            <div class="etiqueta">Aula Normal</div>
                        </div>
                    </div>

                    <div class="tarjeta-aula">
                        <div class="estado ocupado">Ocupado</div>
                        <div class="acciones-aula">
                            <button class="boton-accion"><i class="bi bi-pencil"></i></button>
                            <button class="boton-accion"><i class="bi bi-trash"></i></button>
                        </div>
                        <div class="info-aula">
                            <h4>Laboratorio 1</h4>
                            <div class="detalles">
                                Planta Baja<br>
                                Capacidad: 40 Estudiantes<br>
                                Pizarra, A/C
                            </div>
                            <div class="etiqueta">Laboratorio</div>
                        </div>
                    </div>

                    <div class="tarjeta-aula">
                        <div class="acciones-aula">
                            <button class="boton-accion"><i class="bi bi-pencil"></i></button>
                            <button class="boton-accion"><i class="bi bi-trash"></i></button>
                        </div>
                        <div class="info-aula">
                            <h4>Aula 3</h4>
                            <div class="detalles">
                                Primer Piso<br>
                                Capacidad: 25 Estudiantes<br>
                                Proyector, Pizarra
                            </div>
                            <div class="etiqueta">Aula Normal</div>
                        </div>
                    </div>

                    <div class="tarjeta-aula">
                        <div class="estado disponible">Disponible</div>
                        <div class="acciones-aula">
                            <button class="boton-accion"><i class="bi bi-pencil"></i></button>
                            <button class="boton-accion"><i class="bi bi-trash"></i></button>
                        </div>
                        <div class="info-aula">
                            <h4>Sala de Conferencias</h4>
                            <div class="detalles">
                                Segundo Piso<br>
                                Capacidad: 50 Personas<br>
                                Proyector, Sistema de Audio
                            </div>
                            <div class="etiqueta">Sala Especial</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>