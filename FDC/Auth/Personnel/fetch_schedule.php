<?php
date_default_timezone_set('Asia/Manila');
require '../../Config/conn.config.php';

$doctorId = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;
$type     = isset($_GET['type']) ? $_GET['type'] : '';

if ($doctorId === 0 || $type === '') {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("
    SELECT date_slots, start_time, end_time, availability 
    FROM doctor_schedule 
    WHERE doc_id = :doc_id 
      AND consultation_type = :consult_type
");
$stmt->execute([
    ':doc_id' => $doctorId,
    ':consult_type' => $type
]);

$schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

$today = date('Y-m-d');

$events = array_map(function ($row) use ($today) {
    $status = $row['availability'];
    if ($row['date_slots'] < $today) {
        $status = 'Expired';
    }

    switch ($status) {
        case 'Available':
            $bgColor = '#28a745'; // Green
            break;
        case 'Booked':
            $bgColor = '#dc3545'; // Red
            break;
        case 'Expired':
        default:
            $bgColor = '#6c757d'; // Gray
            break;
    }

    return [
        'title' => $status,
        'start' => $row['date_slots'] . 'T' . $row['start_time'],
        'end' => $row['date_slots'] . 'T' . $row['end_time'],
        'backgroundColor' => $bgColor,
        'borderColor' => $bgColor,
        'extendedProps' => [
            'availability' => $status,
            'date_slots' => $row['date_slots'],
        ],
    ];
}, $schedules);

header('Content-Type: application/json');
echo json_encode($events);
