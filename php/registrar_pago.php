<?php
session_start();
require_once 'conexion.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$data = json_decode(file_get_contents("php://input"), true);

// Validar datos básicos
$suscriptor_id = (int)($data['suscriptor_id'] ?? 0);
$monto = (float)($data['monto'] ?? 0);
$comentarios = trim($data['comentarios'] ?? '');
$usuario_registro_id = (int)($_SESSION['usuario_id'] ?? 0); // Asegúrate que esté en sesión

if (!$suscriptor_id || !$monto || $monto <= 0) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit;
}

$codigo = obtenerCodigoUnico($conexion);
// Fechas de pago y suscripción anual
$fecha_pago = date('Y-m-d H:i:s'); // Fecha y hora actual
$fecha_inicio = date('Y-m-d');
$fecha_fin = date('Y-m-d', strtotime('+1 year -1 day', strtotime($fecha_inicio))); // Vigencia exacta de 1 año
$usada = 0;

// Insertar en la tabla de pagos
$stmt = $conexion->prepare("INSERT INTO pagos_suscripciones (suscriptor_id, fecha_pago, fecha_inicio, fecha_fin, monto, usuario_registro_id, comentarios, codigo, usada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssdssi", $suscriptor_id, $fecha_pago, $fecha_inicio, $fecha_fin, $monto, $usuario_registro_id, $comentarios, $codigo,$usada);

$ok = $stmt->execute();

if ($ok) {
    // Actualizar estado del suscriptor como activo y fecha_fin
    $stmt2 = $conexion->prepare("UPDATE suscriptores SET activo=1, fecha_fin=? WHERE id=?");
    $stmt2->bind_param("si", $fecha_fin, $suscriptor_id);
    $stmt2->execute();

    echo json_encode(['success' => true, 'message' => 'Pago registrado correctamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al registrar el pago']);
}

function generarCodigo($longitud = 12) {
  $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $codigo = '';
  for ($i = 0; $i < $longitud; $i++) {
    $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
  }
  return chunk_split($codigo, 3, '-'); // agrega los guiones
}

function obtenerCodigoUnico($conexion) {
  do {
    $codigo = rtrim(generarCodigo(), '-');
    $stmt = $conexion->prepare("SELECT id FROM pagos_suscripciones WHERE codigo = ?");
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $result = $stmt->get_result();
  } while ($result->num_rows > 0);

  return $codigo;
}
