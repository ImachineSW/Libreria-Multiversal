<!DOCTYPE html>
<html>

    <head>
        <title>Detalle del pedido</title> <!--Titulo de pagina-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./public/css/carrito.css">
        <link rel="icon" type="image/png" href="./public/img/favicon.png"> <!--Favicon-->
        <meta charset="UTF-8">
    </head>

    <body>
     
        <main>
            <div class="carrito pedido-detalle">
                <a href="./index.php?pagina=mis_pedidos" class="back-btn">← Volver a mis pedidos</a>
                <h1>Pedido N.º <?= (int) $pedido['id'] ?></h1>

                <p><strong>Fecha:</strong> <?= escapar(date('d/m/Y H:i', strtotime($pedido['fecha']))) ?></p>
                <p><strong>Pago:</strong>
                    <span class="estado estado-<?= escapar($pedido['estado']) ?>">
                        <?= escapar(ucfirst($pedido['estado'])) ?>
                    </span>
                </p>
                <?php if ($pedido['estado'] === 'pagado'): ?>
                    <p><strong>Envío:</strong>
                        <span class="estado estado-<?= escapar($pedido['estado_envio']) ?>">
                            <?= escapar(ucfirst($pedido['estado_envio'])) ?>
                        </span>
                    </p>
                <?php endif; ?>

                <h2>Libros</h2>
                <table class="detalle-pedido">
                    <thead>
                        <tr>
                            <th>Libro</th>
                            <th class="num">Cantidad</th>
                            <th class="num">Precio</th>
                            <th class="num">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalle as $linea): ?>
                            <tr>
                                <td><?= escapar($linea['nombre']) ?></td>
                                <td class="num"><?= (int) $linea['cantidad'] ?></td>
                                <td class="num">$ <?= escapar(number_format($linea['precio_unitario'], 2, ',', '.')) ?></td>
                                <td class="num">$ <?= escapar(number_format($linea['subtotal'], 2, ',', '.')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p class="fuerte total-pedido">Total: $ <?= escapar(number_format($pedido['total'], 2, ',', '.')) ?></p>

                <h2>Envío</h2>
                <p>
                    <?= escapar($pedido['envio_nombre']) ?><br>
                    <?= escapar($pedido['envio_direccion']) ?><br>
                    <?= escapar($pedido['envio_ciudad']) ?>, <?= escapar($pedido['envio_departamento']) ?><br>
                    Tel.: <?= escapar($pedido['envio_telefono']) ?>
                </p>

                <?php if ($pedido['estado'] !== 'pagado'): ?>
                    <p><a href="./index.php?pagina=pago&id=<?= (int) $pedido['id'] ?>">Pagar este pedido</a></p>
                <?php endif; ?>
            </div>
        </main>

        
    </body>

</html>
