<?php
session_name("doctor_session");
session_start();
include_once '../../Config/conn.config.php';

$doctorId = $_SESSION['doc_id'] ?? null;

if (!$doctorId) {
    echo json_encode(['error' => 'Doctor ID not found in session']);
    exit;
}

$stmt = $conn->prepare("
    SELECT DISTINCT 
        users.user_id,
        CONCAT(users.first_name, ' ', users.last_name) AS name,
        credentials.profile_picture AS img,
        (
            SELECT 
                CASE 
                    WHEN m.sender = 'doctor' THEN CONCAT('You: ', m.message)
                    ELSE m.message
                END
            FROM chat_messages AS m
            WHERE m.doctor_id = :doctorId
              AND m.patient_id = users.user_id
            ORDER BY m.timestamp DESC
            LIMIT 1
        ) AS lastMessage,
        (
            SELECT DATE_FORMAT(m.timestamp, '%h:%i %p')
            FROM chat_messages AS m
            WHERE m.doctor_id = :doctorId2
              AND m.patient_id = users.user_id
            ORDER BY m.timestamp DESC
            LIMIT 1
        ) AS lastMessageTime
    FROM user_patient AS users
    LEFT JOIN user_credentials AS credentials ON users.user_id = credentials.user_id
    JOIN chat_messages AS messages ON messages.patient_id = users.user_id
    WHERE messages.doctor_id = :doctorId3
    ORDER BY messages.timestamp DESC
");

$stmt->execute([
    ':doctorId' => $doctorId,
    ':doctorId2' => $doctorId,
    ':doctorId3' => $doctorId,
]);

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($users);
