<?php

class AdminPedidoController {

    private $conexion;
    private $pedidoModel;
    private $pagoModel;

    // Valores permitidos (listas blancas). Tienen que coincidir con los ENUM de la base.
    private $estadosPago = ['pendiente', 'pagado', 'rechazado'];
    private $estadosEnvio = ['preparando', 'enviado', 'entregado'];

    // El envio solo avanza de a un paso: "estado actual" => "siguiente estado"
    private $siguienteEnvio = ['preparando' => 'enviado', 'enviado' => 'entregado'];

    // Avisos que viajan en la URL (?msg=envio_ok). Solo se muestran los de esta lista.
    private $avisos = [
        'envio_ok'   => ['tipo' => 'exito', 'texto' => 'El estado de envío fue actualizado.'],
        'cancelado'  => ['tipo' => 'exito', 'texto' => 'El pedido fue cancelado y se devolvió el stock.'],
        'invalido'   => ['tipo' => 'error', 'texto' => 'No se pudo realizar la acción: los datos no son válidos.'],
        'transicion' => ['tipo' => 'error', 'texto' => 'Ese cambio de estado no está permitido para este pedido.'],
        'fallo'      => ['tipo' => 'error', 'texto' => 'Ocurrió un problema al guardar el cambio. Intentá de nuevo.']
    ];

    public function __construct($conexion) {
        // Lo PRIMERO: si no es admin, no sigue (login o "Acceso denegado")
        esSuperUser();

        $this->conexion = $conexion;
        $this->pedidoModel = new PedidoModel($conexion);
        $this->pagoModel = new PagoModel($conexion);
    }

    // ---------- LISTAR (historial de todas las compras) ----------
    public function listar() {
        // Filtros que llegaron por la URL (GET). Solo se aceptan valores de las listas blancas.
        $filtroEstado = $this->leerOpcion('estado', $this->estadosPago);
        $filtroEnvio = $this->leerOpcion('estado_envio', $this->estadosEnvio);

        $pedidos = $this->pedidoModel->listarAdmin([
            'estado' => $filtroEstado,
            'estado_envio' => $filtroEnvio
        ]);

        $estadosPago = $this->estadosPago; // para armar los <select> en la vista
        $estadosEnvio = $this->estadosEnvio;
        $aviso = $this->leerAviso();

        require __DIR__ . '/../views/admin/pedidos_lista.php';
    }

    // ---------- VER DETALLE ----------
    public function ver() {
        $id = $this->leerIdDeLaUrl();
        $pedido = ($id !== null) ? $this->pedidoModel->obtenerParaAdmin($id) : null;

        if (!$pedido) {
            redirigir('admin_pedidos', '&msg=invalido'); // no existe: volvemos a la lista
        }

        $detalle = $this->pedidoModel->obtenerDetalle($pedido['id']);
        $pago = $this->pagoModel->obtenerAprobado($pedido['id']); // null si no hay
        $siguiente = $this->siguienteEnvio[$pedido['estado_envio']] ?? null;
        $aviso = $this->leerAviso();

        require __DIR__ . '/../views/admin/pedido_detalle.php';
    }

    // ---------- COMPROBANTE IMPRIMIBLE (PDF) ----------
    public function comprobante() {
        $id = $this->leerIdDeLaUrl();
        $pedido = ($id !== null) ? $this->pedidoModel->obtenerParaAdmin($id) : null;

        if (!$pedido) {
            redirigir('admin_pedidos', '&msg=invalido');
        }

        // solo se emite comprobante de los pedidos donde el pago se efectuo
        if ($pedido['estado'] !== 'pagado') {
            redirigir('admin_pedido_detalle', '&id=' . (int) $pedido['id']);
        }

        $detalle = $this->pedidoModel->obtenerDetalle($pedido['id']);
        $pago = $this->pagoModel->obtenerAprobado($pedido['id']);
        $numeroComprobante = str_pad($pedido['id'], 6, '0', STR_PAD_LEFT);
        $cliente = $pedido['cliente_nombre'];
        $urlVolver = 'index.php?pagina=admin_pedido_detalle&id=' . (int) $pedido['id'];

        require __DIR__ . '/../views/pedido_comprobante.php';
    }

    // ---------- CANCELAR PEDIDO (administracion) ----------
    public function cancelar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('admin_pedidos');
        }

        $id = $this->leerIdDeLaUrl();
        $pedido = ($id !== null) ? $this->pedidoModel->obtenerParaAdmin($id) : null;
        if (!$pedido) {
            redirigir('admin_pedidos', '&msg=invalido');
        }

        $ok = $this->pedidoModel->cancelarPedido((int) $id, null, true);
        redirigir('admin_pedido_detalle', '&id=' . (int) $id . ($ok ? '&msg=cancelado' : '&msg=fallo'));
    }

    // ---------- AVANZAR EL ENVIO ----------
    public function cambiarEnvio() {
        // Solo por POST: un link (GET) nunca debe poder modificar un pedido
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('admin_pedidos');
        }

        $id = $this->leerIdDeLaUrl();
        $nuevo = $_POST['nuevo'] ?? '';

        // 1) Datos validos: id numerico y estado de la lista blanca
        if ($id === null || !is_string($nuevo) || !in_array($nuevo, $this->estadosEnvio, true)) {
            redirigir('admin_pedidos', '&msg=invalido');
        }

        // 2) El pedido tiene que existir
        $pedido = $this->pedidoModel->obtenerParaAdmin($id);
        if (!$pedido) {
            redirigir('admin_pedidos', '&msg=invalido');
        }

        $volver = '&id=' . $id; // para volver al detalle de este pedido

        // 3) Regla: solo se acepta el SIGUIENTE estado de la secuencia
        $esperado = $this->siguienteEnvio[$pedido['estado_envio']] ?? null;
        if ($esperado !== $nuevo) {
            redirigir('admin_pedido_detalle', $volver . '&msg=transicion');
        }

        // 4) Recien ahora se modifica (el modelo vuelve a verificar que este pagado)
        if ($this->pedidoModel->cambiarEstadoEnvio($id, $pedido['estado_envio'], $nuevo)) {
            redirigir('admin_pedido_detalle', $volver . '&msg=envio_ok');
        }
        redirigir('admin_pedido_detalle', $volver . '&msg=fallo');
    }

    // ---------- INFORME DE TRANSACCIONES ----------
    public function informe() {
        if (!esAdmin()) {
            redirigir('admin');
        }
        // Rango por defecto: desde el dia 1 del mes actual hasta hoy (hora de Montevideo)
        $ahora = new DateTime('now', new DateTimeZone('America/Montevideo'));
        $desde = $this->leerFecha('desde') ?: $ahora->format('Y-m-01');
        $hasta = $this->leerFecha('hasta') ?: $ahora->format('Y-m-d');

        $errores = [];
        $resumen = [
            'pendiente' => ['cantidad' => 0, 'total' => 0],
            'pagado'    => ['cantidad' => 0, 'total' => 0],
            'rechazado' => ['cantidad' => 0, 'total' => 0]
        ];
        $ventas = [];

        if ($desde > $hasta) {
            $errores[] = 'La fecha "desde" no puede ser posterior a la fecha "hasta".';
        } else {
            // Se completa el resumen con lo que devuelve la base
            foreach ($this->pedidoModel->resumenPorEstado($desde, $hasta) as $fila) {
                $resumen[$fila['estado']] = [
                    'cantidad' => (int) $fila['cantidad'],
                    'total' => (float) $fila['total']
                ];
            }
            // La lista de ventas: solo pedidos pagados del periodo
            $ventas = $this->pedidoModel->listarAdmin([
                'estado' => 'pagado',
                'desde' => $desde,
                'hasta' => $hasta
            ]);
        }

        // Cifras que muestra la vista
        $cantidadVentas = $resumen['pagado']['cantidad'];
        $totalVentas = $resumen['pagado']['total'];
        $promedio = ($cantidadVentas > 0) ? $totalVentas / $cantidadVentas : 0;

        require __DIR__ . '/../views/admin/informe.php';
    }

    // ---------- Metodos de apoyo (private) ----------

    // Lee el id de la URL (?id=5). Devuelve un entero positivo o null si no es valido.
    private function leerIdDeLaUrl() {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        return ($id !== false && $id > 0) ? $id : null;
    }

    // Lee un parametro de la URL y lo acepta solo si esta en la lista permitida.
    // Devuelve el valor o '' (sin filtro).
    private function leerOpcion($clave, $permitidos) {
        $valor = $_GET[$clave] ?? '';
        return (is_string($valor) && in_array($valor, $permitidos, true)) ? $valor : '';
    }

    // Devuelve el aviso elegido por ?msg= (solo si esta en la lista) o null.
    private function leerAviso() {
        $clave = $_GET['msg'] ?? '';
        return (is_string($clave) && isset($this->avisos[$clave])) ? $this->avisos[$clave] : null;
    }

    // Lee una fecha de la URL con formato AAAA-MM-DD. Devuelve el texto o '' si no es valida.
    private function leerFecha($clave) {
        $valor = $_GET[$clave] ?? '';
        if (!is_string($valor)) {
            return '';
        }
        $fecha = DateTime::createFromFormat('Y-m-d', $valor);
        // createFromFormat "corrige" fechas imposibles (2026-02-31 pasa a 2026-03-03):
        // se compara el resultado con lo que llego para rechazarlas.
        return ($fecha && $fecha->format('Y-m-d') === $valor) ? $valor : '';
    }
}
