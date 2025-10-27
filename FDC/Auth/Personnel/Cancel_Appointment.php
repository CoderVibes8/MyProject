<?php
require '../../Config/conn.config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'], $_POST['user_id'], $_POST['doc_id'])) {
    $appointmentId = intval($_POST['appointment_id']);
    $userId = intval($_POST['user_id']);
    $docId = intval($_POST['doc_id']);

    try {
        // Cancel the appointment
        $stmt = $conn->prepare("UPDATE appointment_schedule SET stat = 'Cancelled' WHERE appointment_id = ? AND stat != 'Done' ");
        $stmt->execute([$appointmentId]);

        if ($stmt->rowCount() > 0) {

            // Get doctor info (optional, for nicer notification text)
            $docN = $conn->prepare("SELECT firstname, lastname FROM doctor_personal_info WHERE doc_id = ?");
            $docN->execute([$docId]);
            $doctor = $docN->fetch(PDO::FETCH_ASSOC);

            // Notification message
            $notification = "Appointment with ID $appointmentId has been cancelled.";
            if ($doctor) {
                $notification = "Your appointment with Dr. " . htmlspecialchars($doctor['firstname'] . ' ' . $doctor['lastname']) . " (ID $appointmentId) has been cancelled.";
            }

            $link = "http://localhost/FDC/User/appointment_schedule.php";
            // Insert notification
            $insert = $conn->prepare("INSERT INTO user_notif (doc_id, user_id, description, link) VALUES (?, ?, ?, ?)");
            $insert->execute([$docId, $userId, $notification, $link]);

            // $update = $conn->prepare("UPDATE user_notif SET isRead = 1 WHERE user_id = ? AND doc_id = ?");
            // $update->execute([$userId, $docId]);

            echo json_encode(["success" => true]);

        } else {
            echo json_encode(["success" => false, "message" => "Appointment not found or already cancelled."]);
        }

    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
