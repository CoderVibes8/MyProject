<?php
require '../../Config/conn.config.php';

header('Content-Type: application/json');

$doctorId = $_GET['doctor_id'] ?? null;
if (!isset($doctorId) || empty($doctorId)) {
    echo json_encode(['availability' => 'Not Availablee']);
    exit;
}

if ($doctorId) {
    try {
        $avail = $conn->prepare("SELECT availability FROM doctor_acc_creation WHERE doc_id = ?");
        $avail->execute([$doctorId]);
        $row = $avail->fetch();

        $availability = $row ? $row['availability'] : 'Not Available';
        echo json_encode(['availability' => $availability]);
    } catch (PDOException $e) {
        echo json_encode(['availability' => 'Not Available']);
    }
} else {
    echo json_encode(['availability' => 'Not Available']);
}
?>
