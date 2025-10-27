<?php
session_name("doctor_session");
session_start();
require_once '../../Config/conn.config.php';

if (!isset($_SESSION['doc_id'])) {
    $_SESSION['message'] = [
        'title' => 'Error',
        'message' => 'Doctor not found.',
        'type' => 'error'
    ];
    header("Location: ../../Personnel/dashboard.personnel.php");
    exit();
}

$doctor_id = $_SESSION['doc_id'];
$new_status = $_POST['availability'] ?? '';

$allowed = ['Available', 'Not Available'];
if (!in_array($new_status, $allowed)) {
    echo json_encode(['success' => false, 'error' => 'Invalid status']);
    exit;
}

try {
    $availability = $conn->prepare("UPDATE doctor_acc_creation SET availability = ? WHERE doc_id = ?");
    $availability->execute([$new_status, $doctor_id]);

    echo json_encode(['success' => true]);

    $_SESSION['message'] = [
        'title' => 'Success',
        'message' => 'Availability status updated successfully.',
        'type' => 'success'
    ];
    header("Location: ../../Personnel/doctor_profile.php");
    exit();
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
