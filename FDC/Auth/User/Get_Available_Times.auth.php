<?php
require '../../config/conn.config.php';

if (!isset($_GET['doc_id'], $_GET['date'], $_GET['type_id'])) {
    echo json_encode([]);
    exit;
}

$doc_id = $_GET['doc_id'];
$date = $_GET['date'];
$type_id = $_GET['type_id'];

try {
    $stmt = $conn->prepare("
        SELECT start_time, end_time
        FROM doctor_schedule
        WHERE doc_id = :doc_id AND consultation_type = :type_id AND date_slots = :date AND availability = 'Available'
        ORDER BY start_time ASC
    ");
    $stmt->execute([
        ':doc_id' => $doc_id,
        ':type_id' => $type_id,
        ':date' => $date
    ]);

    $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result = [];

    foreach ($slots as $slot) {
        $start = date("g:i A", strtotime($slot['start_time']));
        $end = date("g:i A", strtotime($slot['end_time']));
        $result[] = [
            'value' => $slot['start_time'] . '|' . $slot['end_time'],
            'label' => "$start - $end"
        ];
    }

    echo json_encode($result);
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode([]);
}
