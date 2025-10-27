<?php
session_start();
require '../../Config/conn.config.php';


$user_id = $_GET['user_id'] ?? null;
$appointment_id = $_GET['id'] ?? null;

if (!$appointment_id) {
    echo json_encode(['error' => 'No appointment id']);
    exit;
}

try {
    $sql = "SELECT aps.appointment_id AS appointment_id, 
                    CONCAT(aps.doc_fn, ' ', aps.doc_ln) AS doctor_name,
                    DATE_FORMAT(TIMEDIFF(aps.appointment_time_end, aps.appointment_time_start), '%i:%s') AS duration_time,
                    aps.appointment_date, aps.appointment_time_start, aps.appointment_time_end, aps.specialty,
                    a.total_expense, aps.stat AS status
            FROM appointment_schedule aps
            JOIN appointments a ON aps.appointment_id = a.appointment_id
            WHERE aps.appointment_id = ? AND a.user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$appointment_id, $user_id]);

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // ✅ Add this block before output
    header('Content-Type: application/json');
    echo json_encode($data ?: ['error' => 'Not found']);
    exit;
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
