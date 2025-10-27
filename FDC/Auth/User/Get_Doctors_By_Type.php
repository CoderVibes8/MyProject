<?php
require '../../Config/conn.config.php';

if (!isset($_GET['type_id'])) {
    echo json_encode([]);
    exit;
}

$type_id = $_GET['type_id'];

try {
    $stmt = $conn->prepare("
        SELECT DISTINCT d.doctor_id, CONCAT(d.firstname, ' ', d.lastname) AS name, d.specialty, d.price AS onsite_rate
        FROM doctor_info d
        INNER JOIN doctor_schedule s ON d.doctor_id = s.doc_id
        WHERE s.consultation_type = :type_id
        ORDER BY d.firstname ASC
    ");
    $stmt->execute([':type_id' => $type_id]);
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($doctors);

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode([]);
}
