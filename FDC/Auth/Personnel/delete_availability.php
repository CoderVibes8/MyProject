<?php
// delete_availability.php
header('Content-Type: application/json');
require '../../Config/conn.config.php'; // Adjust path as needed

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$doctor_id = (int)($input['doctor_id'] ?? 0);
$date_slots = $input['date_slots'] ?? '';
$start_time = $input['start_time'] ?? '';
$end_time = $input['end_time'] ?? '';

if (!$doctor_id || !$date_slots || !$start_time || !$end_time) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM doctor_schedule WHERE doc_id = :doc_id AND date_slots = :date_slots AND start_time = :start_time AND end_time = :end_time");
    $stmt->execute([
        ':doc_id' => $doctor_id,
        ':date_slots' => $date_slots,
        ':start_time' => $start_time,
        ':end_time' => $end_time
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No matching slot found']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
