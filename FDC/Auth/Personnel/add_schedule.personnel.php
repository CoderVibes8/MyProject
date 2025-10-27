<?php
require '../../Config/conn.config.php';

$data = json_decode(file_get_contents('php://input'), true);

$doctorId = $data['doctor_id'];
$start    = $data['start'];
$end      = $data['end'];

$stmt = $conn->prepare("INSERT INTO doctor_schedule (doctor_id, start_time, end_time) VALUES (:doctor_id, :start, :end)");
$stmt->execute([
    ':doctor_id' => $doctorId,
    ':start'     => $start,
    ':end'       => $end
]);

echo json_encode(['success' => true]);
?>
