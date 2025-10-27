<?php
session_start();
require_once "../../Config/conn.config.php";
require_once '../../Config/phpmailer/src/PHPMailer.php';
require_once '../../Config/phpmailer/src/SMTP.php';
require_once '../../Config/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['add_doctor'])) {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    $hashedPw = password_hash($password, PASSWORD_DEFAULT);

    try {


        // Check if email already exists
        $check_email = $conn->prepare("SELECT email FROM doctor_acc_creation WHERE email = ?");
        $check_email->execute([$email]);
        if ($check_email->rowCount() > 0) {
            $_SESSION["message"] = [
                "title" => "Error!",
                "message" => "Email is already registered.",
                "type" => "error"
            ];
            header("Location: ../../Admin/personnel_management.admin.php");
            exit();
        }

        $add_doctor = $conn->prepare("INSERT INTO doctor_acc_creation (email, password, opw) VALUES (:email, :password, :opw)");
        $add_doctor->execute([
            ":email" => $email,
            ":password" => $hashedPw,
            ":opw" => $hashedPw
        ]);

        // Send OTP Email
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'fldi.facton.ui@phinmaed.com';
        $mail->Password = 'elrw bkkx zqko rhhh';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('fldi.facton.ui@phinmaed.com', 'FDC: Doctor Account Creation');
        $mail->addAddress($email, 'Personnel');
        $mail->Subject = "Your Temporary Password";

        $mailContent = "<h2>Temporary Password:</h2>
                    <p>Your Temporary password is: $password</p>";

        $mail->isHTML(true);
        $mail->Body = $mailContent;
        $mail->send();

        $_SESSION['message'] = [
            "title" => "Account Created!",
            "message" => "Check your email for your temporary password.",
            "type" => "success"
        ];

        header("Location: ../../Admin/personnel_management.admin.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION["message"] = [
            "title" => "Error!",
            "message" => "Database error: " . $e->getMessage(),
            "type" => "error"
        ];
        header("Location: ../../Admin/personnel_management.admin.php");
        exit();
    }
}
