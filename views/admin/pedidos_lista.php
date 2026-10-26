<!DOCTYPE html>
<html>

    <head>
        <title>Pedidos</title> <!--Titulo de pagina-->
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
                <h1>Pedidos</h1>
                <p>
                    <a href="index.php?pagina=admin">← Volver al panel</a> |
                    <a href="index.php?pagina=admin_informes">Informe de transacciones</a>
                </p>

                <?php if ($aviso !== null): ?>
                    <p class="<?= escapar($aviso['tipo']) ?>"><?= escapar($aviso['texto']) ?></p>
                <?php endif; ?>

                <!-- Filtros: es una consulta, por eso GET (los filtros quedan en la URL) -->
                <form action="index.php" method="GET" class="filtros">
                    <input type="hidden" name="pagina" value="admin_pedidos">

                    <label for="estado">Pago</label>
                    <select id="estado" name="estado">
                        <option value="">Todos</option>
                        <?php foreach ($estadosPago as $e): ?>
                            <option value="<?= escapar($e) ?>" <?= ($filtroEstado === $e) ? 'selected' : '' ?>>
                                <?= escapar(ucfirst($e)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="estado_envio">Envío</label>
                    <select id="estado_envio" name="estado_envio">
                        <option value="">Todos</option>
                        <?php foreach ($estadosEnvio as $e): ?>
                            <option value="<?= escapar($e) ?>" <?= ($filtroEnvio === $e) ? 'selected' : '' ?>>
                                <?= escapar(ucfirst($e)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input type="submit" value="Filtrar">
                    <a href="index.php?pagina=admin_pedidos">Limpiar filtros</a>
                </form>

                <?php if (empty($pedidos)): ?>
                    <p class="sin-resultados">No hay pedidos con esos criterios.</p>
                <?php else: ?>
                    <p><?= count($pedidos) ?> pedido(s).</p>
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th>N.º</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Pago</th>
                                <th>Envío</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pedidos as $p): ?>
                                <tr>
                                    <td><?= (int) $p['id'] ?></td>
                                    <td><?= escapar(date('d/m/Y H:i', strtotime($p['fecha']))) ?></td>
                                    <td><?= escapar($p['cliente']) ?></td>
                                    <td>$ <?= escapar(number_format($p['total'], 2, ',', '.')) ?></td>
                                    <td>
                                        <span class="estado estado-<?= escapar($p['estado']) ?>">
                                            <?= escapar(ucfirst($p['estado'])) ?>
                                        </span>
                                    </td>
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
                                        <a href="index.php?pagina=admin_pedido_detalle&id=<?= (int) $p['id'] ?>">Ver detalle</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </main>

        <?php require __DIR__ . "/../footer.php"; ?>
    </body>

</html>
