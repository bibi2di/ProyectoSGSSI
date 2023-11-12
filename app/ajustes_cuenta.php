<?php
session_start();
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header_remove("X-Powered-By");

// Incluir archivo de configuración y verificar la sesión del usuario (debes implementar la lógica de autenticación)
include("config.php");
include("funciones.php");

// Verificar la sesión del usuario (debes implementar esta lógica)
if (!isset($_SESSION["usuario"])) {
    header("Location: index.php"); // Redirigir a la página de inicio de sesión si el usuario no está autenticado
    exit();
}

// Recuperar información del usuario desde la base de datos (debes implementar esta lógica)
$usuario = $_SESSION["usuario"];
$dni = $_SESSION["dni"];
$sql = "SELECT * FROM usuarios_cod WHERE username = ? /*AND dni = ?*/";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $usuario);/*, $dni);*/
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$datosUsuario = mysqli_fetch_assoc($resultado);
/*Meter lo de descifrar*/
$nm = $datosUsuario["nombre"];
$ap = $datosUsuario["apellidos"];
$tlf = $datosUsuario["telefono"];
$em = $datosUsuario["email"];
$fn = $datosUsuario["fecha_nacimiento"];

$sal = "";

$cont = 0;
while ($cont < 10) {
      $sal = $sal.chr(random_int(65, 90));
  $cont++;
}

if ($resultado) {
    $datosUsuario = mysqli_fetch_assoc($resultado);
    print_r($datosUsuario); // Imprime los datos del usuario para depuración
} else {
    echo "Error al recuperar la información del usuario: " . mysqli_error($conn);
}


mysqli_stmt_close($stmt);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera los datos del formulario
    $nombre = cifrar($_POST["nombre"]);
    $apellidos = cifrar($_POST["apellidos"]);
    $telefono = cifrar($_POST["telefono"]);
    $fecha_nacimiento = cifrar($_POST["fecha_nacimiento"]);
    $email = cifrar($_POST["email"]);
    /*$usuario = $_POST["username"];*/ //Hay que poner el DNI
    $password = $_POST["password"];
    $actual=$_SESSION["usuario"];

    $nombresql="UPDATE usuarios_cod SET nombre=? WHERE username=? ";
    $nombrestmt=mysqli_prepare($conn, $nombresql);

    $apellidossql="UPDATE usuarios_cod SET apellidos=? WHERE username=? ";
    $apellidosstmt=mysqli_prepare($conn, $apellidossql);

    $telefonosql="UPDATE usuarios_cod SET telefono=? WHERE username=? ";
    $telefonostmt=mysqli_prepare($conn, $telefonosql);

    $fechasql="UPDATE usuarios_cod SET fecha_nacimiento=? WHERE username=? ";
    $fechastmt=mysqli_prepare($conn, $fechasql);

    $emailsql="UPDATE usuarios_cod SET email=? WHERE username=? ";
    $emailstmt=mysqli_prepare($conn, $emailsql);

    //$usuariosql="UPDATE usuarios_cod SET dni='$dni' WHERE dni='$actual' ";
    $contrasenasql="UPDATE usuarios_cod SET contraseña=? WHERE username=? ";
    $contrasenastmt=mysqli_prepare($conn, $contrasenasql);

    
    
    if(!empty($nombre)){
        mysqli_stmt_bind_param($nombrestmt, "ss", $nombre, $actual);
        //$ejecutar2=mysqli_query($conn,$nombresql);
        if(mysqli_stmt_execute($nombrestmt)){
          ?> 
          <h3 class="bien">¡Nombre modificado correctamente!</h3>
            <?php
        }
    }
    
    if(!empty($apellidos)){
        mysqli_stmt_bind_param($apellidosstmt, "ss", $apellidos, $actual);
        //$ejecutar7=mysqli_query($conn,$apellidossql);
        if(mysqli_stmt_execute($apellidosstmt)){
          ?> 
          <h3 class="bien">¡Apellido modificado correctamente!</h3>
            <?php
        }
    }

    if(!empty($telefono)){
        mysqli_stmt_bind_param($telefonostmt, "ss", $telefono, $actual);
        //$ejecutar3=mysqli_query($conn,$telefonosql);
        if($mysqli_stmt_execute($telefonostmt)){
            ?> 
            <h3 class="bien">¡Telefono modificado correctamente!</h3>
            <?php
        }
    }

    if(!empty($fecha_nacimiento)){
        mysqli_stmt_bind_param($fechastmt, "ss", $fecha_nacimiento, $actual);
        //$ejecutar4=mysqli_query($conn,$fechasql);
        if($mysqli_stmt_execute($fechastmt)){
            ?> 
            <h3 class="bien">¡Fecha modificada correctamente!</h3>
            <?php
        }
    }

    if(!empty($email)){
        mysqli_stmt_bind_param($emailstmt, "ss", $email, $actual);
        //$ejecutar5=mysqli_query($conn,$emailsql);
        if($mysqli_stmt_execute($emailstmt)){
            ?> 
                <h3 class="bien">¡Email modificado correctamente!</h3>
                    <?php
        }
    }

    if(!empty($password)){
        mysqli_stmt_bind_param($contrasenastmt, "ss", $password, $actual);
        //$ejecutar6=mysqli_query($conn,$contrasenasql);
        if($mysqli_stmt_execute($contrasenastmt)){
            ?> 
                <h3 class="bien">¡Contraseña modificada correctamente!</h3>
                    <?php
        }
    }

    mysqli_stmt_close($nombrestmt);
    mysqli_stmt_close($apellidosstmt);
    mysqli_stmt_close($telefonostmt);
    mysqli_stmt_close($fechastmt);
    mysqli_stmt_close($emailstmt);
    mysqli_stmt_close($contrasenastmt);
    mysqli_close($conn);
    
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
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
            <h2>¡Bienvenido <?php echo descifrar($_SESSION['usuario'])?>!</h2>
            <h3>Información a cambiar: </h3>

            <form id="ajustes-form" action="ajustes_cuenta.php" method="POST" onsubmit="return modificarUsuario();">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" placeholder="<?php echo descifrar($nm); ?>"><br>

                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" placeholder="<?php echo descifrar($ap); ?>"><br>

                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" placeholder="<?php echo descifrar($tlf); ?>"><br>

                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="<?php echo descifrar($fn); ?>"><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="<?php echo descifrar($em); ?>"><br>

                <label for="username">Nombre de Usuario:</label>
                <input type="text" id="username" name="username"><br>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password"><br>


                <button class="button secondary-button" type="submit">Guardar Cambios</button>
            </form>
        </section>
    </main>

    <main>
        <div class="button-container">
            <button class="button secondary-button" onclick="window.location.href='principal.php'">Volver a Juegos</button>
            <pre>     </pre>
            <button class="button secondary-button" onclick="window.location.href='cerrar_sesion.php'">Cerrar Sesión</button>
        </div>
    </main>
</body>
</html>
