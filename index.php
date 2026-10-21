<?php

require_once __DIR__ . '/config/conexion.php'; // Se inica la conexion a la base datos para  que todas las paginas puedan acceder a la misma

require_once __DIR__ . '/models/libroModel.php'; // Se carga el modelo de libros

require_once __DIR__ . '/controllers/libroController.php'; // Se carga el controlador de libros

require_once __DIR__ . "/controllers/AuthController.php"; // Se carga el controlador de autenticacion




$paginaSolicitada = $_GET['pagina'] ?? 'catalogo'; // Se define la pagina que se va a mostrar segun lo que se ingrese en la url, si no se ingresa nada se muestra el catalogo

switch ($paginaSolicitada) {

    case 'catalogo': // Si se ingresa 'catalogo' se muestra el catalogo
        $libroController = new LibroController($conexion); // Se crea el controlador de libros
        $libroController->catalogo(); // Se muestra el catalogo
        break;

    case 'carrito': // Si se ingresa 'carrito' se muestra el carrito
        require __DIR__ . '/views/carrito.php'; // Se muestra el carrito
        break;

    case 'register': // Si se ingresa 'register' se muestra el registro
        $mensaje = "";

        // Primero se procesa el formulario y después se muestra la vista.
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $controller = new AuthController($conexion);
            $mensaje = $controller->registrar($_POST);

            if ($mensaje === "Usuario registrado correctamente." || $mensaje === "") {
                header("Location: index.php?pagina=login&registrado=1", true, 303);
                exit;
            }
        }

        require __DIR__ . '/views/register.php';
        break;

    case 'login': // Si se ingresa 'login' se muestra el login
        $mensaje = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (($_POST["accion"] ?? "") === "logout") {
                AuthController::cerrarSesion();
                header("Location: index.php?pagina=login&salio=1", true, 303);
                exit;
            }

            $controller = new AuthController($conexion);
            $mensaje = $controller->iniciarSesion($_POST);

            if ($mensaje === "") {
                header("Location: index.php?pagina=login", true, 303);
                exit;
            }
        }

        require __DIR__ . '/views/login.php';
        break;

    case 'libro': // Si se ingresa 'libro' se muestra el libro
        $libroController = new LibroController($conexion); // Se crea el controlador de libros
        $libroController->mostrarDetalle(); // Se muestra el detalle del libro
        break;

    case 'panel': // Si se ingresa 'panel' se muestra el panel
        require __DIR__ . '/views/panel.php'; // Se muestra el panel
        break;

    default: // Si no se ingresa nada se muestra la pagina no encontrada
        http_response_code(404);
        echo 'Página no encontrada.';
        break;
}
