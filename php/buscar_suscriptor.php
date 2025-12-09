<?php
require_once 'conexion.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener la consulta
$q = trim($_GET['q'] ?? $_GET['query'] ?? '');

if (strlen($q) < 2) {
    echo json_encode(['success' => false, 'message' => 'Búsqueda demasiado corta']);
    exit;
}

// Buscar por nombre o correo
$stmt = $conexion->prepare("SELECT id, nombre,apellido, correo, direccion FROM suscriptores WHERE nombre LIKE CONCAT('%', ?, '%') OR correo LIKE CONCAT('%', ?, '%') LIMIT 10");
$stmt->bind_param("ss", $q, $q);
$stmt->execute();
$res = $stmt->get_result();

$resultados = [];
while ($row = $res->fetch_assoc()) {
    $resultados[] = $row;
}

echo json_encode([
    'success' => true,
    'suscriptores' => $resultados
]);
