<?php

class LibroModel {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerTodos() { //devuelve todos los libros cargados en el base de datos
    $sql = "SELECT * FROM libros";
    $resultado = mysqli_query($this->conexion, $sql);

    if ($resultado === false) {
        die("Error SQL: " . mysqli_error($this->conexion));
    }

    return $resultado;
}

public function obtenerPorId($id) //obtiene el libro por id para mostrar el detalle de cada uno
{
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
