<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer from downloaded folder (GitHub version)
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // SMTP Server (e.g., Gmail)
    $mail->SMTPAuth   = true;
    $mail->Username   = 'fldi.facton.ui@phinmaed.com'; // Your email
    $mail->Password   = 'daukmaphherighmh'; // Use an App Password if using Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS
    $mail->Port       = 587; // SMTP Port (Gmail uses 587)

    // Email Details
    $mail->setFrom('fldi.facton.ui@phinmaed.com', 'Florence Dinong Pogi');
    $mail->addAddress('florencefactondev@gmail.com', 'Florence Factonnnnn'); // Add recipient
    $mail->Subject = 'Test Email Using PHPMailer';
    $mail->Body    = 'This is a test email sent using PHPMailer with SMTP.';

    // Send Email
    $mail->send();
    echo 'Email sent successfully!';
} catch (Exception $e) {
    echo "Email could not be sent. Error: {$mail->ErrorInfo}";
}
?>
