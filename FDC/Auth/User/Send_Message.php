<?php
require '../../Config/conn.config.php';
session_name('patient_session');
session_start();

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $doc_id = $data['doc_id'] ?? null;
    $text = trim($data['text'] ?? '');

    if (!$doc_id || $text === '') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit;
    }

    $patient_id = $_SESSION['user_id'] ?? null;

    if (!$patient_id) {
        echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
        exit;
    }

    $stmt = $conn->prepare("
        INSERT INTO chat_messages (doctor_id, patient_id, sender, message, timestamp)
        VALUES (?, ?, 'user', ?, NOW())
    ");
    $stmt->execute([$doc_id, $patient_id, $text]);

    // Get last inserted ID
    $lastId = $conn->lastInsertId();

    echo json_encode([
        'status' => 'success',
        'id' => $lastId,
        'message' => 'Message sent successfully'
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
