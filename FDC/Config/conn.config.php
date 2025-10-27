<?php
    require_once "constants.config.php";

    try {
        $pdo = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $conn = new PDO($pdo, DB_USERNAME, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    catch(Exception $e) {
        die("Connection failed: " . $e->getMessage());
    }
?>