<?php
require '../../Config/conn.config.php';
header('Content-Type: application/json');

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

$doctorId = $data['doctor_id'] ?? 0;
$slots    = $data['slots'] ?? [];

if ($doctorId == 0 || empty($slots)) {
    echo json_encode([
        'success' => false,
        'message' => '❌ Missing doctor or slots',
        'debug' => $data
    ]);
    exit();
}

try {
    // Prepare statements
    $checkStmt = $conn->prepare("
        SELECT COUNT(*) 
        FROM doctor_schedule 
        WHERE doc_id = :doctor_id 
          AND date_slots = :date_slots 
          AND start_time = :start_time 
          AND end_time = :end_time
    ");

    $insertStmt = $conn->prepare("
        INSERT INTO doctor_schedule 
        (doc_id, consultation_type, date_slots, start_time, end_time, availability) 
        VALUES (:doctor_id, :consult_type, :date_slots, :start_time, :end_time, 'Available')
    ");

    $inserted = 0;
    foreach ($slots as $slot) {
        $date = $slot['date_slots'];
        $start = $slot['start_time'];
        $end   = $slot['end_time'];
        $consultType = $slot['consult_type'] ?? 'General'; // optional fallback

        // Check if slot already exists
        $checkStmt->execute([
            ':doctor_id' => $doctorId,
            ':date_slots' => $date,
            ':start_time' => $start,
            ':end_time' => $end
        ]);

        if ($checkStmt->fetchColumn() == 0) {
            // Insert slot
            $insertStmt->execute([
                ':doctor_id' => $doctorId,
                ':consult_type' => $consultType,
                ':date_slots' => $date,
                ':start_time' => $start,
                ':end_time' => $end
            ]);
            $inserted++;
        }
    }

    echo json_encode([
        'success' => true,
        'inserted' => $inserted,
        'message' => "✅ $inserted slot(s) saved successfully"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'DB Error: ' . $e->getMessage(),
        'debug' => $data
    ]);
}
