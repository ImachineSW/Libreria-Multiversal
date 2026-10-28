<?php
// config/auth.php
// Funciones de ayuda.
/**
* Convierte caracteres especiales en texto inofensivo.
* Usar SIEMPRE al mostrar en pantalla datos que escribió un
* usuario.
* Ej: <script> se convierte en &lt;script&gt; y no se ejecuta.
*/
function escapar($texto) {
return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}
/**
* Redirige a otra página del sitio y corta la ejecución.
*/
function redirigir($pagina, $extra = '') {
header("Location: index.php?pagina=" . $pagina . $extra);
exit;
}

/** ¿Hay un usuario con sesión iniciada? */
function estaLogueado() {
return isset($_SESSION['usuario_id']);
}

/** Id del usuario logueado (o null si no hay). */
function usuarioActualId() {
return $_SESSION['usuario_id'] ?? null;
}

/** Nombre del usuario logueado (o null). */
function usuarioActualNombre() {
return $_SESSION['nombre'] ?? null;
}

/** Email del usuario logueado (o null). */
function usuarioActualEmail() {
return $_SESSION['email'] ?? null;
}

/** Rol del usuario logueado (o null). */
function usuarioActualRol() {
return $_SESSION['rol'] ?? null;
}
/** ¿El usuario logueado es administrador? */
function esAdmin() {
return usuarioActualRol() === 'admin';
}

/** ¿El usuario logueado es empleado? */
function esEmpleado() {
    return usuarioActualRol() === 'empleado';
}

function esSuperUser() {
    requerirLogin();
    if (usuarioActualRol() !== 'admin' && usuarioActualRol() !== 'empleado') {
        http_response_code(403);
        require __DIR__ . '/../views/sin_permiso.php';
        exit;
    }
}
/** Si no está logueado, manda al login. */
function requerirLogin() {
if (!estaLogueado()) {
redirigir('login');
}
}
/** Exige estar logueado y un rol determinado. */
function requerirRol($rol) {
requerirLogin();
if (usuarioActualRol() !== $rol) {
http_response_code(403); // 403 = Prohibido
require __DIR__ . '/../views/sin_permiso.php';
exit;
}
}


function sincronizarSesion($usuario) {
    if (!estaLogueado()) {
        return;
    }

    $usuario = $usuario->obtenerPorId(usuarioActualId());

    if (!$usuario || (int) $usuario['activo'] !== 1) {
        AuthController::cerrarSesion();
        redirigir('login', '&cuenta=desactivada');
    }

    $_SESSION['rol'] = $usuario['rol'];
    $_SESSION['nombre'] = $usuario['nombre'];
}