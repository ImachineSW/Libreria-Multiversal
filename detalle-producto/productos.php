<!--David-->
<!--Actulizado 7-07-2026--> 
<DOCKTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <title>La Soledad</title>
        <link rel="stylesheet" href="../css/productos.css">
        <link rel="icon" type="image/png" href="../img/favicon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--etiqueta para que la pagina sea responsive-->
        <meta charset="UTF-8">
    </head>

    <body>

        <!--Barra Sueprior-->
        <header class="Barra-Superior ">
            <div class="banner">
                <table>
                    <tr>
                        <td>
                            <a href="../index.php">
                                <img src="../img/multiversal.png" alt="Logo de Multiversal Librería" class="logo">
                            </a>
                        </td>
                        <td class="centro">
                            <div class="search-bar">
                                <label for="busqueda">🔍︎</label>
                                <input type="text" placeholder="Búsqueda...">
                            </div>
                        </td>
                        <td class="derecha">
                            <a href="../carrito/carrito.html">
                                <img src="../img/carrito.png" alt="Carrito icon" class="icono">
                            </a>
                            <a href="../sesion/login/login.html">
                                <img src="../img/usuario.png" alt="Usuario icon." class="icono">
                            </a>
                        </td>
                    </tr>
                </table>
            </div>                   
        </header>

        <main>
            <!--Creacion del Articulo, Dividido-->
            <div class="Producto">
                <div class="Libro">
                    <img id="imagen"> 
                </div>
        <div id="mensaje-flotante" class="mensaje-flotante"></div>
                <!--Creando un Grupo para los detalles del Producto-->
                <div class="Detalles-Producto">
                    <a href="../index.php" class="back-btn">← Volver al catálogo</a>
                    <h1 id="titulo"></h1>
                    <div class="Info">
                        <p><span>Genero:</span><span id="genero"></span></p>
                        <p><span>Autor:</span><span id="autor"></span></p>
                        <p><span>Editorial:</span> <span id="editorial"></span></p>
                        <p><span>Año:</span><span id="año"></span></p>
                        <p><span>Idioma:</span><span id="idioma"></span></p>
                        <p><span>Formato:</span><span id="formato"></span></p>
                    </div>
                    <div class="Precio">
                        <p>Precio: <span id="precio"></span></p>
                    </div>
                    <p class="Sinopsis" id="sinopsis"></p>
                    <div class="Botones">
                        <a href="../carrito/carrito.html" class="addbutton"><button>Comprar</button></a>
                        <button class="addbutton">Agregar al Carrito</button>
                    </div>
                </div>              
            </div>
        </main>

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
                    <p><img src="../img/phone.png" alt="" class="sm"> 2401-1010*</p>
                    <p><img src="../img/whatsapp.png" alt="" class="sm"> 091285193</p>
                    <p><img src="../img/instagram.png" alt="" class="sm"> multiversallibreria</p>
                    <p><img src="../img/email.png" alt="" class="sm"> multiversallibreria@gmail.com</p>
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
                        <td class="derecha"><img src="../img/imachine.png" alt="Logo de ImachineSW."></td>
                    </tr>
                </table>
            </div>

        </footer>
    <script src="../js/storage.js"></script>
    <script src="../js/productos.js"></script>
    </body>

</html>
