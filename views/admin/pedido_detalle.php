<!DOCTYPE html>
<html>

    <head>
        <title>Pedido</title> <!--Titulo de pagina-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./public/css/pedidos_admin.css">
        <link rel="icon" type="image/png" href="./public/img/favicon.png"> <!--Favicon-->
        <meta charset="UTF-8">
    </head>

    <body>
        <header>
        <!--Header-->
        <?php require __DIR__ . "/../header.php"; ?>
        </header>

        <main>
            <div class="contenedor">
                <h1>Pedido N.º <?= (int) $pedido['id'] ?></h1>
                <p><a href="index.php?pagina=admin_pedidos">← Volver a pedidos</a></p>

                <?php if ($aviso !== null): ?>
                    <p class="<?= escapar($aviso['tipo']) ?>"><?= escapar($aviso['texto']) ?></p>
                <?php endif; ?>

                <h2>Datos del pedido</h2>
                <p>Fecha: <?= escapar(date('d/m/Y H:i', strtotime($pedido['fecha']))) ?></p>
                <p>Cliente: <?= escapar($pedido['cliente_nombre']) ?> (<?= escapar($pedido['cliente_email']) ?>)</p>
                <p>Pago:
                    <span class="estado estado-<?= escapar($pedido['estado']) ?>">
                        <?= escapar(ucfirst($pedido['estado'])) ?>
                    </span>
                    <?php if ($pago): ?>
                        — tarjeta terminada en <strong><?= escapar($pago['ultimos4']) ?></strong>,
                        el <?= escapar(date('d/m/Y H:i', strtotime($pago['fecha']))) ?>
                    <?php endif; ?>
                </p>
                <?php if ($pedido['estado'] === 'pagado'): ?>
                    <p><a href="index.php?pagina=admin_pedido_comprobante&id=<?= (int) $pedido['id'] ?>" class="btn-pdf">Descargar comprobante (PDF)</a></p>
                <?php endif; ?>

                <h2>Libros</h2>
                <table class="tabla">
                    <thead>
                        <tr><th>Libro</th><th>Cant.</th><th>Precio</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalle as $linea): ?>
                            <tr>
                                <td><?= escapar($linea['nombre']) ?></td>
                                <td><?= (int) $linea['cantidad'] ?></td>
                                <td>$ <?= escapar(number_format($linea['precio_unitario'], 2, ',', '.')) ?></td>
                                <td>$ <?= escapar(number_format($linea['subtotal'], 2, ',', '.')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">Total</th>
                            <th>$ <?= escapar(number_format($pedido['total'], 2, ',', '.')) ?></th>
                        </tr>
                    </tfoot>
                </table>

                <h2>Datos de envío</h2>
                <p>
                    <?= escapar($pedido['envio_nombre']) ?><br>
                    <?= escapar($pedido['envio_direccion']) ?><br>
                    <?= escapar($pedido['envio_ciudad']) ?>, <?= escapar($pedido['envio_departamento']) ?><br>
                    Tel.: <?= escapar($pedido['envio_telefono']) ?>
                </p>

                <h2>Estado de envío</h2>

                <?php if ($pedido['estado'] !== 'pagado'): ?>
                    <p>El pedido todavía no está pagado, por eso no se puede enviar.</p>
                <?php else: ?>
                    <p>
                        Estado actual:
                        <span class="estado estado-<?= escapar($pedido['estado_envio']) ?>">
                            <?= escapar(ucfirst($pedido['estado_envio'])) ?>
                        </span>
                    </p>

                    <?php if ($siguiente !== null): ?>
                        <form action="index.php?pagina=admin_pedido_envio&id=<?= (int) $pedido['id'] ?>"
                              method="POST" class="en-linea">
                            <input type="hidden" name="nuevo" value="<?= escapar($siguiente) ?>">
                            <button type="submit"
                                    onclick="return confirm('¿Cambiar el envío a <?= escapar($siguiente) ?>?');">
                                Marcar como <?= escapar($siguiente) ?>
                            </button>
                        </form>
                    <?php else: ?>
                        <p>El pedido ya fue entregado. No hay más pasos.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </main>

        <?php require __DIR__ . "/../footer.php"; ?>
    </body>

</html>
