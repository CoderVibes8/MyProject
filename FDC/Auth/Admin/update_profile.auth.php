<?php
session_start();
require_once('../../Config/conn.config.php');

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    if (isset($_SESSION['admin_id'])) {
        $admin_id = $_SESSION['admin_id'];
    } else {
        $_SESSION["message"] = [
            "title" => "Error!",
            "message" => "Admin ID not found.",
            "type" => "error"
        ];
        header("Location: ../../Admin/admin_profile.admin.php");
        exit();
    }

    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name = htmlspecialchars(trim($_POST['last_name']));
    $email = htmlspecialchars(trim($_POST['email']));
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
                header("Location: ../../Admin/admin_profile.admin.php");
                exit();
            }
        } else {
            $_SESSION["message"] = [
                "title" => "Error!",
                "message" => "Invalid file type. Allowed: JPG, JPEG, PNG, GIF.",
                "type" => "error"
            ];
            header("Location: ../../Admin/admin_profile.admin.php");
            exit();
        }
    }

    try {
        // Update patient profile information
        $updateProfile = "UPDATE admin SET 
            first_name = ?, 
            last_name = ?, 
            email = ? 
        WHERE admin_id = ?";
        $stmt = $conn->prepare($updateProfile);
        $stmt->execute([$first_name, $last_name, $email, $admin_id]);

        if (!empty($profile_picture)) {
            $checkCredential = $conn->prepare("SELECT COUNT(*) AS count FROM admin WHERE admin_id = ?");
            $checkCredential->execute([$admin_id]);
            $credentialExists = $checkCredential->fetch(PDO::FETCH_ASSOC);

            if ($credentialExists['count'] > 0) {
                $updatePicture = "UPDATE admin SET profile = ? WHERE admin_id = ?";
                $stmt = $conn->prepare($updatePicture);
                $stmt->execute([$profile_picture, $admin_id]);
            } else {
                $insertPicture = "INSERT INTO admin (admin_id, profile) VALUES (?, ?)";
                $stmt = $conn->prepare($insertPicture);
                $stmt->execute([$admin_id, $profile_picture]);
            }
        }

        $_SESSION["message"] = [
            "title" => "Success!",
            "message" => "Profile updated successfully!",
            "type" => "success"
        ];
        header("Location: ../../admin/admin_profile.admin.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION["message"] = [
            "title" => "Error!",
            "message" => "Something went wrong: " . $e->getMessage(),
            "type" => "error"
        ];
        header("Location: ../../admin/admin_profile.admin.php");
        exit();
    }
} else {
    $_SESSION["message"] = [
        "title" => "Error!",
        "message" => "Invalid request.",
        "type" => "error"
    ];
    header("Location: ../../admin/admin_profile.admin.php");
    exit();
}
