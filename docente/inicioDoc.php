<?php
session_start();
include '../front/header.html';
include '../front/navDOC.php'; 
?>
      
<div class="usuario">
    <div class="nombre-usuario">
        <?php
        if (isset($_SESSION['nombre'])) {
            echo htmlspecialchars($_SESSION['nombre']);
        } else {
            echo "Invitado";
        }
        ?>
    </div>
    <div class="tipo-usuario">
        <?php
        if (isset($_SESSION['tipo'])) {
            echo "(" . ucfirst(htmlspecialchars($_SESSION['tipo'])) . ")";
        }
        ?>
    </div>
</div>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <main class="principal">
<header class="encabezado">
    <h1>
        <?php
        if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
            echo "Hola, " . htmlspecialchars($_SESSION['nombre']);
        } else {
            echo "Bienvenido";
        }
        ?>
    </h1>
</header>

        <?php include '../front/carrusel.html'; ?>


        </div>
        </main>
    </body>
</html>