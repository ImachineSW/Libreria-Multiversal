<?php
$server = "localhost"; // server de la base de datos
$user = "root"; // usuario de la base de datos
$passwd = ""; // contraseña de la base de datos
$db = "Multiversal"; // se le asigna el nombre de la base de datos a la variable

$conexion = mysqli_connect($server, $user, $passwd, $db); // se la asgina la conexion de a la db

if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error()); // muestra mensaje de error si la conexion falla
} else {
    echo "conexion realizada correctamente"; // muestra mensaje si la conexion es exitosa
}
?>
