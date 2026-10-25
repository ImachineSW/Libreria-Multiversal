<?php
// config/imagenes.php
// Funciones de ayuda para subir imágenes de libros.
const IMG_LIBROS_DIR = __DIR__ . '/../public/img/Libros/';
const IMG_MAX_BYTES = 2 * 1024 * 1024; // 2 MB
/**
* Valida y guarda una imagen subida desde un formulario.
* Recibe $_FILES['imagen'] y devuelve siempre un arreglo:
* ['ok' => true, 'nombre' => 'a1b2c3d4.jpg'] se guardó
* ['ok' => true, 'nombre' => null] no eligieron archivo (es opcional)
* ['ok' => false, 'error' => 'mensaje'] hubo un problema
*/
function guardarImagenLibro($archivo) {
// 0) ¿Eligieron algún archivo?
if (!isset($archivo) || $archivo['error'] === UPLOAD_ERR_NO_FILE) {
    return ['ok' => true, 'nombre' => null];
}
// 1) ¿Llegó bien al servidor?
if ($archivo['error'] === UPLOAD_ERR_INI_SIZE || $archivo['error'] === UPLOAD_ERR_FORM_SIZE) {
    return ['ok' => false, 'error' => 'La imagen supera el tamaño máximo permitido (2 MB).'];
}
if ($archivo['error'] !== UPLOAD_ERR_OK) {
    return ['ok' => false, 'error' => 'No se pudo subir la imagen. Intentá de nuevo.'];
}
// 2) Tamaño (volvemos a controlar, no alcanza con lo que dice el navegador)
if ($archivo['size'] > IMG_MAX_BYTES) {
    return ['ok' => false, 'error' => 'La imagen supera el tamaño máximo permitido (2 MB).'];
}
$info = @getimagesize($archivo['tmp_name']);
$permitidos = [
    IMAGETYPE_JPEG => 'jpg',
    IMAGETYPE_PNG => 'png',
    IMAGETYPE_WEBP => 'webp'
];
if ($info === false || !isset($permitidos[$info[2]])) {
    return ['ok' => false, 'error' => 'El archivo debe ser una imagen JPG, PNG o WEBP.'];
}
// 4) Nombre nuevo y aleatorio; la extensión sale del tipo real
$nombre = bin2hex(random_bytes(8)) . '.' . $permitidos[$info[2]];
// 5) Mover desde la carpeta temporal a la definitiva
if (!move_uploaded_file($archivo['tmp_name'], IMG_LIBROS_DIR . $nombre)) {
    return ['ok' => false, 'error' => 'No se pudo guardar la imagen en el servidor.'];
}
return ['ok' => true, 'nombre' => $nombre];
}
/**
* Borra una imagen de la carpeta de libros (si existe).
*/
function borrarImagenLibro($nombre) {
if (!$nombre) {
return;
}
// basename() se queda solo con el nombre y elimina cualquier ruta
// (por ejemplo ../../config/conexion.php)
$ruta = IMG_LIBROS_DIR . basename($nombre);
if (is_file($ruta)) {
unlink($ruta);
}
}