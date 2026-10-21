<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/panel.css">
    <link rel="icon" type="image/png" href="./public/img/favicon.png">
    <title>Panel</title>
</head>
<body>
    <?php require __DIR__ . "/header.php"?>
    
    <div class="contenedor">
    <?php if (isset($_SESSION["usuario_id"])): ?>
        <?php if (isset($_SESSION["rol"]) && $_SESSION["rol"] == "admin"): ?>
        <select name="opciones" id="opciones">
            <option value="">Mi Cuenta</option>
            <option value="">Modificar catalogo</option>
            <option value="">Ver informes mensuales</option>
            <option value="">Modificar usuario</option>
        </select>
        <?php endif; ?>
            <h1>Mi cuenta</h1>
            <table>
                <tr>
                    <td>Nombre: <?= htmlspecialchars($_SESSION["nombre"], ENT_QUOTES, "UTF-8") ?></td>
                </tr>
                <tr>
                <td>Rol: <?= htmlspecialchars($_SESSION["rol"], ENT_QUOTES, "UTF-8") ?></td>
                </tr>
                <tr>
                    <td>Contraseña: ***********<button>Cambiar contraseña</button> </td>
                </tr>
                <tr>
                    <td>Correo: *********@gmail.com<button>Cambiar correo</button> </td>
                </tr>
                <?php if(isset($_SESSION["rol"]) && $_SESSION["rol"] == "cliente"): ?>
                <tr>
                    <td>Historial de pedidos: <button>Ver historial de pedidos</button> </td>
                </tr>
                <?php endif; ?>
            </table>

            <form action="./index.php?pagina=login" method="POST">
                <input type="hidden" name="accion" value="logout">
                <button type="submit">Cerrar sesión</button>
            </form>
            </div>
        <?php else: ?>
            <?php header('Location: ./index.php?pagina=login')?>
    <?php endif; ?>

    <?php require __DIR__ . "/footer.php"?>
</body>
</html>