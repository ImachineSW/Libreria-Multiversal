<?php

require_once __DIR__ . '/config/conexion.php'; // Se inica la conexion a la base datos para  que todas las paginas puedan acceder a la misma

require_once __DIR__ . '/models/libroModel.php'; // Se carga el modelo de libros

require_once __DIR__ . '/controllers/libroController.php'; // Se carga el controlador de libros


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
        require __DIR__ . '/views/register.php'; // Se muestra el registro
        break;

    case 'login': // Si se ingresa 'login' se muestra el login
        require __DIR__ . '/views/login.php'; // Se muestra el login
        break;

    case 'libro': // Si se ingresa 'libro' se muestra el libro
        $libroController = new LibroController($conexion); // Se crea el controlador de libros
        $libroController->mostrarDetalle(); // Se muestra el detalle del libro
        break;

    default: // Si no se ingresa nada se muestra la pagina no encontrada
        http_response_code(404);
        echo 'Página no encontrada.';
        break;
}
