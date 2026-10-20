<!DOCTYPE html>
<html lang="es">
    <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device=width, initial-scale=1.0">
            <title>Pedido confirmado - Muliversal Libreria</title>
            <link rel="icon" type="image/png" href="./public/img/favicon.png">
            <link rel="stylesheet" href="./public/css/carrito.css">
</head>
<body>
         <?php require __DIR__ . '/header.php'; ?>
         <main class="confirmacion-pedido">
            <h1>Pedido Guardado</h1>
              <p>Tu pedido fue registrado correctamente. Esta confirmación no incluye todavía los datos de envío ni un pago.</p>
        <p><strong>Número de pedido:</strong> <?= htmlspecialchars((string)$pedido['id'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Fecha:</strong> <?= htmlspecialchars($pedido['fecha'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Estado:</strong> <?= htmlspecialchars($pedido['estado'], ENT_QUOTES, 'UTF-8') ?></p>
        <h2>Detalle</h2>
        <ul>
            <?php foreach ($pedido['detalles'] as $detalle): ?>
                <li>
                    <?= htmlspecialchars($detalle['nombre'], ENT_QUOTES, 'UTF-8') ?> —
                    <?= (int)$detalle['cantidad'] ?> x $
                    <?= number_format((float)$detalle['precio_unitario'], 2, ',', '.') ?> = $
                    <?= number_format((float)$detalle['subtotal'], 2, ',', '.') ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <h2>Total: $<?= number_format((float)$pedido['total'], 2, ',', '.') ?></h2>
        <a href="index.php?pagina=catalogo">Volver al catálogo</a>
    </main>
    <?php require __DIR__ . '/footer.php'; ?>
    <script src="./public/js/storage.js"></script>
    <script>
        // El pedido ya quedó guardado, limpiar el carrito local después de llegar a la confirmación.
        if (typeof guardarCarrito === 'function') guardarCarrito([]);
    </script>
</body>
</html>    