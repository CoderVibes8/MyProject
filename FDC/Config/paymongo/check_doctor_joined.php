<?php
require '../../config/conn.config.php';

header('Content-Type: application/json'); // ✅ Important for JSON response

$consultationId = isset($_GET['consultation_id']) ? (int)$_GET['consultation_id'] : 0;

if ($consultationId <= 0) {
    echo json_encode(['error' => 'Invalid consultation ID']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT doctor_joined FROM doctor_consultation WHERE id = ?");
    $stmt->execute([$consultationId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $doctorJoined = $row ? (int)$row['doctor_joined'] : 0;
    echo json_encode(['doctor_joined' => $doctorJoined]);

} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch data']);
}
