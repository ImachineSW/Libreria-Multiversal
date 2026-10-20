<!DOCTYPE html>
<html>

    <head>
        <title>Detalle del pedido</title> <!--Titulo de pagina-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./public/css/carrito.css?v=2"> <!--Se reutiliza el estilo del carrito-->
        <link rel="icon" type="image/png" href="./public/img/favicon.png"> <!--Favicon-->
        <meta charset="UTF-8">
    </head>

    <body>

        <!--Header-->
        <?php require_once __DIR__ . "/header.php"; ?>

        <main>
            <div class="carrito pedido-detalle">
                <a href="./index.php?pagina=catalogo" class="back-btn">← Volver al catálogo</a>
                <h1>¡Gracias por tu compra!</h1>

                <p><strong>Pedido Nº:</strong> <?= htmlspecialchars($pedido["id"]) ?></p>
                <p><strong>Fecha:</strong> <?= htmlspecialchars(date("d/m/Y H:i", strtotime($pedido["fecha"]))) ?></p>
                <p><strong>Estado:</strong> <span class="estado"><?= htmlspecialchars($pedido["estado"]) ?></span></p>

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
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item["nombre"]) ?></td>
                                <td class="num"><?= htmlspecialchars($item["cantidad"]) ?></td>
                                <td class="num">$<?= htmlspecialchars(number_format($item["precio_unitario"], 2)) ?></td>
                                <td class="num">$<?= htmlspecialchars(number_format($item["precio_unitario"] * $item["cantidad"], 2)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <p class="fuerte total-pedido">Total: $<?= htmlspecialchars(number_format($pedido["total"], 2)) ?></p>
            </div>
        </main>

        <?php require_once __DIR__ . "/footer.php"; ?>
    </body>

</html>