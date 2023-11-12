<?php
session_start();

header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header_remove("X-Powered-By");

// Verificar la sesión del usuario (debes implementar esta lógica)
if (!isset($_SESSION["usuario"])) {
    header("Location: index.php"); // Redirigir a la página de inicio de sesión si el usuario no está autenticado
    exit();
}

include("config.php"); // Incluye el archivo de configuración
//desde página principal o desde buscar, seleccionar videojuego de catálogo --> pantalla videojuego con botón eliminar.
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: index.php"); // Redirigir a la página de inicio de sesión si el usuario no está autenticado
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera los datos del formulario
    $nombre = $_POST["nombre"];
    $creador = $_POST["creador"];
    $productora = $_POST["productora"];
    $genero = $_POST["genero"];
    $sistema_operativo = $_POST["sistema_operativo"];
    $fecha_lanzamiento = $_POST["fecha_lanzamiento"];


if (!$conn) {
    die("La conexión a la base de datos falló: " . mysqli_connect_error());
}


// Parámetros para la paginación
$porPagina = 10; // Número de elementos por página
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $porPagina;

// Consulta SQL con LIMIT para paginación
$sql = "SELECT * FROM mytable WHERE Name LIKE '%$nombre%' AND Developer LIKE '%$creador%' AND Producer LIKE '%$productora%' AND Genre LIKE '%$genero%' AND Operating_System LIKE '%$sistema_operativo%' AND Date_Released LIKE '%$fecha_lanzamiento%'";
//Preparar la consulta 
$stmt = mysqli_prepare($conn, $sql);
// Verificar que la función 'mysqli_prepare' haya tenido éxito
if (!$stmt) {
    die("Error al preparar la consulta SQL: " . $conn->error);
}

// Asocia los parámetros con los valores
mysqli_stmt_bind_param($stmt, "ssssss", $nombre, $creador, $productora, $genero, $sistema_operativo, $fecha_lanzamiento);

// Ejecuta la consulta SQL
mysqli_stmt_execute($stmt);

// Obtiene el resultado
$result = mysqli_stmt_get_result($stmt);

}

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

    <main>
        <section>
            <h2>Catálogo de videojuegos</h2>
            <table>
        <!--<tr>
            <th>Nombre</th>
            <th>Desarrollador</th>
            <th>Productora</th>
            <th>Género</th>
            <th>sistema_operativo</th>
            <th>fecha_lanzamiento</th>
        </tr> -->
            <?php
            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr><th>Nombre</th><th>Género</th></tr>";
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

            // Mostrar enlaces de paginación
            echo "<div>";
            for ($i = 1; $i <= $totalPaginas; $i++) {
                 if ($i == $pagina) {
                     echo "<strong>$i</strong> ";
                } else {
                     echo "<a href='?pagina=$i'>$i</a> ";
                }
            }
            echo "</table>";
           }
           else {
                echo "No se encontraron registros.";
           }
           // Cierra la conexión y la declaración
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            ?>
            </table>
            <button class="button secondary-button" onclick="window.location.href='principal.php'">Volver a Juegos</button>
        </section>
    </main>
</head>
</html>
