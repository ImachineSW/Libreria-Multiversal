<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="./public/css/admin_form.css">
    <link rel="icon" type="image/png" href="./public/img/favicon.png">
</head>

<body>

<?php require __DIR__ . '/../header.php'; ?>

<div class="contenedor">
    <h1><?= escapar($titulo) ?></h1>

    <p>
        <a href="index.php?pagina=admin_libros">← Volver a la lista</a>
    </p>

    <?php if (!empty($errores)): ?>
        <ul class="errores">
            <?php foreach ($errores as $error): ?>
                <li><?= escapar($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="<?= escapar($urlAccion) ?>" method="POST"
        enctype="multipart/form-data" class="formulario">

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" maxlength="255"
            value="<?= escapar($libro['nombre'] ?? '') ?>" required>

        <label for="autor">Autor</label>
        <input type="text" id="autor" name="autor" maxlength="150"
            value="<?= escapar($libro['autor'] ?? '') ?>" required>

        <label for="paginas">Páginas</label>
        <input type="number" id="paginas" name="paginas" min="1" step="1"
            value="<?= escapar($libro['paginas'] ?? '') ?>" required>

        <label for="editorial">Editorial</label>
        <input type="text" id="editorial" name="editorial" maxlength="150"
            value="<?= escapar($libro['editorial'] ?? '') ?>" required>

        <label for="fecha">Fecha (DD-MM-AAAA)</label>
        <input type="text" id="fecha" name="fecha" maxlength="50"
            value="<?= escapar($libro['fecha'] ?? '') ?>" required>

        <label for="idioma">Idioma</label>
        <input type="text" id="idioma" name="idioma" maxlength="50"
            value="<?= escapar($libro['idioma'] ?? '') ?>" required>

        <label for="sinopsis">Sinopsis</label>
        <textarea id="sinopsis" name="sinopsis" rows="4" maxlength="500" required><?= escapar($libro['sinopsis'] ?? '') ?></textarea>

<label for="id_generos">Géneros</label>
        <small>Ctrl + Click para seleccionar más de un genero</small>
<select id="id_generos" name="id_generos[]" multiple>
    <?php
    $generosSeleccionados = array_map(
        'strval',
        $libro['id_generos'] ?? []
    );
    ?>

    <?php foreach ($generos as $g): ?>
            <option value="<?= (int)$g['id'] ?>"
                <?= in_array((string)$g['id'], $generosSeleccionados, true) ? 'selected' : '' ?>>
                <?= escapar($g['nombre']) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <label for="nuevo_genero">Agregar nuevo género</label>
<input type="text" id="nuevo_genero" name="nuevo_genero"
    maxlength="100" placeholder="Ej.: Fantasía">

        <label for="precio">Precio ($)</label>
        <input type="number" id="precio" name="precio" min="0.01" step="0.01"
            value="<?= escapar($libro['precio'] ?? '') ?>" required>

        <label for="stock">Stock (unidades disponibles)</label>
        <input type="number" id="stock" name="stock" min="0" step="1"
            value="<?= escapar($libro['stock'] ?? '0') ?>" required>

        <label for="stock_minimo">Stock mínimo (para la alerta)</label>
        <input type="number" id="stock_minimo" name="stock_minimo" min="0" step="1"
            value="<?= escapar($libro['stock_minimo'] ?? '5') ?>" required>

        <?php if (!empty($libro['imagen'])): ?>
            <p>
                Imagen actual:<br>
                <img src="public/img/libros/<?= escapar($libro['imagen']) ?>"
                    alt="Imagen actual del libro" class="miniatura-grande">
            </p>
        <?php endif; ?>

        <label for="imagen">
            Imagen (JPG, PNG o WEBP, máximo 2 MB)
            <?= !empty($libro['imagen']) ? '— dejá vacío para conservar la actual' : '' ?>
        </label>
        <div class="botones">
            <input type="file" id="imagen" name="imagen"
                accept="image/jpeg,image/png,image/webp">
            <input type="submit" value="Guardar">
        </div>
    </form>
</div>

<?php require __DIR__ . '/../footer.php'; ?>

</body>
</html>