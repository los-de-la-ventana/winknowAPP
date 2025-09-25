<?php
session_start();
require 'adm_usr/func_usr.php';

// ---------------------------
// Verificar permisos
// ---------------------------
if (!($_SESSION['logged_in'] ?? false) || ($_SESSION['tipo'] ?? '') !== 'admin') {
    header("Location: ../login_reg/login.php");
    exit;
}

// ---------------------------
// Procesar acciones POST
// ---------------------------
function procesarAccion() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return null;
    $accion = $_POST['accion'] ?? '';

    switch ($accion) {
        case 'agregar':
            return agregarUsuario(
                $_POST['cedula'],
                $_POST['contrasenia'],
                $_POST['nombre'],
                $_POST['tipo_usuario'],
                $_POST['email'] ?? null,
                $_POST['telefono'] ?? null,
                [
                    'grado'    => $_POST['grado'] ?? null,
                    'estado'   => $_POST['estado'] ?? 'Activo',
                    'rolAdmin' => $_POST['rolAdmin'] ?? null,
                    'fechaNac' => $_POST['fechaNac'] ?? null
                ]
            );
        case 'eliminar':
            return eliminarUsuario($_POST['cedula']);
        case 'cambiar_estado':
            return cambiarEstadoDocente($_POST['cedula'], $_POST['nuevo_estado']);
        default:
            return null;
    }
}

$resultado_accion = procesarAccion();
$mensaje = $resultado_accion['message'] ?? '';
$tipo_mensaje = ($resultado_accion['success'] ?? false) ? 'success' : 'danger';

// ---------------------------
// Obtener usuarios
// ---------------------------
$usuarios = listarUsuarios()['data'] ?? [];
$estadisticas = obtenerEstadisticasUsuarios()['data'] ?? [];

include '../headerfooter/header.html';
include '../headerfooter/navADM.php';

// ---------------------------
// Función para inputs
// ---------------------------
function inputGroup($label, $name, $type='text', $placeholder='', $extra='') {
    return "<div class='mb-3'>
        <label class='form-label'>{$label}:</label>
        <input type='{$type}' name='{$name}' class='form-control' placeholder='{$placeholder}' {$extra}>
    </div>";
}
?>

<body>
<title>WinKnow - Gestión de Usuarios</title>

<main class="principal container py-4">
    <?php if ($mensaje): ?>
        <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($mensaje) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <h2 class="mb-4">Gestión de Usuarios</h2>

    <div class="d-flex mb-3 gap-2">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">
            <i class="bi bi-person-plus"></i> Agregar Usuario
        </button>
        <button class="btn btn-info" onclick="location.reload()">
            <i class="bi bi-arrow-clockwise"></i> Actualizar Lista
        </button>
    </div>

    <hr>

    <h3>Lista de Usuarios</h3>
    <?php if ($usuarios): ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
            <?php foreach ($usuarios as $u): ?>
                <div class="col">
                    <div class="card h-100 border-primary">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($u['Nombre_usr']) ?></h5>
                            <p class="card-text"><strong>Cédula:</strong> <?= htmlspecialchars($u['Cedula']) ?></p>
                            <p class="card-text"><strong>Tipo:</strong> <?= htmlspecialchars($u['tipo_usuario']) ?></p>
                            <?php if ($u['email']): ?><p><strong>Email:</strong> <?= htmlspecialchars($u['email']) ?></p><?php endif; ?>
                            <?php if ($u['numeroTelefono']): ?><p><strong>Teléfono:</strong> <?= htmlspecialchars($u['numeroTelefono']) ?></p><?php endif; ?>
                            <?php if ($u['tipo_usuario']==='Docente' && $u['estado_docente']): ?>
                                <p><strong>Estado:</strong>
                                    <span class="badge <?= $u['estado_docente']==='Activo'?'bg-success':'bg-warning text-dark' ?>">
                                        <?= htmlspecialchars($u['estado_docente']) ?>
                                    </span>
                                </p>
                            <?php endif; ?>
                            <?php if ($u['tipo_usuario']==='Administrador' && $u['rolAdmin']): ?>
                                <p><strong>Rol:</strong> <?= htmlspecialchars($u['rolAdmin']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer d-flex gap-2">
                            <button class="btn btn-primary btn-sm" onclick="editarUsuario(<?= $u['Cedula'] ?>)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <?php if ($u['tipo_usuario']==='Docente'): ?>
                                <button class="btn btn-warning btn-sm" onclick="cambiarEstado(<?= $u['Cedula'] ?>,'<?= $u['estado_docente']==='Activo'?'Inactivo':'Activo' ?>')">
                                    <i class="bi bi-toggle-on"></i>
                                </button>
                            <?php endif; ?>
                            <button class="btn btn-danger btn-sm" onclick="eliminarUsuario(<?= $u['Cedula'] ?>)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info mt-3">No hay usuarios registrados.</div>
    <?php endif; ?>
</main>

<!-- Modal Agregar Usuario -->
<div class="modal fade" id="modalAgregar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST">
                <input type="hidden" name="accion" value="agregar">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?= inputGroup('Cédula','cedula','number','','required') ?>
                    <?= inputGroup('Nombre','nombre') ?>
                    <?= inputGroup('Contraseña','contrasenia','password') ?>
                    <div class="mb-3">
                        <label class="form-label">Tipo de Usuario:</label>
                        <select name="tipo_usuario" class="form-select" required onchange="mostrarCamposEspecificos(this.value)">
                            <option value="">Seleccionar...</option>
                            <option value="docente">Docente</option>
                            <option value="admin">Administrador</option>
                            <option value="estudiante">Estudiante</option>
                        </select>
                    </div>
                    <?= inputGroup('Email','email','email') ?>
                    <?= inputGroup('Teléfono','telefono') ?>

                    <div id="campos-docente" style="display:none;">
                        <?= inputGroup('Grado','grado','number','','min=1 max=5') ?>
                        <div class="mb-3">
                            <label class="form-label">Estado:</label>
                            <select name="estado" class="form-select">
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div id="campos-admin" style="display:none;">
                        <?= inputGroup('Rol Administrativo','rolAdmin') ?>
                    </div>

                    <div id="campos-estudiante" style="display:none;">
                        <?= inputGroup('Fecha de Nacimiento','fechaNac','date') ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Agregar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="adminValidation.js"></script>

</body>
</html>