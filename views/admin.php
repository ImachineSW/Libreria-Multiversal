<!DOCTYPE html>
<html>
    <head>
        <title>Admin</title>
        <link rel="stylesheet" href="./public/css/panel.css">
        <link rel="icon" type="image/png" href="./public/img/favicon.png">
    </head>
    <body>
        <?php require __DIR__ . "/header.php";?>
        <?php if(esAdmin()): ?>
        <div class="contenedor">
            <article>
                <h2>Modificar catalogo</h2>
                <p>Agregar, eliminar o modificar libros</p>
                <a href="./index.php?pagina=admin_libros">Modificar catalogo</a>
            </article>
            <article>
                <h2>Ver informes mensuales</h2>
                <a href="./index.php?pagina=admin_informes">Ver informes mensuales</a>
            </article>
            <article>
                <h2>Modificar usuario</h2>
                <p>Gestionar roles</p>
                <a href="./index.php?pagina=admin_usuarios">Modificar usuario</a>
            </article>
            <article>
                <h2>Ver todos los pedidos</h2>
                <p>Revisar el estado de los pedidos</p>
                <a href="./index.php?pagina=admin_pedidos">Ver pedidos</a>
            </article>
        </div>

        <div class="pedidos">
            <h2>Ultimos pedidos</h2>
            <p>aqui va el listado de los ultimos pedidos</p>
        </div>
        
        <?php else: ?>
            <?php requerirRol('admin'); ?>
        <?php endif; ?>

        <?php require __DIR__ . "/footer.php";?>
    </body>
</html>