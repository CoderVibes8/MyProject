<?php
require '../../Config/conn.config.php';
session_name('patient_session');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT * FROM user_notif WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
    $stmt->execute([$user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $conn->prepare("SELECT COUNT(*) as unreadCount FROM user_notif WHERE user_id = ? AND isRead = 0");
    $stmt2->execute([$user_id]);
    $unreadCount = $stmt2->fetch(PDO::FETCH_ASSOC)['unreadCount'];

    echo json_encode([
        "success" => true,
        "data" => $notifications,
        "unread" => (int)$unreadCount
    ]);
    
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
