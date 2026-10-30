<!DOCTYPE html>
<html>

    <head>
        <title>Pagar pedido</title> <!--Titulo de pagina-->
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
                <h1>Pagar pedido N.º <?= escapar($pedido['id']) ?></h1>

                <?php if ($pedido['estado'] === 'rechazado'): ?>
                    <p class="errores">El intento anterior fue rechazado. Podés intentarlo de nuevo.</p>
                <?php endif; ?>

                <p>Total a pagar: <strong>$ <?= escapar(number_format($pedido['total'], 2, ',', '.')) ?></strong></p>
                <p>
                    Envío a: <?= escapar($pedido['envio_nombre']) ?> —
                    <?= escapar($pedido['envio_direccion']) ?>,
                    <?= escapar($pedido['envio_ciudad']) ?>
                    (<?= escapar($pedido['envio_departamento']) ?>)
                </p>

                <?php if (!empty($errores)): ?>
                    <ul class="errores">
                        <?php foreach ($errores as $error): ?>
                            <li><?= escapar($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <form action="./index.php?pagina=pago&id=<?= (int) $pedido['id'] ?>" method="POST" class="formulario-pedido">
                    <label for="titular">Nombre del titular</label>
                    <input type="text" id="titular" name="titular" value="<?= escapar($titular) ?>" required>

                    <label for="numero">Número de tarjeta (16 dígitos)</label>
                    <input type="text" id="numero" name="numero" inputmode="numeric" maxlength="19"
                           autocomplete="off" placeholder="4111 1111 1111 1112" required>

                    <label for="vencimiento">Vencimiento (MM/AA)</label>
                    <input type="text" id="vencimiento" name="vencimiento" maxlength="5" placeholder="12/28" required>

                    <label for="cvv">Código de seguridad (3 dígitos)</label>
                    <input type="password" id="cvv" name="cvv" inputmode="numeric" maxlength="3" autocomplete="off" required>

                    <input type="submit" value="Pagar">
                </form>

                <details class="ayuda">
                    <summary>Tarjetas de prueba</summary>
                    <ul>
                        <li><code>4111 1111 1111 1112</code> → pago <strong>aprobado</strong></li>
                        <li><code>4111 1111 1111 1111</code> → pago <strong>rechazado</strong></li>
                    </ul>
                    <p>La simulación aprueba si el último dígito es par y rechaza si es impar.</p>
                </details>
            </div>
        </main>

        <?php require __DIR__ . "/footer.php"; ?>

        <!--El pedido ya esta guardado: se vacia el carrito para no duplicarlo al volver-->
        <script src="./public/js/storage.js"></script>
        <script>guardarCarrito([]);</script>
    </body>

</html>
