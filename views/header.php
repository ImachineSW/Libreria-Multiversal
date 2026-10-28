 <?php
$busqueda = $busqueda ?? '';
$genero = $genero ?? '';
$resultado = $resultado ?? [];
?>

 <!--Barra superior-->
    <div class="banner">
        <table>
            <tr>
                <td>
                    <a href="./index.php?pagina=catalogo">
                        <img src="./public/img/multiversal.png" alt="Logo de Multiversal Librería" class="logo">
                    </a>
                </td>
                <td>
                    <div class="buscador">
                        <form action="index.php" method="GET">
                            <input type="hidden" name="pagina" value="catalogo">
                            <label for="q">🔍︎</label>
                            <input type="text" id="q" name="q" maxlength="50" class="barra"
                                placeholder="Ej: El Arte de la Guerra..."
                                value="<?= escapar($busqueda) ?>">
                            <input type="submit" value="Buscar" class="boton">
                            <?php if (!empty($_GET['genero'])): ?>
                                <input type="hidden" name="genero" value="<?= escapar($_GET['genero']) ?>">
                            <?php endif; ?>
                        </form>
                        <div class="resumen-busqueda">
                            <?php if ($busqueda !== '' || $genero !== ''): ?>
                                <p>Se encontraron <?= is_array($resultado) ? count($resultado) : 0 ?> libros <?= $busqueda !== '' ? ' con ' : '' ?>
                                <?php if ($busqueda !== ''): ?><strong>"<?= escapar($busqueda) ?>"</strong><?php endif; ?>
                                <?= $genero !== '' ? ' en ' : '' ?>
                                <?php if ($genero !== ''): ?><strong><?= escapar($genero) ?></strong><?php endif; ?>
                                — <a href="index.php?pagina=catalogo">Limpiar búsqueda</a></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
                        <td class="derecha">
                            <a href="./index.php?pagina=carrito">
                                <img src="./public/img/carrito.png" alt="Carrito icon" class="icono">
                            </a>
                            <?php if (estaLogueado()):?>
                                <a href="./index.php?pagina=panel">
                                    <img src="./public/img/usuario.png" alt="Usuario icon." class="icono">
                                    <p><?php echo "Bienvenido, " . escapar(usuarioActualNombre());?></p>
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

