<?php
require '../../Config/conn.config.php';
session_name('doctor_session');
session_start();

if (!isset($_SESSION['doc_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$doctor_id = $_SESSION['doc_id'];

try {
    $stmt = $conn->prepare("SELECT * FROM doctor_notif WHERE doc_id = ? ORDER BY created_at DESC LIMIT 10");
    $stmt->execute([$doctor_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $conn->prepare("SELECT COUNT(*) as unreadCount FROM doctor_notif WHERE doc_id = ? AND isRead = 0");
    $stmt2->execute([$doctor_id]);
    $unreadCount = $stmt2->fetch(PDO::FETCH_ASSOC)['unreadCount'];

    echo json_encode([
        "success" => true,
        "data" => $notifications,
        "unread" => (int)$unreadCount
    ]);
    
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
