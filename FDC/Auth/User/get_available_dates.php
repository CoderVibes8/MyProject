<?php
require_once '../../Config/conn.config.php';

if (!isset($_GET['doc_id']) || empty($_GET['doc_id'])) {
    echo json_encode([]);
    exit;
}

$docId = $_GET['doc_id'];


$stmt = $conn->prepare("SELECT DISTINCT date_slots FROM doctor_schedule WHERE doc_id = ? AND date_slots >= CURDATE() AND availability = 'Available' ORDER BY date_slots ASC");
$stmt->execute([$docId]);

$dates = $stmt->fetchAll(PDO::FETCH_COLUMN);

$formatted = array_map(function ($rawDate) {
    return [
        'raw' => $rawDate,
        'label' => date("M d, Y", strtotime($rawDate))
    ];
}, $dates);

echo json_encode($formatted);
