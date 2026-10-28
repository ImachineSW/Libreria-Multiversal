<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante <?= escapar($numeroComprobante) ?></title>
    <link rel="icon" type="image/png" href="./public/img/favicon.png">
    <link rel="stylesheet" href="./public/css/pedido_comprobante.css">
        

</head>

<body>
    <div class="barra no-imprimir">
        <button type="button" onclick="window.print()">Descargar PDF / Imprimir</button>
        <a href="<?= escapar($urlVolver) ?>">← Volver al pedido</a>
    </div>

    <div class="encabezado">
        <div>
            <img src="./public/img/multiversal.png" alt="Multiversal Librería">
            <p>Multiversal Librería</p>
        </div>
        <div class="der">
            <h1>COMPROBANTE DE PEDIDO</h1>
            <p>N.º <strong><?= escapar($numeroComprobante) ?></strong><br>
                Fecha del pedido: <?= escapar(date('d/m/Y H:i', strtotime($pedido['fecha']))) ?></p>
        </div>
    </div>

    <div class="datos">
        <div>
            <strong>Cliente</strong><br>
            <?= escapar($cliente) ?>
        </div>
        <div>
            <strong>Enviar a</strong><br>
            <?= escapar($pedido['envio_nombre']) ?><br>
            <?= escapar($pedido['envio_direccion']) ?><br>
            <?= escapar($pedido['envio_ciudad']) ?>, <?= escapar($pedido['envio_departamento']) ?><br>
            Tel.: <?= escapar($pedido['envio_telefono']) ?>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Libro</th>
                <th class="num">Cant.</th>
                <th class="num">Precio unit.</th>
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
        <tfoot>
            <tr>
                <td colspan="3" class="num total">TOTAL</td>
                <td class="num total">$ <?= escapar(number_format($pedido['total'], 2, ',', '.')) ?></td>
            </tr>
        </tfoot>
    </table>

    <?php if ($pago): ?>
        <p>Pagado con tarjeta terminada en <strong><?= escapar($pago['ultimos4']) ?></strong>
            el <?= escapar(date('d/m/Y H:i', strtotime($pago['fecha']))) ?>.</p>
    <?php endif; ?>

    <p class="pie">Documento de práctica generado con fines educativos. Sin validez fiscal.</p>
</body>

</html>
