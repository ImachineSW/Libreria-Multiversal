<!DOCTYPE html>
<html>

    <head>
        <title>Datos de envío</title> <!--Titulo de pagina-->
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
                <a href="./index.php?pagina=carrito" class="back-btn">← Volver al carrito</a>
                <h1>Datos de envío</h1>

                <h2>Tu compra</h2>
                <ul class="lista-checkout">
                    <?php foreach ($carrito as $linea): ?>
                        <li>
                            <?= escapar($linea['nombre']) ?> × <?= (int) $linea['cantidad'] ?>
                            — $ <?= escapar(number_format($linea['precio'] * $linea['cantidad'], 2, ',', '.')) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p>Total: <strong>$ <?= escapar(number_format($total, 2, ',', '.')) ?></strong></p>

                <?php if (!empty($errores)): ?>
                    <ul class="errores">
                        <?php foreach ($errores as $error): ?>
                            <li><?= escapar($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <form action="./index.php?pagina=checkout" method="POST" class="formulario-pedido">
                    <!-- El carrito viaja dentro del formulario (solo ids y cantidades) -->
                    <input type="hidden" name="carritoJson" value="<?= escapar($carritoJson) ?>">

                    <label for="nombre">Nombre de quien recibe</label>
                    <input type="text" id="nombre" name="nombre" value="<?= escapar($envio['nombre']) ?>" required>

                    <label for="direccion">Dirección (calle y número, apto.)</label>
                    <input type="text" id="direccion" name="direccion" value="<?= escapar($envio['direccion']) ?>" required>

                    <label for="ciudad">Ciudad / localidad</label>
                    <input type="text" id="ciudad" name="ciudad" value="<?= escapar($envio['ciudad']) ?>" required>

                    <label for="departamento">Departamento</label>
                    <select id="departamento" name="departamento" required>
                        <option value="">-- Elegí --</option>
                        <?php foreach ($departamentos as $dep): ?>
                            <option value="<?= escapar($dep) ?>" <?= ($envio['departamento'] === $dep) ? 'selected' : '' ?>>
                                <?= escapar($dep) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="telefono">Teléfono de contacto</label>
                    <input type="text" id="telefono" name="telefono" inputmode="numeric" value="<?= escapar($envio['telefono']) ?>" required>

                    <input type="submit" name="confirmar" value="Continuar al pago">
                </form>
            </div>
        </main>

        <?php require __DIR__ . "/footer.php"; ?>
    </body>

</html>
