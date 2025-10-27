<?php
require_once '../../Config/conn.config.php';
session_name('doctor_session');
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['doc_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$doc_id = $_SESSION['doc_id'];

try {
    $stmt = $conn->prepare("UPDATE doctor_notif SET isRead = 1 WHERE doc_id = ? AND isRead = 0");
    $stmt->execute([$doc_id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
