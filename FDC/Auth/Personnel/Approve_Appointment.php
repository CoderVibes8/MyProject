<?php
session_start();
require '../../Config/conn.config.php'; // adjust path

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['appointment_id'])) {
    $appointmentId = $_POST['appointment_id'];
    $userId = intval($_POST['user_id']);
    $docId = intval($_POST['doc_id']);
    $personnelId = intval($_POST['personnel_id']);

    try {
        $sql = "UPDATE appointment_schedule 
                SET stat = 'Approved' 
                WHERE appointment_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$appointmentId]);

        if ($stmt->rowCount() > 0) {

            // Get user info (optional, for nicer notification text)
            $userN = $conn->prepare("SELECT first_name, last_name FROM user_patient WHERE user_id = ?");
            $userN->execute([$userId]);
            $user = $userN->fetch(PDO::FETCH_ASSOC);

            // Notification message
            $notification = "Appointment with ID $appointmentId has been Approved.";
            if ($user) {
                $notification = "Your approved an appointment with Mr/Mrs. " . htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) . " (ID $appointmentId).";
            }

            $link = "http://localhost/FDC/Personnel/my_records.php";
            // Insert notification
            $insert = $conn->prepare("INSERT INTO doctor_notif (doc_id, doctor_id, user_id, description, link) VALUES (?, ?, ?, ?, ?)");
            $insert->execute([$personnelId, $docId, $userId, $notification, $link]);


            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Appointment not found or already cancelled."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
