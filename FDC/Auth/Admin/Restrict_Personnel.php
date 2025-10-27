<?php
require_once '../../Config/conn.config.php';
header('Content-Type: application/json');

if (!isset($_POST['doc_id']) || !isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

$doc_id = $_POST['doc_id'];
$action = $_POST['action'];

try {
    if ($action === 'restrict') {
        $stmt = $conn->prepare("UPDATE doctor_acc_creation SET status = 'restricted' WHERE doc_id = ?");
        $stmt->execute([$doc_id]);
        echo json_encode(['success' => true, 'message' => 'Personnel has been restricted.']);
    } elseif ($action === 'unrestrict') {
        $stmt = $conn->prepare("UPDATE doctor_acc_creation SET status = 'activated' WHERE doc_id = ?");
        $stmt->execute([$doc_id]);
        echo json_encode(['success' => true, 'message' => 'Personnel has been unrestricted.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
