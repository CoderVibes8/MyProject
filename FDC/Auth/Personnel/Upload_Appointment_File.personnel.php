<?php
session_start();
require '../../Config/conn.config.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointmentId = $_POST['appointment_id'] ?? null;
    $userId = $_POST['user_id'] ?? null;
    $docId = $_POST['doc_id'] ?? null;
    $personnelId = $_POST['personnel_id'] ?? null;

    if (isset($_FILES['pdfFile']) && $_FILES['pdfFile']['error'] === 0) {
        $fileTmp = $_FILES['pdfFile']['tmp_name'];
        $fileName = basename($_FILES['pdfFile']['name']);
        $filePath = "../../uploads/Pdf_Files/" . $fileName;

        // Validate PDF
        $fileType = mime_content_type($fileTmp);
        if ($fileType !== "application/pdf") {
            echo json_encode(["success" => false, "message" => "Only PDF files are allowed"]);
            exit;
        }

        if (move_uploaded_file($fileTmp, $filePath)) {
            try {
                $conn->beginTransaction();

                // ✅ Check if record already exists for this appointment
                $checkSql = "SELECT appointment_id FROM appointment_files WHERE appointment_id = ?";
                $checkStmt = $conn->prepare($checkSql);
                $checkStmt->execute([$appointmentId]);
                $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

                if ($existing) {
                    // Update existing record
                    $sql = "UPDATE appointment_files 
                            SET user_id = ?, doc_id = ?, personnel_id = ?, file_path = ?, uploaded_at = NOW() 
                            WHERE appointment_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$userId, $docId, $personnelId, $fileName, $appointmentId]);
                } else {
                    $sql = "INSERT INTO appointment_files (appointment_id, user_id, doc_id, personnel_id, file_path, uploaded_at) 
                            VALUES (?, ?, ?, ?, ?, NOW())";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$appointmentId, $userId, $docId, $personnelId, $fileName]);
                }

                $updateSql = "UPDATE appointment_schedule SET stat = 'Done' WHERE appointment_id = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->execute([$appointmentId]);

                $conn->commit();

                $_SESSION["message"] = [
                    "title" => "Success!",
                    "message" => "File uploaded successfully.",
                    "type" => "success"
                ];
                echo json_encode([
                    "success" => true,
                    "sessionMessage" => $_SESSION["message"]
                ]);
                exit();
            } catch (Exception $e) {
                $conn->rollBack();
                echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Failed to upload file"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No file uploaded"]);
    }
}
