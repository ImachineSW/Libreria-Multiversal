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