<?php
require_once "conexion.php"; // pide una vez la conexion a la db

$sql = "SELECT id, nombre, precio, autor, formato, imagen, genero, editorial, año, idioma, sinopsis FROM libros"; // le asigna la consulta a la db
$resultado = mysqli_query($conexion, $sql); // ejecuta la consulta y la guarda en la variable resultado
?>

<!--Joaquín Mandirola 12/07-->
<!DOCTYPE html>
<html>

    <head>
        <title>Catálogo</title> <!--Titulo-->
        <link rel="icon" type="image/png" href="./img/favicon.png"> <!--Prueba de favicon (la imagen que aparece al lado del titulo en el tab)-->
        <link rel="stylesheet" href="./css/catalogo.css"> <!--Link a archivo de estilo-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--etiqueta para que la pagina sea responsive-->
        <meta charset="UTF-8">
    </head>

    <body>

        <header>
            <!--Barra superior-->
            <div class="banner">
                <table>
                    <tr>
                        <td>
                            <a href="./index.php">
                                <img src="./img/multiversal.png" alt="Logo de Multiversal Librería" class="logo">
                            </a>
                        </td>
                        <td class="centro">
                            <div class="search-bar">
                                <label for="busqueda">🔍︎</label>
                                <input type="text" placeholder="Búsqueda...">
                            </div>
                        </td>
                        <td class="derecha">
                            <a href="./carrito/carrito.html">
                                <img src="./img/carrito.png" alt="Carrito icon" class="icono">
                            </a>
                            <a href="./sesion/login/login.html">
                                <img src="./img/usuario.png" alt="Usuario icon." class="icono">
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
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
        </header>

        <!--Catálogo de productos-->
        <main>
        <div id="mensaje-flotante" class="mensaje-flotante"></div>
            <div id="contenedor"> 
                <?php if (mysqli_num_rows($resultado) > 0): //devuelve el numero de filas que hay en la tabla ?> 
                    <?php while ($libro = mysqli_fetch_assoc($resultado)): //mientras haya lineas en la tabla muestra los libros ?>
                        <div class="tarjeta">
                            <a href="./detalle-producto/productos.php?id=<?php echo $libro["id"]; //le asigna el id del libro a la redireccion a productos.php ?>">
                                <img src="<?php echo $libro["imagen"]; //le asigna la imagen del libro a la portada ?>" alt="<?php echo $libro["nombre"]; //le asigna el nombre del libro al alt ?>" class="portada">
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
        <footer>

            <div class="barra-horizontal">

            </div>

            <div class="multiversal">
                <div>
                    <p><span>Sede</span></p>
                    <p>Diego Duarte Olleros 851</p>
                </div>
                <div>
                    <span>Contacto</span>
                    <p><img src="./img/phone.png" alt="" class="sm"> 2401-1010*</p>
                    <p><img src="./img/whatsapp.png" alt="" class="sm"> 091285193</p>
                    <p><img src="./img/instagram.png" alt="" class="sm"> multiversallibreria</p>
                    <p><img src="./img/email.png" alt="" class="sm"> multiversallibreria@gmail.com</p>
                </div>
                <div>
                    <span>Horarios</span>
                    <p>Lun - Vie / 09:30 - 18:30 hs</p>
                    <p>Sáb / 09:30 - 13:30 hs</p>
                </div>
                <div>
                    <p><span>Empresa</span></p>
                    <p>Nosotros</p>
                    <p>Trabaje con nosotros</p>
                </div>
                <div>
                    <p><span>Compras</span></p>
                    <p>Cómo comprar</p>
                    <p>Envíos y devoluciones</p>
                    <p>Preguntas Frequentes</p>
                </div>
            </div>

            <div class="creditos">
                <table>
                    <tr>
                        <td>© Copyright / Multiversal</td>
                        <td class="derecha"><img src="./img/imachine.png" alt="Logo de ImachineSW."></td>
                    </tr>
                </table>
            </div>

        </footer>
    <script src="./js/storage.js"></script>
    <script src="./js/app.js"></script>
    </body>

</html>