<?php
session_name("patient_session");
session_start();

include_once '../../Config/conn.config.php';

if (isset($_POST['register-patient'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $contact_number = trim($_POST['contact_number']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['cpw'];

    if ($password !== $confirm_password) {
        $_SESSION["message"] = [
            "title" => "Password Mismatch!",
            "message" => "Passwords do not match! Please try again.",
            "type" => "error"
        ];
        header("Location: ../../index.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("SELECT * FROM user_patient WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $_SESSION["message"] = [
                "title" => "Email Already Registered!",
                "message" => "Please use a different email address.",
                "type" => "error"
            ];
            header("Location: ../../index.php");
            exit();
        }

        $sql = "INSERT INTO user_patient 
                (first_name, last_name, dob, home_address, gender, email, contact_number, role, isOnline, password)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'patient', 'Offline', ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$first_name, $last_name, $dob, $address, $gender, $email, $contact_number, $hashed_password]);

        $_SESSION["message"] = [
            "title" => "Inserted Successfully!",
            "message" => "You can now log in to your account.",
            "type" => "success"
        ];
        header("Location: ../../index.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION["message"] = [
            "title" => "Error!",
            "message" => "Something went wrong. Please try again.",
            "type" => "error"
        ];
        header("Location: ../../index.php");
        exit();
    }
} else {
    header("Location: ../../index.php");
    exit();
}
