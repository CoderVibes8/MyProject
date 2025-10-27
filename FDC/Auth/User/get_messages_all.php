<?php
include_once '../../Config/conn.config.php';

$doctorId = $_GET['doctor_id'] ?? null;
$patientId = $_GET['user_id'] ?? null;

if (!$doctorId || !$patientId) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT 
            sender, 
            message AS content, 
            DATE_FORMAT(timestamp, '%h:%i %p') AS time
        FROM chat_messages
        WHERE doctor_id = :doctorId AND patient_id = :patientId
        ORDER BY timestamp ASC
    ");

    $stmt->execute([
        ':doctorId' => $doctorId,
        ':patientId' => $patientId
    ]);

    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($messages);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
