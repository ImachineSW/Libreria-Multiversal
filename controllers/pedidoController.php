<?php

class PedidoController {

    private $pedidoModel;

    public function __construct($conexion) { //recibe la conexion y crea el modelo de pedidos
        $this->pedidoModel = new PedidoModel($conexion);
    }

    // recibe el carrito enviado por el navegador, lo valida y guarda el pedido
    public function crear() {
        header('Content-Type: application/json; charset=UTF-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // solo se acepta el metodo POST
            http_response_code(405);
            echo json_encode(["ok" => false, "mensaje" => "Metodo no permitido."]);
            return;
        }

        $datos = json_decode(file_get_contents('php://input'), true); // lee el carrito enviado en formato JSON

        if (!is_array($datos) || !isset($datos['items']) || !is_array($datos['items']) || count($datos['items']) === 0) { // si no hay libros no se guarda nada
            http_response_code(400);
            echo json_encode(["ok" => false, "mensaje" => "El carrito esta vacio."]);
            return;
        }

        // valida cada item y junta las cantidades si un libro viene repetido
        $items = [];
        foreach ($datos['items'] as $item) {
            if (!is_array($item) || !isset($item['id'], $item['cantidad'])
                || !ctype_digit((string) $item['id']) || !ctype_digit((string) $item['cantidad'])
                || (int) $item['id'] < 1 || (int) $item['cantidad'] < 1) { // id y cantidad deben ser enteros positivos
                http_response_code(400);
                echo json_encode(["ok" => false, "mensaje" => "Los datos del carrito no son validos."]);
                return;
            }
            $id = (int) $item['id'];
            $items[$id] = ($items[$id] ?? 0) + (int) $item['cantidad'];
        }

        $lista = [];
        foreach ($items as $id => $cantidad) {
            $lista[] = ["id" => $id, "cantidad" => $cantidad];
        }

        $idPedido = $this->pedidoModel->crearPedido($lista); // guarda el pedido y su detalle

        if ($idPedido === false) { // si el modelo fallo (por ejemplo un libro que no existe)
            http_response_code(422);
            echo json_encode(["ok" => false, "mensaje" => "No se pudo guardar el pedido."]);
            return;
        }

        $_SESSION['pedidos'][] = $idPedido; // guarda el id del pedido en la sesion para poder ver su detalle

        echo json_encode(["ok" => true, "pedido_id" => $idPedido, "mensaje" => "Pedido realizado correctamente."]);
    }

    // muestra el detalle de un pedido (id, fecha, estado y libros comprados)
    public function detalle() {

        // si no se ingresa un id valido, o el pedido no se hizo en esta sesion, muestra pedido no encontrado
        if (!isset($_GET['id']) || !ctype_digit((string) $_GET['id'])
            || !in_array((int) $_GET['id'], $_SESSION['pedidos'] ?? [], true)) {
            http_response_code(404);
            echo "<p>Pedido no encontrado.</p>";
            return;
        }

        $datos = $this->pedidoModel->obtenerPedido((int) $_GET['id']); // busca el pedido en la base de datos

        if ($datos === null) { // si el pedido no existe
            http_response_code(404);
            echo "<p>Pedido no encontrado.</p>";
            return;
        }

        // La vista pedido_detalle.php utiliza las variables $pedido y $items
        $pedido = $datos["pedido"];
        $items = $datos["items"];

        require __DIR__ . '/../views/pedido_detalle.php';
    }
}
