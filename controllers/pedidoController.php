<?php

class PedidoController {

    private $conexion;
    private $pedidoModel;
    private $pagoModel;
    private $libroModel;
    private $errorCarrito = ''; // motivo por el que el carrito no es valido (por ejemplo, falta stock)

    private $departamentos = [
        'Artigas', 'Canelones', 'Cerro Largo', 'Colonia', 'Durazno', 'Flores',
        'Florida', 'Lavalleja', 'Maldonado', 'Montevideo', 'Paysandú',
        'Río Negro', 'Rivera', 'Rocha', 'Salto', 'San José', 'Soriano',
        'Tacuarembó', 'Treinta y Tres'
    ];

    public function __construct($conexion) { //recibe la conexion y crea los modelos que usa la clase
        $this->conexion = $conexion;
        $this->pedidoModel = new PedidoModel($conexion);
        $this->pagoModel = new PagoModel($conexion);
        $this->libroModel = new LibroModel($conexion);
    }

    // ---------- Metodos publicos (paginas) ----------

    // recibe el carrito, pide la direccion de envio, la valida y crea el pedido
    public function checkout() {
        requerirLogin(); // sin sesion iniciada se redirige al login

        // 1) que quiere comprar (llega desde pedido.js: solo ids y cantidades)
        $items = $this->leerCarrito();
        if (empty($items)) {
            redirigir('carrito');
        }

        // 2) precios y total: SIEMPRE desde la base de datos
        $datos = $this->calcularCarrito($items);
        if ($datos === null) {
            if ($this->errorCarrito !== '') { // falta stock: se explica el motivo
                $errorPedido = $this->errorCarrito;
                require __DIR__ . '/../views/errorPedido.php';
                return;
            }
            redirigir('carrito');
        }
        $carrito = $datos['carrito'];
        $total = $datos['total'];

        // 3) variables que usa la vista
        $departamentos = $this->departamentos;
        $carritoJson = json_encode($items); // se reenvia dentro del formulario
        $errores = [];
        $envio = [
            'nombre' => '', 'direccion' => '', 'ciudad' => '',
            'departamento' => '', 'telefono' => ''
        ];

        // 4) enviaron el formulario de direccion?
        if (isset($_POST['confirmar'])) {
            $envio = [
                'nombre' => trim($_POST['nombre'] ?? ''),
                'direccion' => trim($_POST['direccion'] ?? ''),
                'ciudad' => trim($_POST['ciudad'] ?? ''),
                'departamento' => trim($_POST['departamento'] ?? ''),
                'telefono' => preg_replace('/\D/', '', $_POST['telefono'] ?? '')
            ];

            $errores = $this->validarEnvio($envio);

            // 5) si no hay errores, crear el pedido (queda "pendiente") asociado al usuario de la sesion
            if (empty($errores)) {
                $idPedido = $this->pedidoModel->crearPedidoCompleto(
                    usuarioActualId(), $total, $envio, $carrito
                );

                if ($idPedido) {
                    redirigir('pago', '&id=' . (int) $idPedido);
                }

                $errores[] = 'No pudimos crear el pedido. Intentá de nuevo.';
            }
        }

        // 6) mostrar el formulario (con errores, si los hay)
        require __DIR__ . '/../views/checkout.php';
    }

    // pantalla de pago: valida la tarjeta, simula el cobro y registra el pago
    public function pagar() {
        $pedido = $this->obtenerPedidoDelUsuario();

        // si ya esta pagado no tiene sentido volver a cobrar
        if ($pedido['estado'] === 'pagado') {
            redirigir('pago_resultado', '&id=' . (int) $pedido['id']);
        }

        $errores = [];
        $titular = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titular = trim($_POST['titular'] ?? '');
            $numero = preg_replace('/\D/', '', $_POST['numero'] ?? '');
            $vencimiento = trim($_POST['vencimiento'] ?? '');
            $cvv = $_POST['cvv'] ?? '';

            $errores = $this->validarTarjeta($titular, $numero, $vencimiento, $cvv);

            if (empty($errores)) {
                $resultado = $this->simularPasarela($numero);

                $ok = $this->pagoModel->registrarIntento(
                    $pedido['id'],
                    $pedido['total'],     // sale de la base de datos, no del formulario
                    substr($numero, -4),  // solo los ultimos 4 digitos
                    $resultado
                );

                if ($ok) {
                    redirigir('pago_resultado', '&id=' . (int) $pedido['id']);
                }

                $errores[] = 'Ocurrió un problema al procesar el pago. Intentá de nuevo.';
            }
        }

        require __DIR__ . '/../views/pago.php';
    }

    // muestra si el pago fue aprobado o rechazado (se lee de la base de datos, no de la URL)
    public function resultado() {
        $pedido = $this->obtenerPedidoDelUsuario();

        // todavia no se intento pagar: lo mandamos a pagar
        if ($pedido['estado'] === 'pendiente') {
            redirigir('pago', '&id=' . (int) $pedido['id']);
        }

        require __DIR__ . '/../views/pago_resultado.php';
    }

    // historial: lista de pedidos del usuario logueado
    public function misPedidos() {
        requerirLogin();

        $pedidos = $this->pedidoModel->listarPorUsuario(usuarioActualId());

        require __DIR__ . '/../views/mis_pedidos.php';
    }

    // detalle de un pedido del usuario logueado
    public function verPedido() {
        $pedido = $this->obtenerPedidoDelUsuario(); // primero se verifica que el pedido es del usuario
        $detalle = $this->pedidoModel->obtenerDetalle($pedido['id']); // despues se piden sus libros

        require __DIR__ . '/../views/pedido_detalle.php';
    }

    // ---------- Metodos privados de apoyo ----------

    // lee carritoJson (POST) y devuelve [['id' => 5, 'cantidad' => 2], ...]
    // si algun dato no es valido devuelve un arreglo vacio; si un libro viene repetido junta las cantidades
    private function leerCarrito() {
        $json = $_POST['carritoJson'] ?? '';
        if (!is_string($json)) {
            return [];
        }

        $lista = json_decode($json, true);
        if (!is_array($lista)) {
            return [];
        }

        $acumulado = [];
        foreach ($lista as $item) {
            if (!is_array($item)) {
                return [];
            }
            $id = filter_var($item['id'] ?? null, FILTER_VALIDATE_INT);
            $cantidad = filter_var($item['cantidad'] ?? null, FILTER_VALIDATE_INT);

            if ($id === false || $id < 1 || $cantidad === false || $cantidad < 1) {
                return []; // un solo dato invalido invalida todo el carrito
            }
            $acumulado[$id] = ($acumulado[$id] ?? 0) + $cantidad;
        }

        $items = [];
        foreach ($acumulado as $id => $cantidad) {
            $items[] = ['id' => $id, 'cantidad' => $cantidad];
        }
        return $items;
    }

    // busca cada libro en la base y calcula el total
    // devuelve null si algun libro no existe o esta dado de baja
    private function calcularCarrito($items) {
        $carrito = [];
        $total = 0;

        foreach ($items as $item) {
            $resultado = $this->libroModel->obtenerPorId($item['id']);
            $libro = $resultado ? $resultado->fetch_assoc() : null;

            if (!$libro) {
                return null;
            }

            // si piden mas unidades de las que hay, el carrito no es valido
            if ($item['cantidad'] > (int) $libro['stock']) {
                $this->errorCarrito = 'No hay stock suficiente de "' . $libro['nombre'] . '" (disponible: ' . (int) $libro['stock'] . ').';
                return null;
            }

            $precio = (float) $libro['precio']; // el precio sale de la BASE
            $carrito[] = [
                'id' => $item['id'],
                'nombre' => $libro['nombre'],
                'cantidad' => $item['cantidad'],
                'precio' => $precio
            ];
            $total += $precio * $item['cantidad'];
        }

        $total = round($total, 2);

        return ($total > 0) ? ['carrito' => $carrito, 'total' => $total] : null;
    }

    // el texto tiene entre $min y $max caracteres?
    private function largoEntre($texto, $min, $max) {
        $largo = mb_strlen($texto);
        return $largo >= $min && $largo <= $max;
    }

    // devuelve la lista de errores del formulario de envio
    private function validarEnvio($envio) {
        $errores = [];

        if (!$this->largoEntre($envio['nombre'], 3, 60)) {
            $errores[] = 'El nombre debe tener entre 3 y 60 caracteres.';
        }
        if (!$this->largoEntre($envio['direccion'], 5, 100)) {
            $errores[] = 'La dirección debe tener entre 5 y 100 caracteres.';
        }
        if (!$this->largoEntre($envio['ciudad'], 2, 50)) {
            $errores[] = 'La ciudad debe tener entre 2 y 50 caracteres.';
        }
        if (!in_array($envio['departamento'], $this->departamentos, true)) {
            $errores[] = 'Elegí un departamento de la lista.';
        }
        if (!$this->largoEntre($envio['telefono'], 8, 12)) {
            $errores[] = 'El teléfono debe tener entre 8 y 12 dígitos.';
        }

        return $errores;
    }

    // devuelve la lista de errores del formulario de tarjeta
    private function validarTarjeta($titular, $numero, $vencimiento, $cvv) {
        $errores = [];

        if (!$this->largoEntre($titular, 3, 60)) {
            $errores[] = 'El nombre del titular debe tener entre 3 y 60 caracteres.';
        }
        if (strlen($numero) !== 16) {
            $errores[] = 'El número de tarjeta debe tener 16 dígitos.';
        }
        if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $vencimiento)) {
            $errores[] = 'El vencimiento debe tener el formato MM/AA.';
        } else {
            $zona = new DateTimeZone('America/Montevideo');
            $vence = DateTime::createFromFormat('!m/y', $vencimiento, $zona);
            $vence->modify('last day of this month')->setTime(23, 59, 59);
            if ($vence < new DateTime('now', $zona)) {
                $errores[] = 'La tarjeta está vencida.';
            }
        }
        if (!preg_match('/^\d{3}$/', $cvv)) {
            $errores[] = 'El código de seguridad debe tener 3 dígitos.';
        }

        return $errores;
    }

    // SIMULACION de la pasarela: ultimo digito par = aprobado, impar = rechazado
    private function simularPasarela($numeroTarjeta) {
        $ultimoDigito = (int) substr($numeroTarjeta, -1);
        return ($ultimoDigito % 2 === 0) ? 'aprobado' : 'rechazado';
    }

    // busca el pedido de la URL y verifica que sea del usuario logueado
    private function obtenerPedidoDelUsuario() {
        requerirLogin();

        $idPedido = (int) ($_GET['id'] ?? 0);
        $pedido = $this->pedidoModel->obtenerPorId($idPedido, usuarioActualId());

        if (!$pedido) {
            // mismo mensaje si no existe o si es de otro usuario: no damos pistas
            http_response_code(403);
            require __DIR__ . '/../views/sin_permiso.php';
            exit;
        }

        return $pedido;
    }
}
