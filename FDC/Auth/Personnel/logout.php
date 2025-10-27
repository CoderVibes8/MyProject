<?php
session_name("doctor_session");
session_start();
include_once '../../Config/conn.config.php';


if (isset($_SESSION['doc_id'])) {

    $update = $conn->prepare("UPDATE doctor_acc_creation SET isOnline = 'Offline' WHERE doc_id = ?");
    $update->execute([$_SESSION['doc_id']]);
}

$_SESSION = [];
session_destroy();

// if (ini_get("session.use_cookies")) {
//     setcookie(session_name(), '', time() - 42000, '/');
// }

header("Location: ../../index.php");
exit();
?>
