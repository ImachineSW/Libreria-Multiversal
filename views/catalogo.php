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

    <body> 
        <!--Header-->
        <?php require __DIR__ . "/header.php"; ?>
 
        <!--Catálogo de productos-->
        <main>
        <div id="mensaje-flotante" class="mensaje-flotante"></div>
            <div id="contenedor">
                <?php if ($resultado && !empty($resultado) > 0): //devuelve el numero de filas que hay en la tabla ?> 
                    <?php foreach ($resultado as $libro): //mientras haya lineas en la tabla muestra los libros ?>
                        <div class="tarjeta">
                            <a href="./index.php?pagina=libro&id=<?php echo $libro["id"]; //le asigna el id del libro a la redireccion a productos.php ?>">
                                <img src="<?php echo $libro["imagen"]; //le asigna la imagen del libro a la portada ?>" alt="<?php echo $libro["nombre"]; //le asigna el nombre del libro al alt ?>" class="portada">
                                <h3><?php echo $libro["nombre"]; //le asigna el nombre del libro a el titulo ?></h3>
                                <p class="precio">$<?php echo $libro["precio"]; //le as igna el precio del libro a el precio ?></p>
                                <p><?php echo $libro["autor"]; //le asigna el autor del libro a el autor ?></p>
                                <p><?php echo $libro["formato"]; //le asigna el formato del libro a el formato ?></p>
                            </a>
                            <button class="addb" data-id="<?php echo $libro["id"]; ?>" data-nombre="<?php echo $libro["nombre"]; ?>" data-precio="<?php echo $libro["precio"]; ?>" data-autor="<?php echo $libro["autor"]; ?>" data-formato="<?php echo $libro["formato"]; ?>" data-imagen="<?php echo $libro["imagen"]; ?>">Agregar al Carrito</button> <!--manda los datos de los data  a la funcion de agregar al carrito-->
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