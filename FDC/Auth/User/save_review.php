<?php
require '../../Config/conn.config.php';

header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['consultation_id'], $input['doc_id'], $input['patient_id'], $input['rating'], $input['review'])) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT INTO doctor_reviews 
        (consultation_id, doc_id, patient_id, rating, review) 
        VALUES (:consultation_id, :doc_id, :patient_id, :rating, :review)");

    $stmt->execute([
        ':consultation_id' => $input['consultation_id'],
        ':doc_id' => $input['doc_id'],
        ':patient_id' => $input['patient_id'],
        ':rating' => (int)$input['rating'],
        ':review' => $input['review']
    ]);

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
