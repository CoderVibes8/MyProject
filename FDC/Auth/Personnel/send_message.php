<?php
require '../../Config/conn.config.php';
session_name('doctor_session'); // personnel session
session_start();

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $user_id = $data['user_id'] ?? null; // the patient ID
    $text = trim($data['text'] ?? '');

    $personnel_id = $_SESSION['doc_id'] ?? null; // logged-in personnel

    if (!$personnel_id || !$user_id || $text === '') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        exit;
    }

    $stmt = $conn->prepare("
        INSERT INTO chat_messages (doctor_id, patient_id, sender, message, timestamp)
        VALUES (?, ?, 'personnel', ?, NOW())
    ");

    if ($stmt->execute([$personnel_id, $user_id, $text])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Insert failed']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
