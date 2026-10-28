<!--mathias 20/10/2026-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="./public/img/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
    <link rel="stylesheet" href="./public/css/sesion.css">
</head>

<body>
    <div class="container">

        <h1>Crear cuenta</h1>
        <p class="subtitle">Complete sus datos para registrarse</p>

        <?php if ($mensaje !== ""): ?>
            <p>
                <?= htmlspecialchars($mensaje, ENT_QUOTES, "UTF-8") ?>
            </p>
        <?php endif; ?>

        <form action="./index.php?pagina=register" method="POST">

            <div class="input-group">
                <label for="usuario">Nombre usuario</label>
                <input
                    type="text"
                    id="usuario"
                    name="usuario"
                    placeholder="Juan123"
                    required>
            </div>

            <div class="input-group">
                <label for="email">Correo electrónico</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="correo@ejemplo.com"
                    required>
            </div>

            <div class="input-group">
                <label for="password">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    minlength="8"
                    required>
                    <button type="button" onclick="mostrarPassword('password')">👁</button>
            </div>

            <div class="input-group">
                <label for="confirmPassword">Repetir contraseña</label>
                <input
                    type="password"
                    id="confirmPassword"
                    name="confirmPassword"
                    placeholder="••••••••"
                    required>
                    <button type="button" onclick="mostrarPassword('confirmPassword')">👁</button>
            </div>

            <button type="submit">Crear cuenta</button>
        </form>

        <hr>

        <div class="register">
            ¿Ya tenés una cuenta?
            <a href="./index.php?pagina=login">Iniciar sesión</a>
        </div>

    </div>
    <script>
    function mostrarPassword(id) {
        const input = document.getElementById(id);

        if (input.type === "password") {
            input.type = "text";
        } else {
            input.type = "password";
        }
    }
    </script>
</body>
</html>
