<?php
session_name("doctor_session");
session_start();
require_once "../../Config/conn.config.php";

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['login_doctor'])) {
    $email = strtolower(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
    $password = trim($_POST['password']);


    try {
        $retrieve_doctor = $conn->prepare("SELECT * FROM doctor_acc_creation WHERE email = :email LIMIT 1");
        $retrieve_doctor->bindParam(':email', $email);
        $retrieve_doctor->execute();

        $doctor_acc = $retrieve_doctor->fetch(PDO::FETCH_ASSOC);

        if (!$doctor_acc) {
            $_SESSION["message"] = [
                "title" => "Error!",
                "message" => "Check your email or password. Please try again.",
                "type" => "error"
            ];
            header("Location: ../../index.php");
            exit();
        }

        if (password_verify($password, $doctor_acc['password'])) {
            session_regenerate_id(true); // Security

            $_SESSION['doc_id'] = $doctor_acc['doc_id'];

            if ($doctor_acc['status'] === 'activated') {

                $update = $conn->prepare("UPDATE doctor_acc_creation SET isOnline = 'Online' WHERE doc_id = ?");
                $update->execute([$_SESSION['doc_id']]);

                $_SESSION["message"] = [
                    "title" => "Welcome!",
                    "message" => "Logged in Successfully.",
                    "type" => "success"
                ];
                header("Location: ../../Personnel/dashboard.personnel.php");
                exit();
            } elseif ($doctor_acc['status'] === 'not-activated') {
                $_SESSION["message"] = [
                    "title" => "Welcome!",
                    "message" => "Set up account now.",
                    "type" => "success"
                ];
                header("Location: ../../Personnel/account_setup.personnel.php");
                exit();
            } else {
                // fallback if status is something else
                $_SESSION["message"] = [
                    "title" => "Attention!",
                    "message" => "Please change your password.",
                    "type" => "warning"
                ];
                header("Location: ../../Personnel/change_pass.personnel.php");
                exit();
            }
        } else {
            // Password incorrect
            $_SESSION["message"] = [
                "title" => "Error!",
                "message" => "Invalid Email or Password. Please try again.",
                "type" => "error"
            ];
            header("Location: ../../index.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    $_SESSION["message"] = [
        "title" => "Error!",
        "message" => "Something went wrong.",
        "type" => "error"
    ];
    header("Location: ../../index.php");
    exit();
}
