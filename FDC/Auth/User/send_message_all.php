<?php
require_once '../../Config/conn.config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id  = $_POST['doctor_id'] ?? null;
    $patient_id = $_POST['patient_id'] ?? null;
    $sender     = $_POST['sender'] ?? null; // 'user' or 'doctor'
    $message    = trim($_POST['message'] ?? '');

    // Validate all fields are present and non-empty
    if ($doctor_id && $patient_id && $sender && $message !== '') {
        date_default_timezone_set('Asia/Manila');
        $timestamp = date('Y-m-d H:i:s');

        try {
            $stmt = $conn->prepare("
                INSERT INTO chat_messages (doctor_id, patient_id, sender, message, timestamp)
                VALUES (:doctor_id, :patient_id, :sender, :message, :timestamp)
            ");

            $stmt->execute([
                ':doctor_id'  => $doctor_id,
                ':patient_id' => $patient_id,
                ':sender'     => $sender,
                ':message'    => $message,
                ':timestamp'  => $timestamp
            ]);

            echo json_encode([[
                'sender' => $sender,
                'content' => $message,
                'time' => date('h:i A', strtotime($timestamp))
            ]]);

        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
        }

    } else {
        echo json_encode(['success' => false, 'error' => 'Missing data']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
