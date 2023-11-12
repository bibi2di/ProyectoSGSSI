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

//session_start();
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
 


    //Validar parametros (Falta hacer)


    
    // Verifica la conexión
    if (!$conn) {
        die("La conexión a la base de datos falló: " . mysqli_connect_error());
    }

    // Crea la consulta SQL para insertar el nuevo usuario en la tabla
    $sql = "INSERT INTO mytable (Name, Developer, Producer, Genre, Operating_System, Date_Released) VALUES (?, ?, ?, ?, ?, ?)";

    // Prepara la consulta SQL
    $stmt = mysqli_prepare($conn, $sql);

    //Verificar que la función 'mysqli_prepare' haya tenido éxito
    if (!$stmt) {
        die("Error al preparar la consulta SQL: " . mysqli_error($conn));
    }

    // Asocia los parámetros con los valores
    mysqli_stmt_bind_param($stmt, "ssssss", $nombre, $creador, $productora, $genero, $sistema_operativo, $fecha_lanzamiento);

    // Ejecuta la consulta SQL
    if (mysqli_stmt_execute($stmt)) {
        header("Location: principal.php");
        // echo "Se ha añadido la información del juego";
    } else {
        echo "Error al registrar el juego: " . mysqli_error($conn);
    }

    // Cierra la conexión y la declaración
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

}
?>