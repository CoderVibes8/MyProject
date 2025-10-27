<?php
require_once '../../Config/conn.config.php';
header('Content-Type: application/json');

if (!isset($_POST['doc_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing personnel ID']);
    exit;
}

$doc_id = $_POST['doc_id'];

try {
    // Prepare and execute deletion
    $stmt = $conn->prepare("DELETE FROM doctor_acc_creation WHERE doc_id = ?");
    $stmt->execute([$doc_id]);

    // Check if something was deleted
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Personnel has been deleted.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No record deleted. Possibly invalid ID or column mismatch.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
