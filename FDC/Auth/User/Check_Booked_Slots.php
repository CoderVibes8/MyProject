<?php
require '../../config/conn.config.php';

header("Content-Type: application/json");

if (!isset($_GET['doc_id'], $_GET['date'])) {
    echo json_encode([]);
    exit;
}

$doc_id = $_GET['doc_id'];
$date   = $_GET['date'];

try {
    // Fetch already booked slots for this doctor + date
    $stmt = $conn->prepare("
        SELECT appointment_time_start AS start_time, appointment_time_end AS end_time
        FROM appointment_schedule AS aps LEFT JOIN doctor_schedule ds ON aps.doc_id = ds.doc_id
        WHERE aps.doc_id = :doc_id 
          AND DATE(aps.appointment_date) = :date
          AND ds.availability = 'Booked'
    ");
    $stmt->execute([
        ':doc_id' => $doc_id,
        ':date'   => $date
    ]);

    $booked = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format output for JS
    $result = array_map(function ($slot) {
        return [
            'start_time' => date('H:i', strtotime($slot['start_time'])),
            'end_time'   => date('H:i', strtotime($slot['end_time']))
        ];
    }, $booked);

    echo json_encode($result);

} catch (PDOException $e) {
    error_log("Check_Booked_Slots error: " . $e->getMessage());
    echo json_encode([]);
}
