<?php
session_name('patient_session');
session_start();
require_once '../../Config/conn.config.php';
require '../../Config/phpmailer/src/PHPMailer.php';
require '../../Config/phpmailer/src/SMTP.php';
require '../../Config/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_appointment'])) {

    if (!isset($_SESSION['user_id'])) {
        $_SESSION['message'] = [
            "title" => "Error!",
            "message" => "You must be logged in to book an appointment.",
            "type" => "error"
        ];
        header("Location: ../../User/dashboard.patient.php");
        exit();
    }

    $user_id        = $_SESSION['user_id'];
    $doc_id         = htmlspecialchars(trim($_POST['doc_id']));
    $first_name     = htmlspecialchars(trim($_POST['first_name']));
    $last_name      = htmlspecialchars(trim($_POST['last_name']));
    $gender         = $_POST['gender'];
    $email          = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $contact_number = htmlspecialchars(trim($_POST['contact_number']));
    $doctor         = htmlspecialchars(trim($_POST['doctor']));
    $total_expense  = htmlspecialchars($_POST['total_expense']);
    $otp            = mt_rand(100000, 999999);
    date_default_timezone_set('Asia/Manila');
    $otp_created_at = date('Y-m-d H:i:s');

    // Handle custom date
    $date = !empty($_POST['custom_date']) ? $_POST['custom_date'] : $_POST['date'];

    // Handle custom time
    if (!empty($_POST['custom_time_start'])) {
        // JS sends start|end
        $time = $_POST['custom_time_start'];
    } else {
        $time = $_POST['time'];
    }

    // Mark as custom if either custom date or custom time is provided
    $isCustomed = (!empty($_POST['custom_date']) || !empty($_POST['custom_time_start'])) ? 1 : 0;

    // Optional reason
    $reason = isset($_POST['reason']) ? htmlspecialchars(trim($_POST['reason'])) : '';

    if (!$doc_id || !$first_name || !$last_name || !$email || !$contact_number || !$date || !$time || !$total_expense || !$doctor) {
        $_SESSION['message'] = [
            "title" => "Error!",
            "message" => "All required fields must be filled.",
            "type" => "error"
        ];
        header("Location: ../../User/dashboard.patient.php");
        exit();
    }

    try {
        // Send OTP Email
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'fdc.healthcare@gmail.com';
        $mail->Password   = 'fpmn bvhh abum bqbu';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('fdc.healthcare@gmail.com', 'HealthNet Appointments');
        $mail->addAddress($email, $first_name);
        $mail->Subject = "Your OTP for Appointment Confirmation";

        $mailContent = "<h2>Appointment OTP Confirmation</h2>
                        <p>Dear $first_name $last_name,</p>
                        <p>Your OTP is: <b>$otp</b>. It expires in 5 minutes.</p>
                        <p>Please enter this OTP to confirm your appointment.</p>";

        $mail->isHTML(true);
        $mail->Body = $mailContent;

        if ($mail->send()) {
            $_SESSION['appointment_data'] = [
                "user_id"        => $user_id,
                "doc_id"         => $doc_id,
                "first_name"     => $first_name,
                "last_name"      => $last_name,
                "gender"         => $gender,
                "email"          => $email,
                "contact_number" => $contact_number,
                "date"           => $date,
                "time"           => $time,
                "reason"         => $reason,
                "doctor"         => $doctor,
                "total_expense"  => $total_expense,
                "isCustomed"     => $isCustomed,   // ✅ added here
                "otp"            => $otp,
                "otp_created_at" => $otp_created_at
            ];

            $_SESSION['message'] = [
                "title" => "OTP Sent!",
                "message" => "Check your email to verify your OTP.",
                "type" => "success"
            ];

            header("Location: ../../User/verify_otp.patient.php");
            exit();
        } else {
            throw new Exception("Email could not be sent.");
        }
    } catch (Exception $e) {
        $_SESSION['message'] = [
            "title" => "Error!",
            "message" => "Something went wrong: " . $e->getMessage(),
            "type" => "error"
        ];
        header("Location: ../../User/dashboard.patient.php");
        exit();
    }
} else {
    $_SESSION['message'] = [
        "title" => "Error!",
        "message" => "Invalid request.",
        "type" => "error"
    ];
    header("Location: ../../User/dashboard.patient.php");
    exit();
}
