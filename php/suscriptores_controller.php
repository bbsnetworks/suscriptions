<?php
require_once 'conexion.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$data = json_decode(file_get_contents("php://input"), true);
$accion = $data['action'] ?? $_GET['accion'] ?? $_POST['accion'] ?? '';

switch ($accion) {
    case 'listar':
        $res = $conexion->query("SELECT * FROM suscriptores ORDER BY id DESC");
        echo json_encode(['success' => true, 'suscriptores' => $res->fetch_all(MYSQLI_ASSOC)]);
        exit;

    case 'agregar':
        $nombre = trim($data['nombre'] ?? '');
        $apellido = trim($data['apellido'] ?? '');
        $telefono = trim($data['telefono'] ?? '');
        $correo = trim($data['correo'] ?? '');
        $direccion = trim($data['direccion'] ?? '');
        $fecha_ingreso = $data['fecha_ingreso'] ?? null;
        $fecha_fin = $data['fecha_fin'] ?? null;
        $comentarios = trim($data['comentarios'] ?? '');

        if (!$nombre || !$apellido || !$telefono || !$correo || !$fecha_ingreso || !$fecha_fin) {
            echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
            exit;
        }

        $stmt = $conexion->prepare("INSERT INTO suscriptores 
            (nombre, apellido, telefono, correo, direccion, activo, fecha_ingreso, fecha_fin, comentarios) 
            VALUES (?, ?, ?, ?, ?, 0, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $nombre, $apellido, $telefono, $correo, $direccion, $fecha_ingreso, $fecha_fin, $comentarios);
        $ok = $stmt->execute();

        echo json_encode(['success' => $ok, 'message' => $ok ? 'Suscriptor agregado correctamente' : 'Error al agregar el suscriptor']);
        exit;

    case 'editar':
        $id = (int) ($data['id'] ?? 0);
        $nombre = trim($data['nombre'] ?? '');
        $apellido = trim($data['apellido'] ?? '');
        $telefono = trim($data['telefono'] ?? '');
        $correo = trim($data['correo'] ?? '');
        $direccion = trim($data['direccion'] ?? '');
        $fecha_ingreso = $data['fecha_ingreso'] ?? null;
        $fecha_fin = $data['fecha_fin'] ?? null;
        $comentarios = trim($data['comentarios'] ?? '');
        $activo = (int) ($data['activo'] ?? 1);

        if (!$id || !$nombre || !$apellido || !$telefono || !$correo || !$fecha_ingreso || !$fecha_fin) {
            echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios para editar']);
            exit;
        }

        $stmt = $conexion->prepare("UPDATE suscriptores SET 
            nombre=?, apellido=?, telefono=?, correo=?, direccion=?, fecha_ingreso=?, fecha_fin=?, comentarios=?, activo=? 
            WHERE id=?");
        $stmt->bind_param("ssssssssii", $nombre, $apellido, $telefono, $correo, $direccion, $fecha_ingreso, $fecha_fin, $comentarios, $activo, $id);
        $ok = $stmt->execute();

        echo json_encode(['success' => $ok, 'message' => $ok ? 'Suscriptor actualizado' : 'Error al actualizar']);
        exit;

    case 'eliminar':
        $id = (int) ($data['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            exit;
        }

        $stmt = $conexion->prepare("DELETE FROM suscriptores WHERE id=?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();

        echo json_encode(['success' => $ok, 'message' => $ok ? 'Suscriptor eliminado' : 'Error al eliminar']);
        exit;

    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        exit;
}
