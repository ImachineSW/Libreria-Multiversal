<!DOCTYPE html>
<html lang="es">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No se pudo confirmar el pedido</title>
    <link rel="stylesheet" href="./public/css/carrito.css">
</head>
<body>
    <header>
    <?php require __DIR__ . '/header.php'; ?>
    </header>
    <main class="error-pedido">
        <h1>No se pudo confirmar el pedido</h1>
        <p><?= htmlspecialchars($errorPedido ?? 'Ocurrió un error. Intentá nuevamente.', ENT_QUOTES, 'UTF-8') ?></p>
        <p>El carrito no se ha limpiado, así que podés volver e intentarlo nuevamente.</p>
        <a href="index.php?pagina=carrito">Volver al carrito</a>
    </main>
    <?php require __DIR__ . '/footer.php'; ?>
</body>
</html>
