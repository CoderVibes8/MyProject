<?php
session_name("patient_session");
session_start();
require_once "../../Config/conn.config.php";


if (isset($_POST['patient-login'])) {

    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $pw = filter_input(INPUT_POST, "pw", FILTER_SANITIZE_SPECIAL_CHARS);

    try {

        $retrieve_patient = $conn->prepare("SELECT * FROM user_patient WHERE email = :email AND status = 'activated' LIMIT 1");
        $retrieve_patient->bindParam(':email', $email);
        $retrieve_patient->execute();

        $user = $retrieve_patient->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $_SESSION["message"] = [
                "title" => "Error!",
                "message" => "Invalid Email or You are not activated. Please try again.",
                "type" => "error"
            ];
            header("Location: ../../index.php");
            exit();
        }


        if ($user && password_verify($pw, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['user_id'];

            $update = $conn->prepare("UPDATE user_patient SET isOnline = 'Online' WHERE user_id = ?");
            $update->execute([$_SESSION['user_id']]);

            $_SESSION["message"] = [
                "title" => "Welcome!",
                "message" => "Logged in Successfully.",
                "type" => "success"
            ];

            header("Location: ../../User/dashboard.patient.php");
            exit();
        } else {
            $_SESSION["message"] = [
                "title" => "Error!",
                "message" => "Invalid Input! Please Try Again.",
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
