<?php
session_start();
require '../../Config/conn.config.php';
header('Content-Type: application/json');

$appointment_id = $_GET['id'] ?? null;

if (!$appointment_id) {
    echo json_encode(['error' => 'No appointment id']);
    exit;
}

try {
    $sql = "SELECT aps.appointment_id AS appointment_id, 
                    CONCAT(a.first_name, ' ', a.last_name) AS fullname, a.gender, a.contact, a.email,
                   aps.appointment_date, aps.appointment_time_start, aps.appointment_time_end,
                    a.total_expense, aps.stat AS status
            FROM appointment_schedule aps
            JOIN appointments a ON aps.appointment_id = a.appointment_id
            WHERE aps.appointment_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$appointment_id]);

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($data ?: ['error' => 'Not found']);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
