<?php
require '../../Config/conn.config.php';

header('Content-Type: application/json');

$doctorId    = $_GET['doctor_id'] ?? 0;
$consultType = $_GET['consult_type'] ?? '';
$date        = $_GET['date'] ?? '';

if ($doctorId == 0 || empty($consultType) || empty($date)) {
    echo json_encode([
        'success' => false,
        'message' => '❌ Missing doctor, consultation type, or date'
    ]);
    exit();
}

try {
    $stmt = $conn->prepare("
        SELECT start_time, end_time
        FROM doctor_schedule
        WHERE doc_id = :doctor_id
          AND consultation_type = :consult_type
          AND date_slots = :date
    ");
    $stmt->execute([
        ':doctor_id'    => $doctorId,
        ':consult_type' => $consultType,
        ':date'         => $date
    ]);

    $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'slots'   => $slots
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'DB Error: ' . $e->getMessage()
    ]);
}
