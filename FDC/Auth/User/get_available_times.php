<?php
require_once '../../Config/conn.config.php';

$docId = $_GET['doc_id'] ?? null;
$date = $_GET['date'] ?? null;

if (!$docId || !$date) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT start_time, end_time FROM doctor_schedule WHERE doc_id = ? AND date_slots = ? AND availability = 'Available'");
$stmt->execute([$docId, $date]);
$slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output: [{ "value": "08:00:00|08:30:00", "label": "8:00 AM - 8:30 AM" }, ...]
$formatted = array_map(function($slot) {
    $startRaw = $slot['start_time'];
    $endRaw = $slot['end_time'];
    $startFormatted = date("g:i A", strtotime($startRaw));
    $endFormatted = date("g:i A", strtotime($endRaw));
    return [
        "value" => "$startRaw|$endRaw",
        "label" => "$startFormatted - $endFormatted"
    ];
}, $slots);

echo json_encode($formatted);
