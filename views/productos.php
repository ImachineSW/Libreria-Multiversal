<!--David-->
<!--Actulizado 7-07-2026--> 
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo htmlspecialchars($libro['nombre'], ENT_QUOTES, 'UTF-8'); ?></title>
        <link rel="stylesheet" href="./public/css/productos.css">
        <link rel="icon" type="image/png" href="./public/img/favicon.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!--etiqueta para que la pagina sea responsive-->
    </head>

    <header>
        <!--Header-->
        <?php require __DIR__ . "/header.php"; ?>
    </header>

    <body>

        <main>
            <!--Creacion del Articulo, Dividido-->
            <div id="mensaje-flotante" class="mensaje-flotante"></div>
            <div class="Producto">
                <div class="Libro">
                    <img src="<?php echo $libro["imagen"]; // le asigna la ruta de la imagen ?>" alt="<?php echo $libro["nombre"]; //le asigna el nombre al alt ?>">
                </div>
                <!--Creando un Grupo para los detalles del Producto-->
                <div class="Detalles-Producto">
                    <a href="./index.php?pagina=catalogo" class="back-btn">← Volver al catálogo</a>
                    <h1 id="titulo"><?php echo $libro["nombre"]; // le asigna el nombre del libro ?></h1>
                    <div class="Precio">
                        <p>Precio: <span id="precio">$<?php echo $libro["precio"]; // le asigna el precio del libro ?></span></p>
                    </div>
                    <p class="Sinopsis" id="sinopsis"><?php echo $libro["sinopsis"]; // le asigna la sinopsis del libro ?></p>
                    <div class="Info">
                        <p>
                            Género(s):
                            <span id="genero">
                                <?php echo htmlspecialchars($libro['genero'] ?? 'Sin género', ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </p>
                        <p>
                            Autor:
                            <span id="autor">
                                <?php echo htmlspecialchars($libro['autor'], ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </p>
                        <p>
                            Editorial:
                            <span id="editorial">
                                <?php echo htmlspecialchars($libro['editorial'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </p>
                        <p>
                            Fecha de publicación:
                            <span id="fecha">
                                <?php echo htmlspecialchars($libro['fecha'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </wp>
                        <p>
                            Idioma:
                            <span id="idioma">
                                <?php echo htmlspecialchars($libro['idioma'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </p>
                        <p>
                            Páginas:
                            <span id="paginas">
                                <?php echo $libro['paginas'] !== null ? (int) $libro['paginas']: 'No especificado'; ?>
                            </span>
                        </p>
                    </div>
                    <div class="Botones">
                        <a
                            href="./index.php?pagina=carrito"
                            class="addb"
                            data-id="<?php echo (int) $libro['id']; ?>"
                            data-nombre="<?php echo htmlspecialchars($libro['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                            data-precio="<?php echo htmlspecialchars((string) $libro['precio'], ENT_QUOTES, 'UTF-8'); ?>"
                            data-autor="<?php echo htmlspecialchars($libro['autor'], ENT_QUOTES, 'UTF-8'); ?>"
                            data-paginas="<?php echo (int) ($libro['paginas'] ?? 0); ?>"
                            data-imagen="<?php echo htmlspecialchars($libro['imagen'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            Comprar</a>
                        <button class="addb" data-id="<?php echo (int) $libro['id']; ?>"
                            data-nombre="<?php echo htmlspecialchars($libro['nombre'], ENT_QUOTES, 'UTF-8'); ?>"
                            data-precio="<?php echo htmlspecialchars((string) $libro['precio'], ENT_QUOTES, 'UTF-8'); ?>"
                            data-autor="<?php echo htmlspecialchars($libro['autor'], ENT_QUOTES, 'UTF-8'); ?>"
                            data-paginas="<?php echo (int) ($libro['paginas'] ?? 0); ?>"
                            data-imagen="<?php echo htmlspecialchars($libro['imagen'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            Agregar al carrito
                        </button>
                    </div>
                </div>              
            </div>
        </main>

        <?php require __DIR__ . "/footer.php"; ?>
    <script src="./public/js/storage.js"></script>
    <script src="./public/js/app.js"></script>
    </body>

</html>
