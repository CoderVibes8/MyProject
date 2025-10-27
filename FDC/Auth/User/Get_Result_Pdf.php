<?php
session_start();
require '../../Config/conn.config.php';

header("Content-Type: application/json");

if (!isset($_GET['appointment_id'])) {
    echo json_encode(["success" => false, "message" => "No appointment ID provided"]);
    exit;
}

$appointmentId = intval($_GET['appointment_id']);

try {
    $sql = "SELECT file_path FROM appointment_files WHERE appointment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$appointmentId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && !empty($row['file_path'])) {
        $fileName = $row['file_path']; // e.g. "Florence Resume.pdf"

        // Server path (used for file_exists)
        $fileDirServer = __DIR__ . "/../../uploads/Pdf_Files/";
        $fullPath      = $fileDirServer . $fileName;

        // URL path (used in browser iframe)
        $fileDirUrl = "/FDC/uploads/Pdf_Files/";
        $fileUrl    = $fileDirUrl . rawurlencode($fileName);

        if (file_exists($fullPath)) {
            echo json_encode([
                "success" => true,
                "file" => $fileUrl
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "File not found on server: $fullPath"
            ]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "No file uploaded for this appointment"]);
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
