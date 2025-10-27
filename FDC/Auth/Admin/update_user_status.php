<?php
include_once "../../Config/conn.config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST["user_id"];
    $status = $_POST["status"];

    if ($status === 'approved') {

        $update = $conn->prepare("UPDATE user_patient SET status = ? WHERE user_id = ?");
        $update->execute([$status, $user_id]);

        $_SESSION["message"] = [
            "title" => "User Registration Approved!",
            "message" => "User can now use their accounts!",
            "type" => "success"
        ];

        header("Location: ../../Admin/user_management.admin.php");
        exit();
    }else if ($status === 'rejected'){
        $update = $conn->prepare("UPDATE user_patient SET status = ? WHERE user_id = ?");
        $update->execute([$status, $user_id]);

        $_SESSION["message"] = [
            "title" => "User Registration Rejected!",
            "message" => "User cannot use their accounts!",
            "type" => "error"
        ];

        header("Location: ../../Admin/user_management.admin.php");
        exit();
    }
} else {
    $_SESSION["message"] = [
        "title" => "User Registration Declined!",
        "message" => "Something went wrong!",
        "type" => "error"
    ];

    header("Location: ../../Admin/user_management.admin.php");
    exit();
}
