<?php
session_start();
        include '../front/header.html';
                    include '../front/navEST.php'; 

?>

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
<?php    

        include '../front/carrusel.html'; 

?>
        </div>
        </main>
    </body>
</html>