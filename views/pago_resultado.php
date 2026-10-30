<!DOCTYPE html>
<html>

    <head>
        <title>Resultado del pago</title> <!--Titulo de pagina-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./public/css/carrito.css"> <!--Se reutiliza el estilo del carrito-->
        <link rel="icon" type="image/png" href="./public/img/favicon.png"> <!--Favicon-->
        <meta charset="UTF-8">
    </head>

    <body>
        <header>
        <!--Header-->
        <?php require __DIR__ . "/header.php"; ?>
        </header>

        <main>
            <div class="carrito pedido-detalle">
                <?php if ($pedido['estado'] === 'pagado'): ?>
                    <h1>¡Pago aprobado!</h1>
                    <p>Gracias por tu compra. Tu pedido N.º <strong><?= escapar($pedido['id']) ?></strong> fue pagado correctamente.</p>
                    <p>Estado: <span class="estado estado-pagado"><?= escapar(ucfirst($pedido['estado'])) ?></span></p>
                    <p>Total abonado: <strong>$ <?= escapar(number_format($pedido['total'], 2, ',', '.')) ?></strong></p>
                    <p><a href="./index.php?pagina=catalogo">Seguir comprando</a> | <a href="./index.php?pagina=mis_pedidos">Ver mis pedidos</a></p>
                <?php else: ?>
                    <h1>Pago rechazado</h1>
                    <p>No pudimos procesar el pago del pedido N.º <strong><?= escapar($pedido['id']) ?></strong>.</p>
                    <p>Estado: <span class="estado estado-rechazado"><?= escapar(ucfirst($pedido['estado'])) ?></span></p>
                    <p>
                        <a href="./index.php?pagina=pago&id=<?= (int) $pedido['id'] ?>">Intentar de nuevo</a>
                        |
                        <a href="./index.php?pagina=catalogo">Volver al catálogo</a>
                        |
                        <a href="./index.php?pagina=mis_pedidos">Ver mis pedidos</a>
                    </p>
                <?php endif; ?>
            </div>
        </main>

        <?php require __DIR__ . "/footer.php"; ?>
    </body>

</html>
