<?php

class LibroModel {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function listarLibros($busqueda = "", $genero = "") { //devuelve todos los libros cargados en el base de datos
        $sql = "SELECT * FROM libros WHERE 1=1";
        $tipos = '';
        $params = [];

        if ($busqueda !== "") {
            $sql .= " AND (nombre LIKE ? OR autor LIKE ?)";
            $tipos .= 'ss';
            $patron = '%' . $this->escaparLike($busqueda) . '%';
            $params[] = $patron;
            $params[] = $patron;
        }

        if ($genero !== "") {
            $sql .= " AND genero = ?";
            $tipos .= 's';
            $params[] = $genero;
        }

        $sql .= " ORDER BY nombre";

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
        $sql = "SELECT id, nombre, precio, autor, formato, imagen,
                    genero, editorial, año, idioma, sinopsis
                FROM libros
                WHERE id = ?"; //busca el libro por id con parametros por seguridad

        $stmt = mysqli_prepare($this->conexion, $sql); //se prepara la consulta

        if (!$stmt) { //si no se puede preparar la consulta muestra error
            return false;
        }

        mysqli_stmt_bind_param($stmt, "i", $id); //asigna el id a la consulta
        mysqli_stmt_execute($stmt); //ejecuta la consulta

        return mysqli_stmt_get_result($stmt); //devuelve el resultado
    }
}