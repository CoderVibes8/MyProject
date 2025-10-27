<?php
session_name("doctor_session");
session_start();
require_once('../../Config/conn.config.php');

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (isset($_SESSION['doc_id'])) {
        $doc_id = $_SESSION['doc_id'];
    } else {
        $_SESSION["message"] = [
            "title" => "Error!",
            "message" => "Doctor ID not found.",
            "type" => "error"
        ];
        header("Location: ../../Personnel/doctor_profile.php");
        exit();
    }

    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $specialty = htmlspecialchars(trim($_POST['specialty']));
    $yoe = htmlspecialchars(trim($_POST['yoe']));
    $onsite_rate = htmlspecialchars(trim($_POST['onsite_rate']));
    $online_rate = htmlspecialchars(trim($_POST['online_rate']));
    $profile_picture = '';

    // Handle profile picture upload if provided
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "../../uploads/";
        $file_name = time() . '_' . basename($_FILES["profile_picture"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture = $file_name;
            } else {
                $_SESSION["message"] = [
                    "title" => "Error!",
                    "message" => "Failed to upload profile picture.",
                    "type" => "error"
                ];
                header("Location: ../../Personnel/doctor_profile.php");
                exit();
            }
        } else {
            $_SESSION["message"] = [
                "title" => "Error!",
                "message" => "Invalid file type. Allowed: JPG, JPEG, PNG, GIF.",
                "type" => "error"
            ];
            header("Location: ../../Personnel/doctor_profile.php");
            exit();
        }
    }

    try {
        // Update doctor profile info
        $updateProfile = "UPDATE doctor_acc_creation dac 
                          JOIN doctor_personal_info dpi ON dac.doc_id = dpi.doc_id
                          SET dpi.firstname = ?, 
                              dpi.lastname = ?, 
                              dpi.phone = ?, 
                              dac.email = ?, 
                              dac.specialty = ?, 
                              dpi.years_experience = ?
                          WHERE dac.doc_id = ?";
        $stmt = $conn->prepare($updateProfile);
        $stmt->execute([$firstname, $lastname, $phone, $email, $specialty, $yoe, $doc_id]);

        $update_rate = $conn->prepare("UPDATE doctor_consultation_info SET onsite_rate = ?, online_rate = ? WHERE doc_id = ?");
        $update_rate->execute([$onsite_rate, $online_rate, $doc_id]);


        // If new profile picture was uploaded, update that too
        if (!empty($profile_picture)) {
            $updatePic = "UPDATE doctor_personal_info SET profile_pic = ? WHERE doc_id = ?";
            $stmt = $conn->prepare($updatePic);
            $stmt->execute([$profile_picture, $doc_id]);
        }

        $_SESSION["message"] = [
            "title" => "Success!",
            "message" => "Profile updated successfully!",
            "type" => "success"
        ];
        header("Location: ../../Personnel/doctor_profile.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION["message"] = [
            "title" => "Error!",
            "message" => "Something went wrong: " . $e->getMessage(),
            "type" => "error"
        ];
        header("Location: ../../Personnel/doctor_profile.php");
        exit();
    }
} else {
    $_SESSION["message"] = [
        "title" => "Error!",
        "message" => "Invalid request.",
        "type" => "error"
    ];
    header("Location: ../../Personnel/doctor_profile.php");
    exit();
}
