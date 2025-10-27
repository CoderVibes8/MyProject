<?php
// Start patient session
session_name("patient_session");
session_start();

if (isset($_SESSION['user_id'])) {
    require_once '../../Config/conn.config.php';

    $update = $conn->prepare("UPDATE user_patient SET isOnline = 'Offline' WHERE user_id = ?");
    $update->execute([$_SESSION['user_id']]);
}

$_SESSION = [];
session_destroy();

if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');
}


header("Location: ../../index.php");
exit();
?>
