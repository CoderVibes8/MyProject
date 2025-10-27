<?php
session_name("patient_session");
session_start();
require_once('../../Config/conn.config.php');

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        $_SESSION["message"] = [
            "title" => "Error!",
            "message" => "User ID not found.",
            "type" => "error"
        ];
        header("Location: ../../User/patient_profile.php");
        exit();
    }

    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $home_address = htmlspecialchars(trim($_POST['home_address']));
    $email = htmlspecialchars(trim($_POST['email']));
    $contact_number = htmlspecialchars(trim($_POST['contact_number']));
    $profile_picture = '';

    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "../../uploads/"; // Upload directory
        $file_name = basename($_FILES["profile_picture"]["name"]);
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
                header("Location: ../../User/patient_profile.php");
                exit();
            }
        } else {
            $_SESSION["message"] = [
                "title" => "Error!",
                "message" => "Invalid file type. Allowed: JPG, JPEG, PNG, GIF.",
                "type" => "error"
            ];
            header("Location: ../../User/patient_profile.php");
            exit();
        }
    }

    try {
        // Update patient profile information
        $updateProfile = "UPDATE user_patient SET 
            first_name = ?, 
            last_name = ?, 
            home_address = ?, 
            email = ?, 
            contact_number = ?
        WHERE user_id = ?";
        $stmt = $conn->prepare($updateProfile);
        $stmt->execute([$first_name, $last_name, $home_address, $email, $contact_number, $user_id]);

        if (!empty($profile_picture)) {
            $checkCredential = $conn->prepare("SELECT COUNT(*) AS count FROM user_credentials WHERE user_id = ?");
            $checkCredential->execute([$user_id]);
            $credentialExists = $checkCredential->fetch(PDO::FETCH_ASSOC);

            if ($credentialExists['count'] > 0) {
                $updatePicture = "UPDATE user_credentials SET profile_picture = ? WHERE user_id = ?";
                $stmt = $conn->prepare($updatePicture);
                $stmt->execute([$profile_picture, $user_id]);
            } else {
                $insertPicture = "INSERT INTO user_credentials (user_id, profile_picture) VALUES (?, ?)";
                $stmt = $conn->prepare($insertPicture);
                $stmt->execute([$user_id, $profile_picture]);
            }
        }

        $description = "You updated your profile picture.";
        $link = "http://localhost/FDC/User/patient_profile.php";

        $userNotif = $conn->prepare("INSERT INTO user_notif (user_id, description, link) 
                             VALUES (?,?,?)");
        $userNotif->execute([$user_id, $description, $link]);


        $_SESSION["message"] = [
            "title" => "Success!",
            "message" => "Profile updated successfully!",
            "type" => "success"
        ];
        header("Location: ../../User/patient_profile.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION["message"] = [
            "title" => "Error!",
            "message" => "Something went wrong: " . $e->getMessage(),
            "type" => "error"
        ];
        header("Location: ../../User/patient_profile.php");
        exit();
    }
} else {
    $_SESSION["message"] = [
        "title" => "Error!",
        "message" => "Invalid request.",
        "type" => "error"
    ];
    header("Location: ../../User/patient_profile.php");
    exit();
}
