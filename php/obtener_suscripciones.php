<?php
require_once 'conexion.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validar año recibido
$anio = isset($_GET['anio']) ? intval($_GET['anio']) : date("Y");

if (!$anio) {
    echo json_encode(['success' => false, 'message' => 'Año inválido']);
    exit;
}

// Obtener lista de pagos con suscriptores
$sql = "
    SELECT 
        p.id AS pago_id,
        s.id AS suscriptor_id,
        s.nombre, 
        s.correo, 
        p.fecha_inicio, 
        p.fecha_fin, 
        s.activo, 
        p.codigo,
        p.monto
    FROM pagos_suscripciones p
    INNER JOIN suscriptores s ON p.suscriptor_id = s.id
    WHERE YEAR(p.fecha_inicio) = ? OR YEAR(p.fecha_fin) = ?
    ORDER BY p.fecha_inicio DESC
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("ii", $anio, $anio);
$stmt->execute();
$result = $stmt->get_result();
$suscripciones = $result->fetch_all(MYSQLI_ASSOC);

// Calcular resumen
$total = count($suscripciones);
$activos = 0;
$ingresos = 0;

foreach ($suscripciones as $s) {
    if ($s['activo']) $activos++;
    $ingresos += (float)$s['monto'];
}

echo json_encode([
    'success' => true,
    'suscripciones' => $suscripciones,
    'resumen' => [
        'total_suscriptores' => $total,
        'activos' => $activos,
        'ingresos' => number_format($ingresos, 2, '.', '')
    ]
]);
