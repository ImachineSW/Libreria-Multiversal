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
                WHERE 1=1";
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


    public function obtenerPorId($id) { //obtiene el libro por id para mostrar el detalle de cada uno
        $sql = "SELECT l.id, l.nombre, l.precio, l.autor, l.paginas, l.imagen, l.editorial, l.fecha, l.idioma, l.sinopsis,
                    GROUP_CONCAT(DISTINCT g.nombre ORDER BY g.nombre SEPARATOR ', ') 
                AS genero
                FROM libros l
                LEFT JOIN libros_generos lg ON l.id = lg.libro_id
                LEFT JOIN generos g ON lg.genero_id = g.id
                WHERE l.id = ?
                GROUP BY l.id, l.nombre, l.precio, l.autor, l.paginas, l.imagen, l.editorial, l.fecha, l.idioma, l.sinopsis";
                //busca el libro por id con parametros por seguridad

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

}

