<?php
require_once 'conexion.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$id = (int) ($data['id'] ?? 0);

if (!$id) {
  echo json_encode(['success' => false, 'message' => 'ID no válido']);
  exit;
}

$stmt = $conexion->prepare("DELETE FROM pagos_suscripciones WHERE id = ?");
$stmt->bind_param("i", $id);
$ok = $stmt->execute();

echo json_encode([
  'success' => $ok,
  'message' => $ok ? 'Pago eliminado correctamente' : 'No se pudo eliminar'
]);
