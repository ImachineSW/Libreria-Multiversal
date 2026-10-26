<!--Joaquín Mandirola 12/07-->
<!DOCTYPE html>
<html>

    <head>
        <title>Catálogo</title> <!--Titulo-->
        <link rel="icon" type="image/png" href="./public/img/favicon.png"> <!--Prueba de favicon (la imagen que aparece al lado del titulo en el tab)-->
        <link rel="stylesheet" href="./public/css/catalogo.css"> <!--Link a archivo de estilo-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--etiqueta para que la pagina sea responsive-->
        <meta charset="UTF-8">
    </head>

    <header>

        <!--Header-->
        <?php require __DIR__ . "/header.php"; ?>

    </header>

    <body>
 
        <!--Catálogo de productos-->
        <main>

        <div class="generos">
            <?php foreach ($generos as $g): ?>
                <a class="genero" href="index.php?pagina=catalogo&amp;genero=<?= urlencode($g['nombre']) ?>&amp;q=<?= urlencode($busqueda) ?>">
                    <?= escapar($g['nombre']) ?>
                </a>
            <?php endforeach; ?>
        </div> 

        <div id="mensaje-flotante" class="mensaje-flotante"></div>
            <div id="contenedor">
                <?php if (!empty($resultado)): ?> 
                    <?php foreach ($resultado as $libro): //mientras haya lineas en la tabla muestra los libros ?>
                        <div class="tarjeta">
                            <a href="./index.php?pagina=libro&amp;id=<?php echo (int) $libro['id']; //le asigna el id del libro a la redireccion a productos.php ?>">
                                <img src="./public/img/Libros/<?php echo escapar($libro['imagen']); //le asigna la imagen del libro a la portada ?>" alt="<?php echo escapar($libro['nombre']); //le asigna el nombre del libro al alt ?>" class="portada">
                                <h3 class="titulo-libro"><?php echo escapar($libro['nombre']); //le asigna el nombre del libro a el titulo ?></h3>
                                <p class="precio">$<?php echo escapar($libro["precio"]); //le as igna el precio del libro a el precio ?></p>
                                <p><?php echo escapar($libro["autor"]); //le asigna el autor del libro a el autor ?></p>
                            </a>
                            <?php if ((int) $libro["stock"] > 0): ?>
                                <button class="addb" data-id="<?php echo (int) $libro["id"]; ?>" data-nombre="<?php echo escapar($libro["nombre"]); ?>" data-precio="<?php echo escapar($libro["precio"]); ?>" data-autor="<?php echo escapar($libro["autor"]); ?>" data-paginas="<?php echo (int) ($libro["paginas"] ?? 0); ?>" data-imagen="<?php echo escapar($libro["imagen"]); ?>">Agregar al Carrito</button> <!--manda los datos de los data  a la funcion de agregar al carrito-->
                            <?php else: ?>
                                <span class="stock stock-agotado">Sin stock</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; //termina de recorrer el array?>
                <?php endif?>
            </div>
        </main>

        <!--Footer-->
        <?php require __DIR__ . "/footer.php"; ?>
        
    <script src="./public/js/storage.js"></script>
    <script src="./public/js/app.js"></script>
    </body>

</html>