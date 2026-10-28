<!DOCTYPE html>
<html>
    <head>
        <title>Admin</title>
        <link rel="stylesheet" href="./public/css/panel.css">
        <link rel="icon" type="image/png" href="./public/img/favicon.png">
    </head>
    <body>
        <?php require __DIR__ . "/header.php";?>
        <?php if(esAdmin() || esEmpleado()): ?>
        <div class="contenedor">
            <article>
                <h2>Modificar catalogo</h2>
                <a href="./index.php?pagina=admin_libros">Modificar catalogo</a>
            </article>
            <?php if (esAdmin()): ?>
            <article>
                <h2>Ver informes mensuales</h2>
                <a href="./index.php?pagina=admin_informes">Ver informes mensuales</a>
            </article>
            <article>
                <h2>Modificar usuario</h2>
                <a href="./index.php?pagina=admin_usuarios">Modificar usuario</a>
            </article>
            <?php endif; ?>
            <article>
                <h2>Ver todos los pedidos</h2>
                <a href="./index.php?pagina=admin_pedidos">Ver pedidos</a>
            </article>
        </div>
           <section class="contenedor">
            <h2>Alertas de stock</h2>

            <?php if (empty($libroStockBajo)): ?>
                <p>Todo en orden: ningún producto está por debajo de su stock mínimo.</p>
            <?php else: ?>
                <p class="aviso-stock">
                    ! <?= count($libroStockBajo) ?> producto(s) con stock bajo o
                    agotado.
                </p>

                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Stock</th>
                            <th>Mínimo</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($libroStockBajo as $l): ?>
                            <tr>
                                <td><?= escapar($l['nombre']) ?></td>
                                <td><?= (int)$l['stock'] ?></td>
                                <td><?= (int)$l['stock_minimo'] ?></td>
                                <td>
                                    <span class="stock stock-<?= escapar($l['nivel_stock']) ?>">
                                        <?= ($l['nivel_stock'] === 'agotado') ? 'Sin stock' : 'Stock bajo' ?>
                                    </span>
                                </td>

                                <td>
                                    <a href="index.php?pagina=admin_libro_editar&id=<?= (int)$l['id'] ?>">Reponer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section> 
        <section class="contenedor">
            <h2>Pedidos por enviar</h2>

            <?php if (empty($pedidosPorEnviar)): ?>
                <p>No hay pedidos pagados pendientes de envío.</p>
            <?php else: ?>
                <p class="aviso-pedidos">
                    ! <?= count($pedidosPorEnviar) ?> pedido(s) pagado(s) esperando ser enviado(s).
                    <a href="index.php?pagina=admin_pedidos&estado=pagado&estado_envio=preparando">Verlos</a>
                </p>
            <?php endif; ?>
        </section>

        <section class="contenedor">
            <h2>Ultimos pedidos</h2>

            <?php if (empty($ultimosPedidos)): ?>
                <p>Todavía no hay pedidos.</p>
            <?php else: ?>
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>N.º</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Pago</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($ultimosPedidos as $p): ?>
                            <tr>
                                <td><?= (int)$p['id'] ?></td>
                                <td><?= escapar($p['cliente']) ?></td>
                                <td><?= escapar(date('d/m/Y H:i', strtotime($p['fecha']))) ?></td>
                                <td>$ <?= escapar(number_format($p['total'], 2, ',', '.')) ?></td>
                                <td>
                                    <span class="estado estado-<?= escapar($p['estado']) ?>">
                                        <?= escapar(ucfirst($p['estado'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="index.php?pagina=admin_pedido_detalle&id=<?= (int)$p['id'] ?>">Ver</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <p><a href="index.php?pagina=admin_pedidos">Ver todos los pedidos →</a></p>
        </section>
        
        <?php else: ?>
            <?php esSuperUser(); ?>
        <?php endif; ?>

        <?php require __DIR__ . "/footer.php";?>
    </body>
</html>