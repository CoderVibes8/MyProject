<?php
include_once '../../Config/conn.config.php';
session_name('doctor_session');
session_start();

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$patient_id = $_GET['patient_id'] ?? null;
$after_id = $_GET['after_id'] ?? 0; // fetch messages with id > after_id
$doctor_id = $_SESSION['doc_id'] ?? null;

if (!$doctor_id) {
    echo json_encode(['error' => 'Doctor not logged in']);
    exit;
}

if (!$patient_id) {
    echo json_encode(['error' => 'Missing patient_id']);
    exit;
}

try {
    if ($after_id > 0) {
        $stmt = $conn->prepare("
            SELECT id, sender, message, timestamp 
            FROM chat_messages 
            WHERE doctor_id = ? AND patient_id = ? AND id > ?
            ORDER BY timestamp ASC
        ");
        $stmt->execute([$doctor_id, $patient_id, $after_id]);
    } else {
        $stmt = $conn->prepare("
            SELECT id, sender, message, timestamp 
            FROM chat_messages 
            WHERE doctor_id = ? AND patient_id = ?
            ORDER BY timestamp ASC
        ");
        $stmt->execute([$doctor_id, $patient_id]);
    }

    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $formatted = array_map(function($msg) {
        return [
            'id'   => (int)$msg['id'],
            'from' => strtolower($msg['sender']), // 'user' or 'personnel'
            'text' => $msg['message'],
            'time' => date('h:i A', strtotime($msg['timestamp']))
        ];
    }, $messages);

    echo json_encode($formatted);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
