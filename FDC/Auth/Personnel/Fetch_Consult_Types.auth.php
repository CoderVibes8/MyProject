<?php
require '../../Config/conn.config.php';
header('Content-Type: application/json');

if (!isset($_GET['doctor_id'])) {
    echo json_encode([]);
    exit;
}

$doctor_id = (int)$_GET['doctor_id'];

$stmt = $conn->prepare("
    SELECT DISTINCT specialty AS consultation_type, doctor_id
    FROM doctor_info
    WHERE doctor_id = :doctor_id
");
$stmt->execute(['doctor_id' => $doctor_id]);

$types = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Return each row as an object with doctor_id and consultation_type
    $types[] = [
        'doctor_id' => $row['doctor_id'],
        'consultation_type' => $row['consultation_type']
    ];
}

echo json_encode($types);
