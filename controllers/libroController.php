<?php

class LibroController {

    private $libroModel;

    public function __construct($conexion) { //recibe la conexion y crea el modelo de libros
        $this->libroModel = new LibroModel($conexion);
    }

    // muestra el catalogo
    public function catalogo() {

        $resultado = $this->libroModel->obtenerTodos();

        if ($resultado === false) {
            die("Error al consultar los libros: " .
                mysqli_error($this->conexion ?? null));
        }

        require __DIR__ . "/../views/catalogo.php";
    }

    // muestra el detalle del libro
    public function mostrarDetalle() {
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) { // si no se ingresa un id o no es numerico muestra la pagina no encontrada
        http_response_code(404);
        echo "<p>Libro no encontrado.</p>"; // muestra el mensaje de libro no encontrado
        return;
    }

    $idLibro = (int) $_GET['id']; // obtiene el id del libro, transofrma el numero en entero

    // Obtener el libro desde el modelo
    $resultado = $this->libroModel->obtenerPorId($idLibro);

    if (!$resultado || mysqli_num_rows($resultado) === 0) { //mostrar erro si no existe
        http_response_code(404);
        echo "<p>El libro solicitado no existe.</p>";
        return;
    }

    // La vista libros.php utiliza la variable $libro
    $libro = mysqli_fetch_assoc($resultado);

    require __DIR__ . '/../views/productos.php';
}
}