<?php
class Usuario
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Se utiliza al registrar y al iniciar sesion.
    public function buscarPorEmail($email)
    {
        $consulta = mysqli_prepare(
            $this->conexion,
            "SELECT id, nombre, email, password, rol FROM usuario WHERE email = ?"
        );
        mysqli_stmt_bind_param($consulta, "s", $email);
        mysqli_stmt_execute($consulta);

        $resultado = mysqli_stmt_get_result($consulta);
        $usuario = mysqli_fetch_assoc($resultado);
        mysqli_stmt_close($consulta);

        return $usuario ?: null;
    }

    public function existeEmail($email)
    {
        return $this->buscarPorEmail($email) !== null;
    }

    public function registrar($nombre, $email, $password)
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $rol = "cliente";

        $consulta = mysqli_prepare(
            $this->conexion,
            "INSERT INTO usuario (nombre, email, password, rol) VALUES (?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param($consulta, "ssss", $nombre, $email, $passwordHash, $rol);
        $guardado = mysqli_stmt_execute($consulta);
        mysqli_stmt_close($consulta);

        return $guardado;
    }
}
