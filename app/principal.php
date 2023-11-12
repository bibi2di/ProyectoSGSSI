<?php
session_start();
//header("X-Frame-Options: SAMEORIGIN");
//header("X-Content-Type-Options: nosniff");
//header_remove("X-Powered-By");

include("config.php"); // Incluye el archivo de configuración
//desde página principal o desde buscar, seleccionar videojuego de catálogo --> pantalla videojuego con botón eliminar.
if (!$conn) {
    die("La conexión a la base de datos falló: " . mysqli_connect_error());
}
// Verificar la sesión del usuario (debes implementar esta lógica)
if (!isset($_SESSION["usuario"])) {
    header("Location: index.php"); // Redirigir a la página de inicio de sesión si el usuario no está autenticado
    exit();
}
// Parámetros para la paginación
$porPagina = 10; // Número de elementos por página
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $porPagina;

// Consulta SQL con LIMIT para paginación
$sql = "SELECT * FROM mytable LIMIT $inicio, $porPagina";
$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- Agrega tu archivo CSS personalizado aquí -->
    <title>Goodgames</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <header>
        <h1>Goodgames</h1>
    </header>

    <main>
        <section>
            <h2>Catálogo de videojuegos</h2>
            <table>
        <tr>
            <th>Nombre</th>

            <th>Género</th>
        </tr>
        
            <?php
               while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><a href='ver_videojuego.php?id=" . $row["id"] . "'>" . $row["Name"] . "</a></td>";
                echo "<td>" . $row["Genre"] . "</td>";
                echo "</tr>";
            }
            $sql = "SELECT COUNT(*) AS total FROM mytable";
             $result = $conn->query($sql);
             $fila = $result->fetch_assoc();
            $totalPaginas = ceil($fila["total"] / $porPagina);

           
                ?>
                </table>
                <br> </br>
            <?php
                // Mostrar enlaces de paginación
             echo "<div>";
             for ($i = 1; $i <= $totalPaginas; $i++) {
                 if ($i == $pagina) {
                     echo "<strong>$i</strong> ";
                 } else {
                     echo "<a href='?pagina=$i'>$i</a> ";
                 }
             }
            if ($pagina < $totalPaginas) {
                echo "<a href='?pagina=".($pagina + 1)."'>Siguiente</a>";
            }
                
             ?>

                <pre>     </pre>
                <div class="button-container">
                    <button class="button secondary-button" onclick="window.location.href='buscar_juegos.html'">Buscar videojuegos</button>
                    <pre>     </pre>
                    <button class="button secondary-button" onclick="window.location.href='anadir_videojuego.html'">Añadir videojuego</button>
                </div>

            <!--  </form> -->
        </section>
    </main>

    <main>
        <div class="button-container">
            <button class="button secondary-button" onclick="window.location.href='ajustes_cuenta.php'">Mi Perfil </button>
            <pre>     </pre>
            <button class="button secondary-button" onclick="window.location.href='cerrar_sesion.php'">Cerrar Sesión</button>
        </div>
    </main>

    <!-- 
    <footer>
        <section>
            <button onclick="window.location.href='index.php'">Cerrar Sesión</button>
             
            <ul class= "footer-left">

            </ul>
            <ul class= "footer-right">

            </ul> 
        </section>
    </footer>
    -->

</body>
</html>