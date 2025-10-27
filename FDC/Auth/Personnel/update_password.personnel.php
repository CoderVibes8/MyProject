<?php
session_name("doctor_session");
session_start();
require_once "../../Config/conn.config.php";

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['update_doctor_pass'])) {
    $email = htmlspecialchars($_POST['email']);
    $new_password = trim($_POST['password']);
    $doc_id = $_SESSION['doc_id'];

    try {
        $stmt = $conn->prepare("SELECT password FROM doctor_acc_creation WHERE doc_id = :doc_id");
        $stmt->bindParam(":doc_id", $doc_id);
        $stmt->execute();
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$doctor) {
            throw new Exception("Doctor not found.");
        }

        if (password_verify($new_password, $doctor['password'])) {
            $_SESSION["message"] = [
                "title" => "Error!",
                "message" => "New password cannot be the same as the old password.",
                "type" => "error"
            ];
            header("Location: ../../Personnel/change_pass.personnel.php");
            exit();
        }

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_doctor_acc = $conn->prepare("UPDATE doctor_acc_creation SET password = :password WHERE doc_id = :doc_id");
        $update_doctor_acc->execute([
            ":password" => $hashed_password,
            ":doc_id" => $doc_id]);

        $_SESSION["message"] = [
            "title" => "Success!",
            "message" => "Account Activated Successfully.",
            "type" => "success"
        ];
        header("Location: ../../Personnel/account_setup.personnel.php");
        exit();
    } catch (Exception $e) {
        $_SESSION["message"] = [
            "title" => "Error!",
            "message" => "Something went wrong. " . $e->getMessage(),
            "type" => "error"
        ];
        header("Location: ../../Personnel/change_pass.personnel.php");
        exit();
    }
} else {
    $_SESSION["message"] = [
        "title" => "Error!",
        "message" => "Invalid request.",
        "type" => "error"
    ];
    header("Location: ../../index.php");
    exit();
}
