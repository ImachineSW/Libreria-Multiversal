<?php
require_once "../config/conexion.php"; // pide una vez la conexion a la db

$id = (int)$_GET['id']; // obtiene el valor de la id de la url y la transforma a entero para que no haya problemas de seguridad
$sql = "SELECT id, nombre, precio, autor, formato, imagen, genero, editorial, año, idioma, sinopsis FROM libros WHERE id = $id"; // le asigna la consulta a la db
$resultado = mysqli_query($conexion, $sql); // ejecuta la consulta a la db
$libro = mysqli_fetch_assoc($resultado); // obtiene los datos del libro para mostrarlo
?>

<!--David-->
<!--Actulizado 7-07-2026--> 
<DOCKTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title><?php echo $libro["nombre"]?></title>
        <link rel="stylesheet" href="../public/css/productos.css">
        <link rel="icon" type="image/png" href="../public/img/favicon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--etiqueta para que la pagina sea responsive-->
    </head>

    <body>

        <!--Header-->
        <?php require_once "./header.php"; ?>

        <main>
            <!--Creacion del Articulo, Dividido-->
            <div id="mensaje-flotante" class="mensaje-flotante"></div>
            <div class="Producto">
                <div class="Libro">
                    <img src=".<?php echo $libro["imagen"]; // le asigna la ruta de la imagen ?>" alt="<?php echo $libro["nombre"]; //le asigna el nombre al alt ?>">
                </div>
                <!--Creando un Grupo para los detalles del Producto-->
                <div class="Detalles-Producto">
                    <a href="./catalogo.php" class="back-btn">← Volver al catálogo</a>
                    <h1 id="titulo"><?php echo $libro["nombre"]; // le asigna el nombre del libro ?></h1>
                    <div class="Info">
                        <p><span>Genero:</span><span id="genero "><?php echo $libro["genero"]; // le asigna el genero del libro ?></span></p>
                        <p><span>Autor:</span><span id="autor"><?php echo $libro["autor"]; // le asigna el autor del libro ?></span></p>
                        <p><span>Editorial:</span> <span id="editorial"><?php echo $libro["editorial"]; // le asigna el editorial del libro ?></span></p>
                        <p><span>Año:</span><span id="año"><?php echo $libro["año"]; // le asigna el año del libro ?></span></p>
                        <p><span>Idioma:</span><span id="idioma"><?php echo $libro["idioma"]; // le asigna el idioma del libro ?></span></p>
                        <p><span>Formato:</span><span id="formato"><?php echo $libro["formato"]; // le asigna el formato del libro ?></span></p>
                    </div>
                    <div class="Precio">
                        <p>Precio: <span id="precio"><?php echo $libro["precio"]; // le asigna el precio del libro ?></span></p>
                    </div>
                    <p class="Sinopsis" id="sinopsis"><?php echo $libro["sinopsis"]; // le asigna la sinopsis del libro ?></p>
                    <div class="Botones">
                        <a href="./carrito.html" class="addb" data-id="<?php echo $libro["id"]; ?>" data-nombre="<?php echo $libro["nombre"]; ?>" data-precio="<?php echo $libro["precio"]; ?>" data-autor="<?php echo $libro["autor"]; ?>" data-formato="<?php echo $libro["formato"]; ?>" data-imagen="<?php echo $libro["imagen"]; ?>">Comprar</a> <!-- manda los datos de los data- a la funcion de agregar al carrito -->
                        <button class="addb" data-id="<?php echo $libro["id"]; ?>" data-nombre="<?php echo $libro["nombre"]; ?>" data-precio="<?php echo $libro["precio"]; ?>" data-autor="<?php echo $libro["autor"]; ?>" data-formato="<?php echo $libro["formato"]; ?>" data-imagen="<?php echo $libro["imagen"]; ?>">Agregar al Carrito</button> <!-- manda los datos de los data- a la funcion de agregar al carrito -->
                    </div>
                </div>              
            </div>
        </main>

        <?php require_once "./footer.php"; ?>
    <script src="../public/js/storage.js"></script>
    <script src="../public/js/app.js"></script>
    </body>

</html>
