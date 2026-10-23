<!--Maximo Duran 07/7-->
<!DOCTYPE html>
<html>

    <head>
        <title>Carrito</title> <!--Titulo de pagina-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./public/css/carrito.css"> <!--Vincular carrito.css con carrito.php-->
        <link rel="icon" type="image/png" href="./public/img/favicon.png"> <!--Favicon-->
        <meta charset="UTF-8">
    </head>

    <header>
        <!--Header-->
        <?php require __DIR__ . "/header.php"; ?>
    </header>

    <body>
        
        <main>
            <div id="mensaje-flotante" class="mensaje-flotante"></div>
            <div class="contenedor"> <!--Clase contenedor-->
                <div class="carrito"> <!--Clase carrito-->
                    <a href="./index.php?pagina=catalogo" class="back-btn">← Volver al catálogo</a>
                    <h1>Carrito de Compras</h1>
                    <div class="productos"> <!--Aca se agregan los libros al carrito--> </div>
                    <button class="vaciar">Vaciar Carrito</button>
                </div>
                <div class="resumen"> <!--Resumen de compra-->
                    <h2>Resumen</h2>
                    <div class="libros-r"> <!--Aca se agregan los libros al resumen de compra--> </div>
                    <div class="total"><!--Aca se muestra el total--></div>
                    <button class="comprar">Comprar</button>
                </div>
            </div>
        </main>

        <?php require __DIR__ . "/footer.php"; ?>
    <script src="./public/js/storage.js"></script>
    <script src="./public/js/carrito.js"></script>
    <script src="./public/js/app.js"></script>
    <script src="./public/js/pedido.js"></script>
    </body>

</html>