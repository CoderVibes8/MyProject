<?php
require '../../Config/conn.config.php'; 

try {
    $stmt = $conn->query("SELECT faqs_id, question, answer FROM faqs ORDER BY faqs_id ASC");
    $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($faqs);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
