<!DOCTYPE html>
<html>
    <head>
        <title>Admin</title>
        <link rel="stylesheet" href="./public/css/libros_lista.css">
        <link rel="icon" type="image/png" href="./public/img/favicon.png">
    </head>

    <body>

<?php
require __DIR__ . '/../header.php';

// Textos de cada nivel de stock (el nivel lo calcula la base)
$etiquetas = [
    'agotado' => 'Sin stock',
    'bajo' => 'Stock bajo',
    'ok' => 'OK'
];
?>

<div class="contenedor">
<h1>Libros</h1>

<p>
    <a href="index.php?pagina=admin">← Volver al panel</a> |
    <a href="index.php?pagina=admin_libro_crear">+ Nuevo libro</a>
</p>

<?php if ($aviso !== ''): ?>
    <p class="exito"><?= escapar($aviso) ?></p>
<?php endif; ?>

<?php if (empty($libros)): ?>
    <p>Todavía no hay libros cargados.</p>
<?php else: ?>

    <table class="tabla">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($libros as $l): ?>
                <tr class="<?= ((int)$l['activo'] === 1) ? '' : 'inactivo' ?>">
                    <td>
                        <?php if (!empty($l['imagen'])): ?>
                            <img
                                src="public/img/libros/<?= escapar($l['imagen']) ?>"
                                alt="<?= escapar($l['nombre']) ?>"
                                class="miniatura">
                        <?php else: ?>
                            <span class="sin-imagen">Sin imagen</span>
                        <?php endif; ?>
                    </td>

                    <td><?= escapar($l['nombre']) ?></td>

                    <td><?= escapar($l['generos']) ?></td>

                    <td>
                        $ <?= escapar(number_format($l['precio'], 2, ',', '.')) ?>
                    </td>

                    <td>
                        <span class="stock-valor">
                            <?= (int)$l['stock'] ?>
                        </span>

                        <span class="stock stock-<?= escapar($l['nivel_stock']) ?>">
                            <?= escapar($etiquetas[$l['nivel_stock']]) ?>
                        </span>
                    </td>

                    <td>
                        <?= ((int)$l['activo'] === 1) ? 'Activo' : 'Dado de baja' ?>
                    </td>

                    <td>
                        <a href="index.php?pagina=admin_libro_editar&id=<?= (int)$l['id'] ?>">
                            Editar
                        </a>

                        <form
                            action="index.php?pagina=admin_libro_estado&id=<?= (int)$l['id'] ?>"
                            method="POST"
                            class="en-linea">

                            <?php if ((int)$l['activo'] === 1): ?>
                                <input type="hidden" name="activo" value="0">

                                <button
                                    type="submit"
                                    onclick="return confirm('¿Dar de baja este libro?');">
                                    Dar de baja
                                </button>
                            <?php else: ?>
                                <input type="hidden" name="activo" value="1">

                                <button type="submit">
                                    Activar
                                </button>
                            <?php endif; ?>
                        </form>

                        <?php if ((int)$l['activo'] !== 1): ?>
                            <form
                                action="index.php?pagina=admin_libro_eliminar&id=<?= (int)$l['id'] ?>"
                                method="POST"
                                class="en-linea">
                                <button
                                    type="submit"
                                    onclick="return confirm('¿Eliminar este libro? Solo se podrá eliminar si no aparece en ningún pedido.');">
                                    Eliminar
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
<?php endif; ?>

</body>

<?php require __DIR__ . '/../footer.php'; ?>

</html>