<?php
require_once 'conexion.php';
header('Content-Type: application/json');
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$correo = $data['correo'] ?? '';
$password = $data['password'] ?? '';

if (!$correo || !$password) {
  echo json_encode(['success' => false, 'message' => 'Campos incompletos']);
  exit;
}

$stmt = $conexion->prepare("SELECT id, nombre, contraseña FROM usuarios_registro WHERE correo = ? AND activo = 1");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($usuario = $result->fetch_assoc()) {
  if (password_verify($password, $usuario['contraseña'])) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nombre'] = $usuario['nombre'];
    echo json_encode(['success' => true, 'message' => 'Sesión iniciada']);
  } else {
    echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'Usuario no encontrado o inactivo']);
}
