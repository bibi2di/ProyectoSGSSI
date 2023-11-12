<?php
session_start();
// Configuración de encabezados de seguridad
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header_remove("X-Powered-By");


// Conexión a la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = mysqli_connect($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("La conexión a la base de datos falló: " . $conn->connect_error);
}


// phpinfo();
  $hostname = "db";
  $username = "admin";//"administradoresroot";
  $password = "test";//"ProyectoSGSSI#2023";
  $db = "database";

  $conn = mysqli_connect($hostname,$username,$password,$db);
  if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
  }

  /*
  $backupFolder = "ProyectoSGSSI";

  // Verificar si la carpeta existe, si no, la creamos
  if (!is_dir($backupFolder)) {
      mkdir($backupFolder, 0777, true); // Crea la carpeta con permisos
  }

  $backupFilePath = "$backupFolder/usuarios.sql"; // Ruta relativa

  // Comando para crear una copia de seguridad de la base de datos
  $command = "mysqldump -u $username -p $password $db > $backupFilePath";
  

  if (file_exists($backupFilePath)) {
    echo "Copia de seguridad creada exitosamente.";
  } else {
    echo "Error al crear la copia de seguridad.";
  }
  */


?>