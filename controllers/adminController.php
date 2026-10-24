<?php
// controllers/AdminController.php
class AdminController {
private $conexion;
public function __construct($conexion) {
// Nadie que no sea admin puede ni siquiera usar este controlador
requerirRol('admin'); // Utilizamos la función de auth.php
$this->conexion = $conexion;
}
public function panel() {
$nombreAdmin = usuarioActualNombre();
require __DIR__ . '/../views/admin.php';
}
}