<?php
// fetch_contacts.php
header('Content-Type: application/json');
require_once '../../Config/conn.config.php';

$patient_id = $_GET['patient_id'] ?? null;

try {
    // Fetch all clinic moderators
    $stmt = $conn->query("SELECT dpi.doc_id, CONCAT(dpi.firstname, ' ', dpi.lastname) AS name, dac.isOnline, dpi.profile_pic AS profile FROM doctor_personal_info dpi LEFT JOIN doctor_acc_creation dac ON dpi.doc_id = dac.doc_id ORDER BY dac.status DESC, name ASC");
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];

    foreach ($doctors as $d) {
        // Get last message for this patient
        $lastMsgStmt = $conn->prepare("
            SELECT sender, message, timestamp AS time_sent
            FROM chat_messages 
            WHERE doctor_id = ? AND patient_id = ?
            ORDER BY time_sent DESC 
            LIMIT 1
        ");
        $lastMsgStmt->execute([$d['doc_id'], $patient_id]);
        $lastMsg = $lastMsgStmt->fetch(PDO::FETCH_ASSOC);

        // Get all messages for this patient
        $msgStmt = $conn->prepare("
            SELECT sender, message, timestamp AS time_sent
            FROM chat_messages 
            WHERE doctor_id = ? AND patient_id = ?
            ORDER BY time_sent ASC
        ");
        $msgStmt->execute([$d['doc_id'], $patient_id]);
        $messages = $msgStmt->fetchAll(PDO::FETCH_ASSOC);

        // Compute initials if no profile image
        $initials = strtoupper(substr($d['name'], 0, 1)) . strtoupper(substr(strrchr($d['name'], ' '), 1, 1));

        $result[] = [
            'doc_id' => $d['doc_id'],
            'name' => $d['name'],
            'role' => 'Clinic Moderator',
            'isOnline' => ($d['isOnline'] == 'Online' ? 'Online' : 'Offline'),
            'profile' => $d['profile'],
            'initials' => $initials,
            'lastMsg' => $lastMsg ? $lastMsg['message'] : 'No messages yet',
            'time' => $lastMsg ? date('g:i A', strtotime($lastMsg['time_sent'])) : '',
            'messages' => array_map(fn($m) => [
                'from' => $m['sender'],
                'text' => $m['message'],
                'time' => date('g:i A', strtotime($m['time_sent']))
            ], $messages)
        ];
    }

    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
