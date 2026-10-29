<?php

require_once __DIR__ . '/libroModel.php';

class PedidoModel {

    private $conexion;
    private $libroModel;

    public function __construct($conexion) { //recibe la conexion a la base de datos
        $this->conexion = $conexion;
        $this->libroModel = new LibroModel($conexion); // misma conexion: la transaccion cubre pedido y stock
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

                // descuenta el stock; si no alcanza se cancela TODO el pedido
                if (!$this->libroModel->descontarStock($item['id'], $item['cantidad'])) {
                    throw new Exception("Stock insuficiente para el libro " . $item['id']);
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

    // ---------- Historial del cliente ----------

    // todos los pedidos de un usuario, del mas nuevo al mas viejo
    public function listarPorUsuario($idUsuario) {
        $stmt = $this->conexion->prepare(
            "SELECT id, fecha, total, estado, estado_envio
             FROM pedido
             WHERE id_usuario = ?
             ORDER BY fecha DESC, id DESC"
        );
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $pedidos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $pedidos;
    }

    // libros de un pedido (usar solo DESPUES de verificar que el pedido es del usuario o que quien pide es admin)
    public function obtenerDetalle($idPedido) {
        $stmt = $this->conexion->prepare(
            "SELECT l.nombre, d.cantidad, d.precio_unitario,
                    (d.cantidad * d.precio_unitario) AS subtotal
             FROM detalle_pedido d
             INNER JOIN libros l ON l.id = d.libro_id
             WHERE d.pedido_id = ?"
        );
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $detalle = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $detalle;
    }

    // ---------- Metodos para el panel de administracion ----------

    // lista de pedidos con el nombre del cliente. Los filtros son opcionales.
    // $filtros puede traer: 'estado', 'estado_envio', 'desde', 'hasta', 'limite'
    public function listarAdmin($filtros = []) {
        $sql = "SELECT p.id, p.fecha, p.total, p.estado, p.estado_pedido, p.estado_envio,
                       u.nombre AS cliente
                FROM pedido p
                INNER JOIN usuario u ON u.id = p.id_usuario
                WHERE 1=1";
        $tipos = '';
        $params = [];

        // cada filtro se suma solo si se eligio
        if (!empty($filtros['estado'])) {
            $sql .= " AND p.estado = ?";
            $tipos .= 's';
            $params[] = $filtros['estado'];
        }
        if (!empty($filtros['estado_envio'])) {
            $sql .= " AND p.estado_envio = ?";
            $tipos .= 's';
            $params[] = $filtros['estado_envio'];
        }
        if (!empty($filtros['desde'])) {
            $sql .= " AND p.fecha >= ?";
            $tipos .= 's';
            $params[] = $filtros['desde'];
        }
        if (!empty($filtros['hasta'])) {
            // "hasta" incluye todo ese dia: se llega hasta el comienzo del dia siguiente
            $sql .= " AND p.fecha < DATE_ADD(?, INTERVAL 1 DAY)";
            $tipos .= 's';
            $params[] = $filtros['hasta'];
        }

        $sql .= " ORDER BY p.fecha DESC, p.id DESC";

        if (!empty($filtros['limite'])) {
            $sql .= " LIMIT " . (int) $filtros['limite']; // (int) garantiza que sea un numero
        }

        $stmt = $this->conexion->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($tipos, ...$params);
        }
        $stmt->execute();
        $pedidos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $pedidos;
    }

    // un pedido cualquiera (sin importar de quien sea) con los datos de su cliente, o null
    // solo se usa desde un controlador protegido con requerirRol('admin')
    public function obtenerParaAdmin($idPedido) {
        $stmt = $this->conexion->prepare(
            "SELECT p.*, u.nombre AS cliente_nombre, u.email AS cliente_email
             FROM pedido p
             INNER JOIN usuario u ON u.id = p.id_usuario
             WHERE p.id = ?"
        );
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $pedido = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $pedido ?: null;
    }

    // avanza el envio de un pedido PAGADO. Solo cambia si el pedido sigue en el estado que se cree.
    // devuelve true si cambio, false si no
    public function cambiarEstadoEnvio($idPedido, $estadoActual, $estadoNuevo) {
        $stmt = $this->conexion->prepare(
            "UPDATE pedido SET estado_envio = ?
             WHERE id = ? AND estado = 'pagado' AND estado_pedido = 'activo' AND estado_envio = ?"
        );
        $stmt->bind_param("sis", $estadoNuevo, $idPedido, $estadoActual);
        $stmt->execute();
        $cambio = ($stmt->affected_rows === 1);
        $stmt->close();

        return $cambio;
    }
    // Cancela un pedido y devuelve sus unidades al stock una sola vez.
// Si $idUsuario se indica, el pedido debe pertenecer a ese usuario.
// Si $permitirPagado es false, solo se permite cancelar antes del pago.
public function cancelarPedido($idPedido, $idUsuario = null, $permitirPagado = false) {
    $this->conexion->begin_transaction();

    try {
        $sql = "SELECT id, estado, estado_pedido, estado_envio
                FROM pedido
                WHERE id = ?";

        if ($idUsuario !== null) {
            $sql .= " AND id_usuario = ?";
        }

        $sql .= " FOR UPDATE";

        $stmt = $this->conexion->prepare($sql);

        if ($idUsuario !== null) {
            $stmt->bind_param("ii", $idPedido, $idUsuario);
        } else {
            $stmt->bind_param("i", $idPedido);
        }

        $stmt->execute();
        $pedido = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$pedido || $pedido['estado_pedido'] !== 'activo') {
            $this->conexion->rollback();
            return false;
        }

        // El cliente solo puede cancelar antes de pagar.
        if (!$permitirPagado && $pedido['estado'] === 'pagado') {
            $this->conexion->rollback();
            return false;
        }

        // No se cancela un pedido que ya fue entregado.
        if ($pedido['estado_envio'] === 'entregado') {
            $this->conexion->rollback();
            return false;
        }

        // Al personal se le permite cancelar pedidos pagados,
        // pero no se altera el historial ni se borran sus detalles.
        $stmt = $this->conexion->prepare(
            "SELECT libro_id, cantidad
             FROM detalle_pedido
             WHERE pedido_id = ?"
        );
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if (empty($items)) {
            $this->conexion->rollback();
            return false;
        }

        // Primero marca el pedido como cancelado. La transacción garantiza
        // que el cambio y la devolución del stock se confirmen juntos.
        $stmt = $this->conexion->prepare(
            "UPDATE pedido
             SET estado_pedido = 'cancelado'
             WHERE id = ? AND estado_pedido = 'activo'"
        );
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $actualizado = ($stmt->affected_rows === 1);
        $stmt->close();

        if (!$actualizado) {
            throw new Exception('No se pudo cancelar el pedido.');
        }

        $stmt = $this->conexion->prepare(
            "UPDATE libros SET stock = stock + ? WHERE id = ?"
        );

        foreach ($items as $item) {
            $cantidad = (int) $item['cantidad'];
            $idLibro = (int) $item['libro_id'];

            $stmt->bind_param("ii", $cantidad, $idLibro);

            if (!$stmt->execute() || $stmt->affected_rows !== 1) {
                throw new Exception('No se pudo devolver el stock.');
            }
        }

        $stmt->close();
        $this->conexion->commit();

        return true;

    } catch (Throwable $e) {
        $this->conexion->rollback();
        return false;
    }
}

    // cantidad de pedidos y suma de totales por estado de pago, en un rango de fechas
    public function resumenPorEstado($desde, $hasta) {
        $stmt = $this->conexion->prepare(
            "SELECT estado, COUNT(*) AS cantidad, SUM(total) AS total
             FROM pedido
             WHERE fecha >= ? AND fecha < DATE_ADD(?, INTERVAL 1 DAY)
             GROUP BY estado"
        );
        $stmt->bind_param("ss", $desde, $hasta);
        $stmt->execute();
        $resumen = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $resumen;
    }
}
