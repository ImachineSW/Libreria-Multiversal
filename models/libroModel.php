<?php

class LibroModel {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function listarLibros($busqueda = "", $genero = "") { //devuelve todos los libros cargados en el base de datos
        $sql = "SELECT l.id, l.nombre, l.precio, l.autor, l.paginas, l.imagen, l.editorial, l.fecha, l.idioma, l.sinopsis,
                    GROUP_CONCAT(DISTINCT g.nombre ORDER BY g.nombre SEPARATOR ', ') 
                AS genero
                FROM libros l
                LEFT JOIN libros_generos lg ON l.id = lg.libro_id
                LEFT JOIN generos g ON lg.genero_id = g.id
                WHERE 1=1 AND l.activo = 1";
        $tipos = '';
        $params = [];

        if ($busqueda !== "") {
            $sql .= " AND (l.nombre LIKE ? OR l.autor LIKE ?)";
            $tipos .= 'ss';
            $patron = '%' . $this->escaparLike($busqueda) . '%';
            $params[] = $patron;
            $params[] = $patron;
        }

        if ($genero !== "") {
            $sql .= " AND EXISTS (SELECT 1 FROM libros_generos lg2 INNER JOIN generos g2 ON lg2.genero_id = g2.id WHERE lg2.libro_id = l.id AND g2.nombre = ?)";
            $tipos .= 's';
            $params[] = $genero;
        }

        $sql .= " GROUP BY l.id, l.nombre, l.precio, l.autor, l.paginas, l.imagen, l.editorial, l.fecha, l.idioma, l.sinopsis ORDER BY l.nombre";

        $stmt = $this->conexion->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($tipos, ...$params);
        }
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function escaparLike($texto) {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $texto);
    }


    public function obtenerPorId($id) { //obtiene el libros por id para mostrar el detalle de cada uno
        $sql = "SELECT l.id, l.nombre, l.precio, l.autor, l.paginas, l.imagen, l.editorial, l.fecha, l.idioma, l.sinopsis,
                    GROUP_CONCAT(DISTINCT g.nombre ORDER BY g.nombre SEPARATOR ', ') 
                AS genero
                FROM libros l
                LEFT JOIN libros_generos lg ON l.id = lg.libro_id
                LEFT JOIN generos g ON lg.genero_id = g.id
                WHERE l.id = ? AND l.activo = 1
                GROUP BY l.id, l.nombre, l.precio, l.autor, l.paginas, l.imagen, l.editorial, l.fecha, l.idioma, l.sinopsis";
                //busca el libros por id con parametros por seguridad

        $stmt = mysqli_prepare($this->conexion, $sql); //se prepara la consulta

        if (!$stmt) { //si no se puede preparar la consulta muestra error
            return false;
        }

        mysqli_stmt_bind_param($stmt, "i", $id); //asigna el id a la consulta
        mysqli_stmt_execute($stmt); //ejecuta la consulta

        return mysqli_stmt_get_result($stmt); //devuelve el resultado
    }

    public function listarGeneros() {

        $sql = "SELECT id, nombre FROM generos ORDER BY nombre";

        $resultado = $this->conexion->query($sql);

        if (!$resultado) {
            return false;
        }

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }


    private $sqlNivelStock = "CASE
        WHEN l.stock = 0 THEN 'agotado'
        WHEN l.stock <= l.stock_minimo THEN 'bajo'
        ELSE 'ok'
    END";
    public function listarTodosAdmin() {
        $sql = "SELECT l.id, l.nombre, l.precio, l.stock, l.stock_minimo,
                       l.imagen, l.activo, l.autor, l.sinopsis,
                       GROUP_CONCAT(DISTINCT g.nombre SEPARATOR ', ') AS generos,
                       {$this->sqlNivelStock} AS nivel_stock
                FROM libros l
                LEFT JOIN libros_generos lg ON lg.libro_id = l.id
                LEFT JOIN generos g ON g.id = lg.genero_id
                GROUP BY l.id
                ORDER BY l.activo DESC, l.nombre ASC";

        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener un libro para editarlo
    public function obtenerParaAdmin($id) {
    $stmt = $this->conexion->prepare(
        "SELECT l.id, l.nombre, l.autor, l.paginas, l.editorial, l.fecha, l.idioma, l.sinopsis, l.precio,
                l.stock, l.stock_minimo, l.imagen, l.activo,
                (
                    SELECT GROUP_CONCAT(lg.genero_id)
                    FROM libros_generos lg
                    WHERE lg.libro_id = l.id
                ) AS id_generos,
                {$this->sqlNivelStock} AS nivel_stock
         FROM libros l
         WHERE l.id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $libro = $stmt->get_result()->fetch_assoc();

    if ($libro) {
        $libro['id_generos'] = $libro['id_generos']
            ? explode(',', $libro['id_generos'])
            : [];
    }

    return $libro;
}

    // Libros activos con stock bajo o agotado
    public function listarStockBajo() {
        $sql = "SELECT l.id, l.nombre, l.stock, l.stock_minimo,
                       {$this->sqlNivelStock} AS nivel_stock
                FROM libros l
                WHERE l.activo = 1 AND l.stock <= l.stock_minimo
                ORDER BY l.stock ASC, l.nombre ASC";

        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // Crear un libro
// Crear un libro
// Crear un libro
public function crear($datos) {
    $stmt = $this->conexion->prepare(
        "INSERT INTO libros
            (nombre, autor, sinopsis, precio, stock, stock_minimo, imagen, paginas, editorial, fecha, idioma)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssdiisisss",
        $datos['nombre'],
        $datos['autor'],
        $datos['sinopsis'],
        $datos['precio'],
        $datos['stock'],
        $datos['stock_minimo'],
        $datos['imagen'],
        $datos['paginas'],
        $datos['editorial'],
        $datos['fecha'],
        $datos['idioma']
    );

    if (!$stmt->execute()) {
        return false;
    }

    $idLibro = $this->conexion->insert_id;

    if (!empty($datos['nuevo_genero'])) {
    $idGenero = $this->obtenerOCrearGenero($datos['nuevo_genero']);

    if ($idGenero === false || $idGenero === null) {
        return false;
    }

    $datos['id_generos'][] = $idGenero;
}

    $stmtGenero = $this->conexion->prepare(
        "INSERT INTO libros_generos (libro_id, genero_id)
         VALUES (?, ?)"
    );

    foreach ($datos['id_generos'] as $idGenero) {
        $stmtGenero->bind_param("ii", $idLibro, $idGenero);

        if (!$stmtGenero->execute()) {
            $this->conexion->query(
                "DELETE FROM libros_generos WHERE libro_id = " . (int) $idLibro
            );

            $this->conexion->query(
                "DELETE FROM libros WHERE id = " . (int) $idLibro
            );

            return false;
        }
    }

    return $idLibro;

}
// Modificar un libro
public function actualizar($id, $datos) {
    $stmt = $this->conexion->prepare(
        "UPDATE libros
         SET nombre = ?, autor = ?, sinopsis = ?, precio = ?,
             stock = ?, stock_minimo = ?, imagen = ?, paginas = ?,
             editorial = ?, fecha = ?, idioma = ?
         WHERE id = ?"
    );

    $stmt->bind_param(
        "sssdiisisssi",
        $datos['nombre'],
        $datos['autor'],
        $datos['sinopsis'],
        $datos['precio'],
        $datos['stock'],
        $datos['stock_minimo'],
        $datos['imagen'],
        $datos['paginas'],
        $datos['editorial'],
        $datos['fecha'],
        $datos['idioma'],
        $id
    );

    if (!$stmt->execute()) {
        return false;
    }

    $stmtEliminar = $this->conexion->prepare(
        "DELETE FROM libros_generos WHERE libro_id = ?"
    );

    if (!empty($datos['nuevo_genero'])) {
    $idGenero = $this->obtenerOCrearGenero($datos['nuevo_genero']);

    if ($idGenero === false || $idGenero === null) {
        return false;
    }

    $datos['id_generos'][] = $idGenero;
}

    $stmtEliminar->bind_param("i", $id);

    if (!$stmtEliminar->execute()) {
        return false;
    }

    $stmtGenero = $this->conexion->prepare(
        "INSERT INTO libros_generos (libro_id, genero_id)
         VALUES (?, ?)"
    );

    foreach ($datos['id_generos'] as $idGenero) {
        $stmtGenero->bind_param("ii", $id, $idGenero);

        if (!$stmtGenero->execute()) {
            return false;
        }
    }

    return true;
}

// Baja lógica o reactivación
public function cambiarActivo($id, $activo) {
    $stmt = $this->conexion->prepare(
        "UPDATE libros SET activo = ? WHERE id = ?"
    );

    $stmt->bind_param("ii", $activo, $id);

    return $stmt->execute();
}

public function obtenerOCrearGenero($nombre) {
    $nombre = trim($nombre);

    if ($nombre === '') {
        return null;
    }

    $stmt = $this->conexion->prepare(
        "SELECT id FROM generos WHERE nombre = ?"
    );

    $stmt->bind_param("s", $nombre);
    $stmt->execute();

    $resultado = $stmt->get_result()->fetch_assoc();

    if ($resultado) {
        return (int)$resultado['id'];
    }

    $stmt = $this->conexion->prepare(
        "INSERT INTO generos (nombre) VALUES (?)"
    );

    $stmt->bind_param("s", $nombre);

    if (!$stmt->execute()) {
        return false;
    }

    return $this->conexion->insert_id;
}
}