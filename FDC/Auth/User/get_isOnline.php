<?php
require_once "../../Config/conn.config.php";

try {
    $stmt = $conn->prepare("SELECT doc_id, isOnline FROM doctor_acc_creation");
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($rows);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
