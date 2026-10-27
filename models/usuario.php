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
            "SELECT id, nombre, email, password, rol, activo FROM usuario WHERE email = ?"
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

// ---------- Métodos para el panel de administración ----------


    public function listarTodosAdmin() {
        $sql = "SELECT u.id, u.nombre, u.email, u.rol, u.activo, 
        COUNT(p.id) AS cantidad_pedidos
        FROM usuario u
        LEFT JOIN pedido p ON p.id_usuario = u.id
        GROUP BY u.id, u.nombre, u.email, u.rol, u.activo
        ORDER BY u.activo DESC, u.nombre ASC";
        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
}
// Un usuario por id (sin la contraseña). Devuelve el arreglo o null.
    public function obtenerPorId($id) {
        $stmt = $this->conexion->prepare(
        "SELECT id, nombre, email, rol, activo FROM usuario WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
}
// Modificación del rol. Devuelve true si se ejecutó bien.
    public function cambiarRol($id, $rol) {
        $stmt = $this->conexion->prepare("UPDATE usuario SET rol = ? WHERE id = ?");
        $stmt->bind_param("si", $rol, $id);
        return $stmt->execute();
}
// Baja lógica ($activo = 0) o reactivación ($activo = 1)
    public function cambiarActivo($id, $activo) {
        $stmt = $this->conexion->prepare("UPDATE usuario SET activo = ? WHERE id = ?");
        $stmt->bind_param("ii", $activo, $id);
        return $stmt->execute();
    }

// Cambiar contraseña
public function cambiarPasswd($id, $passwordNueva) {
    $hash = password_hash($passwordNueva, PASSWORD_DEFAULT);
    
    $sql = "UPDATE usuario SET password = ? WHERE id = ?";
    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param("si", $hash, $id);
    return $stmt->execute();

}

}