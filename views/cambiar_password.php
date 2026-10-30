<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/cambiarpasswd.css">
    <link rel="icon" type="image/png" href="./public/img/favicon.png">
    <title>Cambiar contraseña</title>
</head>
<body>
    <?php require __DIR__ . "/header.php"?>

    <div class="contenedor">
    <?php if (estaLogueado()): ?>
        <?php if ($mensaje !== ""): ?>
            <p><?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?></p>
        <?php endif; ?>
        <a href="./index.php?pagina=panel">Volver a mi cuenta</a>
        <h1>Cambiar contraseña</h1>
        <form action="./index.php?pagina=cambiar_password" method="POST">
            <label>Contraseña actual</label><br>
            <input type="password" name="passwordActual" required><br><br>

            <label>Nueva contraseña</label><br>
            <input type="password" name="passwordNueva" minlength="8" required><br><br>

            <label>Confirmar nueva contraseña</label><br>
            <input type="password" name="passwordConfirmar" minlength="8" required><br><br>

            <button type="submit">Guardar</button>
        </form>

    </div>
    <?php else: ?>
        <?php requerirLogin(); ?>
    <?php endif; ?>

    <?php require __DIR__ . "/footer.php"?>
</body>
</html>