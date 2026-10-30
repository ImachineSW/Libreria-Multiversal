<?php
// controllers/AdminController.php
class AdminController {
private $conexion;
private $libroModel;
private $pedidoModel;

public function __construct($conexion) {
// Nadie que no sea admin o empleado puede ni siquiera usar este controlador
esSuperUser(); // Utilizamos la función de auth.php
$this->conexion = $conexion;
$this->libroModel = new LibroModel($conexion);
$this->pedidoModel = new PedidoModel($conexion);
}


public function panel() {
$nombreAdmin = usuarioActualNombre();
$libroStockBajo = $this->libroModel->listarStockBajo();

// los 5 pedidos mas recientes
$ultimosPedidos = $this->pedidoModel->listarAdmin(['limite' => 5]);

// pedidos pagados que todavia no se enviaron
$pedidosPorEnviar = $this->pedidoModel->listarAdmin([
'estado' => 'pagado',
'estado_envio' => 'preparando'
]);

require __DIR__ . '/../views/admin.php';
}


}