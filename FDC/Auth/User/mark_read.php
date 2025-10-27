<?php
require '../../Config/conn.config.php';
session_name('patient_session');
session_start();

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
if (!$input || !isset($input['id'])) {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$notif_id = (int)$input['id'];

try {
    $stmt = $conn->prepare("UPDATE user_notif SET isRead = 1 WHERE id = ? AND user_id = ?");
    $stmt->execute([$notif_id, $user_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Notification marked as read"]);
    } else {
        echo json_encode(["success" => false, "message" => "Notification not updated"]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
