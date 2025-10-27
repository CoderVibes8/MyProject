<?php
session_name("doctor_session");
session_start();
require_once '../../Config/conn.config.php';

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['setup_doctor_info'])) {
    $fn = htmlspecialchars(trim($_POST['fn']));
    $ln = htmlspecialchars(trim($_POST['ln']));
    $email = strtolower(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $dob = htmlspecialchars(trim($_POST['dob']));
    $gender = htmlspecialchars(trim($_POST['gender']));

    $doc_id = $_SESSION['doc_id'];


    try {

        $target_dir = "../../uploads/";
        $allowed_extensions = ["jpg", "jpeg", "png"];
        $profile = "user.png"; // Default profile picture

        if (isset($_FILES["profile"]) && $_FILES["profile"]["error"] === UPLOAD_ERR_OK) {
            $file_name = basename($_FILES["profile"]["name"]);
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $new_file_name = time() . "_" . $file_name;

            if ($_FILES["profile"]["size"] > 3 * 1024 * 1024) {
                die("Profile Picture is too large. Maximum 3MB allowed.");
            }
            if (!in_array($file_extension, $allowed_extensions)) {
                die("Only JPG, JPEG, and PNG files are allowed for Profile Picture.");
            }

            if (move_uploaded_file($_FILES["profile"]["tmp_name"], $target_dir . $new_file_name)) {
                $profile = $new_file_name;
            } else {
                die("Error uploading Profile Picture.");
            }
        }

        $update_doctor_info = $conn->prepare("INSERT INTO doctor_personal_info (profile_pic, firstname, lastname, email, phone, dob, gender, doc_id) VALUES (:profile_pic ,:firstname, :lastname, :email, :phone, :dob, :gender, :doc_id)");
        $update_doctor_info->execute([
            ":profile_pic" => $profile,
            ":firstname" => $fn,
            ":lastname" => $ln,
            ":email" => $email,
            ":phone" => $phone,
            ":dob" => $dob,
            ":gender" => $gender,
            ":doc_id" => $doc_id
        ]);

        // Update the doctor's account status to active
        $update_doctor_acc = $conn->prepare("UPDATE doctor_acc_creation SET status = 'activated' WHERE doc_id = :doc_id");
        $update_doctor_acc->execute([
            ":doc_id" => $doc_id
        ]);


        $_SESSION['message'] = [
            "title" => "Personal Information Updated!",
            "message" => "Your account has been activated!.",
            "type" => "success"
        ];

        header("Location: ../../Personnel/dashboard.personnel.php");
        exit();
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
