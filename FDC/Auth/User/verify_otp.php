<?php
session_name("patient_session");
session_start();
include("../../Config/conn.config.php");

require '../../Config/phpmailer/src/PHPMailer.php';
require '../../Config/phpmailer/src/SMTP.php';
require '../../Config/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = $_POST['otp1'] . $_POST['otp2'] . $_POST['otp3'] . $_POST['otp4'] . $_POST['otp5'] . $_POST['otp6'];

    if (!isset($_SESSION['appointment_data'])) {
        $_SESSION['otp_message'] = "Session expired. Please book again.";
        header("Location: ../../User/dashboard.patient.php");
        exit();
    }

    $appointment = $_SESSION['appointment_data'];
    $stored_otp = $appointment['otp'];

    if ($user_otp == $stored_otp) {
        $_SESSION['message'] = ["title" => "Success!", "message" => "Appointment confirmed!", "type" => "success"];

        try {
            // Split start and end times
            if (isset($appointment['time']) && strpos($appointment['time'], '|') !== false) {
                list($start_time, $end_time) = explode('|', $appointment['time']);
            } else {
                $start_time = $appointment['time'];
                switch ($appointment['appointment_type'] ?? '') {
                    case 'Ultra Sound':
                        $end_time = date('H:i:s', strtotime($start_time . ' +15 minutes'));
                        break;
                    default:
                        $end_time = date('H:i:s', strtotime($start_time . ' +1 hour'));
                }
                $appointment['time'] = $start_time . '|' . $end_time;
            }

            // Format date & time for email
            $formattedDate = date("F j, Y", strtotime($appointment['date']));
            $formattedStart = date("g:i A", strtotime($start_time));
            $formattedEnd = date("g:i A", strtotime($end_time));
            $formattedTime = "$formattedStart - $formattedEnd";


            // Insert into appointments table with isCustomed
            $sql = "INSERT INTO appointments 
                (user_id, doc_id, first_name, last_name, gender, contact, email, appointment_date, appointment_time, total_expense, doctor, status, otp, otp_created_at, isCustomed) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                $appointment['user_id'],
                $appointment['doc_id'],
                $appointment['first_name'],
                $appointment['last_name'],
                $appointment['gender'],
                $appointment['contact_number'],
                $appointment['email'],
                $appointment['date'],
                $appointment['time'],
                $appointment['total_expense'],
                $appointment['doctor'],
                'Pending',
                $appointment['otp'],
                $appointment['otp_created_at'],
                $appointment['isCustomed'] ?? 0
            ]);

            $appointment_id = $conn->lastInsertId();

            // Get doctor info
            $sqlDoc = "SELECT firstname, lastname, specialty FROM doctor_info WHERE doctor_id = ?";
            $stmtDoc = $conn->prepare($sqlDoc);
            $stmtDoc->execute([$appointment['doc_id']]);
            $doctor = $stmtDoc->fetch(PDO::FETCH_ASSOC);

            // Insert into appointment_schedule
            $sqlSched = "INSERT INTO appointment_schedule
                        (appointment_id, doc_id, user_id, appointment_date, appointment_time_start, appointment_time_end, doc_fn, doc_ln, specialty)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtSched = $conn->prepare($sqlSched);
            $stmtSched->execute([
                $appointment_id,
                $appointment['doc_id'],
                $appointment['user_id'],
                $appointment['date'],
                $start_time,
                $end_time,
                $doctor['firstname'],
                $doctor['lastname'],
                $doctor['specialty']
            ]);

            // Update doctor schedule availability
            $sqlUpdate = "UPDATE doctor_schedule 
                          SET availability = 'Booked' 
                          WHERE doc_id = ? AND date_slots = ? AND start_time = ? AND end_time = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->execute([$appointment['doc_id'], $appointment['date'], $start_time, $end_time]);

            // Update appointment status
            $status = ($appointment['isCustomed'] ?? 0) == 1 ? "Pending" : "Approved";
            $sqlStatus = $conn->prepare("UPDATE appointment_schedule SET stat = ? WHERE appointment_id = ?");
            $sqlStatus->execute([$status, $appointment_id]);

            if ($status == "Approved") {
                // Insert notification for user
                $appointmentDate = date("D, M j", strtotime($appointment['date']));
                $description = "Your appointment with Dr. {$doctor['firstname']} {$doctor['lastname']} on {$appointmentDate} at {$formattedStart}-{$formattedEnd} has been confirmed.";
                $link = "http://localhost/FDC/User/appointment_schedule.php";
                $insertNotif = $conn->prepare("INSERT INTO user_notif (doc_id, user_id, description, link) VALUES (?, ?, ?, ?)");
                $insertNotif->execute([$appointment['doc_id'], $appointment['user_id'], $description, $link]);
            } else {
                // Insert notification for user
                $appointmentDate = date("D, M j", strtotime($appointment['date']));
                $description = "Your appointment with Dr. {$doctor['firstname']} {$doctor['lastname']} on {$appointmentDate} at {$formattedStart}-{$formattedEnd} is Pending. Please wait for the confirmation.";
                $link = "http://localhost/FDC/User/appointment_schedule.php";
                $insertNotif = $conn->prepare("INSERT INTO user_notif (doc_id, user_id, description, link) VALUES (?, ?, ?, ?)");
                $insertNotif->execute([$appointment['doc_id'], $appointment['user_id'], $description, $link]);
            }

            // Send confirmation email
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'fdc.healthcare@gmail.com';
            $mail->Password = 'fpmn bvhh abum bqbu';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('fdc.healthcare@gmail.com', 'HealthNet Appointments');
            $mail->addAddress($appointment['email'], $appointment['first_name']);
            $mail->Subject = "Appointment Confirmation - {$appointment['doctor']}";

            $mailContent = "
                <h2>Appointment Confirmation</h2>
                <p>Dear {$appointment['first_name']} {$appointment['last_name']},</p>
                <p>Your appointment has been confirmed.</p>
                <h3>Appointment Details:</h3>
                <ul>
                    <li><strong>Patient Name:</strong> {$appointment['first_name']} {$appointment['last_name']}</li>
                    <li><strong>Gender:</strong> {$appointment['gender']}</li>
                    <li><strong>Date:</strong> $formattedDate</li>
                    <li><strong>Time:</strong> $formattedTime</li>
                    <li><strong>Total Expense:</strong> {$appointment['total_expense']}</li>
                    <li><strong>Custom Date/Time:</strong> " . (($appointment['isCustomed'] ?? 0) ? "Yes" : "No") . "</li>
                </ul>
                <p>Please wait for the approval of the doctor.</p>
                <p>Thank you for choosing <strong>HealthNet</strong>.</p>
            ";

            $mail->isHTML(true);
            $mail->Body = $mailContent;
            $mail->send();

            unset($_SESSION['appointment_data']); // clear session
            header("Location: ../../User/appointment_schedule.php");
            exit();
        } catch (PDOException | Exception $e) {
            $_SESSION['message'] = ["title" => "Error!", "message" => "Failed to book appointment: " . $e->getMessage(), "type" => "danger"];
            header("Location: ../../User/dashboard.patient.php");
            exit();
        }
    } else {
        $_SESSION['otp_message'] = "Invalid OTP. Please try again.";
        header("Location: ../../User/dashboard.patient.php");
        exit();
    }
}
