<?php
session_start();
require_once "../../Config/conn.config.php";


if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['add_doctor'])) {
    $fn = htmlspecialchars($_POST['firstname']);
    $ln = htmlspecialchars($_POST['lastname']);
    $specialty = htmlspecialchars($_POST['specialty']);
    $price = htmlspecialchars($_POST['price']);

    try {
        $add_doctor = $conn->prepare("INSERT INTO doctor_info (firstname, lastname, specialty, price) VALUES (:firstname, :lastname, :specialty , :price)");
        $add_doctor->execute([
            ":firstname" => $fn,
            ":lastname" => $ln,
            ":specialty" => $specialty,
            ":price" => $price
        ]);

        $_SESSION['message'] = [
            "title" => "Doctor Info Added!",
            "message" => "Doctor information has been successfully added.",
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
