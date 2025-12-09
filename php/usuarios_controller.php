<?php
require_once 'conexion.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Establecer zona horaria de México para la sesión actual de MySQL
$conexion->query("SET time_zone = '-06:00'");

// Leer datos JSON desde el cuerpo de la petición
$data = json_decode(file_get_contents("php://input"), true);

// Determinar acción
$accion = $data['action'] ?? $_GET['accion'] ?? $_POST['accion'] ?? '';

switch ($accion) {
    case 'listar':
        $res = $conexion->query("SELECT id, nombre, correo, activo, fecha_creacion AS creado FROM usuarios_registro ORDER BY id DESC");
        echo json_encode(['success' => true, 'usuarios' => $res->fetch_all(MYSQLI_ASSOC)]);
        exit;

    case 'agregar':
        $nombre = trim($data['nombre'] ?? '');
        $correo = trim($data['correo'] ?? '');
        $password = $data['password'] ?? '';

        if (!$nombre || !$correo || !$password) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
            exit;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("INSERT INTO usuarios_registro (nombre, correo, contraseña, activo) VALUES (?, ?, ?, 1)");
        $stmt->bind_param("sss", $nombre, $correo, $hash);
        $ok = $stmt->execute();

        echo json_encode(['success' => $ok, 'message' => $ok ? 'Usuario agregado correctamente' : 'Error al agregar el usuario']);
        exit;

    case 'editar':
        $id = (int) ($data['id'] ?? 0);
        $nombre = trim($data['nombre'] ?? '');
        $correo = trim($data['correo'] ?? '');
        $password = $data['password'] ?? null;

        if (!$id || !$nombre || !$correo) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            exit;
        }

        if ($password) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conexion->prepare("UPDATE usuarios_registro SET nombre=?, correo=?, contraseña=? WHERE id=?");
            $stmt->bind_param("sssi", $nombre, $correo, $hash, $id);
        } else {
            $stmt = $conexion->prepare("UPDATE usuarios_registro SET nombre=?, correo=? WHERE id=?");
            $stmt->bind_param("ssi", $nombre, $correo, $id);
        }

        $ok = $stmt->execute(); // ✅ Esta línea es esencial

        echo json_encode(['success' => $ok, 'message' => $ok ? 'Usuario actualizado' : 'Error al actualizar']);
        exit;


    case 'eliminar':
        $id = (int) ($data['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            exit;
        }

        $stmt = $conexion->prepare("DELETE FROM usuarios_registro WHERE id=?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();

        echo json_encode(['success' => $ok, 'message' => $ok ? 'Usuario eliminado' : 'Error al eliminar']);
        exit;

    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        exit;
}
