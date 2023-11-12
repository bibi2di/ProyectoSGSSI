<?php
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header_remove("X-Powered-By");

include("config.php");
session_start();

// Verificar si el usuario está autenticado; si no, redirigirlo a la página de inicio de sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera los datos del formulario
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $telefono = $_POST["telefono"];
    $fechaNacimiento = $_POST["fecha_nacimiento"];
    $email = $_POST["email"];
    $usuario = $_POST["username"];
    $contraseña = $_POST["password"];


    // Actualiza los datos en la base de datos
    $dni = $_SESSION['DNI'];
    $actualizarDatos = "UPDATE Usuario SET Nombre=?, Apellidos=?, Telefono=?, FechaNacimiento=?, Email=?, Usuario=?, Contraseña=? WHERE DNI=?";    // Preparar la consulta SQL
    $stmt = mysqli_prepare($conn, $actualizarDatos);

    // Verificar que la preparación fue exitosa
    if ($stmt) {
        // Asociar los parámetros con los valores
        mysqli_stmt_bind_param($stmt, "ssssssss", $nombre, $apellidos, $telefono, $fechaNacimiento, $email, $usuario, $contraseña, $dni);
        
        // Ejecutar la consulta preparada
        if (mysqli_query($conn, $actualizarDatos)) {
            // Datos actualizados con éxito
            header("Location: ajustes_cuenta.php?success=Datos actualizados correctamente");
            exit();
        } else {
            // Error al actualizar los datos
            header("Location: ajustes_cuenta.php?error=Error al actualizar los datos");
            exit();
        }
    } else {
        // Error al preparar la consulta
        header("Location: ajustes_cuenta.php?error=Error al preparar la consulta");
        exit();
    }

    // Cerrar la consulta preparada y la conexión
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    // Si no es una solicitud POST, redirige al usuario a la página de ajustes_cuenta.php
    header("Location: ajustes_cuenta.php");
    exit();
}
?>
