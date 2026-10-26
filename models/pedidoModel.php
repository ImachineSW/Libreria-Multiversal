<?php

class PedidoModel {

    private $conexion;

    public function __construct($conexion) { //recibe la conexion a la base de datos
        $this->conexion = $conexion;
    }

    // obtiene el precio actual de un libro (el precio se toma de la base de datos, no del navegador)
    public function obtenerPrecio($idLibro) {
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

    // transaccion: si algo falla al guardar el pedido no se guarda nada a medias
    public function iniciarTransaccion() {
        mysqli_begin_transaction($this->conexion);
    }

    public function confirmarTransaccion() {
        mysqli_commit($this->conexion);
    }

    public function cancelarTransaccion() {
        mysqli_rollback($this->conexion);
    }

    // crea el pedido, recibe el id del usuario, el total y la direccion de envio (arreglo)
    // devuelve el id del pedido creado o false si ocurrio un error
    public function crearPedido($idUsuario, $totalDelPedido, $envio) {
        $consultaSql = "INSERT INTO pedido (id_usuario, fecha, estado, total, envio_nombre, envio_direccion, envio_ciudad, envio_departamento, envio_telefono)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $consultaPreparada = mysqli_prepare($this->conexion, $consultaSql);

        if (!$consultaPreparada) {
            return false;
        }

        $estadoInicial = "pendiente";
        $fecha = (new DateTime('now', new DateTimeZone('America/Montevideo')))->format('Y-m-d H:i:s'); //fecha y hora actual de Montevideo (no depende del servidor de la base de datos)
        mysqli_stmt_bind_param(
            $consultaPreparada,
            "issdsssss",
            $idUsuario, $fecha, $estadoInicial, $totalDelPedido,
            $envio['nombre'], $envio['direccion'], $envio['ciudad'],
            $envio['departamento'], $envio['telefono']
        );

        if (!mysqli_stmt_execute($consultaPreparada)) {
            mysqli_stmt_close($consultaPreparada);
            return false;
        }

        mysqli_stmt_close($consultaPreparada);

        // mysqli_insert_id() devuelve el id que genero el AUTO_INCREMENT
        return mysqli_insert_id($this->conexion);
    }

    // guarda una fila del detalle del pedido (un libro del carrito), devuelve true si se guardo
    public function agregarDetalleAlPedido($idPedido, $idLibro, $cantidad, $precioUnitario) {
        $consultaSql = "INSERT INTO detalle_pedido (pedido_id, libro_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
        $consultaPreparada = mysqli_prepare($this->conexion, $consultaSql);

        if (!$consultaPreparada) {
            return false;
        }

        mysqli_stmt_bind_param($consultaPreparada, "iiid", $idPedido, $idLibro, $cantidad, $precioUnitario);
        $guardado = mysqli_stmt_execute($consultaPreparada);
        mysqli_stmt_close($consultaPreparada);

        return $guardado;
    }

    // crea el pedido y todas sus lineas de detalle en una sola transaccion
    // devuelve el id del pedido, o false si algo fallo
    public function crearPedidoCompleto($idUsuario, $total, $envio, $carrito) {
        $this->iniciarTransaccion();

        try {
            $idPedido = $this->crearPedido($idUsuario, $total, $envio);

            if ($idPedido === false) {
                throw new Exception("No se pudo crear el pedido.");
            }

            foreach ($carrito as $item) {
                if (!$this->agregarDetalleAlPedido($idPedido, $item['id'], $item['cantidad'], $item['precio'])) {
                    throw new Exception("No se pudo guardar el detalle.");
                }
            }

            $this->confirmarTransaccion();
            return $idPedido;

        } catch (Throwable $e) {
            $this->cancelarTransaccion();
            return false;
        }
    }

    // devuelve el pedido SOLO si es del usuario indicado (o null)
    public function obtenerPorId($idPedido, $idUsuario) {
        $stmt = $this->conexion->prepare("SELECT * FROM pedido WHERE id = ? AND id_usuario = ?");
        $stmt->bind_param("ii", $idPedido, $idUsuario);
        $stmt->execute();
        $pedido = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $pedido ?: null;
    }

    // obtiene un pedido con su detalle (libros comprados), devuelve null si no existe
    public function obtenerPedido($idPedido) {
        $stmt = mysqli_prepare($this->conexion, "SELECT id, fecha, estado, total FROM pedido WHERE id = ?"); //busca el pedido con parametros por seguridad
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
