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
function redirigir($pagina) {
header("Location: index.php?pagina=" . $pagina);
exit;
}

/** ¿Hay un usuario con sesión iniciada? */
function estaLogueado() {
return isset($_SESSION['usuario_id']);
}

/** Nombre del usuario logueado (o null). */
function usuarioActualNombre() {
return $_SESSION['nombre'] ?? null;
}

/** Rol del usuario logueado (o null). */
function usuarioActualRol() {
return $_SESSION['rol'] ?? null;
}
/** ¿El usuario logueado es administrador? */
function esAdmin() {
return usuarioActualRol() === 'admin';
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