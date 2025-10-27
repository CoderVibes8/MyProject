<?php
session_name('doctor_session');
session_start();
require_once '../../Config/conn.config.php';

header('Content-Type: application/json');

// Ensure doctor is logged in
if (!isset($_SESSION['doc_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$doctor_id = $_SESSION['doc_id'];

try {
    // ✅ Fetch only patients who have existing chat messages with this doctor
    $stmt = $conn->prepare("
        SELECT DISTINCT 
            u.user_id AS patient_id,
            CONCAT(u.first_name, ' ', u.last_name) AS name,
            uc.profile_picture,
            u.isOnline
        FROM user_patient u
        INNER JOIN user_credentials uc ON uc.user_id = u.user_id
        INNER JOIN chat_messages cm ON cm.patient_id = u.user_id
        WHERE cm.doctor_id = ?
        ORDER BY name ASC
    ");
    $stmt->execute([$doctor_id]);
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];

    foreach ($patients as $p) {
        // ✅ Get last message (if any)
        $msgStmt = $conn->prepare("
            SELECT sender, message, timestamp
            FROM chat_messages
            WHERE doctor_id = ? AND patient_id = ?
            ORDER BY timestamp DESC LIMIT 1
        ");
        $msgStmt->execute([$doctor_id, $p['patient_id']]);
        $lastMsg = $msgStmt->fetch(PDO::FETCH_ASSOC);

        // Safely create initials
        $names = explode(' ', trim($p['name']));
        $initials = strtoupper(substr($names[0] ?? '', 0, 1)) .
                    (isset($names[1]) ? strtoupper(substr($names[1], 0, 1)) : '');

        $result[] = [
            'patient_id' => $p['patient_id'],
            'name' => $p['name'],
            'profile' => $p['profile_picture'] ?: 'user.png',
            'initials' => $initials,
            'isOnline' => $p['isOnline'] === 'Online' ? 'Online' : 'Offline',
            'lastMsg' => $lastMsg['message'] ?? 'No messages yet',
            'lastMsgFrom' => $lastMsg['sender'] ?? null,
            'time' => $lastMsg ? date('g:i A', strtotime($lastMsg['timestamp'])) : ''
        ];
    }

    // ✅ Sort by latest message (newest chats first)
    usort($result, function ($a, $b) {
        $timeA = strtotime($a['time'] ?: '1970-01-01 00:00:00');
        $timeB = strtotime($b['time'] ?: '1970-01-01 00:00:00');
        return $timeB - $timeA;
    });

    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
