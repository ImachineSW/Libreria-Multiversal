<!--Mathias 20/10/2026-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="./public/img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="./public/css/sesion.css">
</head>
<body>
    <div class="container">
        <a href="./index.php?pagina=catalogo" class="back-btn">← Volver al catálogo</a>
        <?php if (($_GET['cuenta'] ?? '') === 'desactivada'): ?>
        <p class="error">
            Tu sesión se cerró porque la cuenta fue desactivada o ya no existe.
            Si creés que es un error, comunicate con el administrador.
        </p>
        <?php endif; ?>
        <?php if (isset($_SESSION["usuario_id"])): ?>
            <?php header('Location: ./index.php?pagina=catalogo')?>
        <?php else: ?>
            <h1>Iniciar sesión</h1>
            <p class="subtitle">Ingrese a su cuenta</p>

            <?php if ($mensaje !== ""): ?>
                <p><?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?></p>
            <?php endif; ?>
            <?php if ($_SERVER["REQUEST_METHOD"] !== "POST" && isset($_GET["registrado"])): ?>
                <p>Cuenta creada correctamente. Ya podés iniciar sesión.</p>
            <?php endif; ?>
            <?php if ($_SERVER["REQUEST_METHOD"] !== "POST" && isset($_GET["salio"])): ?>
                <p>Sesión cerrada correctamente.</p>
            <?php endif; ?>

            <form action="./index.php?pagina=login" method="POST">
                <div class="input-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email"
                           placeholder="correo@ejemplo.com" required>
                </div>

                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password"
                           placeholder="••••••••" required>
                </div>

                <div class="options">
                    <label>
                        <input type="checkbox" disabled>
                        Recordarme
                    </label>
                    <a href="#" onclick="return false;">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit">Ingresar</button>
            </form>

            <hr>
            <div class="social">
                <button type="button" disabled>Google</button>
                <button type="button" disabled>Facebook</button>
            </div>

            <div class="register">
                ¿No tenés una cuenta?
                <a href="./index.php?pagina=register">Registrate</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>