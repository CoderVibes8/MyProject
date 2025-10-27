<?php
include_once "../../Config/conn.config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clinic_id = $_POST["doctor_id"];
    $status = $_POST["status"];

    $stmt = $conn->prepare("UPDATE doctor_account SET status = ? WHERE doctor_id = ?");
    $stmt->execute([$status, $clinic_id]);

    $_SESSION["message"] = [
        "title" => "Doctor Registration Approved!",
        "message" => "Doctor can now use their accounts!",
        "type" => "success"
    ];

    header("Location: ../../Admin/doctor_management"); 
    exit();
}
?>
