<?php
include "../../Config/conn.config.php";
session_start();

if (isset($_SESSION['user_id'])) {
    $update = $conn->prepare("UPDATE user_patient SET availability = 'offline' WHERE user_id = ?");
    $update->execute([$_SESSION['user_id']]);

    unset($_SESSION['user_id']);
}


header("Location: ../../index.php");
exit();
