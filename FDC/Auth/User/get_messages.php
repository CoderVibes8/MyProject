<?php
include_once '../../Config/conn.config.php';
session_name('patient_session');
session_start();

// Get doctor_id from query string (sent by JS)
$doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : null;

// Get patient_id from logged-in session
$patient_id = $_SESSION['user_id'] ?? null;

// Optional: fetch only messages after this ID (for incremental loading)
$after_id = isset($_GET['after_id']) ? intval($_GET['after_id']) : 0;

header('Content-Type: application/json');

if ($doctor_id && $patient_id) {
    // Base query
    $query = "
        SELECT id, sender, message, timestamp 
        FROM chat_messages 
        WHERE doctor_id = :doctor_id AND patient_id = :patient_id
    ";

    $params = [
        ':doctor_id' => $doctor_id,
        ':patient_id' => $patient_id
    ];

    // Incremental filter
    if ($after_id > 0) {
        $query .= " AND id > :after_id";
        $params[':after_id'] = $after_id;
    }

    $query .= " ORDER BY timestamp ASC";

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $formatted = array_map(function($msg) {
            return [
                'id' => (int)$msg['id'],              // needed for incremental fetch
                'from' => $msg['sender'],  
                'text' => $msg['message'],
                'time' => date('h:i A', strtotime($msg['timestamp']))
            ];
        }, $messages);

        echo json_encode($formatted);

    } catch (PDOException $e) {
        // Return empty array on error
        echo json_encode([]);
    }

} else {
    echo json_encode([]);
}
