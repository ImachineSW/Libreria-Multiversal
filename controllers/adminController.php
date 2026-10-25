<?php
// controllers/AdminController.php
class AdminController {
private $conexion;
private $libroModel;

public function __construct($conexion) {
// Nadie que no sea admin puede ni siquiera usar este controlador
requerirRol('admin'); // Utilizamos la función de auth.php
$this->conexion = $conexion;
$this->libroModel = new LibroModel($conexion);
}


public function panel() {
$nombreAdmin = usuarioActualNombre();
$libroStockBajo = $this->libroModel->listarStockBajo();
require __DIR__ . '/../views/admin.php';
}


}