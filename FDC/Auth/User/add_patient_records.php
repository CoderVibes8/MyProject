<?php
session_name('patient_session');
session_start();

require_once '../../Config/conn.config.php';
header('Content-Type: application/json');

$consultationId = $_GET['consultation_id'] ?? 0;

if ($consultationId > 0) {
    try {
        // Get doctor + patient IDs
        $doc = $conn->prepare("SELECT doc_id, user_id, created_at FROM doctor_consultation WHERE id = ?");
        $doc->execute([$consultationId]);
        $consultation = $doc->fetch(PDO::FETCH_ASSOC);

        if (!$consultation) {
            echo json_encode(["status" => "error", "message" => "Consultation not found"]);
            exit;
        }

        $docId        = $consultation['doc_id'];
        $userId       = $consultation['user_id'];
        $transac_date = $consultation['created_at']; // safer source

        // Get doctor info
        $retreiveDoc = $conn->prepare("
            SELECT 
                dac.specialty, 
                dpi.firstname, 
                dpi.lastname, 
                dci.online_rate
            FROM doctor_acc_creation dac
            LEFT JOIN doctor_personal_info dpi ON dac.doc_id = dpi.doc_id
            LEFT JOIN doctor_consultation_info dci ON dac.doc_id = dci.doc_id
            WHERE dac.doc_id = ?
        ");
        $retreiveDoc->execute([$docId]);
        $doctorInfo = $retreiveDoc->fetch(PDO::FETCH_ASSOC);

        if ($doctorInfo && $userId) {
            $fn        = $doctorInfo['firstname'] ?? '';
            $ln        = $doctorInfo['lastname'] ?? '';
            $specialty = $doctorInfo['specialty'] ?? '';
            $price     = $doctorInfo['online_rate'] ?? 0;

            $insertRecords = $conn->prepare("
                INSERT INTO patient_records 
                    (consultation_id, doc_id, user_id, firstname, lastname, specialty, transaction_date, price)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $insertRecords->execute([
                $consultationId,
                $docId,
                $userId,
                $fn,
                $ln,
                $specialty,
                $transac_date,
                $price
            ]);

            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Doctor info not found"]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
