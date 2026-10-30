<?php

class PagoModel {

    private $conexion;

    public function __construct($conexion) { //recibe la conexion a la base de datos
        $this->conexion = $conexion;
    }

    // Registra el intento de pago y actualiza el pedido en una transaccion.
    // Al tercer rechazo, cancela el pedido y devuelve el stock una sola vez.
    public function registrarIntento($idPedido, $monto, $ultimos4, $resultado) {
        $nuevoEstado = ($resultado === 'aprobado') ? 'pagado' : 'rechazado';

        $this->conexion->begin_transaction();

        try {
            // Bloquea el pedido para impedir pagos simultaneos o pagos de pedidos cancelados.
            $stmt = $this->conexion->prepare(
                "SELECT estado_pedido FROM pedido WHERE id = ? FOR UPDATE"
            );
            $stmt->bind_param("i", $idPedido);
            $stmt->execute();
            $pedido = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$pedido || $pedido['estado_pedido'] !== 'activo') {
                throw new Exception("El pedido no existe o ya fue cancelado.");
            }

            // No se aceptan nuevos intentos si ya se aprobó un pago.
            $stmt = $this->conexion->prepare(
                "SELECT estado FROM pedido WHERE id = ?"
            );
            $stmt->bind_param("i", $idPedido);
            $stmt->execute();
            $estadoActual = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            if (!$estadoActual || $estadoActual['estado'] === 'pagado') {
                throw new Exception("El pedido ya está pagado.");
            }

            $stmt = $this->conexion->prepare(
                "INSERT INTO pago (id_pedido, monto, ultimos4, resultado) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("idss", $idPedido, $monto, $ultimos4, $resultado);
            if (!$stmt->execute()) {
                throw new Exception("No se pudo registrar el pago.");
            }
            $stmt->close();

            $stmt = $this->conexion->prepare(
                "UPDATE pedido SET estado = ? WHERE id = ? AND estado_pedido = 'activo'"
            );
            $stmt->bind_param("si", $nuevoEstado, $idPedido);
            if (!$stmt->execute() || $stmt->affected_rows !== 1) {
                throw new Exception("No se pudo actualizar el pedido.");
            }
            $stmt->close();

            if ($resultado !== 'aprobado') {
                $stmt = $this->conexion->prepare(
                    "SELECT COUNT(*) AS cantidad FROM pago WHERE id_pedido = ? AND resultado = 'rechazado'"
                );
                $stmt->bind_param("i", $idPedido);
                $stmt->execute();
                $conteo = $stmt->get_result()->fetch_assoc();
                $stmt->close();

                if ((int) ($conteo['cantidad'] ?? 0) >= 3) {
                    // El cambio de estado solo ocurre una vez; solo esa transacción devuelve stock.
                    $stmt = $this->conexion->prepare(
                        "UPDATE pedido SET estado_pedido = 'cancelado' WHERE id = ? AND estado_pedido = 'activo'"
                    );
                    $stmt->bind_param("i", $idPedido);
                    if (!$stmt->execute()) {
                        throw new Exception("No se pudo cancelar el pedido tras tres rechazos.");
                    }
                    $cancelado = ($stmt->affected_rows === 1);
                    $stmt->close();

                    if ($cancelado) {
                        $stmt = $this->conexion->prepare(
                            "SELECT libro_id, cantidad FROM detalle_pedido WHERE pedido_id = ?"
                        );
                        $stmt->bind_param("i", $idPedido);
                        $stmt->execute();
                        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                        $stmt->close();

                        $stmt = $this->conexion->prepare(
                            "UPDATE libros SET stock = stock + ? WHERE id = ?"
                        );
                        foreach ($items as $item) {
                            $cantidad = (int) $item['cantidad'];
                            $idLibro = (int) $item['libro_id'];
                            $stmt->bind_param("ii", $cantidad, $idLibro);
                            if (!$stmt->execute() || $stmt->affected_rows !== 1) {
                                throw new Exception("No se pudo devolver el stock tras cancelar el pedido.");
                            }
                        }
                        $stmt->close();
                    }
                }
            }

            $this->conexion->commit();
            return true;

        } catch (Throwable $e) {
            $this->conexion->rollback();
            return false;
        }
    }

    // ultimo pago aprobado de un pedido (null si no hay)
    public function obtenerAprobado($idPedido) {
        $stmt = $this->conexion->prepare(
            "SELECT ultimos4, monto, fecha
             FROM pago
             WHERE id_pedido = ? AND resultado = 'aprobado'
             ORDER BY id DESC LIMIT 1"
        );
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $pago = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $pago ?: null;
    }
}
