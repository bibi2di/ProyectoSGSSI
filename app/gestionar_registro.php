<?php
session_start();
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header_remove("X-Powered-By");

include("config.php"); // Incluye el archivo de configuración
include("funciones.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera los datos del formulario
    /*$nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $dni = $_POST["dni"];
    $telefono = $_POST["telefono"];
    $fechaNacimiento = $_POST["fecha_nacimiento"];
    $email = $_POST["email"];
    $usuario = $_POST["username"];
    $contraseña = $_POST["password"];
    */
    $nombre = cifrar($_POST["nombre"]);
    $apellidos = cifrar($_POST["apellidos"]);
    $dni = cifrar($_POST["dni"]);
    $telefono = cifrar($_POST["telefono"]);
    $fechaNacimiento = cifrar($_POST["fecha_nacimiento"]);
    $email = cifrar($_POST["email"]);
    $usuario = cifrar($_POST["username"]);
    $contraseña = $_POST["password"];
    $sal = "";

    /*$cont = 0;
    while ($cont < 10) {
  	    $sal = $sal.chr(random_int(65, 90));
  	$cont++;
    }*/
    
    //$contraseña_sal = $contraseña.$sal;
    //$hash_contraseña = hash('sha256',$contraseña_sal); //No me funciona el hash
    //Validar parametros (Falta hacer)

    $contra_hash = password_hash($contraseña, PASSWORD_DEFAULT);
    
    // Verifica la conexión
    if (!$conn) {
        die("La conexión a la base de datos falló: " . mysqli_connect_error());
    }

    // Crea la consulta SQL para insertar el nuevo usuario en la tabla
    $sql = "INSERT INTO usuarios_cod (nombre, apellidos, dni, telefono, fecha_nacimiento, email, username, sal, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepara la consulta SQL
    $stmt = mysqli_prepare($conn, $sql);

    //Verificar que la función 'mysqli_prepare' haya tenido éxito
    if (!$stmt) {
        die("Error al preparar la consulta SQL: " . mysqli_error($conn));
    }

    // Asocia los parámetros con los valores
    mysqli_stmt_bind_param($stmt, "sssssssss", $nombre, $apellidos, $dni, $telefono, $fechaNacimiento, $email, $usuario, $sal, $contra_hash); /*$hash_contraseña*/

    // Ejecuta la consulta SQL
    if (mysqli_stmt_execute($stmt)) {
        //echo "Registro exitoso. ¡Bienvenido, $nombre!";
        session_start();
        $_SESSION["usuario"] = $usuario;
        $_SESSION["dni"] = $dni;
        echo "<h1> ¡Felicidades! </h1>";
        error_log("Fecha: ".date("d-m-20y, H:i:s")." | IP: ".$_SERVER['REMOTE_ADDR']." --> Se ha creado el user con identificador ".$_POST['usuario']." \n", 3, "logs.log");
        //header("Location: principal.php");
        exit();
    } else {
        echo "Error al registrar el usuario: " . mysqli_error($conn);
    }

    // Cierra la conexión y la declaración
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="./script.js"></script>
    <title>Goodgames</title>
</head>
<body>
    <header>
        <h1>Goodgames</h1>
    </header>
    <main>
        <section>
            <h2>Formulario de Registro</h2>
            <div id="error-message" style="color: F9B17A;"></div>
            <form id="registro-form" action="gestionar_registro.php" class="" method="POST" onsubmit="return verificarFormato();">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" placeholder="p. ej: Ana" required><br>

                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" placeholder="p. ej: García" required><br>

                <label for="dni">DNI:</label>
                <input type="text" id="dni" name="dni" placeholder="p. ej: 99999999R" required><br>

                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" placeholder="p. ej: 600000000" required><br>

                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="p. ej: 2000-01-01" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="p. ej: ejemplo@gmail.com" required><br>

                <label for="username">Nombre de Usuario:</label>
                <input type="text" id="username" name="username" placeholder="p. ej: Anita" required><br>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="p. ej: contraseña1234" required><br>
                
                <div class="button-container">
                    <button class="button secondary-button" type="submit">Registrarse</button>
                    <pre>     </pre>
                    <button class="button secondary-button" onclick="window.location.href='index.php'">Volver a Inicio Sesión</button>
                </div>

            </form>
        </section>
    </main>
    
</body>

