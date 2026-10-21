<header>
            <!--Barra superior-->
            <div class="banner">
                <table>
                    <tr>
                        <td>
                            <a href="./index.php?pagina=catalogo">
                                <img src="./public/img/multiversal.png" alt="Logo de Multiversal Librería" class="logo">
                            </a>
                        </td>
                        <td class="centro">
                            <div class="search-bar">
                                <label for="busqueda">🔍︎</label>
                                <input type="text" placeholder="Búsqueda...">
                            </div>
                        </td>
                        <td class="derecha">
                            <a href="./index.php?pagina=carrito">
                                <img src="./public/img/carrito.png" alt="Carrito icon" class="icono">
                            </a>
                            <?php if (isset($_SESSION['usuario_id'])):?>
                                <a href="./index.php?pagina=panel">
                                    <img src="./public/img/usuario.png" alt="Usuario icon." class="icono">
                                    <?php echo $_SESSION['nombre'];?>
                                </a>
                            <?php else:?>
                                <a href="./index.php?pagina=login">
                                    <img src="./public/img/registrar.png" alt="Usuario icon" class="icono">
                                </a>
                            <?php endif;?>

                        </td>
                    </tr>
                </table>
            </div>
        </header>