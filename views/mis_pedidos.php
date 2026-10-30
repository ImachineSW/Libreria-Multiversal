<!DOCTYPE html>
<html>

    <head>
        <title>Mis pedidos</title> <!--Titulo de pagina-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./public/css/carrito.css">
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
                <a href="./index.php?pagina=panel" class="back-btn">← Volver a mi cuenta</a>
                <h1>Mis pedidos</h1>

                <?php if (empty($pedidos)): ?>
                    <p>Todavía no hiciste ningún pedido.</p>
                    <p><a href="./index.php?pagina=catalogo">Ir al catálogo</a></p>
                <?php else: ?>
                    <table class="detalle-pedido">
                        <thead>
                            <tr>
                                <th>N.º</th>
                                <th>Fecha</th>
                                <th class="num">Total</th>
                                <th>Pago</th>
                                <th>Estado del pedido</th>
                                <th>Envío</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidos as $p): ?>
                                <tr>
                                    <td><?= (int) $p['id'] ?></td>
                                    <td><?= escapar(date('d/m/Y H:i', strtotime($p['fecha']))) ?></td>
                                    <td class="num">$ <?= escapar(number_format($p['total'], 2, ',', '.')) ?></td>
                                    <td>
                                        <span class="estado estado-<?= escapar($p['estado']) ?>">
                                            <?= escapar(ucfirst($p['estado'])) ?>
                                        </span>
                                    </td>
                                    <td><?= escapar(ucfirst($p['estado_pedido'])) ?></td>
                                    <td>
                                        <?php if ($p['estado'] === 'pagado'): ?>
                                            <span class="estado estado-<?= escapar($p['estado_envio']) ?>">
                                                <?= escapar(ucfirst($p['estado_envio'])) ?>
                                            </span>
                                        <?php else: ?>
                                            —
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="./index.php?pagina=pedido_detalle&id=<?= (int) $p['id'] ?>">Ver detalle</a>
                                        <?php if (($p['estado_pedido'] ?? 'activo') !== 'cancelado' && $p['estado'] !== 'pagado'): ?>
                                            | <a href="./index.php?pagina=pago&id=<?= (int) $p['id'] ?>">Pagar</a>
                                            | <form action="./index.php?pagina=pedido_cancelar&id=<?= (int) $p['id'] ?>" method="POST" style="display:inline" onsubmit="return confirm('¿Cancelar este pedido? Se devolverá el stock.');">
                                                <button type="submit" class="cancelar">Cancelar</button>
                                              </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </main>

        <?php require __DIR__ . "/footer.php"; ?>
    </body>

</html>
