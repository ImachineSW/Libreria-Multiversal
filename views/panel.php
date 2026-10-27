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
    <?php if (estaLogueado()): ?>
        <?php if (esAdmin()): ?>
            <a href="./index.php?pagina=admin">Panel de administracion</a>
        <?php endif; ?>
            <h1>Mi cuenta</h1>
            <table>
                <tr>
                    <td>Nombre: <?php echo escapar(usuarioActualNombre());?></td>
                </tr>
                <tr>
                <td>Rol: <?php echo escapar(usuarioActualRol());?></td>
                </tr>
                <tr>
                    <td>Correo: <?php echo escapar(usuarioActualEmail());?></td>
                </tr>
                <tr>
                    <td>Contraseña: ***********<a href="./index.php?pagina=cambiar_password"><button>Cambiar contraseña</button></a>
                    </td>
                </tr>
                <tr>
                    <td>Historial de pedidos:
                        <form action="./index.php" method="GET">
                            <input type="hidden" name="pagina" value="mis_pedidos">
                            <button type="submit">Mis pedidos</button>
                        </form>
                    </td>
                </tr>
            </table>

            <form action="./index.php?pagina=login" method="POST" id="cerrarSesion">
                <input type="hidden" name="accion" value="logout">
                <button type="submit">Cerrar sesión</button>
            </form>
            </div>
        <?php else: ?>
            <?php requerirLogin(); ?>
    <?php endif; ?>

    <?php require __DIR__ . "/footer.php"?>
</body>
</html>