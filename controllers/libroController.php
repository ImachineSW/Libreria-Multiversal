<?php

class LibroController {

    private $libroModel;

    public function __construct($conexion) { //recibe la conexion y crea el modelo de libros
        $this->libroModel = new LibroModel($conexion);
    }

    // muestra el catalogo
    public function catalogo() {

        $busqueda = trim($_GET['q'] ?? '');
        $busqueda = mb_substr($busqueda, 0, 50);

        $genero = trim($_GET['genero'] ?? '');
        $genero = mb_substr($genero, 0, 100);

        $resultado = $this->libroModel->listarLibros(
            $busqueda,
            $genero
        );

        $generos = $this->libroModel->listarGeneros();

        if ($resultado === false || $generos == false) {
            http_response_code(500);
            die("Error al consultar el catálogo.");
        }

        require __DIR__ . "/../views/catalogo.php";
    }

    // muestra el detalle del libro
    public function mostrarDetalle() {

        $busqueda = trim($_GET['q'] ?? '');
        $busqueda = mb_substr($busqueda, 0, 50);

        $genero = trim($_GET['genero'] ?? '');
        $genero = mb_substr($genero, 0, 100);

        if (
            !isset($_GET['id']) ||
            !ctype_digit((string) $_GET['id']) ||
            (int) $_GET['id'] < 1
        ) {
            http_response_code(404);
            echo "<p>Libro no encontrado.</p>";
            return;
        }

        $idLibro = (int) $_GET['id'];

        $resultado = $this->libroModel->obtenerPorId(
            $idLibro
        );

        if (!$resultado || $resultado->num_rows === 0) {
            http_response_code(404);
            echo "<p>El libro solicitado no existe.</p>";
            return;
        }

        $libro = $resultado->fetch_assoc();

        require __DIR__ . "/../views/productos.php";
    }

    public function carrito() {

        $busqueda = trim($_GET['q'] ?? '');
        $busqueda = mb_substr($busqueda, 0, 50);

            $resultado = $this->libroModel->listarLibros(
                $busqueda
            );

            if ($resultado === false) {
            http_response_code(500);
            die("Error al consultar los libros.");
            }


        require __DIR__ . '/../views/carrito.php';
    }

}

