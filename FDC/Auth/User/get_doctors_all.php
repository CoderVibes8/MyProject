<?php
session_name('patient_session');
session_start();
include_once '../../Config/conn.config.php';

$patientId = $_SESSION['user_id'] ?? null;

if (!$patientId) {
    echo json_encode(['error' => 'Patient ID not found in session']);
    exit;
}

$query = "
    SELECT DISTINCT 
        d.doc_id AS doctor_id,
        CONCAT(d.firstname, ' ', d.lastname) AS name,
        d.profile_pic AS img,
        (
            SELECT 
                CASE 
                    WHEN m.sender = 'user' THEN CONCAT('You: ', m.message)
                    ELSE m.message
                END
            FROM chat_messages AS m
            WHERE m.doctor_id = d.doc_id
              AND m.patient_id = :patientId
            ORDER BY m.timestamp DESC
            LIMIT 1
        ) AS lastMessage,
        (
            SELECT DATE_FORMAT(m.timestamp, '%h:%i %p')
            FROM chat_messages AS m
            WHERE m.doctor_id = d.doc_id
              AND m.patient_id = :patientId
            ORDER BY m.timestamp DESC
            LIMIT 1
        ) AS lastMessageTime
    FROM doctor_personal_info AS d
    JOIN chat_messages AS cm ON cm.doctor_id = d.doc_id
    WHERE cm.patient_id = :patientId
    ORDER BY cm.timestamp DESC
";

$stmt = $conn->prepare($query);
$stmt->execute([':patientId' => $patientId]);

$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($doctors);
