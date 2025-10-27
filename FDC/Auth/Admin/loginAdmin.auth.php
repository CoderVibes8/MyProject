<?php
session_start();
require_once("../../Config/conn.config.php");

// START OF ADMIN LOGIN
if (isset($_POST['admin-login'])) {
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $pw = filter_input(INPUT_POST, "pw", FILTER_SANITIZE_SPECIAL_CHARS);

    try {
        // Check if the email exists
        $retrieve_admin = $conn->prepare("SELECT * FROM admin WHERE email = :email");
        $retrieve_admin->execute([
            ":email" => $email
        ]);

        if ($retrieve_admin->rowCount() === 1) {
            $admin_acc = $retrieve_admin->fetch(PDO::FETCH_ASSOC);

            // Verify the password using password_verify()
            if ($pw == $admin_acc['password']) {
                session_regenerate_id(true);

                $_SESSION['admin_id'] = $admin_acc['admin_id'];
                

                $_SESSION["message"] = [
                    "title" => "Welcome!",
                    "message" => "Logged in Successfully.",
                    "type" => "success"
                ];

                header("Location: ../../Admin/dashboard.admin.php");
                exit();
            } else {
                $_SESSION["message"] = [
                    "title" => "Error!",
                    "message" => "Invalid Password! Please Try Again.",
                    "type" => "error"
                ];

                header("Location: ../../index.php");
                exit();
            }
        } else {
            $_SESSION["message"] = [
                "title" => "Error!",
                "message" => "Email not found! Please Try Again.",
                "type" => "error"
            ];

            header("Location: ../../index.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        $_SESSION["message"] = [
            "title" => "Error!",
            "message" => "Something went wrong." . $e->getMessage(),
            "type" => "error"
        ];

        header("Location: ../../index.php");
        exit();
    }
} else {
    $_SESSION["message"] = [
        "title" => "Error!",
        "message" => "Invalid Request.",
        "type" => "error"
    ];

    header("Location: ../../index.php");
    exit();
}
// END OF ADMIN LOGIN
