<?php
require_once __DIR__ . '/../models/usuario.php';

class AdminUsuarioController {
    private $conexion;
    private $usuarioModel;

    private $rolesValidos = ['cliente', 'admin'];

    private $avisos = [
        'rol_cambiado' => [
            'tipo' => 'exito',
            'texto' => 'El rol del usuario fue actualizado.'
        ],
        'desactivado' => [
            'tipo' => 'exito',
            'texto' => 'La cuenta fue desactivada. Esa persona ya no puede iniciar sesión.'
        ],
        'reactivado' => [
            'tipo' => 'exito',
            'texto' => 'La cuenta fue reactivada.'
        ],
        'propio' => [
            'tipo' => 'error',
            'texto' => 'No podés cambiar tu propio rol ni desactivar tu propia cuenta.'
        ],
        'invalido' => [
            'tipo' => 'error',
            'texto' => 'No se pudo realizar la acción: los datos no son válidos.'
        ],
        'fallo' => [
            'tipo' => 'error',
            'texto' => 'Ocurrió un problema al guardar el cambio. Intentá de nuevo.'
        ]
    ];

    public function __construct($conexion) {
        requerirRol('admin');
        $this->conexion = $conexion;
        $this->usuarioModel = new Usuario($conexion);
    }

    public function listar() {
        $usuarios = $this->usuarioModel->listarTodosAdmin();
        $roles = $this->rolesValidos;
        $idActual = (int) usuarioActualId();

        $clave = $_GET['msg'] ?? '';
        $aviso = (is_string($clave) && isset($this->avisos[$clave]))
            ? $this->avisos[$clave]
            : null;

        require __DIR__ . '/../views/admin/usuarios_lista.php';
    }

    public function cambiarRol() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('admin_usuarios');
        }

        $id = $this->leerIdDeLaUrl();
        $rol = $_POST['rol'] ?? '';

        if ($id === null || !in_array($rol, $this->rolesValidos, true)) {
            redirigir('admin_usuarios', '&msg=invalido');
        }

        if ($id === (int) usuarioActualId()) {
            redirigir('admin_usuarios', '&msg=propio');
        }

        if (!$this->usuarioModel->obtenerPorId($id)) {
            redirigir('admin_usuarios', '&msg=invalido');
        }

        if ($this->usuarioModel->cambiarRol($id, $rol)) {
            redirigir('admin_usuarios', '&msg=rol_cambiado');
        }

        redirigir('admin_usuarios', '&msg=fallo');
    }

    public function cambiarEstado() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirigir('admin_usuarios');
        }

        $id = $this->leerIdDeLaUrl();
        $activo = (($_POST['activo'] ?? '') === '1') ? 1 : 0;

        if ($id === null) {
            redirigir('admin_usuarios', '&msg=invalido');
        }

        if ($id === (int) usuarioActualId()) {
            redirigir('admin_usuarios', '&msg=propio');
        }

        if (!$this->usuarioModel->obtenerPorId($id)) {
            redirigir('admin_usuarios', '&msg=invalido');
        }

        if ($this->usuarioModel->cambiarActivo($id, $activo)) {
            redirigir(
                'admin_usuarios',
                $activo ? '&msg=reactivado' : '&msg=desactivado'
            );
        }

        redirigir('admin_usuarios', '&msg=fallo');
    }

    private function leerIdDeLaUrl() {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        return ($id !== false && $id > 0) ? $id : null;
    }
}