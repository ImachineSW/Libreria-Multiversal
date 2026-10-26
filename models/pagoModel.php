<?php

class PagoModel {

    private $conexion;

    public function __construct($conexion) { //recibe la conexion a la base de datos
        $this->conexion = $conexion;
    }

    // registra el intento de pago Y actualiza el estado del pedido en una sola transaccion
    // devuelve true si salio bien, false si fallo
    public function registrarIntento($idPedido, $monto, $ultimos4, $resultado) {
        $nuevoEstado = ($resultado === 'aprobado') ? 'pagado' : 'rechazado';

        $this->conexion->begin_transaction();

        try {
            // cambio 1: guardar el pago
            $stmt = $this->conexion->prepare(
                "INSERT INTO pago (id_pedido, monto, ultimos4, resultado) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("idss", $idPedido, $monto, $ultimos4, $resultado);
            if (!$stmt->execute()) {
                throw new Exception("No se pudo registrar el pago.");
            }
            $stmt->close();

            // cambio 2: actualizar el estado del pedido
            $stmt = $this->conexion->prepare("UPDATE pedido SET estado = ? WHERE id = ?");
            $stmt->bind_param("si", $nuevoEstado, $idPedido);
            if (!$stmt->execute()) {
                throw new Exception("No se pudo actualizar el pedido.");
            }
            $stmt->close();

            $this->conexion->commit();
            return true;

        } catch (Throwable $e) {
            $this->conexion->rollback();
            return false;
        }
    }
}
