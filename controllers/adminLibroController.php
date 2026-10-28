<?php

require_once __DIR__ . '/../models/LibroModel.php';
require_once __DIR__ . '/../config/imagenes.php';

class AdminLibroController {
    private $conexion;
    private $libroModel;

    public function __construct($conexion) {
        esSuperUser();
        $this->conexion = $conexion;
        $this->libroModel = new LibroModel($conexion);
    }

    public function listar() {
        $libros = $this->libroModel->listarTodosAdmin();

        $avisos = [
            'creado' => 'Libro creado correctamente.',
            'editado' => 'Libro actualizado correctamente.',
            'desactivado' => 'El libro fue dado de baja y ya no se ve en el catálogo.',
            'reactivado' => 'El libro volvió a estar disponible.'
        ];

        $clave = $_GET['msg'] ?? '';
        $aviso = (is_string($clave) && isset($avisos[$clave]))
            ? $avisos[$clave]
            : '';

        require __DIR__ . '/../views/admin/libros_lista.php';
    }

    public function crear() {
        $errores = [];
        $libro = $this->libroVacio();
        $generos = $this->libroModel->listarGeneros();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libro = $this->leerFormulario();
            $errores = $this->validar($libro, $generos);

            if (empty($errores)) {
                $img = guardarImagenLibro($_FILES['imagen'] ?? null);

                if (!$img['ok']) {
                    $errores[] = $img['error'];
                } else {
                    $libro['imagen'] = $img['nombre'];

                    if ($this->libroModel->crear($libro)) {
                        redirigir('admin_libros', '&msg=creado');
                    }

                    borrarImagenLibro($img['nombre']);
                    $errores[] = 'No se pudo guardar el libro. Intentá de nuevo.';
                }
            }
        }

        $titulo = 'Nuevo libro';
        $urlAccion = 'index.php?pagina=admin_libro_crear';

        require __DIR__ . '/../views/admin/admin_form.php';
    }

    public function editar() {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

        $actual = ($id !== false && $id > 0)
            ? $this->libroModel->obtenerParaAdmin($id)
            : null;

        if (!$actual) {
            redirigir('admin_libros');
        }

        $errores = [];
        $libro = $actual;
        $generos = $this->libroModel->listarGeneros();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libro = $this->leerFormulario();
            $libro['imagen'] = $actual['imagen'];
            $libro['activo'] = $actual['activo'];

            $errores = $this->validar($libro, $generos);

            if (empty($errores)) {
                $img = guardarImagenLibro($_FILES['imagen'] ?? null);

                if (!$img['ok']) {
                    $errores[] = $img['error'];
                } else {
                    $subioNueva = ($img['nombre'] !== null);

                    if ($subioNueva) {
                        $libro['imagen'] = $img['nombre'];
                    }

                    if ($this->libroModel->actualizar($id, $libro)) {
                        if ($subioNueva) {
                            borrarImagenLibro($actual['imagen']);
                        }

                        redirigir('admin_libros', '&msg=editado');
                    }

                    if ($subioNueva) {
                        borrarImagenLibro($img['nombre']);
                    }

                    $errores[] = 'No se pudo actualizar el libro. Intentá de nuevo.';
                }
            }
        }

        $titulo = 'Editar libro';
        $urlAccion = 'index.php?pagina=admin_libro_editar&id=' . (int)$id;

        require __DIR__ . '/../views/admin/admin_form.php';
    }

    public function cambiarEstado() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('admin_libros');
        }

        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        $activo = (($_POST['activo'] ?? '') === '1') ? 1 : 0;

        if ($id !== false && $id > 0 && $this->libroModel->obtenerParaAdmin($id)) {
            $this->libroModel->cambiarActivo($id, $activo);

            redirigir(
                'admin_libros',
                $activo ? '&msg=reactivado' : '&msg=desactivado'
            );
        }

        redirigir('admin_libros');
    }

    private function libroVacio() {
    return [
        'nombre' => '',
        'autor' => '',
        'paginas' => '',
        'editorial' => '',
        'fecha' => '',
        'idioma' => '',
        'sinopsis' => '',
        'precio' => '',
        'stock' => '0',
        'stock_minimo' => '5',
        'id_generos' => [],
        'imagen' => null,
        'activo' => 1
    ];
}

    private function leerFormulario() {
    return [
        'nombre' => trim($_POST['nombre'] ?? ''),
        'autor' => trim($_POST['autor'] ?? ''),
        'paginas' => trim($_POST['paginas'] ?? ''),
        'editorial' => trim($_POST['editorial'] ?? ''),
        'fecha' => trim($_POST['fecha'] ?? ''),
        'idioma' => trim($_POST['idioma'] ?? ''),
        'sinopsis' => trim($_POST['sinopsis'] ?? ''),
        'precio' => trim($_POST['precio'] ?? ''),
        'stock' => trim($_POST['stock'] ?? ''),
        'stock_minimo' => trim($_POST['stock_minimo'] ?? ''),
        'id_generos' => isset($_POST['id_generos']) && is_array($_POST['id_generos'])
            ? array_map('intval', $_POST['id_generos'])
            : [],
        'nuevo_genero' => trim($_POST['nuevo_genero'] ?? ''),
        'imagen' => null,
        'activo' => 1
    ];
}

    private function validar($l, $generos) {
        $errores = [];

        if (mb_strlen($l['nombre']) < 2 || mb_strlen($l['nombre']) > 255) {
            $errores[] = 'El nombre debe tener entre 2 y 255 caracteres.';
        }

        if (mb_strlen($l['autor']) < 2 || mb_strlen($l['autor']) > 150) {
            $errores[] = 'El autor debe tener entre 2 y 150 caracteres.';
        }

        if ($l['paginas'] !== '') {
            $paginas = filter_var($l['paginas'], FILTER_VALIDATE_INT);

            if ($paginas === false || $paginas < 1) {
                $errores[] = 'Las páginas deben ser un número entero mayor que 0.';
            }
        }

        if (mb_strlen($l['editorial']) > 150) {
            $errores[] = 'La editorial no puede superar los 150 caracteres.';
        }

        if (mb_strlen($l['fecha']) > 50) {
            $errores[] = 'La fecha no puede superar los 50 caracteres.';
        }

        if (mb_strlen($l['idioma']) > 50) {
            $errores[] = 'El idioma no puede superar los 50 caracteres.';
        }

        if (mb_strlen($l['sinopsis']) > 5000) {
            $errores[] = 'La sinopsis no puede superar los 5000 caracteres.';
        }

        $precio = filter_var($l['precio'], FILTER_VALIDATE_FLOAT);

        if ($precio === false || $precio <= 0 || $precio > 9999999) {
            $errores[] = 'El precio debe ser un número mayor que 0.';
        }

        $stock = filter_var($l['stock'], FILTER_VALIDATE_INT);

        if ($stock === false || $stock < 0) {
            $errores[] = 'El stock debe ser un número entero (0 o más).';
        }

        $minimo = filter_var($l['stock_minimo'], FILTER_VALIDATE_INT);

        if ($minimo === false || $minimo < 0) {
            $errores[] = 'El stock mínimo debe ser un número entero (0 o más).';
        }

$idsValidos = array_map('intval', array_column($generos, 'id'));
$idsSeleccionados = $l['id_generos'];

if (empty($idsSeleccionados) && empty($l['nuevo_genero'])) {
    $errores[] = 'Elegí un género existente o escribí uno nuevo.';
} else {
    foreach ($idsSeleccionados as $idGenero) {
        if (!in_array($idGenero, $idsValidos, true)) {
            $errores[] = 'Uno de los géneros seleccionados no es válido.';
            break;
        }
    }

    if (count($idsSeleccionados) !== count(array_unique($idsSeleccionados))) {
        $errores[] = 'No podés seleccionar el mismo género más de una vez.';
    }
}
        if (mb_strlen($l['nuevo_genero']) > 100) {
        $errores[] = 'El nuevo género no puede superar los 100 caracteres.';
    }
        return $errores;
}

}