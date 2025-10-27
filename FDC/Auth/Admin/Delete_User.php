<?php
require_once '../../Config/conn.config.php';
header('Content-Type: application/json');

if (!isset($_POST['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing user ID']);
    exit;
}

$user_id = $_POST['user_id'];

try {
    // Make sure the column and table names match your database
    $stmt = $conn->prepare("DELETE FROM user_patient WHERE user_id = ?");
    $stmt->execute([$user_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'User has been deleted.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No user found with that ID.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
