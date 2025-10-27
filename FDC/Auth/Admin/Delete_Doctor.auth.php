<?php
session_start();
require_once "../../Config/conn.config.php";

if (isset($_GET['doctor_id']) && is_numeric($_GET['doctor_id'])) {
    $doctorId = (int) $_GET['doctor_id'];

    try {
        $stmt = $conn->prepare("DELETE FROM doctor_info WHERE doctor_id = :doctor_id");

        if ($stmt->execute([":doctor_id" => $doctorId])) {
            $_SESSION['message'] = [
                "title" => "Deleted!",
                "message" => "Doctor account has been removed successfully.",
                "type" => "success"
            ];
        } else {
            $_SESSION['message'] = [
                "title" => "Error!",
                "message" => "Something went wrong while deleting the doctor.",
                "type" => "error"
            ];
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = [
            "title" => "Database Error!",
            "message" => "Error deleting doctor: " . $e->getMessage(),
            "type" => "error"
        ];
    }
} else {
    $_SESSION['message'] = [
        "title" => "Invalid Request",
        "message" => "Doctor ID is missing or invalid.",
        "type" => "warning"
    ];
}

// Redirect back
header("Location: ../../Admin/DoctorList.admin.php");
exit();
