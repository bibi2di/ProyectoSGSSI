<?php
//header("X-Frame-Options: SAMEORIGIN");
//header("X-Content-Type-Options: nosniff");
//header_remove("X-Powered-By");

include("config.php"); // Incluye el archivo de configuración
include("funciones.php");



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera los datos del formulario
    $usuario = cifrar($_POST["username"]);
    $dni = cifrar($_POST ["dni"]);
    $contr = $_POST["password"];
    // Verifica la conexión
    if (!$conn) {
        die("La conexión a la base de datos falló: " . mysqli_connect_error());
    }

    // Crea la consulta SQL para buscar al usuario en la tabla
    $sql = "SELECT * FROM usuarios_cod WHERE username = ?";
    #$sql2 = "SELECT dni FROM usuarios WHERE usuario = ? AND contraseña = ?";

    // Prepara la consulta SQL
    $stmt = mysqli_prepare($conn, $sql);
    #$stmt2 = mysqli_prepare($conn, $sql);

    // Verificar que la función 'mysqli_prepare' haya tenido éxito
    if (!$stmt) {
        die("Error al preparar la consulta SQL: " . mysqli_error($conn));
    }

    // Asocia los parámetros con los valores
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    // Ejecuta la consulta SQL
    if (mysqli_stmt_execute($stmt)) {
        // Obtiene el resultado de la consulta
        $resultado= mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($resultado) === 1) {
            $row = $resultado->fetch_assoc();
            $hash_contr = $row['password']; // Puedes ajustar el formato según tus necesidades
            $dni_cifr = $row['dni'];
            if (password_verify($contr, $hash_contr) && $dni == $dni_cifr) { // Inicio de sesión exitoso
                
                    $_SESSION["usuario"] = $usuario;
                    header("Location: principal.php"); // Redirige a la página de inicio
                    exit();
                } else {
                    // Credenciales incorrectas
                    // echo "Credenciales incorrectas...";
                    if ($_SESSION['incorrectosSeguidos'] == ''){
                        $_SESSION['incorrectosSeguidos'] = 1;
                        echo "<script> window.location.replace('http://localhost:81/index.php'); </script> ";
                    }
                    else {
                        $_SESSION['incorrectosSeguidos'] = $_SESSION['incorrectosSeguidos'] + 1;
            
                        error_log("Fecha: ".date("d-m-20y, H:i:s")." | IP: ".$_SERVER["REMOTE_ADDR"]." --> ERROR de autentificación password o nombre de user incorrectos. Intentos gastados: ".$_SESSION["incorrectosSeguidos"]."/5 \n", 3, "logs.log");
            
                        if ($_SESSION['incorrectosSeguidos'] == 5) {
                            error_log("Fecha: ".date("d-m-20y, H:i:s")." | IP: ".$_SERVER['REMOTE_ADDR']." --> Redirección a dirección antibotting. \n", 3, "logs.log");
                            echo "<script> window.location.replace('http://localhost:81/fallo5veces.php'); </script> ";
                        } else {
                            echo "<script> window.location.replace('http://localhost:81/index.php'); </script> ";
                        }
                    }
                }
            }
        else {
            echo "Error: No existe el usuario";
        }
    }
    else {
        echo "No existe el usuario";
    }

    // Cierra la conexión y la declaración
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    }

    /* Validar contraseña con hash:
        1º PASO:  Obtener el sal del usuario
        $usuario = cifrar($_POST["username"]);
        $sal = "";
        $sql = "SELECT sal FROM usuarios WHERE usuario = ?";    
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        if (mysqli_stmt_execute($stmt)) {
            $sal = mysqli_stmt_get_result($stmt);
        }
        else {
            echo "No existe el usuario"
        }
        2º PASO:  Obtener el hash_contraseña
        $contaseña_sal = ""
        $hash_contraseña = ""
        $contraseña_sal = $contraseña.$sal;
        $hash_contraseña = hash('sha256', $contraseña_sal, false);
        3º PASO:   Comprobar que la contraseña coincide
        $sql2 = "SELECT * FROM usuarios WHERE username = ? AND password = ?";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "ss", $usuario, $hash_contraseña);
        if (mysqli_stmt_execute($stmt2)) {
            $sal = mysqli_stmt_get_result($stmt);
            // Crear la sesión
            session_start();
            $_SESSION["usuario"] = $usuario;
            $_SESSION['dni'] = $dni;
        }
        else {
            // Credenciales incorrectas
            // echo "Credenciales incorrectas...";
            if ($_SESSION['incorrectosSeguidos'] == ''){
                $_SESSION['incorrectosSeguidos'] = 1;
            }
            else {
                $_SESSION['incorrectosSeguidos'] = $_SESSION['incorrectosSeguidos'] + 1;
    
                error_log("Fecha: ".date("d-m-20y, H:i:s")." | IP: ".$_SERVER["REMOTE_ADDR"]." --> ERROR de autentificación password o nombre de user incorrectos. Intentos gastados: ".$_SESSION["incorrectosSeguidos"]."/5 \n", 3, "logs.log");
    
                if ($_SESSION['incorrectosSeguidos'] == 5) {
                    error_log("Fecha: ".date("d-m-20y, H:i:s")." | IP: ".$_SERVER['REMOTE_ADDR']." --> Redirección a dirección antibotting. \n", 3, "logs.log");
                    echo "<script> window.location.replace('http://localhost:81/fallo5veces.php'); </script> ";
                } else {
                    echo "<script> window.location.replace('http://localhost:81/index.php'); </script> ";
                }
            }
        }
    */
