<?php
session_name("patient_session");
session_start();
require_once '../../Config/conn.config.php';
require '../../Config/phpmailer/src/PHPMailer.php';
require '../../Config/phpmailer/src/SMTP.php';
require '../../Config/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    $_SESSION['message'] = [
        "title" => "Error!",
        "message" => "Unauthorized access.",
        "type" => "error"
    ];
    header("Location: ../../User/clinic_page.patient.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];
$otp = mt_rand(100000, 999999);
$otp_created_at = date('Y-m-d H:i:s');

// Update OTP in the database
$stmt = $conn->prepare("UPDATE clinic_appointments SET otp = ?, otp_created_at = ? WHERE user_id = ?");
if (!$stmt->execute([$otp, $otp_created_at, $user_id])) {
    $_SESSION['message'] = [
        "title" => "Error!",
        "message" => "Failed to update OTP.",
        "type" => "error"
    ];
    header("Location: ../../User/clinic_page.patient.php");
    exit();
}

// Send OTP via email
try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'fldi.facton.ui@phinmaed.com';
    $mail->Password = 'elrw bkkx zqko rhhh';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('fldi.facton.ui@phinmaed.com', 'HealthNet Appointments');
    $mail->addAddress($email);
    $mail->Subject = "Your OTP Code for Appointment Verification";
    $mail->isHTML(true);
    $mail->Body = "<h2>OTP Verification</h2>
                   <p>Your OTP code is: <strong>$otp</strong></p>
                   <p>Please enter this code to verify your appointment.</p>";

    if ($mail->send()) {
        $_SESSION['message'] = [
            "title" => "Success!",
            "message" => "A new OTP has been sent to your email.",
            "type" => "success"
        ];
    } else {
        throw new Exception("Failed to send OTP email.");
    }
} catch (Exception $e) {
    $_SESSION['message'] = [
        "title" => "Error!",
        "message" => "Something went wrong: " . $e->getMessage(),
        "type" => "error"
    ];
}

header("Location: ../../User/clinic_page.patient.php");
exit();
