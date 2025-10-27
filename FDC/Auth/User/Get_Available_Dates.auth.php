<?php
require '../../config/conn.config.php';

if (!isset($_GET['doc_id'], $_GET['type_id'])) {
    echo json_encode([]);
    exit;
}

$doc_id = $_GET['doc_id'];
$type_id = $_GET['type_id'];

try {
    $stmt = $conn->prepare("
        SELECT DISTINCT date_slots AS raw, DATE_FORMAT(date_slots, '%b %d, %Y') AS label
        FROM doctor_schedule
        WHERE doc_id = :doc_id AND consultation_type = :type_id AND date_slots >= CURDATE() AND availability = 'Available'
        ORDER BY date_slots ASC
    ");
    $stmt->execute([
        ':doc_id' => $doc_id,
        ':type_id' => $type_id
    ]);
    $dates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($dates);
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode([]);
}
