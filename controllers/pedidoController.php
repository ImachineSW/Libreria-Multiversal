<?php

class PedidoController {

    private $pedidoModel;

    public function __construct($conexion) { //recibe la conexion y crea el modelo de pedidos
        $this->pedidoModel = new PedidoModel($conexion);
    }

    // recibe el carrito enviado por el formulario, lo valida, guarda el pedido y muestra la confirmacion
    public function confirmarPedido() {
        // Cuando exista el login: requerirLogin() y $idUsuario = usuarioActualId();
        $idUsuario = null; // por ahora puede comprar un invitado, el pedido queda sin usuario

        // Si alguien intenta entrar a esta pagina sin enviar el formulario, lo mandamos de vuelta al carrito
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['carritoJson'])) {
            header('Location: index.php?pagina=carrito');
            exit;
        }

        $carritoRecibido = json_decode($_POST['carritoJson'], true);

        if (empty($carritoRecibido) || !is_array($carritoRecibido)) {
            header('Location: index.php?pagina=carrito');
            exit;
        }

        // Valida cada item y junta las cantidades si un libro viene repetido
        $items = [];
        foreach ($carritoRecibido as $itemDelCarrito) {
            if (!is_array($itemDelCarrito) || !isset($itemDelCarrito['id'], $itemDelCarrito['cantidad'])
                || !ctype_digit((string) $itemDelCarrito['id']) || !ctype_digit((string) $itemDelCarrito['cantidad'])
                || (int) $itemDelCarrito['id'] < 1 || (int) $itemDelCarrito['cantidad'] < 1) { // id y cantidad deben ser enteros positivos
                header('Location: index.php?pagina=carrito');
                exit;
            }
            $id = (int) $itemDelCarrito['id'];
            $items[$id] = ($items[$id] ?? 0) + (int) $itemDelCarrito['cantidad'];
        }

        // Calcula el total del pedido sumando (precio x cantidad) de cada libro
        // El precio se toma de la base de datos, no del navegador, para que nadie pueda modificarlo
        $totalDelPedido = 0;
        $lineas = [];
        foreach ($items as $idLibro => $cantidad) {
            $precio = $this->pedidoModel->obtenerPrecio($idLibro);
            if ($precio === null) { // si el libro no existe no se guarda nada
                header('Location: index.php?pagina=carrito');
                exit;
            }
            $totalDelPedido += $precio * $cantidad;
            $lineas[] = ["id" => $idLibro, "cantidad" => $cantidad, "precio" => $precio];
        }
        $totalDelPedido = round($totalDelPedido, 2);

        $this->pedidoModel->iniciarTransaccion(); // si algo falla no se guarda nada a medias

        // 1. Creamos el pedido y obtenemos el id que le asigno la base de datos
        $idPedidoCreado = $this->pedidoModel->crearPedido($idUsuario, $totalDelPedido);
        $guardado = ($idPedidoCreado !== false);

        // 2. Guardamos el detalle: una fila por cada libro del carrito
        if ($guardado) {
            foreach ($lineas as $linea) {
                if (!$this->pedidoModel->agregarDetalleAlPedido($idPedidoCreado, $linea["id"], $linea["cantidad"], $linea["precio"])) {
                    $guardado = false;
                    break;
                }
            }
        }

        if (!$guardado) { // si algo fallo se deshace todo
            $this->pedidoModel->cancelarTransaccion();
            http_response_code(500);
            echo "<p>No se pudo guardar el pedido.</p>";
            return;
        }

        $this->pedidoModel->confirmarTransaccion(); // confirma todo junto

        // 3. Mostramos la vista de confirmacion con el resumen del pedido
        // La vista pedido_confirmado.php utiliza las variables $pedido y $items
        $datos = $this->pedidoModel->obtenerPedido($idPedidoCreado);
        $pedido = $datos["pedido"];
        $items = $datos["items"];

        require __DIR__ . '/../views/pedido_confirmado.php';
    }
}
