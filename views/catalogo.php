<?php
require_once "../config/conexion.php"; // pide una vez la conexion a la db

$sql = "SELECT id, nombre, precio, autor, formato, imagen, genero, editorial, año, idioma, sinopsis FROM libros"; // le asigna la consulta a la db
$resultado = mysqli_query($conexion, $sql); // ejecuta la consulta y la guarda en la variable resultado
?>

<!--Joaquín Mandirola 12/07-->
<!DOCTYPE html>
<html>

    <head>
        <title>Catálogo</title> <!--Titulo-->
        <link rel="icon" type="image/png" href="../public/img/favicon.png"> <!--Prueba de favicon (la imagen que aparece al lado del titulo en el tab)-->
        <link rel="stylesheet" href="../public/css/catalogo.css"> <!--Link a archivo de estilo-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--etiqueta para que la pagina sea responsive-->
        <meta charset="UTF-8">
    </head>

    <body> 
        <!--Header-->
        <?php require_once "../views/header.php"; ?>

            <!--Navegador de catálogo-->
            <div class="navegador">
                <nav>
                    <details>
                        <summary>Generos</summary>
                        <div class="generos">
                            <ul>
                                <a href="">Crimen</a><br>
                                <a href="">Policías</a><br>
                                <a href="">Romance</a><br>
                                <a href="">Fantasía</a><br>
                                <a href="">Ciencia Ficción</a><br>
                                <a href="">Acción</a><br>
                                <a href="">Aventura</a><br>
                                <a href="">Misterio</a><br>
                                <a href="">Terror</a><br>
                                <a href="">Comedia</a><br>
                                <a href="">Prosa literaria</a><br>
                                <a href="">Poesía</a><br>
                                <a href="">Drama</a><br>
                                <a href="">Histórico</a><br>
                                <a href="">Infantil</a><br>
                                <a href="">Juvenil</a><br>
                                <a href="">Filosófico</a><br>
                                <a href="">Religioso</a><br>
                                <a href="">Novela gráfica</a><br>
                            </ul>
                        </div>
                    </details>
                    <button>Mangas</button>
                    <button>Cómics</button>
                    <button>EBooks</button>
                    <button>Libros de Bolsillo</button>
                    <button>Infantiles</button>
                    <button>Audio Libros</button>
                    <button>Libros en Descuentos</button>
                    <button>Ofertas</button>
                </nav>
            </div>
 

        <!--Catálogo de productos-->
        <main>
        <div id="mensaje-flotante" class="mensaje-flotante"></div>
            <div id="contenedor"> 
                <?php if (mysqli_num_rows($resultado) > 0): //devuelve el numero de filas que hay en la tabla ?> 
                    <?php while ($libro = mysqli_fetch_assoc($resultado)): //mientras haya lineas en la tabla muestra los libros ?>
                        <div class="tarjeta">
                            <a href="./productos.php?id=<?php echo $libro["id"]; //le asigna el id del libro a la redireccion a productos.php ?>">
                                <img src=".<?php echo $libro["imagen"]; //le asigna la imagen del libro a la portada ?>" alt="<?php echo $libro["nombre"]; //le asigna el nombre del libro al alt ?>" class="portada">
                                <h3><?php echo $libro["nombre"]; //le asigna el nombre del libro a el titulo ?></h3>
                                <p class="precio">$<?php echo $libro["precio"]; //le as igna el precio del libro a el precio ?></p>
                                <p><?php echo $libro["autor"]; //le asigna el autor del libro a el autor ?></p>
                                <p><?php echo $libro["formato"]; //le asigna el formato del libro a el formato ?></p>
                            </a>
                            <button class="addb" data-id="<?php echo $libro["id"]; ?>" data-nombre="<?php echo $libro["nombre"]; ?>" data-precio="<?php echo $libro["precio"]; ?>" data-autor="<?php echo $libro["autor"]; ?>" data-formato="<?php echo $libro["formato"]; ?>" data-imagen="<?php echo $libro["imagen"]; ?>">Agregar al Carrito</button> <!--manda los datos de los data  a la funcion de agregar al carrito-->
        	            </div>
        <?php endwhile; //termina de recorrer el array?>
        <?php else: //si no hay productos cargados muestra mensaje de que no hay productos?>
            <p>No hay productos cargados en este momento.</p>
            <?php endif; //termina el if?>
                
            </div>
        </main>

        <!--Footer-->
        <?php require_once "./footer.php"; ?>
        
    <script src="../public/js/storage.js"></script>
    <script src="../public/js/app.js"></script>
    </body>

</html>