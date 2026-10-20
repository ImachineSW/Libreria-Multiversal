<?php

class PedidoModel {

    private $conexion;

    public function __construct($conexion) { //recibe la conexion a la base de datos
        $this->conexion = $conexion;
    }

    // obtiene el precio actual de un libro (el precio se toma de la base de datos, no del navegador)
    private function obtenerPrecio($idLibro) {
        $sql = "SELECT precio FROM libros WHERE id = ?"; //busca el precio del libro con parametros por seguridad
        $stmt = mysqli_prepare($this->conexion, $sql); //se prepara la consulta

        if (!$stmt) { //si no se puede preparar la consulta devuelve null
            return null;
        }

        mysqli_stmt_bind_param($stmt, "i", $idLibro); //asigna el id a la consulta
        mysqli_stmt_execute($stmt); //ejecuta la consulta
        $resultado = mysqli_stmt_get_result($stmt);
        $fila = $resultado ? mysqli_fetch_assoc($resultado) : null;
        mysqli_stmt_close($stmt);

        return $fila ? $fila["precio"] : null; //devuelve el precio o null si el libro no existe
    }

    // guarda el pedido y su detalle, recibe un array con 'id' y 'cantidad' de cada libro
    // devuelve el id del pedido creado o false si ocurrio un error
    public function crearPedido($items) {
        mysqli_begin_transaction($this->conexion); //si algo falla no se guarda nada a medias

        try {
            // calcula el total en el servidor con los precios de la base de datos
            $total = 0;
            $lineas = [];
            foreach ($items as $item) {
                $precio = $this->obtenerPrecio($item["id"]);
                if ($precio === null) { //si el libro no existe se cancela el pedido
                    throw new Exception("Libro inexistente");
                }
                $total += $precio * $item["cantidad"];
                $lineas[] = ["id" => $item["id"], "cantidad" => $item["cantidad"], "precio" => $precio];
            }

            // inserta el pedido con la fecha y hora actual de Montevideo (no depende del servidor de la base de datos)
            $stmt = mysqli_prepare($this->conexion, "INSERT INTO pedidos (total, fecha) VALUES (?, ?)");
            if (!$stmt) {
                throw new Exception("Error al preparar el pedido");
            }
            $total = round($total, 2);
            $fecha = (new DateTime('now', new DateTimeZone('America/Montevideo')))->format('Y-m-d H:i:s');
            mysqli_stmt_bind_param($stmt, "ds", $total, $fecha);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error al guardar el pedido");
            }
            $idPedido = mysqli_insert_id($this->conexion); //id del pedido recien creado
            mysqli_stmt_close($stmt);

            // inserta una fila de detalle por cada libro
            $stmt = mysqli_prepare($this->conexion,
                "INSERT INTO detalle_pedido (pedido_id, libro_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Error al preparar el detalle");
            }
            foreach ($lineas as $linea) {
                mysqli_stmt_bind_param($stmt, "iiid", $idPedido, $linea["id"], $linea["cantidad"], $linea["precio"]);
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Error al guardar el detalle");
                }
            }
            mysqli_stmt_close($stmt);

            mysqli_commit($this->conexion); //confirma todo junto
            return $idPedido;

        } catch (Throwable $e) {
            mysqli_rollback($this->conexion); //deshace todo si hubo un error
            return false;
        }
    }

    // obtiene un pedido con su detalle (libros comprados), devuelve null si no existe
    public function obtenerPedido($idPedido) {
        $stmt = mysqli_prepare($this->conexion, "SELECT id, fecha, estado, total FROM pedidos WHERE id = ?"); //busca el pedido con parametros por seguridad
        if (!$stmt) {
            return null;
        }
        mysqli_stmt_bind_param($stmt, "i", $idPedido);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $pedido = $resultado ? mysqli_fetch_assoc($resultado) : null;
        mysqli_stmt_close($stmt);

        if (!$pedido) { //si el pedido no existe
            return null;
        }

        // busca los libros del pedido junto con el nombre de cada libro
        $sql = "SELECT l.nombre, d.cantidad, d.precio_unitario
                FROM detalle_pedido d
                INNER JOIN libros l ON l.id = d.libro_id
                WHERE d.pedido_id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        if (!$stmt) {
            return null;
        }
        mysqli_stmt_bind_param($stmt, "i", $idPedido);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $items = $resultado ? mysqli_fetch_all($resultado, MYSQLI_ASSOC) : [];
        mysqli_stmt_close($stmt);

        return ["pedido" => $pedido, "items" => $items];
    }
}
