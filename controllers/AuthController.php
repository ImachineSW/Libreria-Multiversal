<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/conexion.php";
require_once __DIR__ . "/../models/usuario.php";

class AuthController
{
    private $usuario;

    public function __construct($conexion)
    {
        $this->usuario = new Usuario($conexion);
    }

    // Se mantiene compatible con el register.php que hicimos antes.
    public function registrar($datos)
    {
        $nombre = trim($datos["nombre"] ?? "");
        $email = strtolower(trim($datos["email"] ?? ""));
        $password = $datos["password"] ?? "";
        $confirmPassword = $datos["confirmPassword"] ?? "";

        if ($nombre === "" || $email === "" || $password === "" || $confirmPassword === "") {
            return "Todos los campos son obligatorios.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "El correo electrónico no es válido.";
        }
        if (strlen($password) < 8) {
            return "La contraseña debe tener al menos 8 caracteres.";
        }
        if ($password !== $confirmPassword) {
            return "Las contraseñas no coinciden.";
        }

        try {
            if ($this->usuario->existeEmail($email)) {
                return "Este correo ya está registrado.";
            }
            if ($this->usuario->registrar($nombre, $email, $password)) {
                return "Usuario registrado correctamente.";
            }
        } catch (mysqli_sql_exception $error) {
            return "No se pudo registrar el usuario.";
        }

        return "No se pudo registrar el usuario.";
    }

    // Devuelve un mensaje de error, o texto vacio si el login es correcto.
    public function iniciarSesion($datos)
    {
        $email = strtolower(trim($datos["email"] ?? ""));
        $password = $datos["password"] ?? "";

        if ($email === "" || $password === "") {
            return "Complete el correo y la contraseña.";
        }

        try {
            $datosUsuario = $this->usuario->buscarPorEmail($email);
        } catch (mysqli_sql_exception $error) {
            return "No se pudo iniciar sesión. Intentá nuevamente.";
        }

        if (!$datosUsuario || !password_verify($password, $datosUsuario["password"])) {
            return "Correo o contraseña incorrectos.";
        }

        session_regenerate_id(true);
        $_SESSION["usuario_id"] = $datosUsuario["id"];
        $_SESSION["nombre"] = $datosUsuario["nombre"];
        $_SESSION["rol"] = $datosUsuario["rol"];

        return "";
    }

    public static function cerrarSesion()
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $opciones = session_get_cookie_params();
            setcookie(session_name(), "", [
                "expires" => time() - 3600,
                "path" => $opciones["path"],
                "domain" => $opciones["domain"],
                "secure" => $opciones["secure"],
                "httponly" => $opciones["httponly"],
                "samesite" => $opciones["samesite"] ?? "Lax"
            ]);
        }
        session_destroy();
    }

    // Llamar al comienzo de las paginas que sean solo para admin.
    public static function exigirRol($rol)
    {
        if (!isset($_SESSION["usuario_id"])) {
            http_response_code(401);
            exit("Tenés que iniciar sesión.");
        }
        if (($_SESSION["rol"] ?? "") !== $rol) {
            http_response_code(403);
            exit("Acceso denegado.");
        }
    }
}