<?php
session_start();
include '../front/header.html';
include '../front/navdoc.php'; 
?>
      
        </div>
    </aside>

    <!-- Contenido Principal -->
    <main class="principal">
<header class="encabezado">
    <h1>
        <span data-lang="hello">Hola </span>, 
        <?php
        if (isset($_SESSION['nombre']) && isset($_SESSION['tipo'])) {
            echo htmlspecialchars($_SESSION['nombre']);
        } else {
            echo '<span data-lang="default_user">Usuario</span>';
        }
        ?>
    </h1>
</header>
        <?php include '../front/carrusel.html'; ?>
        </div>
        </main>
    </body>
</html>