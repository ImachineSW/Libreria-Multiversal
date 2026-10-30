<html>
    <head>
    <link rel="stylesheet" href="./public/css/usuarios_lista.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="./public/img/favicon.png">
    <title>Panel</title>
    </head>


<?php require __DIR__ . '/../header.php'; ?>

<body>
    <div class="contenedor">

<h1>Usuarios</h1>
<p><a href="index.php?pagina=admin">← Volver al panel</a></p>

<?php if ($aviso !== null): ?>
    <p class="<?= escapar($aviso['tipo']) ?>">
        <?= escapar($aviso['texto']) ?>
    </p>
<?php endif; ?>

<?php if (empty($usuarios)): ?>
    <p>Todavía no hay usuarios registrados.</p>
<?php else: ?>
    <table class="tabla">
        <thead>
            <tr>
                <th>N.º</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Pedidos</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
                <?php $esMiCuenta = ((int) $u['id'] === $idActual); ?>
                <tr class="<?= ((int) $u['activo'] === 1) ? '' : 'inactivo' ?>">
                    <td><?= (int) $u['id'] ?></td>
                    <td>
                        <?= escapar($u['nombre']) ?>
                        <?php if ($esMiCuenta): ?><em>(Tu cuenta)</em><?php endif; ?>
                    </td>
                    <td><?= escapar($u['email']) ?></td>
                    <td>
                        <span class="rol rol-<?= escapar($u['rol']) ?>">
                            <?= escapar(ucfirst($u['rol'])) ?>
                        </span>
                    </td>
                    <td><?= (int) $u['cantidad_pedidos'] ?></td>
                    <td><?= ((int) $u['activo'] === 1) ? 'Activo' : 'Desactivado' ?></td>
                    <td>
                        <?php if ($esMiCuenta): ?>
                            <em>No disponible para tu propia cuenta</em>
                        <?php else: ?>
                            <form action="index.php?pagina=admin_usuario_rol&id=<?= (int) $u['id'] ?>"
                                  method="POST" class="en-linea">
                                <label for="rol-<?= (int) $u['id'] ?>" class="solo-lectores">Rol</label>
                                <select id="rol-<?= (int) $u['id'] ?>" name="rol">
                                    <?php foreach ($roles as $r): ?>
                                        <option value="<?= escapar($r) ?>"
                                            <?= ($u['rol'] === $r) ? 'selected' : '' ?>>
                                            <?= escapar(ucfirst($r)) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit"
                                        onclick="return confirm('¿Cambiar el rol de este usuario?');">
                                    Cambiar rol
                                </button>
                            </form>

                            <form action="index.php?pagina=admin_usuario_estado&id=<?= (int) $u['id'] ?>"
                                  method="POST" class="en-linea">
                                <?php if ((int) $u['activo'] === 1): ?>
                                    <input type="hidden" name="activo" value="0">
                                    <button type="submit"
                                            onclick="return confirm('¿Desactivar esta cuenta? La persona no podrá iniciar sesión.');">
                                        Desactivar
                                    </button>
                                <?php else: ?>
                                    <input type="hidden" name="activo" value="1">
                                    <button type="submit">Reactivar</button>
                                <?php endif; ?>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</div>
<?php require __DIR__ . '/../footer.php'; ?>
    
</body>
</html>