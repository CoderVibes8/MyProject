<?php 
include_once '../../Config/conn.config.php';


$stmt = $conn->prepare("
    SELECT 
        dac.specialty, 
        dpi.profile_pic AS img,
        CONCAT('Dr. ' ,dpi.firstname, ' ', dpi.lastname) AS name
    FROM doctor_acc_creation AS dac 
    LEFT JOIN doctor_personal_info AS dpi ON dac.doc_id = dpi.doc_id
");
$stmt->execute();
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);


header('Content-Type: application/json');
echo json_encode($doctors);

?>
