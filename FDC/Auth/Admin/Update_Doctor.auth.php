<?php
session_start();
require_once "../../Config/conn.config.php";

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['update_doctor'])) {
    $doctorId  = intval($_POST['doctor_id']);
    $firstname = trim($_POST['firstname']);
    $lastname  = trim($_POST['lastname']);
    $specialty = trim($_POST['specialty']);
    $price     = trim($_POST['price']);

    try {
        $stmt = $conn->prepare("
            UPDATE doctor_info 
            SET firstname = :firstname, lastname = :lastname, specialty = :specialty, price = :price 
            WHERE doctor_id = :doctor_id
        ");

        $updated = $stmt->execute([
            ":firstname" => $firstname,
            ":lastname"  => $lastname, 
            ":specialty" => $specialty,
            ":price"     => $price,
            ":doctor_id" => $doctorId
        ]);

        if ($updated) {
            $_SESSION['message'] = [
                "title" => "Updated!",
                "message" => "Doctor information has been updated successfully.",
                "type" => "success"
            ];
        } else {
            $_SESSION['message'] = [
                "title" => "Error!",
                "message" => "Failed to update doctor information.",
                "type" => "error"
            ];
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = [
            "title" => "Database Error!",
            "message" => "Error updating doctor: " . $e->getMessage(),
            "type" => "error"
        ];
    }
} else {
    $_SESSION['message'] = [
        "title" => "Invalid Request",
        "message" => "Form submission error.",
        "type" => "warning"
    ];
}

// Redirect back to doctor list
header("Location: ../../Admin/DoctorList.admin.php");
exit();
